<?php

namespace App\Providers;

use App\Models\Backup;
use App\Events\BackupEvents;
use Illuminate\Support\ServiceProvider;

class ModelObserverServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Backup::observe(BackupEvents::class);
    }
}
