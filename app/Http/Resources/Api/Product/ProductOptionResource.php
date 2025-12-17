<?php

namespace App\Http\Resources\Api\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductOptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request) : array
    {
        return [
            'id'            => $this->id,
            'start_date'    => $this->start_date,
            'end_date'      => $this->end_date,
            'closing_type'  => $this->closing_type,
            'closing_dates' => $this->closing_dates,
            'closing_days'  => $this->closing_days,
            'boat'          => [
                'id'       => $this->boat->id,
                'name'     => $this->boat->name,
                'capacity' => $this->boat->capacity,
                'images'   => ProductImageResource::collection($this->boat->images),
            ]
        ];
    }
}
