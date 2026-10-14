<?php

namespace App\Http\Controllers\Student;

use App\ExamAttempt;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LeaderboardController extends Controller
{
    // ── Leaderboard หลัก ──────────────────────────────────────────────────
    public function index(Request $request)
    {
        $period = $request->get('period', 'week'); // day | week | month | year | all

        $from = $this->periodStart($period);

        // Query อันดับ: จัดกลุ่มตาม user_id, คำนวณ avg score%, pass count, attempt count
        $query = ExamAttempt::query()
            ->whereNotNull('submitted_at')
            ->with('user');

        if ($from) {
            $query->where('submitted_at', '>=', $from);
        }

        // ดึง raw stats ต่อ user
        $rows = $query->select(
                'user_id',
                DB::raw('COUNT(*) as attempt_count'),
                DB::raw('SUM(CASE WHEN passed = 1 THEN 1 ELSE 0 END) as pass_count'),
                DB::raw('MAX(CASE WHEN total_points > 0 THEN ROUND(score / total_points * 100, 2) ELSE 0 END) as best_percent'),
                DB::raw('AVG(CASE WHEN total_points > 0 THEN ROUND(score / total_points * 100, 2) ELSE 0 END) as avg_percent'),
                DB::raw('SUM(score) as total_score')
            )
            ->groupBy('user_id')
            ->orderByDesc('best_percent')
            ->orderByDesc('pass_count')
            ->orderByDesc('total_score')
            ->limit(50)
            ->get();

        // แนบข้อมูล user
        $userIds = $rows->pluck('user_id');
        $users   = User::whereIn('id', $userIds)->get()->keyBy('id');

        $rankings = $rows->map(function ($r) use ($users) {
            $r->user = $users->get($r->user_id);
            return $r;
        })->filter(function ($r) {
            return $r->user !== null;
        })->values();

        // สถิติ my position (ถ้า login อยู่)
        $myRank = null;
        if (Auth::check()) {
            $myRank = $rankings->search(function ($r) {
                return (int) $r->user_id === (int) Auth::id();
            });
            $myRank = $myRank !== false ? $myRank + 1 : null;
        }

        // สรุปภาพรวม
        $overallQuery = ExamAttempt::whereNotNull('submitted_at');
        if ($from) $overallQuery->where('submitted_at', '>=', $from);
        $overview = [
            'total_attempts' => (clone $overallQuery)->count(),
            'total_passed'   => (clone $overallQuery)->where('passed', true)->count(),
            'unique_users'   => (clone $overallQuery)->distinct('user_id')->count('user_id'),
        ];

        return view('student.leaderboard.index', compact(
            'rankings', 'period', 'myRank', 'overview'
        ));
    }

    // ── สถิติรายบุคคล ─────────────────────────────────────────────────────
    public function show(User $user)
    {
        // ดึง attempt ทั้งหมดของ user นี้
        $attempts = ExamAttempt::where('user_id', $user->id)
            ->whereNotNull('submitted_at')
            ->with('examSet.subject')
            ->latest('submitted_at')
            ->get();

        $totalAttempts = $attempts->count();
        $passCount     = $attempts->where('passed', true)->count();
        $failCount     = $totalAttempts - $passCount;
        $passRate      = $totalAttempts > 0 ? round($passCount / $totalAttempts * 100, 1) : 0;

        // คะแนนเฉลี่ย
        $avgPercent = $totalAttempts > 0
            ? round($attempts->filter(function ($a) {
                return $a->total_points > 0;
            })->avg(function ($a) {
                return $a->score / $a->total_points * 100;
            }), 1)
            : 0;

        // Best score
        $bestPercent = $totalAttempts > 0
            ? round($attempts->filter(function ($a) {
                return $a->total_points > 0;
            })->max(function ($a) {
                return $a->score / $a->total_points * 100;
            }), 1)
            : 0;

        // จัดกลุ่มต่อ exam set
        $byExamSet = $attempts->groupBy('exam_set_id')->map(function ($group) {
            $best = $group->filter(function ($a) {
                return $a->total_points > 0;
            })->max(function ($a) {
                return $a->score / $a->total_points * 100;
            });
            return [
                'exam_set'     => $group->first()->examSet,
                'count'        => $group->count(),
                'pass_count'   => $group->where('passed', true)->count(),
                'best_percent' => round($best ?? 0, 1),
            ];
        })->sortByDesc('best_percent')->values();

        // กิจกรรม 30 วันล่าสุด (สำหรับ heatmap mini)
        $recent30 = $attempts->filter(function ($a) {
            return $a->submitted_at >= Carbon::now()->subDays(30);
        })->groupBy(function ($a) {
            return $a->submitted_at->format('Y-m-d');
        })->map->count();

        return view('student.leaderboard.show', compact(
            'user', 'attempts', 'totalAttempts', 'passCount', 'failCount',
            'passRate', 'avgPercent', 'bestPercent', 'byExamSet', 'recent30'
        ));
    }

    // ── Helper ────────────────────────────────────────────────────────────
    private function periodStart($period)
    {
        switch ($period) {
            case 'day':   return Carbon::today();
            case 'week':  return Carbon::now()->startOfWeek();
            case 'month': return Carbon::now()->startOfMonth();
            case 'year':  return Carbon::now()->startOfYear();
            default:      return null;
        }
    }
}
