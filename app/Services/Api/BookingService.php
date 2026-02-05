<?php
namespace App\Services\Api;

use App\Models\Booking;
use App\Models\BoatSeatLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BookingService
{
    public function detail($booking_number)
    {
        $booking = Booking::with([
            'items.boatItem.boatItemDetail'
        ])
            ->where('booking_number', $booking_number);

        if (Auth::check()) {
            $booking->where('user_id', Auth::id());
        }

        $booking = $booking->firstOrFail();

        return $booking;
    }

    public function bookingsCalendar($product_id, $start_date, $end_date)
    {
        $query = BoatSeatLog::where('product_id', $product_id * 1);

        if ($start_date && $end_date) {
            $query->where('date', '>=', Carbon::parse($start_date)->startOfDay())
                ->where('date', '<=', Carbon::parse($end_date)->endOfDay());
        } else if ($start_date) {
            $query->where('date', '>=', Carbon::parse($start_date)->startOfDay());
        } else if ($end_date) {
            $query->where('date', '<=', Carbon::parse($end_date)->endOfDay());
        }

        $seats = $query->get();

        return $seats;
    }
}