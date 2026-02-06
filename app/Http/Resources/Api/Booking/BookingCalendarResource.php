<?php

namespace App\Http\Resources\Api\Booking;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\Booking\BookingItemResource;

class BookingCalendarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request) : array
    {
        $result = [
            'id'               => $this->_id,
            'date'             => Carbon::parse($this->date)->format('Y-m-d'),
            'available_seats'  => $this->available_seats,
        ];

        return $result;
    }
}
