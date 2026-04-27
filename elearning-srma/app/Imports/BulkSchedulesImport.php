<?php

namespace App\Imports;

use App\Models\ClassSubject;
use App\Models\EClass;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class BulkSchedulesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    /** @var array<string, int|null> */
    private array $classCache = [];

    /** @var array<string, int|null> */
    private array $subjectCache = [];

    /** @var array<string, int|null> */
    private array $teacherCache = [];

    /**
     * @param 'replace'|'merge' $mode
     */
    public function __construct(private readonly string $mode = 'merge')
    {
    }

    public function headingRowFormatter(): callable
    {
        return function ($heading) {
            $heading = (string) $heading;
            $heading = preg_replace('/^\xEF\xBB\xBF/', '', $heading);
            $heading = strtolower(trim($heading));
            $heading = preg_replace('/\s+/', '_', $heading);
            return $heading;
        };
    }

    public function model(array $row)
    {
        $classKey = trim((string) ($row['class_id'] ?? ''));
        if ($classKey === '') {
            $classKey = trim((string) ($row['class_name'] ?? ''));
        }

        $dayOfWeek = strtolower(trim((string) ($row['day_of_week'] ?? '')));
        $startTime = trim((string) ($row['start_time'] ?? ''));
        $endTime = trim((string) ($row['end_time'] ?? ''));

        $subjectCode = strtoupper(trim((string) ($row['subject_code'] ?? '')));
        $teacherEmail = strtolower(trim((string) ($row['teacher_email'] ?? '')));
        $room = isset($row['room']) ? trim((string) $row['room']) : null;
        $notes = isset($row['notes']) ? trim((string) $row['notes']) : null;

        $classId = $this->resolveClassId($row);
        if (!$classId) {
            // validation should catch; but keep safe
            return null;
        }

        $subjectId = $this->resolveSubjectId($subjectCode);
        $teacherId = $this->resolveTeacherId($teacherEmail);

        if (!$subjectId || !$teacherId) {
            return null;
        }

        // Replace mode: delete existing schedules for class once per import run.
        // Use a lightweight flag in cache.
        if ($this->mode === 'replace') {
            $flagKey = "__replaced__{$classId}";
            if (!isset($this->classCache[$flagKey])) {
                DB::transaction(function () use ($classId) {
                    Schedule::where('e_class_id', $classId)->delete();
                });
                $this->classCache[$flagKey] = 1;
            }
        }

        // Ensure class_subject exists
        $classSubject = ClassSubject::firstOrCreate(
            [
                'e_class_id' => $classId,
                'subject_id' => $subjectId,
                'teacher_id' => $teacherId,
            ],
            []
        );

        // Merge behavior: update existing slot, else create
        // Unique key on schedules is (e_class_id, day_of_week, start_time, end_time)
        $schedule = Schedule::updateOrCreate(
            [
                'e_class_id' => $classId,
                'day_of_week' => $dayOfWeek,
                'start_time' => $startTime,
                'end_time' => $endTime,
            ],
            [
                'class_subject_id' => $classSubject->id,
                'room' => $room,
                'notes' => $notes,
            ]
        );

        return $schedule;
    }

    private function resolveClassId(array $row): ?int
    {
        $rawId = trim((string) ($row['class_id'] ?? ''));
        if ($rawId !== '' && ctype_digit($rawId)) {
            return (int) $rawId;
        }

        $name = trim((string) ($row['class_name'] ?? ''));
        if ($name === '') {
            return null;
        }

        $cacheKey = Str::lower($name);
        if (array_key_exists($cacheKey, $this->classCache)) {
            return $this->classCache[$cacheKey];
        }

        $id = EClass::where('name', $name)->value('id');
        $this->classCache[$cacheKey] = $id;
        return $id;
    }

    private function resolveSubjectId(string $subjectCode): ?int
    {
        $key = strtoupper(trim($subjectCode));
        if ($key === '') return null;
        if (array_key_exists($key, $this->subjectCache)) return $this->subjectCache[$key];

        $id = Subject::where('code', $key)->value('id');
        $this->subjectCache[$key] = $id;
        return $id;
    }

    private function resolveTeacherId(string $teacherEmail): ?int
    {
        $key = strtolower(trim($teacherEmail));
        if ($key === '') return null;
        if (array_key_exists($key, $this->teacherCache)) return $this->teacherCache[$key];

        $id = User::where('email', $key)->value('id');
        $this->teacherCache[$key] = $id;
        return $id;
    }

    public function rules(): array
    {
        return [
            'class_id' => ['nullable'],
            'class_name' => ['nullable'],
            'day_of_week' => ['required', 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'subject_code' => ['required', 'string'],
            'teacher_email' => ['required', 'email'],
            'room' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $data = $validator->getData();

            $classId = trim((string) ($data['class_id'] ?? ''));
            $className = trim((string) ($data['class_name'] ?? ''));

            if ($classId === '' && $className === '') {
                $validator->errors()->add('class_id', 'Isi class_id atau class_name.');
            }

            if ($classId !== '' && ctype_digit($classId)) {
                if (!EClass::where('id', (int) $classId)->exists()) {
                    $validator->errors()->add('class_id', "Kelas dengan id '{$classId}' tidak ditemukan.");
                }
            }

            if ($classId === '' && $className !== '') {
                if (!EClass::where('name', $className)->exists()) {
                    $validator->errors()->add('class_name', "Kelas dengan nama '{$className}' tidak ditemukan.");
                }
            }

            $subjectCode = strtoupper(trim((string) ($data['subject_code'] ?? '')));
            if ($subjectCode !== '' && !Subject::where('code', $subjectCode)->exists()) {
                $validator->errors()->add('subject_code', "Subject dengan code '{$subjectCode}' tidak ditemukan.");
            }

            $teacherEmail = strtolower(trim((string) ($data['teacher_email'] ?? '')));
            if ($teacherEmail !== '' && !User::where('email', $teacherEmail)->exists()) {
                $validator->errors()->add('teacher_email', "Guru dengan email '{$teacherEmail}' tidak ditemukan.");
            }
        });
    }
}
