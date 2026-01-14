<?php
namespace App\Services\Api;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class BookingService
{
    public function detail($booking_number)
    {
        $booking = Booking::with([
            'items.boatItem.boatItemDetail'
        ])
            ->where('booking_number', $booking_number);

        if(Auth::check()) {
            $booking->where('user_id', Auth::id());
        }

        $booking = $booking->firstOrFail();

        return $booking;
    }
}