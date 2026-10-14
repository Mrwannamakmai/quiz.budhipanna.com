<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EssayCriterionScore extends Model
{
    protected $fillable = [
        'student_answer_id', 'essay_criterion_id', 'points_earned', 'comment',
    ];

    protected $casts = [
        'points_earned' => 'decimal:2',
    ];

    public function studentAnswer()
    {
        return $this->belongsTo(StudentAnswer::class);
    }

    public function criterion()
    {
        return $this->belongsTo(EssayCriterion::class, 'essay_criterion_id');
    }
}
