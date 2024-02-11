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
        // قم بنقل الملف MakeAllCommand.php إلى المسار المطلوب
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
        // لا يلزم تسجيل أي خدمات هنا.
    }
}
