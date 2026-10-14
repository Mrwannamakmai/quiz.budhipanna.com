<?php

namespace App\Http\Controllers\Teacher;

use App\Certificate;
use App\Course;
use App\EssayCriterion;
use App\EssayCriterionScore;
use App\ExamAttempt;
use App\ExamSet;
use App\Subject;
use App\StudentAnswer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

class StudentResultController extends Controller
{
    private function teacherExamSetIds()
    {
        if (Auth::user()->isSuperAdmin()) {
            return ExamSet::pluck('id');
        }

        $courseIds  = Course::where('user_id', Auth::id())->pluck('id');
        $subjectIds = Subject::whereIn('course_id', $courseIds)->pluck('id');
        return ExamSet::whereIn('subject_id', $subjectIds)->pluck('id');
    }

    public function index(Request $request)
    {
        $examSetIds = $this->teacherExamSetIds();

        $allAttempts = ExamAttempt::whereIn('exam_set_id', $examSetIds)
            ->whereNotNull('submitted_at')
            ->get();

        $examSets = ExamSet::whereIn('id', $examSetIds)->with('subject')->get();

        // Overall stats
        $stats = [
            'total_attempts' => $allAttempts->count(),
            'total_students' => $allAttempts->pluck('user_id')->unique()->count(),
            'passed'         => $allAttempts->where('passed', true)->count(),
            'failed'         => $allAttempts->where('passed', false)->count(),
            'avg_score'      => $allAttempts->count() > 0
                ? round($allAttempts->avg(function ($a) {
                    return $a->total_points > 0 ? ($a->score / $a->total_points * 100) : 0;
                }), 1)
                : 0,
            'pending_essay'  => ExamAttempt::whereIn('exam_set_id', $examSetIds)
                ->whereNotNull('submitted_at')
                ->whereHas('answers', function ($q) {
                    $q->where('is_graded', false)->whereNotNull('essay_answer');
                })->count(),
        ];

        // Per exam set stats
        $examSetStats = $examSets->map(function ($es) use ($allAttempts) {
            $esAttempts = $allAttempts->where('exam_set_id', $es->id);
            $count = $esAttempts->count();
            $passed = $esAttempts->where('passed', true)->count();
            return [
                'id'          => $es->id,
                'name'        => $es->name,
                'subject'     => $es->subject->name ?? '-',
                'total'       => $count,
                'passed'      => $passed,
                'failed'      => $count - $passed,
                'pass_rate'   => $count > 0 ? round($passed / $count * 100, 1) : 0,
                'avg_score'   => $count > 0
                    ? round($esAttempts->avg(function ($a) {
                        return $a->total_points > 0 ? ($a->score / $a->total_points * 100) : 0;
                    }), 1)
                    : 0,
            ];
        })->sortByDesc('total')->values();

        // Score distribution (0-10, 11-20, ..., 91-100)
        $distribution = collect(range(0, 9))->map(function ($i) use ($allAttempts) {
            $min = $i * 10;
            $max = $i * 10 + 10;
            $label = $min . '-' . ($max === 100 ? '100' : ($max));
            $count = $allAttempts->filter(function ($a) use ($min, $max) {
                $pct = $a->total_points > 0 ? ($a->score / $a->total_points * 100) : 0;
                return $pct >= $min && ($max === 100 ? $pct <= $max : $pct < $max);
            })->count();
            return ['label' => $label, 'count' => $count];
        });

        // Students who have taken exams
        $studentIds = $allAttempts->pluck('user_id')->unique();
        $students = User::whereIn('id', $studentIds)->orderBy('name')->get(['id', 'name']);

        // Recent attempts for table
        $query = ExamAttempt::whereIn('exam_set_id', $examSetIds)
            ->with(['user', 'examSet'])
            ->whereNotNull('submitted_at');

        if ($request->filled('exam_set_id')) {
            $query->where('exam_set_id', $request->exam_set_id);
        }
        if ($request->filled('passed')) {
            $query->where('passed', $request->passed === '1');
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $attempts = $query->latest('submitted_at')->paginate(30);

        return view('teacher.results.index', compact(
            'attempts', 'examSets', 'stats', 'examSetStats', 'distribution', 'students'
        ));
    }

    public function show(ExamAttempt $attempt)
    {
        abort_unless(in_array($attempt->exam_set_id, $this->teacherExamSetIds()->toArray()), 403);
        $attempt->load([
            'user',
            'examSet',
            'studentAnswers.question.essayCriteria',
            'studentAnswers.criterionScores.criterion',
            'certificate',
        ]);
        return view('teacher.results.show', compact('attempt'));
    }

    public function gradeEssay(Request $request, StudentAnswer $answer)
    {
        $attempt = $answer->attempt;
        abort_unless(in_array($attempt->exam_set_id, $this->teacherExamSetIds()->toArray()), 403);

        $criteria = $answer->question->essayCriteria;

        if ($criteria->isNotEmpty()) {
            $data = $request->validate([
                'criterion_scores'            => 'required|array',
                'criterion_scores.*.points'   => 'required|numeric|min:0',
                'criterion_scores.*.comment'  => 'nullable|string|max:1000',
                'grader_comment'              => 'nullable|string|max:2000',
            ]);

            $totalEarned = 0;
            foreach ($criteria as $c) {
                $scoreData = $data['criterion_scores'][$c->id] ?? ['points' => 0];
                $points = min($scoreData['points'], $c->max_points);

                EssayCriterionScore::updateOrCreate(
                    ['student_answer_id' => $answer->id, 'essay_criterion_id' => $c->id],
                    ['points_earned' => $points, 'comment' => $scoreData['comment'] ?? null]
                );
                $totalEarned += $points;
            }

            $update = [
                'points_earned'  => $totalEarned,
                'is_graded'      => true,
            ];
            if (StudentAnswer::hasGraderCommentColumn()) {
                $update['grader_comment'] = $data['grader_comment'] ?? null;
            }
            $answer->update($update);
        } else {
            $data = $request->validate([
                'points_earned'  => 'required|numeric|min:0',
                'grader_comment' => 'nullable|string|max:2000',
            ]);

            $update = [
                'points_earned'  => $data['points_earned'],
                'is_graded'      => true,
            ];
            if (StudentAnswer::hasGraderCommentColumn()) {
                $update['grader_comment'] = $data['grader_comment'] ?? null;
            }
            $answer->update($update);
        }

        $this->recalculateAttempt($attempt);

        return back()->with('success', 'ให้คะแนนสำเร็จ');
    }

    private function recalculateAttempt(ExamAttempt $attempt)
    {
        $attempt->load('studentAnswers.question');
        $totalEarned = $attempt->studentAnswers->sum('points_earned');
        $totalPoints = 0;
        foreach ($attempt->studentAnswers as $a) {
            if ($a->question) {
                $totalPoints += $a->question->points;
            }
        }

        $passed = ($totalPoints > 0)
            ? ($totalEarned / $totalPoints * 100) >= $attempt->examSet->pass_percentage
            : false;

        $attempt->update([
            'score'        => $totalEarned,
            'total_points' => $totalPoints,
            'passed'       => $passed,
        ]);

        if ($passed && $attempt->user_id) {
            $alreadyIssued = Certificate::where('user_id', $attempt->user_id)
                ->where('exam_set_id', $attempt->exam_set_id)
                ->exists();

            if (!$alreadyIssued) {
                Certificate::create([
                    'exam_set_id'    => $attempt->exam_set_id,
                    'user_id'        => $attempt->user_id,
                    'issued_by'      => Auth::id(),
                    'certificate_no' => Certificate::generateCertificateNo(),
                    'score'          => $totalEarned,
                    'issued_type'    => 'manual',
                    'issued_at'      => now(),
                ]);
            }
        }
    }

    public function export(Request $request)
    {
        $examSetIds = $this->teacherExamSetIds();

        $query = ExamAttempt::whereIn('exam_set_id', $examSetIds)
            ->whereNotNull('submitted_at')
            ->with(['user', 'examSet.subject']);

        if ($request->filled('exam_set_id')) {
            $query->where('exam_set_id', $request->exam_set_id);
        }
        if ($request->filled('passed')) {
            $query->where('passed', $request->passed === '1');
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }

        $attempts = $query->latest('submitted_at')->get();

        $filename = 'results_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($attempts) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM — ให้ Excel อ่านภาษาไทยถูกต้อง
            fwrite($handle, "\xEF\xBB\xBF");

            // Header row
            fputcsv($handle, [
                'ลำดับ', 'ชื่อนักเรียน', 'ชุดข้อสอบ', 'วิชา',
                'คะแนนที่ได้', 'คะแนนเต็ม', 'เปอร์เซ็นต์', 'ผล',
                'ครั้งที่', 'เวลาที่ใช้ (นาที)', 'วันที่ส่ง',
            ]);

            foreach ($attempts as $i => $a) {
                $pct = $a->total_points > 0
                    ? round($a->score / $a->total_points * 100, 1) : 0;

                fputcsv($handle, [
                    $i + 1,
                    $a->user->name ?? '-',
                    $a->examSet->name ?? '-',
                    $a->examSet->subject->name ?? '-',
                    $a->score,
                    $a->total_points,
                    $pct . '%',
                    $a->passed ? 'ผ่าน' : 'ไม่ผ่าน',
                    $a->attempt_number ?? '-',
                    $a->duration ?? '-',
                    $a->submitted_at ? $a->submitted_at->format('d/m/Y H:i') : '-',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function itemAnalysis(ExamSet $examSet)
    {
        abort_unless(in_array($examSet->id, $this->teacherExamSetIds()->toArray()), 403);

        // All completed attempts for this exam set
        $attempts = ExamAttempt::where('exam_set_id', $examSet->id)
            ->whereNotNull('submitted_at')
            ->with('answers')
            ->get();

        $totalAttempts = $attempts->count();

        if ($totalAttempts === 0) {
            return view('teacher.results.item_analysis', compact('examSet'))
                ->with('items', collect())
                ->with('totalAttempts', 0)
                ->with('summary', null);
        }

        // Sort by score % for discrimination groups
        $sorted = $attempts->sortByDesc(function ($a) {
            return $a->total_points > 0 ? $a->score / $a->total_points : 0;
        })->values();

        $cut    = max(1, (int) round($totalAttempts * 0.27));
        $upper  = $sorted->take($cut)->pluck('id');
        $lower  = $sorted->slice($totalAttempts - $cut)->pluck('id');

        // Get all questions for this exam set (both pivot and direct)
        $questions = $examSet->questions()->get();
        if ($questions->isEmpty()) {
            $questions = $examSet->directQuestions()->where('is_active', true)->orderBy('sort_order')->get();
        }

        // All answers indexed by question_id
        $allAnswers = \App\StudentAnswer::whereIn('exam_attempt_id', $attempts->pluck('id'))
            ->whereNotNull('answer')
            ->get()
            ->groupBy('question_id');

        $items = $questions->map(function ($q) use ($allAnswers, $upper, $lower) {
            $answers = $allAnswers->get($q->id, collect());
            $total   = $answers->count();

            if ($total === 0) {
                return null;
            }

            // Difficulty (P)
            $correct   = $answers->where('is_correct', true)->count();
            $p         = round($correct / $total, 4);

            // Discrimination (D)
            $upperAns  = $answers->whereIn('exam_attempt_id', $upper);
            $lowerAns  = $answers->whereIn('exam_attempt_id', $lower);
            $pUpper    = $upperAns->count() > 0 ? $upperAns->where('is_correct', true)->count() / $upperAns->count() : 0;
            $pLower    = $lowerAns->count() > 0 ? $lowerAns->where('is_correct', true)->count() / $lowerAns->count() : 0;
            $d         = round($pUpper - $pLower, 4);

            // Distractor analysis (multiple choice only)
            $distractors = null;
            if ($q->type === 'multiple_choice') {
                $distractors = [];
                foreach (['a', 'b', 'c', 'd'] as $opt) {
                    $count = $answers->where('answer', $opt)->count();
                    $distractors[$opt] = [
                        'count' => $count,
                        'pct'   => $total > 0 ? round($count / $total * 100, 1) : 0,
                        'text'  => $q->{'option_' . $opt},
                    ];
                }
            }

            return [
                'question'    => $q,
                'total'       => $total,
                'correct'     => $correct,
                'p'           => $p,
                'p_pct'       => round($p * 100, 1),
                'd'           => $d,
                'd_pct'       => round($d * 100, 1),
                'distractors' => $distractors,
                'difficulty'  => $p >= 0.8 ? 'easy' : ($p >= 0.4 ? 'medium' : 'hard'),
                'discrim'     => $d >= 0.4 ? 'excellent' : ($d >= 0.3 ? 'good' : ($d >= 0.2 ? 'fair' : 'poor')),
            ];
        })->filter()->values();

        $summary = $items->isNotEmpty() ? [
            'avg_p'         => round($items->avg('p') * 100, 1),
            'avg_d'         => round($items->avg('d') * 100, 1),
            'easy_count'    => $items->where('difficulty', 'easy')->count(),
            'medium_count'  => $items->where('difficulty', 'medium')->count(),
            'hard_count'    => $items->where('difficulty', 'hard')->count(),
            'excellent_d'   => $items->where('discrim', 'excellent')->count(),
            'good_d'        => $items->where('discrim', 'good')->count(),
            'fair_d'        => $items->where('discrim', 'fair')->count(),
            'poor_d'        => $items->where('discrim', 'poor')->count(),
        ] : null;

        return view('teacher.results.item_analysis',
            compact('examSet', 'items', 'totalAttempts', 'summary'));
    }

    public function issueCertificate(Request $request, ExamAttempt $attempt)
    {
        abort_unless(in_array($attempt->exam_set_id, $this->teacherExamSetIds()->toArray()), 403);

        $cert = Certificate::firstOrCreate(
            ['user_id' => $attempt->user_id, 'exam_set_id' => $attempt->exam_set_id],
            [
                'issued_by'      => Auth::id(),
                'certificate_no' => Certificate::generateCertificateNo(),
                'score'          => $attempt->score,
                'issued_type'    => 'manual',
                'issued_at'      => now(),
            ]
        );

        return back()->with('success', 'ออกใบประกาศนียบัตรสำเร็จ: ' . $cert->certificate_no);
    }
}
