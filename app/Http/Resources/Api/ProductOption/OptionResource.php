<?php

namespace App\Http\Resources\Api\ProductOption;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OptionResource extends JsonResource
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
            'start_date'     => $this->start_date,
            'end_date'       => $this->end_date,
            'closing_type'   => $this->closing_type,
            'closing_dates'  => $this->closing_dates,
            'closing_days'   => $this->closing_days,
            'zones'          => $this->boat->zones,
            'schedule_times' => ProductOptionScheduleTimeResource::collection($this->whenLoaded('scheduleTimes')),
            'tickets'        => OptionTicketResource::collection($this->whenLoaded('tickets')),
        ];
    }
}
