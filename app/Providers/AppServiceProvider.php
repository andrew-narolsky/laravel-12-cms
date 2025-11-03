<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerBladeDirectives();
    }

    public function boot(): void
    {

    }

    private function registerBladeDirectives(): void
    {
        Blade::directive('ifroute', function($arguments)
        {
            return '<?php if(in_array(Route::currentRouteName(), ['. $arguments .'])): ?>';
        });

        Blade::directive('endifroute', function() {
            return '<?php endif; ?>';
        });
    }
}
