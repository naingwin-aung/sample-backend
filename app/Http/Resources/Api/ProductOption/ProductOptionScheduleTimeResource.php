<?php

namespace App\Http\Resources\Api\ProductOption;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductOptionScheduleTimeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request) : array
    {
        return [
            'id'         => $this->id,
            'start_time' => $this->start_time,
            'end_time'   => $this->end_time,
        ];
    }
}
