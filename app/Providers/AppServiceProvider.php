<?php

namespace App\Providers;

use App\Models\Setting;
use App\Observers\SettingObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {

    }

    public function boot(): void
    {
        Setting::observe(SettingObserver::class);
    }
}
