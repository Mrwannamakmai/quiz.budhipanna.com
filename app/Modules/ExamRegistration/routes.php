<?php

use Illuminate\Support\Facades\Route;

Route::prefix('teacher/exam-registration')
    ->name('teacher.exam_registration.')
    ->middleware(['auth', 'role:superadmin,teacher', 'module:exam_registration'])
    ->namespace('App\Http\Controllers\Teacher')
    ->group(function () {
        // ติดตามผู้สมัคร (ต้องอยู่ก่อน {id} wildcard)
        Route::get('followup/list', 'ExamFollowUpController@index')->name('followup.index');
        Route::get('followup/{id}/edit', 'ExamFollowUpController@edit')->name('followup.edit');
        Route::post('followup/{id}', 'ExamFollowUpController@update')->name('followup.update');

        // พิมพ์ใบสมัคร
        Route::get('print/list', 'ExamPrintController@index')->name('print.index');
        Route::get('print/{id}', 'ExamPrintController@show')->name('print.show');

        // รายงาน
        Route::get('reports/overview', 'ExamReportController@index')->name('reports.index');

        // ตั้งค่า
        Route::get('settings/period', 'ExamPeriodSettingsController@edit')->name('settings.period');
        Route::post('settings/period', 'ExamPeriodSettingsController@update')->name('settings.update');
    });

Route::prefix('exam-registration')
    ->name('exam_registration.')
    ->middleware('web')
    ->namespace('App\Modules\ExamRegistration\Http\Controllers')
    ->group(function () {
        Route::get('public', 'PublicExamRegistrationController@create')->name('public.create');
    });
