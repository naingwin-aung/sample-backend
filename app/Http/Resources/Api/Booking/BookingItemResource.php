<?php

namespace App\Http\Resources\Api\Booking;

use App\Enums\ProductTypeEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request) : array
    {
        $result = [];

        $result = match ($this->product_type) {
            ProductTypeEnum::BOAT->value => [
                'product_type'       => ProductTypeEnum::BOAT->value,
                'id'                 => $this->boatItem->id,
                'booking_id'         => $this->boatItem->booking_id,
                'booking_item_id'    => $this->boatItem->booking_item_id,
                'product'            => [
                    'id'    => $this->boatItem->product_id,
                    'name'  => $this->boatItem->name,
                    'slug'  => $this->boatItem->boatItemDetail->product['slug'],
                    'image' => $this->boatItem->boatItemDetail->product['images'][0]['url'] ?? null,
                ],
                'piers'              => isset($this->boatItem->boatItemDetail->product['piers']) ? collect($this->boatItem->boatItemDetail->product['piers'])->map(function ($pier) {
                        return [
                        'id'   => $pier['id'],
                        'name' => $pier['name'],
                        ];
                    }) : [],
                'date'               => $this->boatItem->date,
                'boat'               => [
                    'id'   => $this->boatItem->boatItemDetail->boat['id'],
                    'name' => $this->boatItem->boatItemDetail->boat['name'],
                ],
                'zone'               => [
                    'id'   => $this->boatItem->boatItemDetail->zone['id'],
                    'name' => $this->boatItem->boatItemDetail->zone['name'],
                ],
                'ticket'             => [
                    'id'   => $this->boatItem->boatItemDetail->ticket['id'],
                    'name' => $this->boatItem->boatItemDetail->ticket['name'],
                ],
                'schedule_time'      => [
                    'id'         => $this->boatItem->boatItemDetail->schedule_time['id'],
                    'start_time' => $this->boatItem->boatItemDetail->schedule_time['start_time'],
                    'end_time'   => $this->boatItem->boatItemDetail->schedule_time['end_time'],
                ],
                'variations'         => collect($this->boatItem->boatItemDetail->variations)->map(function ($variation) {
                        return [
                        'id'       => $variation['id'],
                        'name'     => $variation['name'],
                        'price'    => $variation['net_price'],
                        'quantity' => $variation['quantity'],
                        ];
                    }),
                'additional_options' => isset($this->boatItem->boatItemDetail->additional_options) ? collect($this->boatItem->boatItemDetail->additional_options)->map(function ($option) {
                        return [
                        'id'       => $option['id'],
                        'name'     => $option['additional_option']['name'],
                        'price'    => $option['net_price'],
                        'quantity' => $option['quantity'],
                        ];
                    }) : [],
            ],
        };

        $result['payment_status'] = $this->boatItem->payment_status;
        $result['booking_status'] = $this->boatItem->booking_status;

        return $result;
    }
}
