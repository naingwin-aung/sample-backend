<?php

namespace App\Http\Resources\Api\ProductOption;

use Carbon\Carbon;
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
        $result = [
            'id'             => $this->id,
            'product_id'     => $this->product_id,
            'start_date'     => Carbon::parse($this->start_date)->isPast()
                ? now()->format('Y-m-d')
                : $this->start_date,
            'end_date'       => $this->end_date,
            'zones'          => $this->boat->zones,
            'schedule_times' => ProductOptionScheduleTimeResource::collection($this->whenLoaded('scheduleTimes')),
            'tickets'        => OptionTicketResource::collection($this->whenLoaded('tickets')),
        ];

        $result['closing_dates'] = [];

        if ($this->closing_type == 'closing_date') {
            $result['closing_dates'] = array_filter($this->closing_dates, function ($date) {
                return Carbon::parse($date)->startOfDay()->gte(today());
            });
        } elseif ($this->closing_type == 'closing_day') {
            $result['closing_dates'] = generateDatesFromClosingDays($this->closing_days, $this->start_date, $this->end_date);
        }

        return $result;
    }
}
