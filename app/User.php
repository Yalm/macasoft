<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','role_id', 'avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function generateToken()
    {
        return auth('api')->login($this);
    }

    public function role()
	{
		return $this->belongsTo(Role::class);
    }

    public function scopeSearch($query,$s)
	{
        if($s != 'false' && $s != 'null' && $s)
            return $query->where('name','LIKE',"%$s%")
                        ->orWhere('email','LIKE',"%$s%")
                        ->orWhereHas('role', function($q) use($s)
                        {
                            $q->where('name', 'like',"%$s%");
                        });
    }

    public function scopeByRole($query,$s)
	{
        if($s != 'false' && $s != 'null' && $s)
            return $query->whereHas('role', function($q) use($s)
                        {
                            $q->where('name', 'like',"%$s%");
                        });
    }
}
