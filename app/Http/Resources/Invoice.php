<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use App\Order;

class Invoice extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $items = Order::join('items', 'orders.item_id', '=', 'items.id')
                    ->select(DB::raw('items.name, items.price, count(items.id) as quantity'))
                    ->where('orders.meal_id', $this->meal->id)
                    ->groupBy('items.id')
                    ->orderby('items.name')
                    ->get();

        return [
            'id' => $this->id,
            'state' => $this->state,
            'meal_id' => $this->meal->id,
            'table_number' => $this->meal->table_number,
            'responsible_waiter_id' => $this->meal->responsible_waiter->id,
            'responsible_waiter_name' => $this->meal->responsible_waiter->name,
            'items' => $items,
            'nif' => $this->nif,
            'name' => $this->name,
            'date' => $this->date,
            'total_price' => $this->total_price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
