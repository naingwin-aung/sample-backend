<?php

namespace App\Http\Resources\Api\Checkout;

use App\ProductTypeEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\Checkout\BoatCheckoutResource;

class CheckoutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request) : array
    {
        $result = [
            'product_type' => $this['product_type'],
        ];

        if ($this['product_type'] === ProductTypeEnum::BOAT->value) {
            $result['product'] = BoatCheckoutResource::make($this->resource)->toArray($request);
        }

        $variations = collect($result['product']['variations'] ?? []);
        $result['total_price'] = $variations->reduce(fn ($carry, $item) => $carry + ($item['price'] * $item['quantity']), 0);

        return $result;
    }
}
