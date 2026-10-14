<?php

namespace App\Http\Controllers\Student;

use App\Certificate;
use App\ExamAttempt;
use App\ExamSet;
use App\StudentAnswer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    // List available exams for the student's classroom
    public function index()
    {
        $user = Auth::user();

        $examSets = ExamSet::active()
            ->where(function ($q) use ($user) {
                $q->whereNull('classroom_id')
                  ->orWhere('classroom_id', $user->classroom_id);
            })
            ->with('subject.course')
            ->get();

        // Attach student's attempt status
        $attemptMap = ExamAttempt::where('user_id', $user->id)
            ->get()
            ->groupBy('exam_set_id');

        $grouped = $examSets->groupBy(fn ($es) => optional($es->subject)->name ?? 'ไม่ระบุวิชา');

        $passedCount = $examSets->filter(function ($es) use ($attemptMap) {
            return ($attemptMap[$es->id] ?? collect())->contains('passed', true);
        })->count();

        $stats = [
            'total'    => $examSets->count(),
            'subjects' => $grouped->count(),
            'passed'   => $passedCount,
        ];

        return view('student.exams.index', compact('examSets', 'attemptMap', 'grouped', 'stats'));
    }

    // Show exam detail & start button
    public function show(ExamSet $examSet)
    {
        $this->authorizeExam($examSet);

        $user    = Auth::user();
        $attempts = ExamAttempt::where('user_id', $user->id)
            ->where('exam_set_id', $examSet->id)
            ->orderByDesc('created_at')
            ->get();

        $attemptCount  = $attempts->count();
        $latestAttempt = $attempts->first();

        $scheduleBlocked = !$examSet->isWithinSchedule();
        $canAttempt      = !$scheduleBlocked && $attemptCount < $examSet->max_attempts;

        return view('student.exams.show', compact(
            'examSet', 'attemptCount', 'canAttempt',
            'latestAttempt', 'attempts', 'scheduleBlocked'
        ));
    }

    // Start a new attempt
    public function start(ExamSet $examSet)
    {
        $this->authorizeExam($examSet);

        if ($redirect = $this->checkSchedule($examSet)) {
            return $redirect;
        }

        $user         = Auth::user();
        $attemptCount = ExamAttempt::where('user_id', $user->id)
            ->where('exam_set_id', $examSet->id)
            ->count();

        if ($attemptCount >= $examSet->max_attempts) {
            return redirect()->route('student.exams.show', $examSet)
                ->withErrors(['Exceeded maximum attempts.']);
        }

        $questions = $examSet->getQuestionsForAttempt();

        $attempt = ExamAttempt::create([
            'exam_set_id'    => $examSet->id,
            'user_id'        => $user->id,
            'attempt_number' => $attemptCount + 1,
            'started_at'     => now(),
            'question_ids'   => json_encode($questions->pluck('id')->toArray()),
            'total_points'   => $questions->sum('points'),
        ]);

        return view('student.exams.take', compact('examSet', 'attempt', 'questions'));
    }

    // Submit answers
    public function submit(Request $request, ExamAttempt $attempt)
    {
        abort_unless((int) $attempt->user_id === (int) Auth::id(), 403);
        abort_if($attempt->isSubmitted(), 400, 'Already submitted.');

        $request->validate([
            'answers'   => 'nullable|array',
            'answers.*' => 'nullable|string|max:10',
            'essays'    => 'nullable|array',
            'essays.*'  => 'nullable|string',
        ]);

        $examSet   = $attempt->examSet;
        $questionIds = $attempt->getQuestionIdsArrayAttribute();
        $questions = \App\Question::whereIn('id', $questionIds)->get()->keyBy('id');

        $totalPoints = 0;
        $earnedPoints = 0;

        foreach ($questionIds as $qid) {
            $question = $questions[$qid] ?? null;
            if (!$question) continue;

            $totalPoints += $question->points;

            if ($question->isMultipleChoice()) {
                $answer    = $request->input("answers.{$qid}");
                $isCorrect = ($answer && strtolower($answer) === $question->correct_answer);
                $earned    = $isCorrect ? $question->points : 0;

                StudentAnswer::updateOrCreate(
                    ['exam_attempt_id' => $attempt->id, 'question_id' => $qid],
                    ['answer' => $answer, 'is_correct' => $isCorrect, 'points_earned' => $earned]
                );

                $earnedPoints += $earned;
            } else {
                // Essay – requires manual grading
                $essay = $request->input("essays.{$qid}");
                StudentAnswer::updateOrCreate(
                    ['exam_attempt_id' => $attempt->id, 'question_id' => $qid],
                    ['essay_answer' => $essay, 'is_correct' => null, 'points_earned' => 0]
                );
            }
        }

        $passed = $totalPoints > 0
            ? ($earnedPoints / $totalPoints * 100) >= $examSet->pass_percentage
            : false;

        $attempt->update([
            'submitted_at' => now(),
            'score'        => $earnedPoints,
            'total_points' => $totalPoints,
            'passed'       => $passed,
        ]);

        // Auto-issue certificate if passed and no essay questions
        $hasEssay = $questions->where('type', 'essay')->isNotEmpty();
        if ($passed && !$hasEssay) {
            Certificate::firstOrCreate(
                ['user_id' => Auth::id(), 'exam_set_id' => $examSet->id],
                [
                    'certificate_no' => Certificate::generateCertificateNo(),
                    'score'          => $earnedPoints,
                    'issued_type'    => 'auto',
                    'issued_at'      => now(),
                ]
            );
        }

        return redirect()->route('student.exams.result', $attempt);
    }

    // View result
    public function result(ExamAttempt $attempt)
    {
        abort_unless((int) $attempt->user_id === (int) Auth::id() || Auth::user()->isSuperAdmin(), 403);
        $attempt->load(['examSet', 'answers.question', 'certificate']);

        $dims = \App\Question::$dimensions;
        $dimAnalysis = [];
        foreach (array_keys($dims) as $key) {
            $dimAnalysis[$key] = ['total' => 0, 'correct' => 0, 'points' => 0, 'earned' => 0];
        }

        foreach ($attempt->answers as $ans) {
            $d = $ans->question->dimension;
            if ($d && isset($dimAnalysis[$d])) {
                $dimAnalysis[$d]['total']++;
                $dimAnalysis[$d]['points'] += $ans->question->points;
                if ($ans->is_correct) {
                    $dimAnalysis[$d]['correct']++;
                    $dimAnalysis[$d]['earned'] += $ans->points_earned;
                }
            }
        }

        $hasDimData = collect($dimAnalysis)->sum('total') > 0;

        return view('student.exams.result', compact('attempt', 'dimAnalysis', 'dims', 'hasDimData'));
    }

    // ── Private ──────────────────────────────────────────────────
    private function authorizeExam(ExamSet $examSet)
    {
        abort_unless($examSet->is_active, 403, 'Exam is not active.');

        $user = Auth::user();
        // ครู/แอดมินเข้าทดลองทำได้ทุกห้อง — จำกัดห้องเฉพาะนักเรียน
        if ($user->isStudent() && $examSet->classroom_id && (int) $examSet->classroom_id !== (int) $user->classroom_id) {
            abort(403, 'You are not in the assigned classroom for this exam.');
        }
    }

    private function checkSchedule(ExamSet $examSet)
    {
        if ($examSet->isNotYetOpen()) {
            return redirect()->route('student.exams.show', $examSet)
                ->withErrors(['ยังไม่ถึงเวลาเปิดสอบ กรุณารอจนถึง '.$examSet->available_from->format('d/m/Y H:i')]);
        }
        if ($examSet->isExpired()) {
            return redirect()->route('student.exams.show', $examSet)
                ->withErrors(['หมดสิทธิ์การสอบแล้ว เนื่องจากเกินกำหนด '.$examSet->available_until->format('d/m/Y H:i')]);
        }
        return null;
    }
}
