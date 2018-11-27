<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Item extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // This can be formnated
        return parent::toArray($request);
    }

    /**
     * Append information to the request
     */
    public function with($request)
    {
        return [
            'version' => 'v1',
        ];
    }
}
