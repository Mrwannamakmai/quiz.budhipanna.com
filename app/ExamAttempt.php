<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExamAttempt extends Model
{
    protected $fillable = [
        'exam_set_id', 'user_id', 'guest_name', 'attempt_number',
        'started_at', 'submitted_at', 'score', 'total_points',
        'passed', 'question_ids',
    ];

    protected $casts = [
        'started_at'   => 'datetime',
        'submitted_at' => 'datetime',
        'score'        => 'decimal:2',
        'total_points' => 'decimal:2',
        'passed'       => 'boolean',
    ];

    // Relationships
    public function examSet()
    {
        return $this->belongsTo(ExamSet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(StudentAnswer::class);
    }

    public function studentAnswers()
    {
        return $this->answers();
    }

    public function certificate()
    {
        return $this->hasOne(Certificate::class, 'exam_set_id', 'exam_set_id')
            ->where('user_id', $this->user_id);
    }

    public function getIsPassedAttribute(): bool
    {
        return (bool) $this->passed;
    }

    // Helpers
    public function isSubmitted(): bool
    {
        return $this->submitted_at !== null;
    }

    public function getScorePercentageAttribute(): float
    {
        if (!$this->total_points || $this->total_points == 0) {
            return 0;
        }
        return round(($this->score / $this->total_points) * 100, 2);
    }

    public function getQuestionIdsArrayAttribute(): array
    {
        if (!$this->question_ids) return [];
        return json_decode($this->question_ids, true) ?? [];
    }

    public function getDurationAttribute(): ?int
    {
        if (!$this->submitted_at || !$this->started_at) return null;
        return $this->started_at->diffInMinutes($this->submitted_at);
    }
}
