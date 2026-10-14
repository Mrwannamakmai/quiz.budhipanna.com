<?php

namespace App\Http\Controllers\Student;

use App\Certificate;
use App\DailyAttempt;
use App\ExamAttempt;
use App\ExamSet;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalExams = ExamSet::active()
            ->where(function ($q) use ($user) {
                $q->whereNull('classroom_id')
                  ->orWhere('classroom_id', $user->classroom_id);
            })
            ->count();

        $passedCount = ExamAttempt::where('user_id', $user->id)
            ->whereNotNull('submitted_at')
            ->where('passed', true)
            ->count();

        $attempts = ExamAttempt::where('user_id', $user->id)
            ->whereNotNull('submitted_at')
            ->count();

        $certificates = Certificate::where('user_id', $user->id)->count();

        $recentExamSets = ExamSet::active()
            ->where(function ($q) use ($user) {
                $q->whereNull('classroom_id')
                  ->orWhere('classroom_id', $user->classroom_id);
            })
            ->with('subject')
            ->latest()
            ->limit(5)
            ->get();

        $recentCertificates = Certificate::where('user_id', $user->id)
            ->with('examSet')
            ->latest('issued_at')
            ->limit(5)
            ->get();

        $dailyAttempt = DailyAttempt::where('user_id', $user->id)
            ->where('challenge_date', today()->toDateString())
            ->first();

        return view('student.dashboard', compact(
            'totalExams',
            'passedCount',
            'attempts',
            'certificates',
            'recentExamSets',
            'recentCertificates',
            'dailyAttempt'
        ));
    }
}
