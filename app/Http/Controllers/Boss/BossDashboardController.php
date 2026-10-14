<?php

namespace App\Http\Controllers\Boss;

use App\Classroom;
use App\ExamAttempt;
use App\ExamRegistration;
use App\ExamSession;
use App\User;
use App\Http\Controllers\Controller;

class BossDashboardController extends Controller
{
    public function index()
    {
        // ── สถิติภาพรวม ────────────────────────────────────────────
        $stats = [
            'total_classrooms' => Classroom::count(),
            'total_students'   => User::where('role', 'student')->count(),
            'total_teachers'   => User::where('role', 'teacher')->count(),
            'total_attempts'   => ExamAttempt::whereNotNull('submitted_at')->count(),
        ];

        // ── สำนักเรียนทั้งหมดพร้อมข้อมูลเชิงลึก ────────────────────
        $classrooms = Classroom::orderBy('sort_order')
            ->withCount([
                'users as student_count' => function ($q) {
                    $q->where('role', 'student');
                },
                'users as teacher_count' => function ($q) {
                    $q->where('role', 'teacher');
                },
            ])
            ->get();

        // ── ผู้สมัครสอบตามสำนักเรียน ─────────────────────────────
        $regByClassroom = ExamRegistration::selectRaw('office_name, COUNT(*) as total,
                SUM(CASE WHEN status = "eligible" THEN 1 ELSE 0 END) as eligible,
                SUM(CASE WHEN status = "absent"   THEN 1 ELSE 0 END) as absent')
            ->groupBy('office_name')
            ->orderByDesc('total')
            ->get();

        // ── สถิติผู้สมัครสอบ ──────────────────────────────────────
        $regStats = [
            'total'      => ExamRegistration::count(),
            'registered' => ExamRegistration::where('status', 'registered')->count(),
            'checked'    => ExamRegistration::where('status', 'checked')->count(),
            'eligible'   => ExamRegistration::where('status', 'eligible')->count(),
            'absent'     => ExamRegistration::where('status', 'absent')->count(),
        ];

        // ── สถิติผู้สมัครตามชั้น ──────────────────────────────────
        $regByTier = ExamRegistration::selectRaw('exam_tier, COUNT(*) as total')
            ->groupBy('exam_tier')
            ->pluck('total', 'exam_tier')
            ->toArray();

        // ── รอบสอบล่าสุด ──────────────────────────────────────────
        $latestSession = ExamSession::latest('exam_date')->first();

        // ── ผู้สมัครล่าสุด 10 คน ──────────────────────────────────
        $recentRegs = ExamRegistration::latest()->limit(10)->get();

        return view('boss.dashboard', compact(
            'stats', 'classrooms', 'regByClassroom',
            'regStats', 'regByTier', 'latestSession', 'recentRegs'
        ));
    }
}
