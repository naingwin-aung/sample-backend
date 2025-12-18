<?php
namespace App\Services\Api;

use Exception;
use App\Models\BoatZone;
use App\Models\ProductTicket;
use App\Models\ProductTicketPrice;
use App\Models\ProductScheduleTime;

class CheckoutService
{
    public ProductTicket $ticket;

    public BoatZone $zone;

    public ProductScheduleTime $scheduleTime;

    public function processCheckout(array $data)
    {
        $this->_validateData($data);

        return [$this->ticket, $this->zone, $this->scheduleTime];
    }

    private function _validateData(array $data)
    {
        $this->ticket = ProductTicket::with([
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

        $this->zone = BoatZone::where('id', $data['zone_id'])
            ->firstOrFail();

        $this->scheduleTime = ProductScheduleTime::where('id', $data['schedule_time_id'])
            ->firstOrFail();
    }
}