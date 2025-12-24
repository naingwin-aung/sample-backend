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
            'min_price'     => $this->ticket_prices_min_net_price * 1,
            'boat'          => [
                'id'          => $this->boat->id,
                'name'        => $this->boat->name,
                'description' => fake()->paragraphs(3, true),
                'images'      => ProductImageResource::collection($this->boat->images),
            ]
        ];
    }
}
