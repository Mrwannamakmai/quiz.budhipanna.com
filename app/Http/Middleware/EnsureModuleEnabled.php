<?php

namespace App\Http\Middleware;

use App\Support\Settings\Settings;
use Closure;

/**
 * ใช้: ->middleware('module:attendance')
 * ถ้าโมดูลถูกปิดในหน้าตั้งค่า จะตอบ 404
 */
class EnsureModuleEnabled
{
    public function handle($request, Closure $next, $module)
    {
        abort_unless(Settings::moduleEnabled($module), 404);
        return $next($request);
    }
}
