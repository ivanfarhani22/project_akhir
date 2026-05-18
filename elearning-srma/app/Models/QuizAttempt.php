<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    protected $fillable = [
        'quiz_id',
        'student_id',
        'submission_id',
        'started_at',
        'submitted_at',
        'total_points',
        'earned_points',
        'final_score',
        'answers',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'answers' => 'array',
        'total_points' => 'integer',
        'earned_points' => 'integer',
        'final_score' => 'decimal:2',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }
}
