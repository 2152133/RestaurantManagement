<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\VerifyEmail;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password', 'type', 'photo_url', 'remember_token', 'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
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

    
    public function verified()
    {
        return $this->remember_token === null;
    }

    public function sendVerificationEmail()
    {
        $this->notify(new VerifyEmail($this)); 
    }
}
