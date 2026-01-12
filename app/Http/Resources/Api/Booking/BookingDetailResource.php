<?php

namespace App\Http\Resources\Api\Booking;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\Booking\BookingItemResource;

class BookingDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request) : array
    {
        $result = [
            'id' => $this->id,
            'booking_number' => $this->booking_number,
            'payment_reference' => $this->payment_reference,
            'sub_total' => $this->sub_total,
            'grand_total' => $this->grand_total,
            'payment_status' => $this->payment_status,
            'remark' => $this->remark,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'items' => BookingItemResource::collection($this->whenLoaded('items')),
        ];

        return $result;
    }
}
