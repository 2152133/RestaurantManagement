<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function responsible_waiter()
    {
        return $this->belongsTo(User::class);
    }

    public function table()
    {
        return $this->belongsTo(RestaurantTable::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}
