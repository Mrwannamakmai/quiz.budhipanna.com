<?php

namespace App\Modules\Attendance\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;

/**
 * Controller ฐานของโมดูล — ถ้ายังไม่ได้สร้างตารางของโมดูล จะแสดงหน้าแนะนำการติดตั้งแทน error
 */
abstract class BaseController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            static $installed = null;
            if ($installed === null) {
                try {
                    $installed = Schema::hasTable('attendance_sessions') && Schema::hasTable('attendance_records');
                } catch (\Throwable $e) {
                    $installed = false;
                }
            }
            if (!$installed) {
                return response()->view('attendance::not-installed', [], 503);
            }
            return $next($request);
        });
    }
}
