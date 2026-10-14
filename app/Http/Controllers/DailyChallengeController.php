<?php

namespace App\Http\Controllers;

use App\DailyAttempt;
use App\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyChallengeController extends Controller
{
    const QUESTION_COUNT = 10;
    const PASS_PERCENT   = 70;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user  = Auth::user();
        $today = today()->toDateString();

        $attempt = DailyAttempt::where('user_id', $user->id)
            ->where('challenge_date', $today)
            ->first();

        $history = DailyAttempt::where('user_id', $user->id)
            ->where('challenge_date', '>=', today()->subDays(6)->toDateString())
            ->orderBy('challenge_date')
            ->get()
            ->keyBy(function ($a) { return $a->challenge_date->toDateString(); });

        $totalQuestions = Question::where('type', 'multiple_choice')
            ->where('is_active', true)
            ->count();

        return view('daily.index', compact('user', 'attempt', 'today', 'history', 'totalQuestions'));
    }

    public function play()
    {
        $user  = Auth::user();
        $today = today()->toDateString();

        if (DailyAttempt::where('user_id', $user->id)->where('challenge_date', $today)->exists()) {
            return redirect()->route('daily.result');
        }

        $questions = $this->getTodayQuestions();

        if ($questions->isEmpty()) {
            return redirect()->route('daily.index')
                ->with('error', 'ยังไม่มีคำถามในระบบ กรุณาติดต่อผู้ดูแล');
        }

        return view('daily.play', compact('questions', 'today'));
    }

    public function submit(Request $request)
    {
        $user  = Auth::user();
        $today = today()->toDateString();

        if (DailyAttempt::where('user_id', $user->id)->where('challenge_date', $today)->exists()) {
            return redirect()->route('daily.result');
        }

        $questions = $this->getTodayQuestions();
        $score     = 0;
        $total     = 0;
        $answers   = [];

        foreach ($questions as $q) {
            $userAnswer = trim($request->input('answer_' . $q->id, ''));
            $total += $q->points;
            $correct = ($userAnswer !== '' && $userAnswer === $q->correct_answer);
            if ($correct) $score += $q->points;
            $answers[$q->id] = [
                'user_answer'    => $userAnswer,
                'correct_answer' => $q->correct_answer,
                'correct'        => $correct,
                'points'         => $q->points,
                'question_text'  => $q->question_text,
                'option_a'       => $q->option_a,
                'option_b'       => $q->option_b,
                'option_c'       => $q->option_c,
                'option_d'       => $q->option_d,
            ];
        }

        $passed = $total > 0 && ($score / $total * 100) >= self::PASS_PERCENT;

        $yesterday  = today()->subDay()->toDateString();
        $newStreak  = ($user->last_daily_date === $yesterday)
            ? (($user->daily_streak ?? 0) + 1)
            : 1;
        $longest = max($newStreak, $user->longest_streak ?? 0);

        DailyAttempt::create([
            'user_id'        => $user->id,
            'challenge_date' => $today,
            'score'          => $score,
            'total'          => $total,
            'passed'         => $passed,
            'answers'        => json_encode($answers),
            'streak_at'      => $newStreak,
        ]);

        $user->update([
            'daily_streak'    => $newStreak,
            'longest_streak'  => $longest,
            'last_daily_date' => $today,
        ]);

        session(['daily_result_' . $today => [
            'score'   => $score,
            'total'   => $total,
            'passed'  => $passed,
            'streak'  => $newStreak,
            'longest' => $longest,
            'answers' => $answers,
        ]]);

        return redirect()->route('daily.result');
    }

    public function result()
    {
        $user  = Auth::user();
        $today = today()->toDateString();

        $resultData = session('daily_result_' . $today);

        if (!$resultData) {
            $attempt = DailyAttempt::where('user_id', $user->id)
                ->where('challenge_date', $today)
                ->first();

            if (!$attempt) {
                return redirect()->route('daily.index');
            }

            $resultData = [
                'score'   => $attempt->score,
                'total'   => $attempt->total,
                'passed'  => $attempt->passed,
                'streak'  => $attempt->streak_at,
                'longest' => $user->longest_streak ?? 0,
                'answers' => $attempt->answers_decoded,
            ];
        }

        $scorePct = $resultData['total'] > 0
            ? (int) round($resultData['score'] / $resultData['total'] * 100)
            : 0;

        return view('daily.result', compact('user', 'resultData', 'scorePct', 'today'));
    }

    private function getTodayQuestions()
    {
        $allIds = Question::where('type', 'multiple_choice')
            ->where('is_active', true)
            ->pluck('id')
            ->toArray();

        $count = count($allIds);

        if ($count === 0) {
            return collect();
        }

        // Seeded Fisher-Yates shuffle by today's date — same questions for everyone each day
        mt_srand(crc32(today()->toDateString()));
        for ($i = $count - 1; $i > 0; $i--) {
            $j = mt_rand(0, $i);
            $tmp        = $allIds[$i];
            $allIds[$i] = $allIds[$j];
            $allIds[$j] = $tmp;
        }

        $todayIds = array_slice($allIds, 0, min(self::QUESTION_COUNT, $count));

        return Question::whereIn('id', $todayIds)
            ->get()
            ->sortBy(function ($q) use ($todayIds) {
                return array_search($q->id, $todayIds);
            })
            ->values();
    }
}
