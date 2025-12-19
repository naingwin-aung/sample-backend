<?php
namespace App\Services\Api\Checkout;

use App\Enums\PaymentStatusEnum;
use App\Models\BookingBoat;

class CheckAvailableSeatService
{
    public function check(array $product, int $requested_quantity) : bool
    {
        $allocation_seat = $product['zone']['capacity'] ?? 0;

        $booked_seat = BookingBoat::where('product_id', $product['ticket']['product']['id'])
            ->where('option_id', $product['ticket']['option']['id'])
            ->where('boat_id', $product['zone']['boat']['id'])
            ->where('zone_id', $product['zone']['id'])
            ->where('ticket_id', $product['ticket']['id'])
            ->where('schedule_time_id', $product['schedule_time']['id'])
            ->where('date', $product['date'])
            ->where('payment_status', PaymentStatusEnum::PAID->value)
            ->sum('total_quantity');

        $available_seats = $allocation_seat - ($booked_seat * 1);

        return $requested_quantity <= $available_seats;
    }
}