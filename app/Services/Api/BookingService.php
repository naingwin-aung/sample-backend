<?php
namespace App\Services\Api;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class BookingService
{
    public function detail($booking_number)
    {
        $booking = Booking::with([
            'items.boatItem.bookingItemDetail'
        ])
            ->where('booking_number', $booking_number)
            ->where('user_id', Auth::user()->id)
            ->firstOrFail();

        return $booking;
    }
}