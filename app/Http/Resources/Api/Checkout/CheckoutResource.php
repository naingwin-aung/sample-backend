<?php

namespace App\Http\Resources\Api\Checkout;

use Illuminate\Http\Request;
use App\Enums\ProductTypeEnum;
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
        $variations_total = $variations->reduce(fn ($carry, $item) => $carry + ($item['price'] * $item['quantity']), 0);

        $result['total_price'] = $variations_total;

        return $result;
    }
}
