<?php
namespace App\Services\Api\Boat;

use App\Services\Api\Boat\BoatSeatLogService;

class CheckAvailableSeatService
{
    public function check(int $allocation_seat, int $product_id, int $option_id, int $zone_id, int $ticket_id, int $schedule_time_id, string $date, int $requested_quantity)
    {
        $allocation_seat = $product['zone']['capacity'] ?? 0;

        $boat_seat_log = (new BoatSeatLogService())->check([
            'product_id'       => $product_id,
            'option_id'        => $option_id,
            'boat_id'          => $zone_id,
            'zone_id'          => $zone_id,
            'ticket_id'        => $ticket_id,
            'schedule_time_id' => $schedule_time_id,
            'date'             => $date,
        ]);

        if(!$boat_seat_log) {
            $available_seats = $allocation_seat;
        } else {
            $available_seats = $boat_seat_log->available_seats;
        }

        return [$available_seats, $requested_quantity <= $available_seats];
    }
}