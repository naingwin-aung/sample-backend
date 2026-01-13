<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Booking;
use App\Models\BoatSeatLog;
use Illuminate\Http\Request;
use App\Enums\PaymentStatusEnum;
use App\Http\Controllers\Controller;
use App\Services\Api\Boat\BoatSeatLogService;

class PaymentController extends Controller
{
    public function confirm(Request $request)
    {
        $request->validate([
            'booking_number' => 'required|string',
        ]);

        $booking = Booking::with('items.boatItem.boatItemDetail')
            ->where('booking_number', $request->booking_number)
            ->firstOrFail();

        $booking->payment_status = PaymentStatusEnum::PAID->value;
        $booking->update();

        $booking->items()->update([
            'payment_status' => PaymentStatusEnum::PAID->value,
        ]);

        $booking->items()->each(function ($item) {
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
                    throw new Exception('Error in updating boat seat log: insufficient seats.');
                }

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
                ];

                (new BoatSeatLogService())->create($log);
            }
        });

        return success([], 'Payment confirmed and booking updated successfully.');
    }
}
