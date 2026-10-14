<?php

/*
|--------------------------------------------------------------------------
| Web Routes – db_quiz / Laravel 5.8
|--------------------------------------------------------------------------
|
| Roles: superadmin | teacher | student
|
*/

use Illuminate\Support\Facades\Route;

// ── Public ───────────────────────────────────────────────────────────────
Route::middleware('track.visitor')->group(function () {
    Route::get('/', function () {
        $mapAttempt = function ($a) {
            $raw  = $a->guest_name ?? ($a->user->name ?? null);
            $name = $raw ? mb_substr(explode(' ', trim($raw))[0], 0, 12) : 'ไม่ระบุ';
            return [
                'name'   => $name,
                'pct'    => $a->total_points > 0 ? round($a->score / $a->total_points * 100, 1) : 0,
                'exam'   => mb_substr($a->examSet->name ?? '-', 0, 22),
                'passed' => $a->passed,
                'at'     => $a->submitted_at,
            ];
        };

        // dedup: คนเดียวกัน (user_id หรือ guest_name) แสดงแค่ครั้งเดียว
        $dedup = function ($attempts) use ($mapAttempt) {
            $seen = [];
            $out  = [];
            foreach ($attempts as $a) {
                if ($a->user_id) {
                    $key = 'u:' . $a->user_id;
                } elseif (!empty($a->guest_name)) {
                    $key = 'g:' . mb_strtolower(trim($a->guest_name));
                } else {
                    $key = 'anon:' . $a->id; // ไม่มีชื่อ นับแยกกัน
                }
                if (isset($seen[$key])) continue;
                $seen[$key] = true;
                $out[] = $mapAttempt($a);
                if (count($out) >= 3) break;
            }
            return collect($out);
        };

        $topScores = $dedup(
            \App\ExamAttempt::whereNotNull('submitted_at')
                ->where('total_points', '>', 0)
                ->orderByRaw('(score / total_points) DESC')
                ->with(['user', 'examSet'])
                ->limit(50)->get()
        );

        $recentScores = $dedup(
            \App\ExamAttempt::whereNotNull('submitted_at')
                ->where('total_points', '>', 0)
                ->latest('submitted_at')
                ->with(['user', 'examSet'])
                ->limit(50)->get()
        );

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
            'pretest'      => \App\ExamSet::where('is_active', true)->where('is_public', true)->count(),
        ];

        $updates = \App\SystemUpdate::published()->latest()->limit(8)->get();

        $examRegistrationOpen = \App\ExamSession::where('is_published', true)
            ->where('status', 'open')
            ->exists();

        return view('landing', compact('topScores', 'recentScores', 'visitorStats', 'siteStats', 'updates', 'examRegistrationOpen'));
    })->name('landing');

    // Certificate public verification
    Route::get('verify/{certificateNo}', 'Student\CertificateController@verify')
        ->name('certificates.verify');

    // ── Pre-Test (ฟรี ไม่ต้องล๊อกอิน) ────────────────────────────────────────
    // ── Know How (คลังความรู้ — public) ─────────────────────────────────────
    Route::get('know-how',          'KnowHowController@index')->name('knowhow.index');
    Route::get('know-how/{article}','KnowHowController@show') ->name('knowhow.show');

    Route::get( 'pretest',                  'PreTestController@index') ->name('pretest.index');
    Route::get( 'pretest/guide',            'PreTestController@guide') ->name('pretest.guide');
    Route::get( 'pretest/stats',            'PreTestController@stats') ->name('pretest.stats');
    Route::get( 'pretest/{examSet}',        'PreTestController@show')  ->name('pretest.show');
    Route::match(['get','post'], 'pretest/{examSet}/start', 'PreTestController@start')->name('pretest.start');
    Route::post('pretest/{examSet}/submit', 'PreTestController@submit')->name('pretest.submit');
    Route::get('exam-announcements', 'ExamAnnouncementController@index')
        ->middleware('module:exam_registration')
        ->name('exam_announcements.index');
    Route::get('exam-registrations/live', 'ExamAnnouncementController@liveRegistrations')
        ->name('exam_registrations.live');
    Route::get('exam-results/search', 'ExamAnnouncementController@searchPassed')
        ->name('exam_results.search');

    // Public Dharma exam registration (no login required)
    Route::get('exam-registration', 'PublicExamRegistrationController@index')
        ->name('public_exam_registration');
    Route::post('exam-registration', 'PublicExamRegistrationController@store')
        ->name('public_exam_registration.store');
    Route::get('api/thai-addresses', 'PublicExamRegistrationController@addresses')
        ->name('api.thai_addresses');

});

// Auth (Login / Register / Password reset)
Auth::routes(['verify' => false]);

// ── Authenticated (all roles) ────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // ── Daily Challenge ────────────────────────────────────────────
    Route::prefix('daily')->name('daily.')->group(function () {
        Route::get('/',       'DailyChallengeController@index') ->name('index');
        Route::get('/play',   'DailyChallengeController@play')  ->name('play');
        Route::post('/submit','DailyChallengeController@submit')->name('submit');
        Route::get('/result', 'DailyChallengeController@result')->name('result');
    });

    // Redirect to role-appropriate home after login
    Route::get('/home', function () {
        $user = auth()->user();
        if ($user->isSuperAdmin()) return redirect()->route('admin.dashboard');
        if ($user->isBoss())       return redirect()->route('boss.dashboard');
        if ($user->isTeacher())    return redirect()->route('teacher.dashboard');
        return redirect()->route('student.dashboard');
    })->name('home');

    Route::get('profile', 'ProfileController@edit')->name('profile.edit');
    Route::put('profile', 'ProfileController@update')->name('profile.update');

        Route::prefix('teacher/exam-registration')
            ->name('exam_registration.')
            ->middleware(['role:student,teacher,superadmin', 'module:exam_registration'])
            ->group(function () {
                Route::get('/', 'Teacher\ExamRegistrationController@index')->name('index');
                Route::post('/', 'Teacher\ExamRegistrationController@store')->name('store');
                Route::get('list', 'Teacher\ExamRegistrationController@list')->name('list');
                Route::get('export', 'Teacher\ExamRegistrationController@export')->name('export');
                Route::post('{registration}/follow-up', 'Teacher\ExamRegistrationController@updateFollowUp')->name('follow_up');
                
                Route::get('{registration}', 'Teacher\ExamRegistrationController@show')->name('show');
                Route::get('{registration}/edit', 'Teacher\ExamRegistrationController@edit')->name('edit');
                Route::put('{registration}', 'Teacher\ExamRegistrationController@update')->name('update');
                Route::delete('{registration}', 'Teacher\ExamRegistrationController@destroy')->name('destroy');
            });

    // ── BOSS ───────────────────────────────────────────────────────
    Route::prefix('boss')
        ->name('boss.')
        ->middleware('role:boss,superadmin')
        ->group(function () {
        Route::get('/', 'Boss\BossDashboardController@index')->name('dashboard');
        Route::resource('registrations', 'Boss\ExamRegistrationController')->except(['create', 'store', 'destroy']);
    });

    // ── ADMIN ──────────────────────────────────────────────────────
    Route::prefix('admin')
        ->name('admin.')
        ->middleware('role:superadmin')
        ->group(function () {

        Route::get('/', 'Admin\ReportController@dashboard')->name('dashboard');

        // Users
        Route::resource('users', 'Admin\UserController');

        // Classrooms
        Route::resource('classrooms', 'Admin\ClassroomController');

        // Courses
        Route::resource('courses', 'Admin\CourseController');

        // Exam sessions and public announcements
        Route::get('exam-sessions', 'Admin\ExamSessionController@index')->name('exam_sessions.index');
        Route::get('exam-sessions/create', 'Admin\ExamSessionController@create')->name('exam_sessions.create');
        Route::post('exam-sessions', 'Admin\ExamSessionController@store')->name('exam_sessions.store');
        Route::get('exam-sessions/{examSession}/registrations', 'Admin\ExamSessionController@registrations')->name('exam_sessions.registrations');
        Route::put('exam-sessions/registrations/{registration}/status', 'Admin\ExamSessionController@updateRegistrationStatus')->name('exam_sessions.registrations.status');
        Route::get('exam-sessions/{examSession}/edit', 'Admin\ExamSessionController@edit')->name('exam_sessions.edit');
        Route::put('exam-sessions/{examSession}', 'Admin\ExamSessionController@update')->name('exam_sessions.update');
        Route::get('exam-sessions/{examSession}/export', 'Admin\ExamSessionController@exportRegistrations')->name('exam_sessions.export');

        // System settings
        Route::get('settings', 'Admin\SettingController@index')->name('settings.index');
        Route::put('settings/{group}', 'Admin\SettingController@update')->name('settings.update');

        // System Updates (What's New)
        Route::resource('updates', 'Admin\SystemUpdateController');
        Route::post('updates/{update}/toggle', 'Admin\SystemUpdateController@toggle')
            ->name('updates.toggle');

        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('attempts',     'Admin\ReportController@attempts')    ->name('attempts');
            Route::get('certificates', 'Admin\ReportController@certificates')->name('certificates');
            Route::get('visitors',     'Admin\ReportController@visitorLogs') ->name('visitors');
        });

    });

    // ── TEACHER ────────────────────────────────────────────────────
    Route::prefix('teacher')
        ->name('teacher.')
        ->middleware('role:superadmin,teacher')
        ->group(function () {

        Route::get('/', 'Teacher\ExamSetController@index')->name('dashboard');

        // Subjects
        Route::resource('subjects', 'Teacher\SubjectController');

        // Question Bank (static routes before resource to avoid {question} match)
        Route::get('question_bank/import', 'Teacher\QuestionBankController@importForm')
            ->name('question_bank.import_form');
        Route::post('question_bank/import', 'Teacher\QuestionBankController@import')
            ->name('question_bank.import');
        Route::post('question_bank/bulk-assign', 'Teacher\QuestionBankController@bulkAssign')
            ->name('question_bank.bulk_assign');
        Route::resource('question_bank', 'Teacher\QuestionBankController')
            ->except(['show'])
            ->parameters(['question_bank' => 'question']);
        Route::post('question_bank/{question}/toggle', 'Teacher\QuestionBankController@toggleActive')
            ->name('question_bank.toggle');
        Route::get('question_bank/{question}/assign', 'Teacher\QuestionBankController@assignForm')
            ->name('question_bank.assign_form');
        Route::post('question_bank/{question}/assign', 'Teacher\QuestionBankController@assign')
            ->name('question_bank.assign');

        // Exam Sets
        Route::resource('exam_sets', 'Teacher\ExamSetController');
        Route::post('exam_sets/{exam_set}/toggle', 'Teacher\ExamSetController@toggleActive')
            ->name('exam_sets.toggle');

        // Questions (nested under exam_set)
        Route::prefix('exam_sets/{exam_set}')->name('exam_sets.')->group(function () {
            Route::resource('questions', 'Teacher\QuestionController');
            Route::post('questions/reorder', 'Teacher\QuestionController@reorder')
                ->name('questions.reorder');
            Route::get('questions/import',  'Teacher\QuestionController@importForm')
                ->name('questions.import.form');
            Route::post('questions/import', 'Teacher\QuestionController@import')
                ->name('questions.import');
        });

        // Essay Criteria (nested under question_bank)
        Route::prefix('question_bank/{question}/criteria')->name('question_bank.criteria.')->group(function () {
            Route::get('/',              'Teacher\EssayCriteriaController@index')    ->name('index');
            Route::post('/',             'Teacher\EssayCriteriaController@store')    ->name('store');
            Route::put('{criterion}',    'Teacher\EssayCriteriaController@update')   ->name('update');
            Route::delete('{criterion}', 'Teacher\EssayCriteriaController@destroy')  ->name('destroy');
            Route::post('bulk',          'Teacher\EssayCriteriaController@bulkStore')->name('bulk');
        });

        // Student Results
        Route::prefix('results')->name('results.')->group(function () {
            Route::get('/',                                   'Teacher\StudentResultController@index')        ->name('index');
            Route::get('export',                              'Teacher\StudentResultController@export')       ->name('export');
            Route::get('item-analysis/{examSet}',             'Teacher\StudentResultController@itemAnalysis') ->name('item_analysis');
            Route::get('{attempt}',                           'Teacher\StudentResultController@show')         ->name('show');
            Route::post('grade/{answer}',                     'Teacher\StudentResultController@gradeEssay')   ->name('grade');
            Route::post('certificate/{attempt}',              'Teacher\StudentResultController@issueCertificate')->name('certificate');
        });

        // Know How (คลังความรู้)
        Route::prefix('articles')->name('articles.')->group(function () {
            Route::get('/',              'Teacher\ArticleController@index')  ->name('index');
            Route::get('/create',        'Teacher\ArticleController@create') ->name('create');
            Route::post('/',             'Teacher\ArticleController@store')  ->name('store');
            Route::get('/{article}/edit','Teacher\ArticleController@edit')   ->name('edit');
            Route::put('/{article}',     'Teacher\ArticleController@update') ->name('update');
            Route::delete('/{article}',  'Teacher\ArticleController@destroy')->name('destroy');
            Route::post('/{article}/toggle','Teacher\ArticleController@toggle')->name('toggle');
        });

        // Analytics
        Route::prefix('analytics')->name('analytics.')->group(function () {
            Route::get('/',              'Teacher\AnalyticsController@index')  ->name('index');
            Route::get('student/{user}', 'Teacher\AnalyticsController@student')->name('student');
        });
    });

    // ── STUDENT ────────────────────────────────────────────────────
    Route::prefix('student')
        ->name('student.')
        ->middleware('role:student,teacher,superadmin')
        ->group(function () {

        Route::get('/', 'Student\DashboardController@index')->name('dashboard');

        // Exams
        Route::get ('exams',                  'Student\ExamController@index')  ->name('exams.index');
        Route::get ('exams/{exam_set}',       'Student\ExamController@show')   ->name('exams.show');
        Route::post('exams/{exam_set}/start', 'Student\ExamController@start')  ->name('exams.start');
        Route::post('attempts/{attempt}',     'Student\ExamController@submit') ->name('exams.submit');
        Route::get ('attempts/{attempt}',     'Student\ExamController@result') ->name('exams.result');

        // Certificates
        Route::get('certificates',       'Student\CertificateController@index')->name('certificates.index');
        Route::get('certificates/{certificate}', 'Student\CertificateController@show')->name('certificates.show');

        // Leaderboard & Stats
        Route::get('leaderboard',          'Student\LeaderboardController@index')->name('leaderboard.index');
        Route::get('leaderboard/{user}',   'Student\LeaderboardController@show') ->name('leaderboard.show');
    });
});
