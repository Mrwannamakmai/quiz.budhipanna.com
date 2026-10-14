<?php

namespace App\Modules\Attendance\Support;

use App\Classroom;
use App\Course;
use App\Modules\Attendance\Models\AttendanceRecord;
use App\Modules\Attendance\Models\AttendanceSession;
use App\Subject;
use App\Support\Settings\Settings;
use App\User;
use Carbon\Carbon;

/**
 * ตรรกะกลางของโมดูลเช็คชื่อ (สถานะ, สิทธิ์ห้องเรียน, เวลา, สถิติ)
 */
class Attendance
{
    const STATUSES = [
        'present' => ['label' => 'มา',  'short' => 'ม', 'color' => '#16a34a', 'bg' => '#dcfce7'],
        'late'    => ['label' => 'สาย', 'short' => 'ส', 'color' => '#d97706', 'bg' => '#fef3c7'],
        'leave'   => ['label' => 'ลา',  'short' => 'ล', 'color' => '#2563eb', 'bg' => '#dbeafe'],
        'absent'  => ['label' => 'ขาด', 'short' => 'ข', 'color' => '#dc2626', 'bg' => '#fee2e2'],
    ];

    /** สถานะที่ใช้ได้ตามการตั้งค่า */
    public static function statuses()
    {
        $s = self::STATUSES;
        if (!Settings::get('attendance.use_leave', true)) {
            unset($s['leave']);
        }
        return $s;
    }

    public static function status($key)
    {
        return self::STATUSES[$key] ?? ['label' => '-', 'short' => '-', 'color' => '#94a3b8', 'bg' => '#f1f5f9'];
    }

    public static function tz()
    {
        return Settings::timezone();
    }

    /** เวลาปัจจุบันตามเขตเวลาของระบบ */
    public static function now()
    {
        return Carbon::now(self::tz());
    }

    /** แสดงเวลา (เก็บใน DB เป็นเวลาแอป) ตามเขตเวลาที่ตั้งค่า */
    public static function local($dt, $format = 'H:i')
    {
        return $dt ? $dt->copy()->setTimezone(self::tz())->format($format) : '';
    }

    // ── สิทธิ์ ───────────────────────────────────────────────
    public static function classroomIdsFor(User $user)
    {
        if ($user->isSuperAdmin() || Settings::get('attendance.teacher_scope', 'all') === 'all') {
            return Classroom::pluck('id')->map(function ($id) { return (int) $id; })->all();
        }
        return Course::where('user_id', $user->id)->whereNotNull('classroom_id')
            ->pluck('classroom_id')->unique()->map(function ($id) { return (int) $id; })->values()->all();
    }

    public static function classroomsFor(User $user)
    {
        return Classroom::whereIn('id', self::classroomIdsFor($user))->orderBy('sort_order')->orderBy('name')->get();
    }

    public static function subjectsFor(User $user)
    {
        $q = Subject::with('course')->orderBy('name');
        if (!($user->isSuperAdmin() || Settings::get('attendance.teacher_scope', 'all') === 'all')) {
            $q->whereIn('course_id', Course::where('user_id', $user->id)->pluck('id'));
        }
        return $q->get();
    }

    public static function canManage(User $user, AttendanceSession $session)
    {
        return in_array((int) $session->classroom_id, self::classroomIdsFor($user), true);
    }

    public static function studentsOf($classroomId)
    {
        return User::where('role', 'student')->where('classroom_id', $classroomId)->orderBy('name')->get();
    }

    // ── เช็คชื่อด้วยตนเอง ────────────────────────────────────
    public static function generateCode()
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // ตัด 0/O/1/I ที่สับสนง่าย
        do {
            $code = '';
            for ($i = 0; $i < 6; $i++) {
                $code .= $chars[random_int(0, strlen($chars) - 1)];
            }
        } while (AttendanceSession::where('checkin_code', $code)->where('checkin_closes_at', '>', now())->exists());
        return $code;
    }

    /** เวลาเริ่มคาบ (ตามเขตเวลาระบบ) — ไม่ได้ระบุเวลา ใช้เวลาที่เปิดรับรหัส */
    public static function startsAt(AttendanceSession $session)
    {
        if ($session->start_time) {
            return Carbon::createFromFormat('Y-m-d H:i:s',
                $session->session_date->format('Y-m-d') . ' ' . substr($session->start_time, 0, 5) . ':00', self::tz());
        }
        return $session->checkin_opened_at ? $session->checkin_opened_at->copy()->setTimezone(self::tz()) : null;
    }

    /** สถานะเมื่อนักเรียนเช็คชื่อเอง ณ เวลานี้ */
    public static function selfStatus(AttendanceSession $session)
    {
        $start = self::startsAt($session);
        if (!$start) return 'present';
        $lateAfter = (int) Settings::get('attendance.late_after', 15);
        return self::now()->greaterThan($start->copy()->addMinutes($lateAfter)) ? 'late' : 'present';
    }

    // ── สถิติ ────────────────────────────────────────────────
    public static function countsAsAttended($status)
    {
        if ($status === 'present') return true;
        if ($status === 'late')    return (bool) Settings::get('attendance.late_counts_present', true);
        if ($status === 'leave')   return (bool) Settings::get('attendance.leave_counts_present', false);
        return false;
    }

    /**
     * สรุปจากรายการสถานะ
     * @param iterable $statuses  เช่น ['present','late',null,...]  (null = ยังไม่เช็ค ไม่นับ)
     */
    public static function summarize($statuses)
    {
        $out = ['present' => 0, 'late' => 0, 'leave' => 0, 'absent' => 0, 'total' => 0, 'attended' => 0, 'percent' => null];
        foreach ($statuses as $s) {
            if (!$s || !isset($out[$s])) continue;
            $out[$s]++;
            $out['total']++;
            if (self::countsAsAttended($s)) $out['attended']++;
        }
        if ($out['total'] > 0) {
            $out['percent'] = round($out['attended'] / $out['total'] * 100, 1);
        }
        return $out;
    }

    public static function minPercent()
    {
        return (float) Settings::get('attendance.min_percent', 80);
    }
}
