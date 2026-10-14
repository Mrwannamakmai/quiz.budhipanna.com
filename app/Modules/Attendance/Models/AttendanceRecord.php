<?php

namespace App\Modules\Attendance\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * ผลการเช็คชื่อของนักเรียน 1 คน ใน 1 คาบ
 * status: present | late | leave | absent
 * method: teacher (ครูเช็ค) | self (นักเรียนกรอกรหัส)
 */
class AttendanceRecord extends Model
{
    protected $table = 'attendance_records';

    protected $fillable = [
        'attendance_session_id', 'user_id', 'status', 'method', 'checked_in_at', 'note', 'recorded_by',
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
    ];

    public function session()
    {
        return $this->belongsTo(AttendanceSession::class, 'attendance_session_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
