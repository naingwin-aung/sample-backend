<?php

namespace App\Http\Resources\Api\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductListingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'product_type' => 'Boats',
            'city' => 'Bangkok',
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'min_price' => $this->ticket_prices_min_net_price * 1,
            'images' => ProductImageResource::collection($this->whenLoaded('images')),
            'piers' => ProductPierResource::collection($this->whenLoaded('piers')),
        ];
    }
}
