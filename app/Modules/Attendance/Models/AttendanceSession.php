<?php

namespace App\Modules\Attendance\Models;

use App\Classroom;
use App\Subject;
use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * คาบเรียน 1 ครั้งที่มีการเช็คชื่อ
 */
class AttendanceSession extends Model
{
    protected $table = 'attendance_sessions';

    protected $fillable = [
        'classroom_id', 'subject_id', 'created_by', 'session_date', 'start_time',
        'title', 'note', 'checkin_code', 'checkin_opened_at', 'checkin_closes_at',
    ];

    protected $casts = [
        'session_date'      => 'date',
        'checkin_opened_at' => 'datetime',
        'checkin_closes_at' => 'datetime',
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function records()
    {
        return $this->hasMany(AttendanceRecord::class, 'attendance_session_id');
    }

    public function isCheckinOpen()
    {
        return $this->checkin_code && $this->checkin_closes_at && $this->checkin_closes_at->isFuture();
    }

    public function label()
    {
        $parts = [];
        if ($this->subject) $parts[] = $this->subject->name;
        if ($this->title)   $parts[] = $this->title;
        return $parts ? implode(' · ', $parts) : 'คาบเรียน';
    }
}
