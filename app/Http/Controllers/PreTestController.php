<?php

namespace App\Http\Controllers;

use App\Certificate;
use App\Classroom;
use App\ExamAttempt;
use App\ExamSet;
use App\Question;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PreTestController extends Controller
{
    // รายการ pre-test ทั้งหมด (public)
    public function index()
    {
        $examSets = ExamSet::where('is_public', true)
            ->where('is_active', true)
            ->with(['subject.course', 'classroom'])
            ->withCount(['questions', 'directQuestions'])
            ->get();

        $examSets->each(function ($es) {
            $es->total_questions = $es->questions_count ?: $es->direct_questions_count;
        });

        $classrooms = Classroom::active()->get();

        $grouped = collect();
        $idx = 0;
        foreach ($classrooms as $cl) {
            $items = $examSets->where('classroom_id', $cl->id)->values();
            if ($items->isNotEmpty()) {
                $idx++;
                $grouped[$cl->name] = [
                    'classroom'   => $cl,
                    'slug'        => 'level-' . $cl->id,
                    'description' => $cl->description,
                    'items'       => $items,
                ];
            }
        }

        $noClass = $examSets->where('classroom_id', null)->values();
        if ($noClass->isNotEmpty()) {
            $grouped['ทั่วไป'] = [
                'classroom'   => null,
                'slug'        => 'level-general',
                'description' => 'ข้อสอบที่ไม่ได้ระบุระดับชั้น',
                'items'       => $noClass,
            ];
        }

        $totalQ = $examSets->sum('total_questions');

        $stats = [
            'total'      => $examSets->count(),
            'classrooms' => $grouped->count(),
            'questions'  => $totalQ,
            'views'      => $examSets->sum('view_count'),
            'attempts'   => $examSets->sum('attempt_count'),
            'passed'     => $examSets->sum('pass_count'),
        ];

        $visitorStats = [
            'total'     => \App\VisitorLog::count(),
            'unique'    => \App\VisitorLog::distinct('ip_address')->count('ip_address'),
            'returning' => \App\VisitorLog::select('ip_address')
                            ->groupBy('ip_address')->havingRaw('COUNT(*) > 1')->get()->count(),
            'today'     => \App\VisitorLog::whereDate('visited_at', today())->count(),
        ];

        $siteStats = [
            'students'     => \App\User::where('role', 'student')->count(),
            'exam_sets'    => \App\ExamSet::where('is_active', true)->count(),
            'attempts'     => \App\ExamAttempt::whereNotNull('submitted_at')->count(),
            'passed'       => \App\ExamAttempt::whereNotNull('submitted_at')->where('passed', true)->count(),
            'certificates' => \App\Certificate::count(),
        ];

        return view('pretest.index', compact('grouped', 'stats', 'visitorStats', 'siteStats'));
    }

    public function guide()
    {
        return view('pretest.guide');
    }

    public function stats()
    {
        $examSets = ExamSet::where('is_public', true)
            ->where('is_active', true)
            ->with(['subject.course', 'classroom'])
            ->withCount(['questions', 'directQuestions'])
            ->orderByDesc('attempt_count')
            ->get();

        $examSets->each(function ($es) {
            $es->total_questions = $es->questions_count ?: $es->direct_questions_count;
            $es->pass_rate = $es->attempt_count > 0
                ? round($es->pass_count / $es->attempt_count * 100, 1)
                : 0;
        });

        $totals = [
            'views'    => $examSets->sum('view_count'),
            'attempts' => $examSets->sum('attempt_count'),
            'passed'   => $examSets->sum('pass_count'),
            'failed'   => $examSets->sum('attempt_count') - $examSets->sum('pass_count'),
            'rate'     => $examSets->sum('attempt_count') > 0
                ? round($examSets->sum('pass_count') / $examSets->sum('attempt_count') * 100, 1)
                : 0,
        ];

        $recentAttempts = ExamAttempt::whereIn('exam_set_id', $examSets->pluck('id'))
            ->with(['examSet', 'user'])
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        $classrooms = Classroom::active()->get();

        return view('pretest.stats', compact('examSets', 'totals', 'recentAttempts', 'classrooms'));
    }

    // หน้ารายละเอียดและปุ่มเริ่ม
    public function show(ExamSet $examSet)
    {
        abort_unless($examSet->is_public && $examSet->is_active, 404);

        $viewKey = 'pretest_viewed_' . $examSet->id;
        if (!session()->has($viewKey)) {
            $examSet->increment('view_count');
            session([$viewKey => true]);
        }

        $examSet->load(['subject', 'classroom']);

        $questionCount = $examSet->questions()->count();
        if ($questionCount === 0) {
            $questionCount = $examSet->directQuestions()->count();
        }

        $passRate = $examSet->attempt_count > 0
            ? round($examSet->pass_count / $examSet->attempt_count * 100, 1)
            : null;

        $visitorStats = [
            'total'     => \App\VisitorLog::count(),
            'unique'    => \App\VisitorLog::distinct('ip_address')->count('ip_address'),
            'returning' => \App\VisitorLog::select('ip_address')
                            ->groupBy('ip_address')->havingRaw('COUNT(*) > 1')->get()->count(),
            'today'     => \App\VisitorLog::whereDate('visited_at', today())->count(),
        ];

        $siteStats = [
            'students'     => \App\User::where('role', 'student')->count(),
            'exam_sets'    => \App\ExamSet::where('is_active', true)->count(),
            'attempts'     => \App\ExamAttempt::whereNotNull('submitted_at')->count(),
            'passed'       => \App\ExamAttempt::whereNotNull('submitted_at')->where('passed', true)->count(),
            'certificates' => \App\Certificate::count(),
        ];

        return view('pretest.show', compact(
            'examSet', 'questionCount', 'passRate', 'visitorStats', 'siteStats'
        ));
    }

    // เริ่มทำข้อสอบ — โหลดคำถามและเก็บใน session
    public function start(Request $request, ExamSet $examSet)
    {
        abort_unless($examSet->is_public && $examSet->is_active, 404);

        // Guest ต้องกรอกชื่อก่อนเริ่ม
        if (!auth()->check()) {
            $request->validate(
                ['guest_name' => 'required|string|max:100'],
                ['guest_name.required' => 'กรุณากรอกชื่อสำหรับใบประกาศ']
            );
        }

        $questions = $examSet->getQuestionsForAttempt();

        if ($questions->isEmpty()) {
            return redirect()->route('pretest.show', $examSet)
                ->withErrors(['ชุดข้อสอบนี้ยังไม่มีคำถาม']);
        }

        $sessionKey = 'pretest_' . $examSet->id;
        session([$sessionKey => [
            'exam_set_id'  => $examSet->id,
            'question_ids' => $questions->pluck('id')->toArray(),
            'started_at'   => now()->toISOString(),
            'guest_name'   => auth()->check() ? null : trim($request->input('guest_name')),
        ]]);

        return view('pretest.take', compact('examSet', 'questions'));
    }

    // รับคำตอบ คำนวณคะแนน แล้วแสดงผล
    public function submit(Request $request, ExamSet $examSet)
    {
        abort_unless($examSet->is_public && $examSet->is_active, 404);

        $sessionKey = 'pretest_' . $examSet->id;
        $sessionData = session($sessionKey);

        // ถ้าไม่มี session ให้กลับไปหน้าเริ่ม
        if (!$sessionData) {
            return redirect()->route('pretest.show', $examSet)
                ->withErrors(['กรุณาเริ่มทำแบบทดสอบใหม่']);
        }

        $questionIds = $sessionData['question_ids'];
        $questions   = Question::whereIn('id', $questionIds)->get()->keyBy('id');

        $answers      = $request->input('answers', []);
        $totalPoints  = 0;
        $earnedPoints = 0;
        $results      = [];

        foreach ($questionIds as $qid) {
            $question = $questions[$qid] ?? null;
            if (!$question) continue;

            $totalPoints += $question->points;
            $userAnswer   = $answers[$qid] ?? null;

            if ($question->type === 'multiple_choice') {
                $isCorrect     = $userAnswer && strtolower($userAnswer) === $question->correct_answer;
                $earned        = $isCorrect ? $question->points : 0;
                $earnedPoints += $earned;

                $results[] = [
                    'question'      => $question,
                    'user_answer'   => $userAnswer,
                    'correct'       => $isCorrect,
                    'points_earned' => $earned,
                ];
            } else {
                $earned = 0;
                $isCorrect = false;
                $similarity = 0;
                $userText = trim($userAnswer ?? '');

                if ($userText === '') {
                    $isCorrect = false;
                    $earned = 0;
                } elseif ($question->essay_answer) {
                    $norm = function ($s) {
                        return mb_strtolower(preg_replace('/\s+/u', '', trim($s)));
                    };
                    $expected = $norm($question->essay_answer);
                    $given    = $norm($userText);

                    if ($expected === $given) {
                        $similarity = 100;
                    } else {
                        similar_text($expected, $given, $similarity);
                        $similarity = round($similarity, 1);
                    }

                    if ($similarity >= 80) {
                        $earned = $question->points;
                        $isCorrect = true;
                    } elseif ($similarity >= 50) {
                        $earned = round($question->points * 0.5);
                        $isCorrect = true;
                    }
                } else {
                    $earned = $question->points;
                    $isCorrect = true;
                    $similarity = 100;
                }

                $earnedPoints += $earned;

                $results[] = [
                    'question'      => $question,
                    'user_answer'   => $userAnswer,
                    'correct'       => $isCorrect,
                    'points_earned' => $earned,
                    'similarity'    => $similarity,
                ];
            }
        }

        $scorePercent = $totalPoints > 0 ? round($earnedPoints / $totalPoints * 100, 1) : 0;
        $passed       = $scorePercent >= $examSet->pass_percentage;

        $guestName = $sessionData['guest_name'] ?? null;

        $attempt = ExamAttempt::create([
            'exam_set_id'    => $examSet->id,
            'user_id'        => auth()->id(),
            'guest_name'     => auth()->check() ? null : $guestName,
            'attempt_number' => 1,
            'started_at'     => Carbon::parse($sessionData['started_at']),
            'submitted_at'   => now(),
            'score'          => $earnedPoints,
            'total_points'   => $totalPoints,
            'passed'         => $passed,
            'question_ids'   => json_encode($questionIds),
        ]);

        $examSet->increment('attempt_count');

        $certificate = null;
        if ($passed) {
            $examSet->increment('pass_count');

            $recipientName = auth()->check()
                ? auth()->user()->name
                : $guestName;

            if (!empty($recipientName)) {
                $certificate = Certificate::create([
                    'exam_set_id'    => $examSet->id,
                    'user_id'        => auth()->id(),
                    'guest_name'     => auth()->check() ? null : $guestName,
                    'issued_by'      => null,
                    'certificate_no' => Certificate::generateCertificateNo(),
                    'score'          => $earnedPoints,
                    'issued_type'    => 'auto',
                    'issued_at'      => now(),
                ]);
            }
        }

        session()->forget($sessionKey);

        return view('pretest.result', compact(
            'examSet', 'results', 'earnedPoints', 'totalPoints', 'scorePercent', 'passed', 'certificate'
        ));
    }
}
