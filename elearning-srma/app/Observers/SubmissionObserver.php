<?php

namespace App\Observers;

use App\Models\Submission;
use App\Services\NotificationService;

class SubmissionObserver
{
    public function created(Submission $submission): void
    {
        $this->notifyTeacherIfSubmitted($submission);
    }

    public function updated(Submission $submission): void
    {
        // Notify when it becomes submitted (submitted_at set)
        if ($submission->wasChanged('submitted_at') && $submission->submitted_at) {
            $this->notifyTeacherIfSubmitted($submission);
        }
    }

    protected function notifyTeacherIfSubmitted(Submission $submission): void
    {
        // Only when actually submitted
        if (!$submission->submitted_at) {
            return;
        }

        $assignment = $submission->assignment;
        if (!$assignment) return;

        $class = $assignment->eClass;
        if (!$class) return;

        $studentName = $submission->student?->name ?? 'Siswa';
        $assignmentTitle = $assignment->title;

        // Siswa -> Guru: notify teacher(s) of the class
        NotificationService::notifyClassTeachers($class, [
            'title' => 'Pengumpulan Tugas',
            'message' => "{$studentName} mengumpulkan tugas \"{$assignmentTitle}\" (Kelas {$class->name})",
            'type' => 'submission',
            'icon' => 'fas fa-file-upload',
            'related_model' => Submission::class,
            'related_id' => $submission->id,
            // No dedicated guru route for single submission; send to assignment details
            'action_url' => route('guru.assignments.show', $assignment),
        ]);
    }
}
