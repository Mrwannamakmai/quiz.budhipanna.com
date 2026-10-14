<?php

namespace App\Modules\ExamRegistration\Models;

use Illuminate\Database\Eloquent\Model;

class SrdExamCenter extends Model
{
    protected $table = 'srd_exam_centers';

    protected $fillable = [
        'name',
        'address',
        'province',
        'district',
        'subdistrict',
        'postal_code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function registrations()
    {
        return $this->hasMany(SrdExamRegistration::class, 'exam_center_id');
    }
}
