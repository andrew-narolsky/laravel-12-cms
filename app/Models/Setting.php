<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public const PAGINATION_LIMIT = 20;

    protected $table = 'settings';

    protected $fillable = [
        'name',
        'slug',
        'value',
    ];

    public $timestamps = false;
}
