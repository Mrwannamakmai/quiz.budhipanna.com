<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentPassedExam extends Model
{
    protected $table = 'students_passed_exam';

    public $timestamps = false;

    const CREATED_AT = 'created_at';

    protected $casts = [
        'certified_date' => 'date',
    ];

    public function fullName()
    {
        return trim($this->title . ' ' . $this->first_name . ' ' . ($this->pali_title ? $this->pali_title . ' ' : '') . $this->last_name);
    }

    public function levelLabel()
    {
        return $this->course_type . 'ชั้น' . $this->exam_level;
    }
}
