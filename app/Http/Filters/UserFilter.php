<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class UserFilter extends QueryFilter
{
    protected function getCallbacks(): array
    {
        return [
            'search'  => [$this, 'search'],
            'role_id' => [$this, 'roleId'],
        ];
    }

    protected function search(Builder $builder, string $value): Builder
    {
        return $builder->where('name', 'like', "%{$value}%");
    }

    protected function roleId(Builder $builder, string $value): Builder
    {
        return $builder->where('role_id', $value);
    }
}
