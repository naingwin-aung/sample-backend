<?php

namespace App\Http\Resources\Api\ProductOption;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdditionalOptionResource extends JsonResource
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
            'name'          => $this->additionalOption->name,
            'description'   => $this->additionalOption->description,
            'selling_price' => $this->selling_price * 1,
            'net_price'     => $this->net_price * 1,
        ];
    }
}
