<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Grade;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function show(Assignment $assignment)
    {
        abort_if(! auth()->user()->classes->contains($assignment->eClass), 403);

        $quiz = Quiz::query()->where('assignment_id', $assignment->id)->with(['questions'])->firstOrFail();

        // Enforce published status -> show info page (no plain 403)
        if ($quiz->status !== 'published') {
            return view('siswa.quizzes.unavailable', [
                'assignment' => $assignment,
                'title' => 'Quiz masih draft',
                'message' => 'Quiz belum dipublikasikan oleh guru/admin. Silakan coba lagi nanti.',
                'meta' => [
                    'Status' => strtoupper($quiz->status),
                ],
            ]);
        }

        // Enforce attempts limit
        $attemptsUsed = QuizAttempt::query()
            ->where('quiz_id', $quiz->id)
            ->where('student_id', auth()->id())
            ->whereNotNull('submitted_at')
            ->count();

        if ($attemptsUsed >= (int) ($quiz->attempts_allowed ?? 1)) {
            return view('siswa.quizzes.unavailable', [
                'assignment' => $assignment,
                'title' => 'Percobaan habis',
                'message' => 'Kamu sudah mencapai batas maksimal percobaan untuk quiz ini.',
                'meta' => [
                    'Percobaan digunakan' => $attemptsUsed,
                    'Maks percobaan' => (int) ($quiz->attempts_allowed ?? 1),
                ],
            ]);
        }

        // Create (or reuse) an active attempt for time limit enforcement
        $attempt = QuizAttempt::query()
            ->where('quiz_id', $quiz->id)
            ->where('student_id', auth()->id())
            ->whereNull('submitted_at')
            ->orderByDesc('id')
            ->first();

        if (! $attempt) {
            $attempt = QuizAttempt::create([
                'quiz_id' => $quiz->id,
                'student_id' => auth()->id(),
                'started_at' => now(),
                'submitted_at' => null,
                'total_points' => 0,
                'earned_points' => 0,
                'final_score' => 0,
                'answers' => null,
            ]);
        }

        // Basic: show all questions (can be upgraded later for shuffle/time limit)
        $questions = $quiz->questions()->orderBy('order')->get();

        return view('siswa.quizzes.show', compact('assignment', 'quiz', 'questions', 'attempt'));
    }

    public function submit(Request $request, Assignment $assignment)
    {
        abort_if(! auth()->user()->classes->contains($assignment->eClass), 403);

        $quiz = Quiz::query()->where('assignment_id', $assignment->id)->with(['questions'])->firstOrFail();

        // Enforce published status
        if ($quiz->status !== 'published') {
            return view('siswa.quizzes.unavailable', [
                'assignment' => $assignment,
                'title' => 'Quiz masih draft',
                'message' => 'Quiz belum dipublikasikan oleh guru/admin. Silakan coba lagi nanti.',
                'meta' => [
                    'Status' => strtoupper($quiz->status),
                ],
            ]);
        }

        // Enforce attempts limit (re-check on submit)
        $attemptsUsed = QuizAttempt::query()
            ->where('quiz_id', $quiz->id)
            ->where('student_id', auth()->id())
            ->whereNotNull('submitted_at')
            ->count();

        if ($attemptsUsed >= (int) ($quiz->attempts_allowed ?? 1)) {
            return view('siswa.quizzes.unavailable', [
                'assignment' => $assignment,
                'title' => 'Percobaan habis',
                'message' => 'Kamu sudah mencapai batas maksimal percobaan untuk quiz ini.',
                'meta' => [
                    'Percobaan digunakan' => $attemptsUsed,
                    'Maks percobaan' => (int) ($quiz->attempts_allowed ?? 1),
                ],
            ]);
        }

        $validated = $request->validate([
            'answers' => 'required|array',
            'attempt_id' => 'nullable|integer',
        ]);

        $studentId = auth()->id();

        return DB::transaction(function () use ($quiz, $assignment, $validated, $studentId) {
            // Resolve active attempt (created on show)
            $attempt = null;
            if (! empty($validated['attempt_id'])) {
                $attempt = QuizAttempt::query()
                    ->where('id', (int) $validated['attempt_id'])
                    ->where('quiz_id', $quiz->id)
                    ->where('student_id', $studentId)
                    ->whereNull('submitted_at')
                    ->first();
            }

            if (! $attempt) {
                $attempt = QuizAttempt::query()
                    ->where('quiz_id', $quiz->id)
                    ->where('student_id', $studentId)
                    ->whereNull('submitted_at')
                    ->orderByDesc('id')
                    ->first();
            }

            if (! $attempt) {
                $attempt = QuizAttempt::create([
                    'quiz_id' => $quiz->id,
                    'student_id' => $studentId,
                    'started_at' => now(),
                ]);
            }

            // Enforce time limit at submit time
            $limitMinutes = (int) ($quiz->time_limit_minutes ?? 0);
            if ($limitMinutes > 0 && $attempt->started_at) {
                $deadline = $attempt->started_at->copy()->addMinutes($limitMinutes);
                if (now()->greaterThan($deadline)) {
                    return view('siswa.quizzes.unavailable', [
                        'assignment' => $assignment,
                        'title' => 'Waktu habis',
                        'message' => 'Batas waktu pengerjaan quiz sudah terlewati. Kamu tidak bisa submit attempt ini.',
                        'meta' => [
                            'Batas waktu' => $limitMinutes . ' menit',
                            'Mulai' => $attempt->started_at->format('d M Y H:i'),
                            'Deadline' => $deadline->format('d M Y H:i'),
                        ],
                    ]);
                }
            }

            $score = 0;
            $totalPoint = 0;

            $answers = $validated['answers'] ?? [];

            foreach ($quiz->questions as $q) {
                $points = (int) ($q->points ?? 0);
                $totalPoint += $points;

                $studentAnswer = $answers[$q->id] ?? null;

                // Normalize comparison for simple types
                $correct = $q->correct_answer;
                if (is_string($studentAnswer)) {
                    $studentAnswer = trim($studentAnswer);
                }
                if (is_string($correct)) {
                    $correct = trim($correct);
                }

                if ($studentAnswer !== null && $correct !== null && (string) $studentAnswer === (string) $correct) {
                    $score += $points;
                }
            }

            $finalScore = $totalPoint > 0 ? round(($score / $totalPoint) * 100, 2) : 0;

            // Create/Update submission (no file required for quiz)
            $submission = Submission::firstOrNew([
                'assignment_id' => $assignment->id,
                'student_id' => $studentId,
            ]);

            // `submissions.file_path` migration is NOT nullable, so store a placeholder.
            // Keeps backward compatibility with existing schema.
            if (! $submission->exists) {
                $submission->file_path = 'quiz://attempt';
            }

            $submission->submitted_at = now();
            $submission->save();

            // Finalize attempt record (history)
            $attempt->update([
                'submission_id' => $submission->id,
                'submitted_at' => now(),
                'total_points' => $totalPoint,
                'earned_points' => $score,
                'final_score' => $finalScore,
                'answers' => $answers,
            ]);

            // Upsert grade (integrated with legacy grade system)
            Grade::updateOrCreate(
                [
                    'assignment_id' => $assignment->id,
                    'student_id' => $studentId,
                ],
                [
                    'submission_id' => $submission->id,
                    'score' => $finalScore,
                    'graded_at' => now(),
                ]
            );

            return redirect()
                ->route('siswa.quizzes.result', ['assignment' => $assignment->id, 'attempt' => $attempt->id])
                ->with('success', 'Quiz berhasil dikumpulkan.');
        });
    }

    public function result(Assignment $assignment, QuizAttempt $attempt)
    {
        abort_if($attempt->student_id !== auth()->id(), 403);
        abort_if($attempt->quiz?->assignment_id !== $assignment->id, 404);

        $attempt->load(['quiz', 'quiz.questions']);

        return view('siswa.quizzes.result', compact('assignment', 'attempt'));
    }

    public function timeUp(Assignment $assignment)
    {
        abort_if(! auth()->user()->classes->contains($assignment->eClass), 403);

        $quiz = Quiz::query()->where('assignment_id', $assignment->id)->first();

        return view('siswa.quizzes.unavailable', [
            'assignment' => $assignment,
            'title' => 'Waktu habis',
            'message' => 'Batas waktu pengerjaan quiz sudah berakhir. Jika kamu sempat submit, nilai akan muncul di halaman nilai.',
            'meta' => [
                'Quiz' => $quiz ? ('#' . $quiz->id) : '-',
            ],
        ]);
    }
}
