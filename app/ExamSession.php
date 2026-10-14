<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExamSession extends Model
{
    protected $fillable = [
        'academic_year',
        'exam_date',
        'status',
        'details',
        'schedule',
        'announcement',
        'notebooklm_url',
        'is_published',
        'registration_mode',
        'registration_start_date',
        'registration_end_date',
    ];

    protected $casts = [
        'exam_date'               => 'date',
        'is_published'            => 'boolean',
        'registration_start_date' => 'date',
        'registration_end_date'   => 'date',
    ];

    public function registrations()
    {
        return $this->hasMany(ExamRegistration::class);
    }
}
