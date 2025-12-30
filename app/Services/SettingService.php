<?php

namespace App\Services;

use App\Models\Setting;
use App\Services\Abstracts\AbstractCachedService;

/**
 * Service for accessing application settings.
 *
 * Usage via Service:
 *  $settings = app(SettingService::class);
 *  $settings->get('site_name');
 *  $settings->get('site_name', 'Default');
 *  $settings->getAll();
 *
 * Usage via global helpers:
 *  // Get a single setting
 *  $siteName = setting('site_name');
 *  $siteName = setting('site_name', 'Default');
 *
 *  // Get all settings as slug => value map
 *  $allSettings = settings_all();
 *
 * Notes:
 *  - Values are cached forever
 *  - Cache is automatically invalidated via SettingObserver
 */
class SettingService extends AbstractCachedService
{
    protected function cacheKey(): string
    {
        return 'settings';
    }

    public function get(string $slug, mixed $default = null)
    {
        $settings = $this->getAll();

        return $settings[$slug] ?? $default;
    }

    public function getAll(): array
    {
        return $this->rememberForever(fn() => Setting::query()
            ->pluck('value', 'slug')
            ->toArray()
        );
    }
}
