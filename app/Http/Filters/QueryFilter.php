<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

abstract class QueryFilter
{
    private array $queryParams;

    public function __construct(Request $request)
    {
        $this->queryParams = array_filter($request->query());
    }

    abstract protected function getCallbacks(): array;

    final public function apply(Builder $builder): Builder
    {
        // Apply filters
        foreach ($this->queryParams as $name => $value) {
            if (Arr::has($this->getCallbacks(), $name)) {
                $callback = Arr::get($this->getCallbacks(), $name);
                call_user_func($callback, $builder, $value);

                // Add active filter to request
                request()->filters = Arr::add(request()->filters, $name, $value);
            }
        }

        return $builder;
    }
}
