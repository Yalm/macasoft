<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name', 'slug', 'description',
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];
}
