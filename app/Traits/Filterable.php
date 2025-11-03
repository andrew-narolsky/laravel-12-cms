<?php

namespace App\Traits;

use App\Http\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Traits\Filterable
 *
 * @method static filter(QueryFilter $filter))
 */

trait Filterable
{
    public function scopeFilter(Builder $builder, QueryFilter $filter): Builder
    {
        return $filter->apply($builder);
    }
}
