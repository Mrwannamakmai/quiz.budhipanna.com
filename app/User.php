<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'classroom_id',
        'provider', 'provider_id', 'email_verified_at',
        'daily_streak', 'longest_streak', 'last_daily_date',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_daily_date'   => 'date',
        'daily_streak'      => 'integer',
        'longest_streak'    => 'integer',
    ];

    // ── Role helpers ──────────────────────────────────────────────
    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isBoss(): bool
    {
        return $this->role === 'boss';
    }

    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    // ── Relationships ─────────────────────────────────────────────
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    // Courses the teacher owns
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function examAttempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function issuedCertificates()
    {
        return $this->hasMany(Certificate::class, 'issued_by');
    }

    public function visitorLogs()
    {
        return $this->hasMany(VisitorLog::class);
    }

    public function dailyAttempts()
    {
        return $this->hasMany(DailyAttempt::class);
    }

    // ── Scopes ───────────────────────────────────────────────────
    public function scopeStudents($query)
    {
        return $query->where('role', 'student');
    }

    public function scopeTeachers($query)
    {
        return $query->where('role', 'teacher');
    }

    public function scopeByClassroom($query, $classroomId)
    {
        return $query->where('classroom_id', $classroomId);
    }
}
