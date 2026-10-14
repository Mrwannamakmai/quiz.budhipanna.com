<?php

namespace App\Support\Settings;

/**
 * ตัวช่วยเรียกใช้แบบสั้น:
 *   Settings::get('attendance.late_after', 15)
 *   Settings::set('general.school_name', 'สำนักเรียน...')
 * ใน Blade: {{ \App\Support\Settings\Settings::get('general.school_name') }}
 */
class Settings
{
    public static function get($key, $default = null)
    {
        return app(SettingsManager::class)->get($key, $default);
    }

    public static function set($key, $value)
    {
        app(SettingsManager::class)->set($key, $value);
    }

    public static function moduleEnabled($module)
    {
        return app(SettingsManager::class)->moduleEnabled($module);
    }

    public static function timezone()
    {
        $tz = static::get('general.timezone', 'Asia/Bangkok');
        return in_array($tz, timezone_identifiers_list(), true) ? $tz : 'Asia/Bangkok';
    }
}
