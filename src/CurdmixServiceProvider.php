<?php

namespace Dyalnu\Curdmix;

use Illuminate\Support\ServiceProvider;

class CurdmixServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // يمكنك هنا تنفيذ أي منطق تشغيلي للحزمة
        // مثل تحميل العرضيات أو تسجيل الوحدات النمطية إلخ.
        $this->publishes([
            __DIR__.'/Console/Commands/MakeAllCommand.php' => app_path('Console/Commands/MakeAllCommand.php'),
        ], 'curdmix-command');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // يمكنك هنا تسجيل أي خدمات تحتاج إلى توفيرها
        // مثل تسجيل الأجهزة الوسيطة أو الخدمات الخاصة.
    }
}