<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // ถ้า APP_URL เป็น https ให้ลิงก์/ฟอร์ม/asset ทั้งหมดเป็น https (กัน mixed content)
        if (strpos((string) config('app.url'), 'https://') === 0) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
