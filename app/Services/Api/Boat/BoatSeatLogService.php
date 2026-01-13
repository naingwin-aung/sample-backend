<?php

use App\Models\BoatSeatLog;

class BoatSeatLogService
{
    public function create(array $data)
    {
        BoatSeatLog::create([
            'product_id'       => $data['product_id'],
            'option_id'        => $data['option_id'],
            'boat_id'          => $data['boat_id'],
            'zone_id'          => $data['zone_id'],
            'ticket_id'        => $data['ticket_id'],
            'schedule_time_id' => $data['schedule_time_id'],
            'date'             => $data['date'],
            'allocation_seats' => $data['allocation_seats'],
            'booked_seats'     => $data['booked_seats'],
            'available_seats'  => $data['available_seats'],
            'logged_at'        => now(),
        ]);
    }
}