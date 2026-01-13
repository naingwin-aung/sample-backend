<?php
namespace App\Services\Api\Boat;

use App\Enums\PaymentStatusEnum;
use App\Models\BoatSeatLog;
use App\Models\BookingBoat;

class CheckAvailableSeatService
{
    public function check(array $product, int $requested_quantity)
    {
        $allocation_seat = $product['zone']['capacity'] ?? 0;

        $boat_seat = BoatSeatLog::where('product_id', $product['ticket']['product']['id'])
            ->where('option_id', $product['ticket']['option']['id'])
            ->where('boat_id', $product['zone']['boat']['id'])
            ->where('zone_id', $product['zone']['id'])
            ->where('ticket_id', $product['ticket']['id'])
            ->where('schedule_time_id', $product['schedule_time']['id'])
            ->where('date', $product['date'])
            ->orderByDesc('logged_at')
            ->first();

        if(!$boat_seat) {
            $available_seats = $allocation_seat;
        } else {
            $available_seats = $boat_seat->available_seats;
        }

        return [$available_seats, $requested_quantity <= $available_seats];
    }
}