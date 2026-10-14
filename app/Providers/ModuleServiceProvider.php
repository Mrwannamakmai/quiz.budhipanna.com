<?php

namespace App\Providers;

use App\Support\Modules\ModuleRegistry;
use App\Support\Settings\SettingsManager;
use App\Support\Settings\SettingsRegistry;
use Illuminate\Support\ServiceProvider;

/**
 * แกนกลางของระบบโมดูล + ระบบตั้งค่า
 * - ลงทะเบียน SettingsRegistry / SettingsManager / ModuleRegistry
 * - ลงทะเบียนกลุ่มตั้งค่า "ทั่วไป"
 * - โหลด ServiceProvider ของทุกโมดูลใน config/modules.php
 */
class ModuleServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(SettingsRegistry::class);
        $this->app->singleton(SettingsManager::class);
        $this->app->singleton(ModuleRegistry::class);

        foreach ((array) config('modules', []) as $key => $provider) {
            if (class_exists($provider)) {
                $this->app->register($provider);
            }
        }
    }

    public function boot()
    {
        $this->app->make(SettingsRegistry::class)->group('general', [
            'label'       => 'ทั่วไป',
            'icon'        => 'fas fa-sliders-h',
            'order'       => 1,
            'description' => 'ค่าพื้นฐานที่ทุกโมดูลใช้ร่วมกัน',
        ], [
            [
                'key'     => 'general.school_name',
                'label'   => 'ชื่อสถานศึกษา',
                'type'    => 'text',
                'default' => 'สำนักเรียนวัดสารอด',
                'help'    => 'แสดงบนหัวรายงานต่าง ๆ',
            ],
            [
                'key'     => 'general.timezone',
                'label'   => 'เขตเวลา',
                'type'    => 'select',
                'default' => 'Asia/Bangkok',
                'options' => [
                    'Asia/Bangkok' => 'ประเทศไทย (Asia/Bangkok, UTC+7)',
                    'UTC'          => 'UTC',
                ],
                'help'    => 'ใช้คำนวณ "วันนี้" และเวลาเข้าเรียน/มาสาย',
            ],
        ]);
    }
}
