<?php

namespace App\Http\Resources\Api\ProductOption;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OptionTicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request) : array
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'short_description' => $this->short_description,
            'prices'            => OptionTicketPriceResource::collection($this->whenLoaded('prices')),
        ];
    }
}
