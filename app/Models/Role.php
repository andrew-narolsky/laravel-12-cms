<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    const PAGINATION_LIMIT = 10;
    const ADMIN = 'admin';
    const EDITOR = 'editor';

    protected $fillable = [
        'name',
        'slug'
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public static function getRoleBySlug(string $slug): mixed
    {
        return self::where('slug', $slug)->first();
    }
}
