<?php

namespace App\Http\Controllers\Admin;

use App\Certificate;
use App\Classroom;
use App\ExamAttempt;
use App\ExamRegistration;
use App\ExamSession;
use App\User;
use App\VisitorLog;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_students'     => User::students()->count(),
            'total_teachers'     => User::teachers()->count(),
            'total_attempts'     => ExamAttempt::whereNotNull('submitted_at')->count(),
            'total_certificates' => Certificate::count(),
        ];

        $recentAttempts = ExamAttempt::with(['user', 'examSet'])
            ->whereNotNull('submitted_at')
            ->latest('submitted_at')
            ->limit(10)
            ->get();

        $regStats = [
            'total'      => ExamRegistration::count(),
            'registered' => ExamRegistration::where('status', 'registered')->count(),
            'checked'    => ExamRegistration::where('status', 'checked')->count(),
            'eligible'   => ExamRegistration::where('status', 'eligible')->count(),
            'absent'     => ExamRegistration::where('status', 'absent')->count(),
        ];

        $regByTier = ExamRegistration::selectRaw("exam_tier, count(*) as total")
            ->groupBy('exam_tier')
            ->pluck('total', 'exam_tier')
            ->toArray();

        $recentRegs = ExamRegistration::latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentAttempts', 'regStats', 'regByTier', 'recentRegs'));
    }

    public function attempts(Request $request)
    {
        $query = ExamAttempt::with(['user', 'examSet.subject'])
            ->whereNotNull('submitted_at');

        if ($request->filled('classroom_id')) {
            $classroomId = $request->classroom_id;
            $query->whereHas('user', function ($q) use ($classroomId) {
                $q->where('classroom_id', $classroomId);
            });
        }
        if ($request->filled('passed')) {
            $query->where('passed', $request->passed === '1');
        }

        $attempts   = $query->latest('submitted_at')->paginate(30);
        $classrooms = Classroom::active()->get();
        return view('admin.reports.attempts', compact('attempts', 'classrooms'));
    }

    public function certificates(Request $request)
    {
        $query = Certificate::with(['user', 'examSet', 'issuedBy']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('certificate_no', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%');
                  });
        }

        $certificates = $query->latest('issued_at')->paginate(30);
        return view('admin.reports.certificates', compact('certificates'));
    }

    public function visitorLogs(Request $request)
    {
        $days  = (int) $request->get('days', 30);
        $days  = in_array($days, [7, 30, 60, 90]) ? $days : 30;
        $since = now()->subDays($days)->startOfDay();

        // ── Summary stats ────────────────────────────────────────
        $totalVisits  = VisitorLog::where('visited_at', '>=', $since)->count();
        $uniqueIps    = VisitorLog::where('visited_at', '>=', $since)
                            ->distinct('ip_address')->count('ip_address');
        $returningIps = VisitorLog::where('visited_at', '>=', $since)
                            ->select('ip_address')
                            ->groupBy('ip_address')
                            ->havingRaw('COUNT(*) > 1')
                            ->get()->count();
        $todayCount   = VisitorLog::whereDate('visited_at', today())->count();

        // ── Chart: visits per day ────────────────────────────────
        $chartRows = VisitorLog::where('visited_at', '>=', $since)
            ->selectRaw('DATE(visited_at) as day, COUNT(*) as total, COUNT(DISTINCT ip_address) as unique_ips')
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        $chartLabels = [];
        $chartTotal  = [];
        $chartUnique = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $d = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('d/m');
            $chartTotal[]  = $chartRows[$d]->total  ?? 0;
            $chartUnique[] = $chartRows[$d]->unique_ips ?? 0;
        }

        // ── Top pages ────────────────────────────────────────────
        $topPages = VisitorLog::where('visited_at', '>=', $since)
            ->selectRaw('url, COUNT(*) as hits')
            ->groupBy('url')
            ->orderByDesc('hits')
            ->limit(10)
            ->get();

        // ── Top IPs ──────────────────────────────────────────────
        $topIps = VisitorLog::where('visited_at', '>=', $since)
            ->selectRaw('ip_address, COUNT(*) as hits, MAX(visited_at) as last_seen')
            ->groupBy('ip_address')
            ->orderByDesc('hits')
            ->limit(20)
            ->get();

        // ── Filtered log table ───────────────────────────────────
        $query = VisitorLog::with('user')->where('visited_at', '>=', $since);

        if ($request->filled('ip')) {
            $query->where('ip_address', 'like', '%' . $request->ip_filter . '%');
        }
        if ($request->filled('url_filter')) {
            $query->where('url', 'like', '%' . $request->url_filter . '%');
        }

        $logs = $query->orderByDesc('visited_at')->paginate(50)->appends(request()->query());

        return view('admin.reports.visitor_logs', compact(
            'logs', 'days',
            'totalVisits', 'uniqueIps', 'returningIps', 'todayCount',
            'chartLabels', 'chartTotal', 'chartUnique',
            'topPages', 'topIps'
        ));
    }
}
