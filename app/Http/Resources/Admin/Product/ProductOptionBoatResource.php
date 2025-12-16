<?php

namespace App\Http\Resources\Admin\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductOptionBoatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request) : array
    {
        return [
            'id'             => $this->id,
            'product_id'     => $this->product_id,
            'boat_id'        => $this->boat_id,
            'boat'           => $this->boat,
            'start_date'     => $this->start_date,
            'end_date'       => $this->end_date,
            'schedule_times' => $this->scheduleTimes,
            'tickets'        => $this->tickets,
        ];
    }
}
