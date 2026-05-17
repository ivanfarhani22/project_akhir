<?php

namespace App\Services;

use App\Models\ClassSubject;
use App\Models\EClass;
use App\Models\Schedule;
use Illuminate\Support\Collection;

class ScheduleAutoGenerator
{
    public function generateForClass(EClass $class, array $classSubjectIds, array $options = []): array
    {
        $days = $options['days'] ?? ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        $dayStart = $options['day_start'] ?? '07:00';
        $dayEnd = $options['day_end'] ?? '15:00';
        $slotMinutes = (int) ($options['slot_minutes'] ?? 60);
        $breakMinutes = (int) ($options['break_minutes'] ?? 0);
        $room = $options['room'] ?? null;

        $classSubjects = ClassSubject::query()
            ->where('e_class_id', $class->id)
            ->whereIn('id', $classSubjectIds)
            ->get(['id', 'teacher_id']);

        // Existing schedules for this class & for involved teachers (global)
        $teacherIds = $classSubjects->pluck('teacher_id')->filter()->unique()->values();

        $existing = Schedule::query()
            ->with(['classSubject:id,teacher_id'])
            ->where(function ($q) use ($class, $teacherIds) {
                $q->where('e_class_id', $class->id);
                if ($teacherIds->isNotEmpty()) {
                    $q->orWhereHas('classSubject', fn ($qq) => $qq->whereIn('teacher_id', $teacherIds));
                }
            })
            ->get(['id', 'e_class_id', 'class_subject_id', 'day_of_week', 'start_time', 'end_time']);

        // Build occupied intervals per day for class and per teacher
        $occupiedClass = $this->indexOccupied($existing->where('e_class_id', $class->id));

        $occupiedTeacher = [];
        foreach ($teacherIds as $tid) {
            $occupiedTeacher[(int) $tid] = $this->indexOccupied(
                $existing->filter(fn ($s) => (int) optional($s->classSubject)->teacher_id === (int) $tid)
            );
        }

        // Candidate slots for each day
        $candidatesByDay = [];
        foreach ($days as $d) {
            $candidatesByDay[$d] = $this->buildSlotsForDay($d, $dayStart, $dayEnd, $slotMinutes, $breakMinutes);
        }

        // Heuristic: schedule teachers with most conflicts first
        $ordered = $classSubjects
            ->sortByDesc(function ($cs) use ($occupiedTeacher) {
                $tid = (int) $cs->teacher_id;
                $count = 0;
                if ($tid && isset($occupiedTeacher[$tid])) {
                    foreach ($occupiedTeacher[$tid] as $day => $intervals) {
                        $count += count($intervals);
                    }
                }
                return $count;
            })
            ->values();

        $result = [];
        $used = [
            'class' => $occupiedClass,
            'teacher' => $occupiedTeacher,
        ];

        foreach ($ordered as $cs) {
            $placed = false;
            foreach ($days as $day) {
                foreach ($candidatesByDay[$day] as $slot) {
                    if ($this->conflicts($used['class'][$day] ?? [], $slot['start'], $slot['end'])) {
                        continue;
                    }

                    $tid = (int) $cs->teacher_id;
                    if ($tid && isset($used['teacher'][$tid])) {
                        if ($this->conflicts($used['teacher'][$tid][$day] ?? [], $slot['start'], $slot['end'])) {
                            continue;
                        }
                    }

                    // place
                    $result[] = [
                        'class_subject_id' => (int) $cs->id,
                        'day_of_week' => $day,
                        'start_time' => $slot['start'],
                        'end_time' => $slot['end'],
                        'room' => $room,
                        'notes' => 'Auto-generated (anti bentrok kelas & guru)',
                    ];

                    $used['class'][$day][] = [$slot['start'], $slot['end']];
                    if ($tid) {
                        $used['teacher'][$tid][$day][] = [$slot['start'], $slot['end']];
                    }

                    $placed = true;
                    break 2;
                }
            }

            if (! $placed) {
                // Fail softly: leave unscheduled item unreturned
                // Caller can show message and ask admin to adjust constraints.
            }
        }

        return $result;
    }

    /**
     * @param Collection<int,Schedule> $schedules
     * @return array<string, array<int, array{0:string,1:string}>> day => [[start,end],...]
     */
    private function indexOccupied(Collection $schedules): array
    {
        $out = [];
        foreach ($schedules as $s) {
            $day = strtolower((string) $s->day_of_week);
            $out[$day] ??= [];
            $out[$day][] = [(string) $s->start_time, (string) $s->end_time];
        }
        return $out;
    }

    /**
     * @return array<int, array{day:string,start:string,end:string}>
     */
    private function buildSlotsForDay(string $day, string $dayStart, string $dayEnd, int $slotMinutes, int $breakMinutes): array
    {
        $slots = [];
        $cursor = $dayStart;
        while (true) {
            $end = $this->addMinutes($cursor, $slotMinutes);
            if ($end > $dayEnd) {
                break;
            }

            $slots[] = [
                'day' => $day,
                'start' => $cursor,
                'end' => $end,
            ];

            $cursor = $this->addMinutes($end, $breakMinutes);
        }

        return $slots;
    }

    /**
     * @param array<int, array{0:string,1:string}> $intervals
     */
    private function conflicts(array $intervals, string $start, string $end): bool
    {
        foreach ($intervals as [$aStart, $aEnd]) {
            // overlap if start < aEnd AND end > aStart
            if ($start < $aEnd && $end > $aStart) {
                return true;
            }
        }
        return false;
    }

    private function addMinutes(string $time, int $minutes): string
    {
        [$h, $m] = array_map('intval', explode(':', substr($time, 0, 5)));
        $total = $h * 60 + $m + $minutes;
        $nh = intdiv($total, 60);
        $nm = $total % 60;
        return sprintf('%02d:%02d', $nh, $nm);
    }
}
