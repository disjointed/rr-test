<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    protected $attributes = [
        'photos' => '[]',
    ];

    protected $guarded = [];

    protected $casts = [
        'photos' => 'array',
        'created_at' => 'datetime',
    ];
}
