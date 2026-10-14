<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExamSet extends Model
{
    protected $fillable = [
        'subject_id', 'classroom_id', 'name', 'description',
        'time_limit', 'pass_percentage', 'shuffle_questions',
        'pool_enabled', 'pool_size', 'max_attempts', 'is_active', 'is_public',
        'available_from', 'available_until',
        'view_count', 'attempt_count', 'pass_count',
    ];

    protected $casts = [
        'is_active'         => 'boolean',
        'is_public'         => 'boolean',
        'shuffle_questions' => 'boolean',
        'pool_enabled'      => 'boolean',
        'pass_percentage'   => 'integer',
        'max_attempts'      => 'integer',
        'pool_size'         => 'integer',
        'time_limit'        => 'integer',
        'available_from'    => 'datetime',
        'available_until'   => 'datetime',
    ];

    public function isNotYetOpen(): bool
    {
        return $this->available_from && now()->lt($this->available_from);
    }

    public function isExpired(): bool
    {
        return $this->available_until && now()->gt($this->available_until);
    }

    public function isWithinSchedule(): bool
    {
        return !$this->isNotYetOpen() && !$this->isExpired();
    }

    // Relationships
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'exam_set_question')
                    ->withPivot('sort_order')
                    ->orderBy('exam_set_question.sort_order');
    }

    // Direct questions linked via exam_set_id on questions table
    public function directQuestions()
    {
        return $this->hasMany(Question::class);
    }

    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Helpers
    public function getQuestionsForAttempt()
    {
        $questions = $this->questions()->get();
        if ($questions->isEmpty()) {
            $questions = $this->directQuestions()->orderBy('sort_order')->get();
        }

        if ($this->shuffle_questions) {
            $questions = $questions->shuffle();
        }

        if ($this->pool_enabled && $this->pool_size && $this->pool_size < $questions->count()) {
            $questions = $questions->random($this->pool_size);
        }

        return $questions;
    }
}
