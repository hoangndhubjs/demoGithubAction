<?php
namespace App\Classes\Settings;

use Illuminate\Support\ServiceProvider;

class SettingManagerProvider extends ServiceProvider
{
    public function register() {
        $this->app->singleton('hrm', function ($app) {
            return new SettingManager($app);
        });
    }

    public function boot() {
        if (!app()->runningInConsole()) {
            app('hrm')->applySettings();
        }
    }
}
