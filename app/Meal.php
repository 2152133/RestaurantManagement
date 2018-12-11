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
        'id', 'state', 'table_number', 'start', 'end', 'responsible_waiter_id', 'total_price_preview',
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
        return $this->belongsTo(Order::class);
    }

    public function table()
    {
        return $this->belongsTo(RestaurantTable::class);
    }
}
