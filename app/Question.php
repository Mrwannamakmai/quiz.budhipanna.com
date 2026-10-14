<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'exam_set_id', 'subject_id', 'type', 'dimension', 'question_text',
        'option_a', 'option_b', 'option_c', 'option_d',
        'correct_answer', 'essay_answer', 'points', 'sort_order', 'is_active',
    ];

    public static $dimensions = [
        'kaya'    => ['label' => 'ภายภาพ',   'short' => 'กาย',       'icon' => 'fa-walking',   'color' => '#e67e22'],
        'conduct' => ['label' => 'พฤติภาพ',  'short' => 'พฤติกรรม', 'icon' => 'fa-hands',     'color' => '#2980b9'],
        'citta'   => ['label' => 'จิตภาพ',   'short' => 'จิต',       'icon' => 'fa-heart',     'color' => '#8e44ad'],
        'panna'   => ['label' => 'ปัญญาภาพ', 'short' => 'ปัญญา',    'icon' => 'fa-lightbulb', 'color' => '#27ae60'],
    ];

    protected $casts = [
        'points'     => 'integer',
        'sort_order' => 'integer',
        'is_active'  => 'boolean',
    ];

    // Relationships
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function examSet()
    {
        return $this->belongsTo(ExamSet::class);
    }

    public function examSets()
    {
        return $this->belongsToMany(ExamSet::class, 'exam_set_question')
                    ->withPivot('sort_order');
    }

    public function studentAnswers()
    {
        return $this->hasMany(StudentAnswer::class);
    }

    public function essayCriteria()
    {
        return $this->hasMany(EssayCriterion::class)->orderBy('sort_order');
    }

    // Helpers
    public function isMultipleChoice(): bool
    {
        return $this->type === 'multiple_choice';
    }

    public function isEssay(): bool
    {
        return $this->type === 'essay';
    }

    public function getOptionsAttribute(): array
    {
        return [
            'a' => $this->option_a,
            'b' => $this->option_b,
            'c' => $this->option_c,
            'd' => $this->option_d,
        ];
    }
}
