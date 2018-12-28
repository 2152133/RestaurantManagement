<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\VerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password', 'type', 'photo_url', 'remember_token', 'shift_active', 'email_verified_at', 'last_shift_start', 'last_shift_end', 'deleted_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'created_at', 'updated_at'
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
