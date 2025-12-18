<?php
namespace App\Services\Api;

use Exception;
use App\Models\BoatZone;
use App\Models\ProductTicket;
use App\Models\ProductTicketPrice;
use App\Models\ProductScheduleTime;

class CheckoutService
{
    public array $products = [];

    public function processCheckout(array $data)
    {
        $this->_validateData($data);
        return [$this->products];
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
        $ticket = ProductTicket::with([
            'product',
            'option',
            'prices' => function ($query) use ($data) {
                $quantities_ids = collect($data['quantities'])->pluck('id');
                $query->whereIn('id', $quantities_ids);
            },
        ])
            ->whereHas('prices', function ($query) use ($data) {
                $quantities_ids = collect($data['quantities'])->pluck('id');
                $query->whereIn('id', $quantities_ids);
            })
            ->where('id', $data['ticket_id'])
            ->where('product_id', $data['product_id'])
            ->where('option_id', $data['option_id'])
            ->firstOrFail();

        $zone = BoatZone::where('id', $data['zone_id'])
            ->firstOrFail();

        $scheduleTime = ProductScheduleTime::where('id', $data['schedule_time_id'])
            ->firstOrFail();

        $this->products['boat'][] = [
            'ticket'        => $ticket,
            'zone'          => $zone,
            'schedule_time' => $scheduleTime,
        ];
    }
}