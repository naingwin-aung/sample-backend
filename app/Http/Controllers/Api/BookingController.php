<?php

namespace App\Http\Controllers\Api;

use Exception;
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
}
