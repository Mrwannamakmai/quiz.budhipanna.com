<?php

namespace App\Modules\ExamRegistration\Models;

use Illuminate\Database\Eloquent\Model;

class SrdExamSession extends Model
{
    protected $table = 'srd_exam_sessions';

    protected $fillable = [
        'name',
        'exam_date',
        'academic_year',
        'details',
        'schedule',
        'announcement',
        'is_published',
        'notebooklm_url',
    ];

    protected $casts = [
        'exam_date'    => 'date',
        'is_published' => 'boolean',
    ];

    public function registrations()
    {
        return $this->hasMany(SrdExamRegistration::class, 'exam_session_id');
    }
}
