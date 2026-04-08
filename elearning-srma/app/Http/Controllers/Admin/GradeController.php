<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EClass;
use App\Models\Grade;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Display a listing of all grades (admin view).
     */
    public function index()
    {
        $classId = request('class');
        $student = request('student');
        
        $query = Grade::with(['submission' => fn($q) => $q->with(['student', 'assignment' => fn($qa) => $qa->with(['classSubject' => fn($qcs) => $qcs->with(['eClass', 'subject'])])])]);
        
        if ($classId) {
            $query->whereHas('submission.assignment.classSubject', fn($q) => $q->where('e_class_id', $classId));
        }
        
        if ($student) {
            $query->whereHas('submission.student', fn($q) => $q->where('name', 'like', "%$student%"));
        }
        
        $grades = $query->orderBy('created_at', 'desc')->paginate(20);
        $classes = EClass::orderBy('name')->get();
        
        // Statistics
        $allGrades = Grade::get();
        $statistics = [
            'average' => $allGrades->avg('score') ?? 0,
            'highest' => $allGrades->max('score') ?? 0,
            'lowest' => $allGrades->min('score') ?? 0,
            'total' => $allGrades->count(),
        ];
        
        return view('admin.grades.index', compact('grades', 'classes', 'statistics'));
    }

    /**
     * Show grades by class.
     */
    public function byClass($classId)
    {
        $class = EClass::with('students')->findOrFail($classId);
        
        // Get all grades for students in this class
        $studentGrades = $class->students->map(function($student) use ($class) {
            $submissions = Submission::where('student_id', $student->id)
                ->whereHas('assignment', fn($q) => $q->where('e_class_id', $class->id))
                ->with('grade', 'assignment')
                ->get();

            return [
                'student' => $student,
                'grades' => $submissions,
                'average' => $submissions->avg(fn($s) => $s->grade?->score ?? 0),
            ];
        });

        return view('admin.grades.by-class', compact('class', 'studentGrades'));
    }

    /**
     * Show grades for a specific student across all classes.
     */
    public function byStudent($studentId)
    {
        $student = User::where('role', 'siswa')->findOrFail($studentId);
        
        $submissions = Submission::where('student_id', $studentId)
            ->with('assignment.eClass', 'grade')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(fn($s) => $s->assignment->eClass->id);

        $overallGPA = Submission::where('student_id', $studentId)
            ->whereHas('grade')
            ->with('grade')
            ->get()
            ->avg(fn($s) => $s->grade->score);

        return view('admin.grades.by-student', compact('student', 'submissions', 'overallGPA'));
    }

    /**
     * Show grade form for editing (bulk edit).
     */
    public function edit(Request $request)
    {
        $assignmentId = $request->query('assignment_id');
        $classId = $request->query('class_id');

        if ($assignmentId) {
            $submissions = Submission::where('assignment_id', $assignmentId)
                ->with('student', 'grade', 'assignment')
                ->get();
        } elseif ($classId) {
            $submissions = Submission::whereHas('assignment', fn($q) => $q->where('e_class_id', $classId))
                ->with('student', 'grade', 'assignment')
                ->get();
        } else {
            abort(400, 'Required parameters missing');
        }

        return view('admin.grades.edit-bulk', compact('submissions'));
    }

    /**
     * Update multiple grades at once.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'grades' => 'required|array',
            'grades.*.submission_id' => 'required|exists:submissions,id',
            'grades.*.score' => 'required|numeric|min:0',
            'grades.*.feedback' => 'nullable|string',
        ]);

        foreach ($validated['grades'] as $gradeData) {
            $submission = Submission::findOrFail($gradeData['submission_id']);
            
            Grade::updateOrCreate(
                ['submission_id' => $gradeData['submission_id']],
                [
                    'score' => $gradeData['score'],
                    'feedback' => $gradeData['feedback'] ?? null,
                    'graded_by' => auth()->id(),
                    'graded_at' => now(),
                ]
            );
        }

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update_grades_bulk',
            'description' => "Admin update " . count($validated['grades']) . " nilai sekaligus",
            'ip_address' => $request->ip(),
            'timestamp' => now(),
        ]);

        return redirect()->back()->with('success', 'Nilai berhasil disimpan!');
    }

    /**
     * Generate grade report.
     */
    public function report(Request $request)
    {
        $type = $request->query('type', 'summary'); // summary, detailed, comparative
        $classId = $request->query('class_id');
        $from = $request->query('from');
        $to = $request->query('to');

        $query = Grade::with('submission.student', 'submission.assignment.eClass');

        if ($classId) {
            $query->whereHas('submission.assignment.eClass', fn($q) => $q->where('e_class_id', $classId));
        }

        if ($from && $to) {
            $query->whereBetween('graded_at', [$from, $to]);
        }

        $grades = $query->get();

        $report = match ($type) {
            'detailed' => $this->generateDetailedReport($grades),
            'comparative' => $this->generateComparativeReport($grades),
            default => $this->generateSummaryReport($grades),
        };

        $classes = EClass::orderBy('name')->get();

        if ($request->query('export') === 'csv') {
            return $this->exportReportCSV($report, $type);
        }

        return view('admin.grades.report', compact('report', 'type', 'classes'));
    }

    /**
     * Export grades as CSV.
     */
    public function exportCSV(Request $request)
    {
        $classId = $request->query('class_id');
        
        $filename = "grades_" . now()->format('Y-m-d_His') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($classId) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, ['Nama Siswa', 'Kelas', 'Tugas', 'Nilai', 'Feedback', 'Nilai Diberikan Oleh']);
            
            // Data
            if ($classId) {
                $grades = Grade::whereHas('submission.assignment.eClass', fn($q) => $q->where('e_class_id', $classId))
                    ->with('submission.student', 'submission.assignment.eClass', 'gradedBy')
                    ->get();
            } else {
                $grades = Grade::with('submission.student', 'submission.assignment.eClass', 'gradedBy')->get();
            }
            
            foreach ($grades as $grade) {
                fputcsv($file, [
                    $grade->submission->student->name,
                    $grade->submission->assignment->eClass->name,
                    $grade->submission->assignment->title,
                    $grade->score,
                    $grade->feedback,
                    $grade->gradedBy->name,
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Helper methods
    private function generateSummaryReport($grades)
    {
        return [
            'total_grades' => $grades->count(),
            'average_score' => $grades->avg('score'),
            'highest_score' => $grades->max('score'),
            'lowest_score' => $grades->min('score'),
            'by_class' => $grades->groupBy(fn($g) => $g->submission->assignment->eClass->name)
                ->map(fn($g) => [
                    'count' => $g->count(),
                    'average' => $g->avg('score'),
                ]),
        ];
    }

    private function generateDetailedReport($grades)
    {
        return $grades->groupBy(fn($g) => $g->submission->assignment->eClass->id)
            ->map(fn($classGrades) => [
                'class' => $classGrades->first()->submission->assignment->eClass->name,
                'students' => $classGrades->groupBy(fn($g) => $g->submission->student->id)
                    ->map(fn($studentGrades) => [
                        'name' => $studentGrades->first()->submission->student->name,
                        'grades' => $studentGrades->map(fn($g) => [
                            'assignment' => $g->submission->assignment->title,
                            'score' => $g->score,
                        ]),
                    ]),
            ]);
    }

    private function generateComparativeReport($grades)
    {
        return $grades->groupBy(fn($g) => $g->submission->assignment->id)
            ->map(fn($assignmentGrades) => [
                'assignment' => $assignmentGrades->first()->submission->assignment->title,
                'average' => $assignmentGrades->avg('score'),
                'highest' => $assignmentGrades->max('score'),
                'lowest' => $assignmentGrades->min('score'),
                'count' => $assignmentGrades->count(),
            ]);
    }
}
