<?php

namespace App\Modules\ExamRegistration\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class SrdExamFollowUp extends Model
{
    protected $table = 'srd_exam_followups';

    protected $fillable = [
        'exam_registration_id',
        'followed_at',
        'channel',
        'result',
        'notes',
        'followed_by',
    ];

    protected $casts = [
        'followed_at' => 'datetime',
    ];

    public function registration()
    {
        return $this->belongsTo(SrdExamRegistration::class, 'exam_registration_id');
    }

    public function followedBy()
    {
        return $this->belongsTo(User::class, 'followed_by');
    }
}
