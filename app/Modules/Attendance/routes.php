<?php

use Illuminate\Support\Facades\Route;

/*
| Routes ของโมดูลเช็คชื่อ (โหลดจาก AttendanceServiceProvider, middleware web)
*/

$ns = 'App\Modules\Attendance\Http\Controllers';

// ── ครู / แอดมิน ─────────────────────────────────────────────
Route::prefix('teacher/attendance')
    ->name('attendance.teacher.')
    ->middleware(['auth', 'role:superadmin,teacher', 'module:attendance'])
    ->namespace($ns)
    ->group(function () {
        Route::get('/',                         'TeacherAttendanceController@index')       ->name('index');
        Route::get('sessions/create',           'TeacherAttendanceController@create')      ->name('sessions.create');
        Route::post('sessions',                 'TeacherAttendanceController@store')       ->name('sessions.store');
        Route::get('sessions/{session}',        'TeacherAttendanceController@show')        ->name('sessions.show');
        Route::put('sessions/{session}',        'TeacherAttendanceController@saveMarks')   ->name('sessions.marks');
        Route::delete('sessions/{session}',     'TeacherAttendanceController@destroy')     ->name('sessions.destroy');
        Route::post('sessions/{session}/open',  'TeacherAttendanceController@openCheckin') ->name('sessions.open');
        Route::post('sessions/{session}/close', 'TeacherAttendanceController@closeCheckin')->name('sessions.close');
        Route::get('report',                    'AttendanceReportController@index')        ->name('report');
        Route::get('report/export',             'AttendanceReportController@export')       ->name('report.export');
    });

// ── นักเรียน ─────────────────────────────────────────────────
Route::prefix('student/attendance')
    ->name('attendance.student.')
    ->middleware(['auth', 'role:student,teacher,superadmin', 'module:attendance'])
    ->namespace($ns)
    ->group(function () {
        Route::get('/',         'StudentAttendanceController@index')  ->name('index');
        Route::post('check-in', 'StudentAttendanceController@checkIn')->name('checkin')->middleware('throttle:10,1');
    });
