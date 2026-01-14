<?php
namespace App\Services\Api;

use Exception;
use App\Models\Booking;
use App\Models\BoatSeatLog;
use App\Enums\PaymentStatusEnum;
use App\Services\Api\Boat\BoatSeatLogService;

class PaymentService
{
    public function confirm($booking_number)
    {
        $booking = Booking::with('items.boatItem.boatItemDetail')
            ->where('booking_number', $booking_number)
            ->firstOrFail();

        $booking->payment_status = PaymentStatusEnum::PAID->value;
        $booking->update();

        $booking->items()->update([
            'payment_status' => PaymentStatusEnum::PAID->value,
        ]);

        $booking->items()->each(function ($item) use ($booking) {
            if ($item->product_type === 'boat' && $item->boatItem) {
                $item->boatItem->update([
                    'payment_status' => PaymentStatusEnum::PAID->value,
                ]);

                $booking_boat = $item->boatItem;

                $boat_seat_log = (new BoatSeatLogService())->check([
                    'product_id'       => $booking_boat->product_id,
                    'option_id'        => $booking_boat->option_id,
                    'boat_id'          => $booking_boat->boat_id,
                    'zone_id'          => $booking_boat->zone_id,
                    'ticket_id'        => $booking_boat->ticket_id,
                    'schedule_time_id' => $booking_boat->schedule_time_id,
                    'date'             => $booking_boat->date,
                ]);

                if (!$boat_seat_log) {
                    $current_seats = $booking_boat->boatItemDetail->zone['capacity'] ?? 0;
                } else {
                    $current_seats = $boat_seat_log->available_seats;
                }

                if ($current_seats < $booking_boat->total_quantity) {
                    // send refund email to user and admin
                    throw new Exception('Boat seat not available, please contact support for refund process.');
                }

                $detail = $booking_boat->boatItemDetail;

                $log = [
                    'product_id'       => $booking_boat->product_id,
                    'option_id'        => $booking_boat->option_id,
                    'boat_id'          => $booking_boat->boat_id,
                    'zone_id'          => $booking_boat->zone_id,
                    'ticket_id'        => $booking_boat->ticket_id,
                    'schedule_time_id' => $booking_boat->schedule_time_id,
                    'date'             => $booking_boat->date,
                    'allocation_seats' => $booking_boat->boatItemDetail->zone['capacity'] ?? 0,
                    'booked_seats'     => $booking_boat->total_quantity,
                    'available_seats'  => $current_seats - $booking_boat->total_quantity,
                    'product'          => $detail->product,
                    'option'           => $detail->option,
                    'boat'             => $detail->boat,
                    'zone'             => $detail->zone,
                    'ticket'           => $detail->ticket,
                    'schedule_time'    => $detail->schedule_time,
                    'variations'       => $detail->variations,
                ];

                (new BoatSeatLogService())->create($log);
            }
        });
    }
}