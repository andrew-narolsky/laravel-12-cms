<?php

namespace App\Observers;

use App\Models\Setting;
use App\Services\SettingService;

class SettingObserver
{
    public function __construct(
        protected SettingService $settings
    ) {}

    public function saved(Setting $setting): void
    {
        $this->settings->clearCache();
    }

    public function deleted(Setting $setting): void
    {
        $this->settings->clearCache();
    }
}
