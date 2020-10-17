<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
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
        'name', 'email', 'password','avatar_path',
    ];

    protected $appends = [
        'userAvatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function getUserAvatarAttribute()
    {
        return $this->avatar();
    }

    public function avatar()
    {
        if ($this->avatar_path) {
            $filePath = $this->avatar_path;
        } else {
            $filePath = 'avatars/default.png';
        }

        return asset('storage/'.$filePath);
    }
}
