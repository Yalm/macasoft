<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public function users()
	{
		return $this->hasMany(User::class);
    }

    protected $fillable = [
        'name', 'slug', 'description',
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function scopeSearch($query,$s)
	{
        if($s != 'false' && $s != 'null' && $s)
            return $query->where('name','LIKE',"%$s%")
                        ->orWhere('slug','LIKE',"%$s%")
                        ->orWhere('description','LIKE',"%$s%");
    }
}
