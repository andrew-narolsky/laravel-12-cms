<?php

namespace App\Services\Abstracts;

use Illuminate\Support\Facades\Cache;

abstract class AbstractCachedService
{
    abstract protected function cacheKey(): string;

    protected function rememberForever(callable $callback): array
    {
        return Cache::rememberForever(
            $this->cacheKey(),
            $callback
        );
    }

    public function clearCache(): void
    {
        Cache::forget($this->cacheKey());
    }
}
