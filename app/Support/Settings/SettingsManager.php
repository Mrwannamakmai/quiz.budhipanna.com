<?php

namespace App\Support\Settings;

use App\Setting;
use Illuminate\Contracts\Cache\Repository as Cache;

/**
 * อ่าน/เขียนค่าตั้งค่า (ตาราง settings) พร้อม cache
 * ถ้ายังไม่ได้สร้างตาราง settings จะใช้ค่า default จากทะเบียนแทน (ระบบไม่ล้ม)
 */
class SettingsManager
{
    const CACHE_KEY = 'app_settings.v1';

    protected $registry;
    protected $cache;
    protected $values = null;

    public function __construct(SettingsRegistry $registry, Cache $cache)
    {
        $this->registry = $registry;
        $this->cache    = $cache;
    }

    public function registry()
    {
        return $this->registry;
    }

    public function get($key, $default = null)
    {
        $values = $this->all();
        if (array_key_exists($key, $values)) {
            return $this->registry->cast($key, $values[$key]);
        }
        $registered = $this->registry->defaultOf($key);
        return $registered !== null ? $registered : $default;
    }

    public function set($key, $value)
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }
        Setting::updateOrCreate(['key' => $key], ['value' => $value === null ? null : (string) $value]);
        $this->flush();
    }

    public function setMany(array $pairs)
    {
        foreach ($pairs as $key => $value) {
            if (is_bool($value)) {
                $value = $value ? '1' : '0';
            }
            Setting::updateOrCreate(['key' => $key], ['value' => $value === null ? null : (string) $value]);
        }
        $this->flush();
    }

    /** ค่าที่บันทึกไว้ทั้งหมด (key => value ดิบ) */
    public function all()
    {
        if ($this->values !== null) {
            return $this->values;
        }
        try {
            $this->values = $this->cache->rememberForever(self::CACHE_KEY, function () {
                return Setting::pluck('value', 'key')->toArray();
            });
        } catch (\Throwable $e) {
            // ยังไม่มีตาราง settings / ต่อฐานข้อมูลไม่ได้ → ใช้ค่า default
            $this->values = [];
        }
        return $this->values;
    }

    public function flush()
    {
        $this->values = null;
        try {
            $this->cache->forget(self::CACHE_KEY);
        } catch (\Throwable $e) {
        }
    }

    /** โมดูลเปิดใช้งานหรือไม่ (ค่า <module>.enabled, ค่าเริ่มต้นเปิด) */
    public function moduleEnabled($module)
    {
        return (bool) $this->get($module . '.enabled', true);
    }
}
