<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Order extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'state' => $this->state,
            'item' => $this->item,
            'meal_id' => $this->meal_id,
            'meal' => $this->meal,
            'responsible_cook' => $this->responsible_cook,
            'start' => $this->start,
            'end' => $this->end,
            'created_at' => $this->created_at,
        ];
    }

    
}
