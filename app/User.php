<?php

namespace App;

use Laravel\Passport\HasAPITokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasAPITokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //para os waiters
    public function meals()
    {
        return $this->hasMany(Meal::class);
    }

    //para os cooks
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
