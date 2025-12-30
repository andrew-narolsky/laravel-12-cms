<?php

use App\Services\SettingService;

if (!function_exists('setting')) {
    function setting(string $slug, mixed $default = null): mixed
    {
        return app(SettingService::class)->get($slug, $default);
    }
}

if (!function_exists('settings_all')) {
    function settings_all(): array
    {
        return app(SettingService::class)->getAll();
    }
}
