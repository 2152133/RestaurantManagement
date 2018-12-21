<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'state', 'item_id', 'meal_id', 'responsible_coock_id', 'start', 'end', 'created_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }

    public function responsible_cook()
    {
        return $this->belongsTo(User::class);
    }

}
