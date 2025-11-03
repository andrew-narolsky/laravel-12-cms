<?php

namespace App\Providers;

use App\View\Composers\UserComposer;
use Illuminate\Support\Facades;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {

    }

    public function boot(): void
    {
        Facades\View::composer('*', UserComposer::class);
    }
}
