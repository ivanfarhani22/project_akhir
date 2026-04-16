<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassSubject;
use App\Models\EClass;
use App\Models\Submission;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RekapNilaiController extends Controller
{
    public function index(Request $request)
    {
        $classId = $request->query('class_id');
        $classSubjectId = $request->query('class_subject_id');

        $classes = EClass::orderBy('name')->get();

        $classSubjects = collect();
        if ($classId) {
            $classSubjects = ClassSubject::where('e_class_id', $classId)
                ->with(['subject', 'teacher', 'eClass'])
                ->orderBy('subject_id')
                ->get();
        }

        $table = null;

        if ($classId && $classSubjectId) {
            $classSubject = ClassSubject::with(['eClass.students', 'subject', 'teacher'])
                ->where('e_class_id', $classId)
                ->findOrFail($classSubjectId);

            $assignments = $classSubject->eClass
                ->assignments()
                ->where('class_subject_id', $classSubject->id)
                ->orderBy('created_at')
                ->get(['id', 'title', 'max_score']);

            $studentIds = $classSubject->eClass->students->pluck('id');
            $assignmentIds = $assignments->pluck('id');

            $submissions = Submission::query()
                ->whereIn('student_id', $studentIds)
                ->whereIn('assignment_id', $assignmentIds)
                ->with(['grade'])
                ->get(['id', 'assignment_id', 'student_id']);

            $scoreMap = [];
            foreach ($submissions as $sub) {
                $scoreMap[$sub->student_id][$sub->assignment_id] = $sub->grade?->score;
            }

            $rows = $classSubject->eClass->students
                ->sortBy('name')
                ->values()
                ->map(function ($student) use ($assignments, $scoreMap) {
                    $scores = [];
                    $sum = 0;
                    $count = 0;

                    foreach ($assignments as $as) {
                        $score = $scoreMap[$student->id][$as->id] ?? 0;
                        $scores[$as->id] = $score;
                        $sum += (float) $score;
                        $count++;
                    }

                    $avg = $count > 0 ? round($sum / $count, 2) : 0;

                    return [
                        'student' => $student,
                        'scores' => $scores,
                        'average' => $avg,
                    ];
                });

            $table = [
                'classSubject' => $classSubject,
                'assignments' => $assignments,
                'rows' => $rows,
            ];
        }

        return view('admin.rekap-nilai.index', compact('classes', 'classId', 'classSubjects', 'classSubjectId', 'table'));
    }

    public function export(Request $request): StreamedResponse
    {
        $classId = $request->query('class_id');
        $classSubjectId = $request->query('class_subject_id');

        abort_if(!$classId || !$classSubjectId, 400, 'class_id and class_subject_id are required');

        $classSubject = ClassSubject::with(['eClass.students', 'subject', 'teacher'])
            ->where('e_class_id', $classId)
            ->findOrFail($classSubjectId);

        $assignments = $classSubject->eClass
            ->assignments()
            ->where('class_subject_id', $classSubject->id)
            ->orderBy('created_at')
            ->get(['id', 'title']);

        $studentIds = $classSubject->eClass->students->pluck('id');
        $assignmentIds = $assignments->pluck('id');

        $submissions = Submission::query()
            ->whereIn('student_id', $studentIds)
            ->whereIn('assignment_id', $assignmentIds)
            ->with(['grade'])
            ->get(['id', 'assignment_id', 'student_id']);

        $scoreMap = [];
        foreach ($submissions as $sub) {
            $scoreMap[$sub->student_id][$sub->assignment_id] = $sub->grade?->score;
        }

        $filename = 'rekap_nilai_admin_' . now()->format('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($classSubject, $assignments, $scoreMap) {
            $out = fopen('php://output', 'w');

            $header = ['Nama Siswa'];
            foreach ($assignments as $as) {
                $header[] = $as->title;
            }
            $header[] = 'Rata-rata';

            fputcsv($out, $header);

            foreach ($classSubject->eClass->students->sortBy('name') as $student) {
                $row = [$student->name];
                $sum = 0;
                $count = 0;

                foreach ($assignments as $as) {
                    $score = $scoreMap[$student->id][$as->id] ?? 0;
                    $row[] = $score;
                    $sum += (float) $score;
                    $count++;
                }

                $row[] = $count > 0 ? round($sum / $count, 2) : 0;

                fputcsv($out, $row);
            }

            fclose($out);
        }, $filename);
    }
}
