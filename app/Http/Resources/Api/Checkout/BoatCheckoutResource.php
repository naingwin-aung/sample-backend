<?php

namespace App\Http\Resources\Api\Checkout;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BoatCheckoutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request) : array
    {
        return [
            'product'       => [
                'id'    => $this['ticket']['product']->id ?? null,
                'name'  => $this['ticket']['product']->name ?? null,
                'slug'  => $this['ticket']['product']->slug ?? null,
                'image' => $this['ticket']['product']->images->first()->url ?? null,
            ],
            'option_id'     => $this['ticket']['option']['id'] ?? null,
            'boat'          => [
                'id'   => $this['zone']->boat->id ?? null,
                'name' => $this['zone']->boat->name ?? null,
            ],
            'zone'          => [
                'id'   => $this['zone']->id ?? null,
                'name' => $this['zone']->name ?? null,
            ],
            'date'          => $this['date'] ?? null,
            'ticket'        => [
                'id'   => $this['ticket']->id ?? null,
                'name' => $this['ticket']->name ?? null,
            ],
            'schedule_time' => [
                'id'         => $this['schedule_time']->id ?? null,
                'start_time' => $this['schedule_time']->start_time ?? null,
                'end_time'   => $this['schedule_time']->end_time ?? null,
            ],
            'variations'    => $this['ticket']->prices->map(function ($price) {
                return [
                    'id'       => $price->id ?? null,
                    'name'     => $price->name ?? null,
                    'price'    => $price->selling_price ?? null,
                    'quantity' => $price->quantity ?? 0,
                ];
            })->toArray(),
        ];
    }
}
