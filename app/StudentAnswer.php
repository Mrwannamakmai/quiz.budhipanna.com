<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    protected $fillable = [
        'exam_attempt_id', 'question_id', 'answer',
        'essay_answer', 'is_correct', 'points_earned',
        'is_graded', 'grader_comment',
    ];

    protected $casts = [
        'is_correct'    => 'boolean',
        'is_graded'     => 'boolean',
        'points_earned' => 'decimal:2',
    ];

    /**
     * ตาราง student_answers บางฐานข้อมูลยังไม่มีคอลัมน์ grader_comment
     * ใช้ตรวจก่อนบันทึก เพื่อไม่ให้การให้คะแนนอัตนัยล้ม (Unknown column)
     */
    public static function hasGraderCommentColumn()
    {
        static $has = null;
        if ($has === null) {
            $has = \Illuminate\Support\Facades\Schema::hasColumn('student_answers', 'grader_comment');
        }
        return $has;
    }

    // Relationships
    public function attempt()
    {
        return $this->belongsTo(ExamAttempt::class, 'exam_attempt_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function criterionScores()
    {
        return $this->hasMany(EssayCriterionScore::class);
    }
}
