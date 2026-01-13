<?php
namespace App\Services\Api\Boat;

use App\Services\Api\Boat\BoatSeatLogService;

class CheckAvailableSeatService
{
    public function check(array $product, int $requested_quantity)
    {
        $allocation_seat = $product['zone']['capacity'] ?? 0;

        $boat_seat_log = (new BoatSeatLogService())->check([
            'product_id'       => $product['ticket']['product']['id'],
            'option_id'        => $product['ticket']['option']['id'],
            'boat_id'          => $product['zone']['boat']['id'],
            'zone_id'          => $product['zone']['id'],
            'ticket_id'        => $product['ticket']['id'],
            'schedule_time_id' => $product['schedule_time']['id'],
            'date'             => $product['date'],
        ]);

        if(!$boat_seat_log) {
            $available_seats = $allocation_seat;
        } else {
            $available_seats = $boat_seat_log->available_seats;
        }

        return [$available_seats, $requested_quantity <= $available_seats];
    }
}