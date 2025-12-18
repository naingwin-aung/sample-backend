<?php
namespace App\Services\Api;

use Exception;
use App\Models\BoatZone;
use App\Models\ProductTicket;
use App\Models\ProductTicketPrice;
use App\Models\ProductScheduleTime;
use App\ProductTypeEnum;

class CheckoutService
{
    public array $products = [];

    public function processCheckout(array $data)
    {
        $this->_validateData($data);
        return $this->products;
    }

    private function _validateData(array $data)
    {
        foreach ($data['products'] as $product) {
            if ($product['product_type'] === 'boat') {
                $this->_validateBoatProduct($product);
            } else {
                throw new Exception('Unsupported product type: ' . $product['product_type']);
            }
        }
    }

    private function _validateBoatProduct(array $data)
    {
        $quantitiesById = collect($data['quantities'])->keyBy('id');

        $ticket = ProductTicket::with([
            'product',
            'option',
            'prices' => function ($query) use ($quantitiesById) {
                $query->whereIn('id', $quantitiesById->keys());
            },
        ])
            ->whereHas('prices', function ($query) use ($quantitiesById) {
                $query->whereIn('id', $quantitiesById->keys());
            })
            ->where('id', $data['ticket_id'])
            ->where('product_id', $data['product_id'])
            ->where('option_id', $data['option_id'])
            ->firstOrFail();

        $ticket->prices->each(function ($price) use ($quantitiesById) {
            $price->quantity = $quantitiesById->get($price->id)['quantity'] ?? 0;
        });

        $zone = BoatZone::with('boat')
            ->where('id', $data['zone_id'])
            ->firstOrFail();

        $scheduleTime = ProductScheduleTime::where('id', $data['schedule_time_id'])
            ->firstOrFail();

        $this->products[] = [
            'product_type'  => ProductTypeEnum::BOAT->value,
            'date'          => $data['date'],
            'ticket'        => $ticket,
            'zone'          => $zone,
            'schedule_time' => $scheduleTime,
        ];
    }
}