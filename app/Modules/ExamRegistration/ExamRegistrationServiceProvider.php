<?php

namespace App\Modules\ExamRegistration;

use App\Support\Modules\ModuleRegistry;
use App\Support\Settings\SettingsRegistry;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ExamRegistrationServiceProvider extends ServiceProvider
{
    const KEY = 'exam_registration';

    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/Views', 'examregistration');

        if (!$this->app->routesAreCached()) {
            Route::middleware('web')->group(__DIR__ . '/routes.php');
        }
        $this->app->make(SettingsRegistry::class)->group(self::KEY, [
            'label'       => 'ลงทะเบียนสอบธรรมศึกษา',
            'icon'        => 'fas fa-id-card',
            'order'       => 30,
            'description' => 'ตั้งค่าการเปิดใช้งานเมนูและหน้าลงทะเบียนสอบธรรมศึกษา',
        ], [
            [
                'key'     => 'exam_registration.enabled',
                'label'   => 'เปิดใช้งานโมดูลลงทะเบียนสอบธรรมศึกษา',
                'type'    => 'boolean',
                'default' => true,
                'help'    => 'ปิดแล้วเมนูและหน้าลงทะเบียนจะถูกซ่อน โดยข้อมูลเดิมยังอยู่',
            ],
        ]);

        $this->app->make(ModuleRegistry::class)
            ->add(self::KEY, ['label' => 'ลงทะเบียนสอบธรรมศึกษา'])
            ->menu('teacher', [
                'module' => self::KEY,
                'section' => 'ห้องเรียน',
                'order' => 12,
                'label' => 'ลงทะเบียนสอบธรรมศึกษา',
                'icon' => 'fas fa-id-card',
                'route' => 'exam_registration.index',
                'active' => 'exam_registration.*',
            ])
            ->menu('admin', [
                'module' => self::KEY,
                'order' => 11,
                'label' => 'ลงทะเบียนสอบธรรมศึกษา',
                'icon' => 'fas fa-id-card',
                'route' => 'exam_registration.index',
                'active' => 'exam_registration.*',
            ]);
    }
}
