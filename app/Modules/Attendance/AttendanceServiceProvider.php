<?php

namespace App\Modules\Attendance;

use App\Support\Modules\ModuleRegistry;
use App\Support\Settings\SettingsRegistry;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * โมดูลเช็คชื่อเข้าเรียน (Attendance)
 * ทุกอย่างของโมดูลอยู่ใน app/Modules/Attendance — routes, controllers, models, views, migrations
 * เปิด/ปิด และปรับค่าได้ที่ แอดมิน > ตั้งค่าระบบ > เช็คชื่อเข้าเรียน
 */
class AttendanceServiceProvider extends ServiceProvider
{
    const KEY = 'attendance';

    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/Views', 'attendance');
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');

        if (!$this->app->routesAreCached()) {
            Route::middleware('web')->group(__DIR__ . '/routes.php');
        }

        $this->registerSettings();
        $this->registerMenus();
    }

    protected function registerSettings()
    {
        $this->app->make(SettingsRegistry::class)->group(self::KEY, [
            'label'       => 'เช็คชื่อเข้าเรียน',
            'icon'        => 'fas fa-user-check',
            'order'       => 20,
            'description' => 'ตั้งค่าโมดูลเช็คชื่อ: การเช็คชื่อด้วยตนเอง เกณฑ์มาสาย และเกณฑ์เวลาเรียน',
        ], [
            ['key' => 'attendance.enabled', 'label' => 'เปิดใช้งานโมดูลเช็คชื่อ', 'type' => 'boolean', 'default' => true,
             'help' => 'ปิดแล้วเมนูและหน้าเช็คชื่อทั้งหมดจะถูกซ่อน (ข้อมูลไม่หาย)'],
            ['key' => 'attendance.self_checkin', 'label' => 'ให้นักเรียนเช็คชื่อด้วยตนเอง (รหัส)', 'type' => 'boolean', 'default' => true,
             'help' => 'ครูเปิดรหัส 6 ตัวในคาบ แล้วนักเรียนกรอกรหัสเพื่อเช็คชื่อ'],
            ['key' => 'attendance.checkin_window', 'label' => 'ระยะเวลาเปิดรับรหัส', 'type' => 'number', 'default' => 15,
             'min' => 1, 'max' => 240, 'suffix' => 'นาที'],
            ['key' => 'attendance.late_after', 'label' => 'นับว่ามาสาย เมื่อเช็คชื่อหลังเวลาเริ่มคาบเกิน', 'type' => 'number', 'default' => 15,
             'min' => 0, 'max' => 240, 'suffix' => 'นาที'],
            ['key' => 'attendance.default_status', 'label' => 'สถานะเริ่มต้นตอนครูเช็คชื่อ', 'type' => 'select', 'default' => 'present',
             'options' => ['present' => 'มา (ทุกคน)', 'absent' => 'ขาด (ทุกคน)', 'none' => 'ไม่เลือกไว้']],
            ['key' => 'attendance.use_leave', 'label' => 'มีสถานะ "ลา"', 'type' => 'boolean', 'default' => true],
            ['key' => 'attendance.late_counts_present', 'label' => 'นับ "สาย" เป็นการเข้าเรียน', 'type' => 'boolean', 'default' => true,
             'help' => 'ใช้คำนวณเปอร์เซ็นต์เวลาเรียน'],
            ['key' => 'attendance.leave_counts_present', 'label' => 'นับ "ลา" เป็นการเข้าเรียน', 'type' => 'boolean', 'default' => false],
            ['key' => 'attendance.min_percent', 'label' => 'เกณฑ์เวลาเรียนขั้นต่ำ', 'type' => 'number', 'default' => 80,
             'min' => 0, 'max' => 100, 'suffix' => '%', 'help' => 'ต่ำกว่าเกณฑ์จะแสดงเป็นสีแดงในรายงาน'],
            ['key' => 'attendance.teacher_scope', 'label' => 'ครูเช็คชื่อได้ห้องใดบ้าง', 'type' => 'select', 'default' => 'all',
             'options' => ['all' => 'ทุกห้อง (ครูทุกคนเช็คชื่อได้)', 'own' => 'เฉพาะห้องที่ครูเป็นเจ้าของวิชา'],
             'help' => 'superadmin เช็คชื่อได้ทุกห้องเสมอ'],
        ]);
    }

    protected function registerMenus()
    {
        $this->app->make(ModuleRegistry::class)
            ->add(self::KEY, ['label' => 'เช็คชื่อเข้าเรียน', 'version' => '1.0.0'])
            ->menu('teacher', [
                'module' => self::KEY, 'section' => 'ห้องเรียน', 'order' => 10,
                'label' => 'เช็คชื่อเข้าเรียน', 'icon' => 'fas fa-user-check',
                'route' => 'attendance.teacher.index', 'active' => 'attendance.teacher.sessions.*',
            ])
            ->menu('teacher', [
                'module' => self::KEY, 'section' => 'ห้องเรียน', 'order' => 11,
                'label' => 'รายงานเวลาเรียน', 'icon' => 'fas fa-table',
                'route' => 'attendance.teacher.report', 'active' => 'attendance.teacher.report*',
            ])
            ->menu('admin', [
                'module' => self::KEY, 'section' => null, 'order' => 10,
                'label' => 'เช็คชื่อเข้าเรียน', 'icon' => 'fas fa-user-check',
                'route' => 'attendance.teacher.index', 'active' => 'attendance.teacher.*',
            ])
            ->menu('student', [
                'module' => self::KEY, 'order' => 10,
                'label' => 'เช็คชื่อ', 'icon' => 'fas fa-user-check',
                'route' => 'attendance.student.index', 'active' => 'attendance.student.*',
            ]);
    }
}
