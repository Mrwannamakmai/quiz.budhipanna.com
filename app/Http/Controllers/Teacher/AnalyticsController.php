<?php

namespace App\Http\Controllers\Teacher;

use App\Course;
use App\ExamAttempt;
use App\ExamSet;
use App\Question;
use App\Subject;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    private function examSetIds()
    {
        if (Auth::user()->isSuperAdmin()) {
            return ExamSet::pluck('id');
        }
        $courseIds  = Course::where('user_id', Auth::id())->pluck('id');
        $subjectIds = Subject::whereIn('course_id', $courseIds)->pluck('id');
        return ExamSet::whereIn('subject_id', $subjectIds)->pluck('id');
    }

    /* ─── Overview dashboard ───────────────────────────────────────── */
    public function index(Request $request)
    {
        $examSetIds = $this->examSetIds();
        $period     = $request->get('period', 'month'); // day|week|month|year

        // All submitted attempts for this teacher
        $allAttempts = ExamAttempt::whereIn('exam_set_id', $examSetIds)
            ->whereNotNull('submitted_at')
            ->with(['user', 'examSet'])
            ->orderBy('submitted_at')
            ->get();

        // ── Time-series chart data ──────────────────────────────────
        [$labels, $countData, $passRateData, $avgScoreData] = $this->buildTimeSeries($allAttempts, $period);

        // ── Summary stats ───────────────────────────────────────────
        $totalStudents  = $allAttempts->pluck('user_id')->unique()->count();
        $totalAttempts  = $allAttempts->count();
        $totalPassed    = $allAttempts->where('passed', true)->count();
        $passRate       = $totalAttempts > 0 ? round($totalPassed / $totalAttempts * 100, 1) : 0;
        $avgScore       = $totalAttempts > 0
            ? round($allAttempts->avg(fn($a) => $a->total_points > 0 ? ($a->score / $a->total_points * 100) : 0), 1)
            : 0;

        // ── Per-student summary for table ───────────────────────────
        $studentStats = $allAttempts->filter(fn($a) => !is_null($a->user_id))
            ->groupBy('user_id')->map(function ($group) {
            $user     = $group->first()->user;
            $count    = $group->count();
            $passed   = $group->where('passed', true)->count();
            $avgPct   = round($group->avg(fn($a) => $a->total_points > 0 ? ($a->score / $a->total_points * 100) : 0), 1);
            $latest   = $group->sortByDesc('submitted_at')->first()->submitted_at;
            return [
                'user_id'    => $group->first()->user_id,
                'name'       => $user->name ?? '-',
                'attempts'   => $count,
                'passed'     => $passed,
                'pass_rate'  => $count > 0 ? round($passed / $count * 100, 1) : 0,
                'avg_score'  => $avgPct,
                'latest'     => $latest,
            ];
        })->sortByDesc('avg_score')->values();

        // Students list for select dropdown
        $studentIds = $allAttempts->pluck('user_id')->unique();
        $students   = User::whereIn('id', $studentIds)->orderBy('name')->get(['id', 'name']);

        // Exam sets for filter
        $examSets = ExamSet::whereIn('id', $examSetIds)->with('subject')->get(['id', 'name', 'subject_id']);

        return view('teacher.analytics.index', compact(
            'period', 'labels', 'countData', 'passRateData', 'avgScoreData',
            'totalStudents', 'totalAttempts', 'totalPassed', 'passRate', 'avgScore',
            'studentStats', 'students', 'examSets'
        ));
    }

    /* ─── Individual student analytics ────────────────────────────── */
    public function student(Request $request, User $user)
    {
        $examSetIds = $this->examSetIds();

        $attempts = ExamAttempt::where('user_id', $user->id)
            ->whereIn('exam_set_id', $examSetIds)
            ->whereNotNull('submitted_at')
            ->with(['examSet.subject', 'answers.question'])
            ->orderBy('submitted_at')
            ->get();

        abort_if($attempts->isEmpty(), 404, 'ไม่มีข้อมูลการสอบของนักเรียนคนนี้');

        // ── Score trend (all attempts) ──────────────────────────────
        $trendLabels = $attempts->map(fn($a) => $a->submitted_at->format('d/m/y H:i'))->values()->toArray();
        $trendScores = $attempts->map(fn($a) => $a->total_points > 0
            ? round($a->score / $a->total_points * 100, 1) : 0)->values()->toArray();
        $trendPass   = $attempts->map(fn($a) => $a->passed ? 1 : 0)->values()->toArray();

        // ── Per-period chart (month) ────────────────────────────────
        [$pLabels, $pCount, $pPassRate, $pAvg] = $this->buildTimeSeries($attempts, 'month');

        // ── Dimension analysis ──────────────────────────────────────
        $dims    = Question::$dimensions;
        $dimData = [];
        foreach (array_keys($dims) as $k) {
            $dimData[$k] = ['total' => 0, 'correct' => 0, 'points' => 0, 'earned' => 0];
        }
        foreach ($attempts as $attempt) {
            foreach ($attempt->answers as $ans) {
                $d = $ans->question->dimension ?? null;
                if ($d && isset($dimData[$d])) {
                    $dimData[$d]['total']++;
                    $dimData[$d]['points'] += $ans->question->points;
                    if ($ans->is_correct) {
                        $dimData[$d]['correct']++;
                        $dimData[$d]['earned'] += $ans->points_earned;
                    }
                }
            }
        }

        // Radar chart data — use short labels (กาย / พฤติกรรม / จิต / ปัญญา)
        $radarLabels = array_column($dims, 'short');
        $radarData   = array_map(function ($d) {
            return $d['points'] > 0 ? round($d['earned'] / $d['points'] * 100) : 0;
        }, $dimData);

        // ── Per exam stats ──────────────────────────────────────────
        $perExam = $attempts->groupBy('exam_set_id')->map(function ($group) {
            $es    = $group->first()->examSet;
            $count = $group->count();
            $best  = $group->max(fn($a) => $a->total_points > 0 ? round($a->score / $a->total_points * 100, 1) : 0);
            $avg   = round($group->avg(fn($a) => $a->total_points > 0 ? ($a->score / $a->total_points * 100) : 0), 1);
            return [
                'name'     => $es->name ?? '-',
                'subject'  => $es->subject->name ?? '-',
                'attempts' => $count,
                'passed'   => $group->where('passed', true)->count(),
                'best'     => $best,
                'avg'      => $avg,
                'latest'   => $group->sortByDesc('submitted_at')->first()->submitted_at,
            ];
        })->values();

        // ── Summary ─────────────────────────────────────────────────
        $summary = [
            'total'     => $attempts->count(),
            'passed'    => $attempts->where('passed', true)->count(),
            'avg_score' => round($attempts->avg(fn($a) => $a->total_points > 0 ? ($a->score / $a->total_points * 100) : 0), 1),
            'best'      => round($attempts->max(fn($a) => $a->total_points > 0 ? ($a->score / $a->total_points * 100) : 0), 1),
            'first_at'  => $attempts->first()->submitted_at,
            'last_at'   => $attempts->last()->submitted_at,
        ];

        // All students for prev/next navigation
        $studentIds = ExamAttempt::whereIn('exam_set_id', $examSetIds)
            ->whereNotNull('submitted_at')
            ->distinct()->pluck('user_id');
        $students = User::whereIn('id', $studentIds)->orderBy('name')->get(['id', 'name']);

        return view('teacher.analytics.student', compact(
            'user', 'attempts', 'summary',
            'trendLabels', 'trendScores', 'trendPass',
            'pLabels', 'pCount', 'pPassRate', 'pAvg',
            'dims', 'dimData', 'radarLabels', 'radarData',
            'perExam', 'students'
        ));
    }

    /* ─── Time-series helper ───────────────────────────────────────── */
    private function buildTimeSeries($attempts, string $period): array
    {
        switch ($period) {
            case 'day':
                $fmt   = 'Y-m-d';
                $start = now()->subDays(29)->startOfDay();
                $slots = collect(range(0, 29))->map(fn($i) => now()->subDays(29 - $i)->format($fmt));
                $dispFmt = 'd/m';
                break;
            case 'week':
                $fmt   = 'Y-W';
                $start = now()->subWeeks(11)->startOfWeek();
                $slots = collect(range(0, 11))->map(fn($i) => now()->subWeeks(11 - $i)->format($fmt));
                $dispFmt = null;
                break;
            case 'year':
                $fmt   = 'Y';
                $start = now()->subYears(4)->startOfYear();
                $slots = collect(range(0, 4))->map(fn($i) => now()->subYears(4 - $i)->format($fmt));
                $dispFmt = 'Y';
                break;
            default: // month
                $fmt   = 'Y-m';
                $start = now()->subMonths(11)->startOfMonth();
                $slots = collect(range(0, 11))->map(fn($i) => now()->subMonths(11 - $i)->format($fmt));
                $dispFmt = null;
                break;
        }

        $filtered = $attempts->filter(fn($a) => $a->submitted_at >= $start);
        $grouped  = $filtered->groupBy(fn($a) => $a->submitted_at->format($fmt));

        $labels      = [];
        $countData   = [];
        $passRateArr = [];
        $avgArr      = [];

        foreach ($slots as $slot) {
            // Label display
            if ($period === 'month') {
                $parts  = explode('-', $slot);
                $labels[] = $this->thaiMonth((int)$parts[1]) . ' ' . ((int)$parts[0] + 543);
            } elseif ($period === 'week') {
                [$y, $w] = explode('-', $slot);
                $labels[] = 'สัปดาห์ ' . $w;
            } elseif ($period === 'day') {
                $labels[] = Carbon::createFromFormat('Y-m-d', $slot)->format('d/m');
            } else {
                $labels[] = (int)$slot + 543;
            }

            $group = $grouped->get($slot, collect());
            $c     = $group->count();
            $p     = $group->where('passed', true)->count();
            $avg   = $c > 0
                ? round($group->avg(fn($a) => $a->total_points > 0 ? ($a->score / $a->total_points * 100) : 0), 1)
                : 0;

            $countData[]   = $c;
            $passRateArr[] = $c > 0 ? round($p / $c * 100, 1) : 0;
            $avgArr[]      = $avg;
        }

        return [$labels, $countData, $passRateArr, $avgArr];
    }

    private function thaiMonth(int $m): string
    {
        return ['', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.',
                'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'][$m] ?? '';
    }
}
