<?php

namespace App\Http\Resources\Api\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request) : array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'slug'        => $this->slug,
            'description' => $this->description,
            'images'      => ProductImageResource::collection($this->whenLoaded('images')),
            'piers'       => ProductPierResource::collection($this->whenLoaded('piers')),
            'options'     => ProductOptionResource::collection($this->whenLoaded('options')),
        ];
    }
}
