<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Services\Api\BookingService;
use App\Http\Resources\Api\Booking\BookingDetailResource;

class BookingController extends Controller
{
    public function __construct(public BookingService $service)
    {
        //
    }

    public function show($booking_number)
    {
        try {
            $booking = $this->service->detail($booking_number);

            return success([
                // 'data' => $booking,
                'data' => BookingDetailResource::make($booking),
            ], 'Booking detail retrieved successfully.');
        } catch (Exception $e) {
            return error($e->getMessage());
        }
    }

    public function voucher($booking_number)
    {
        try {
            $booking = $this->service->detail($booking_number);

            $pdf = Pdf::loadView('voucher', [
                'booking' => $booking,
            ]);

            return $pdf->stream('invoice.pdf');
        } catch (Exception $e) {
            return error($e->getMessage());
        }
    }

    public function bookingsCalendar($productId, Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date',
        ]);

        try {
            $data = $this->service->bookingsCalendar($productId, $request->start_date, $request->end_date);

            return success([
                'data' => $data,
            ], 'Bookings calendar retrieved successfully.');
        } catch (Exception $e) {
            return error($e->getMessage());
        }
    }
}
