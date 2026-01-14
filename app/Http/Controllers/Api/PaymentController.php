<?php

namespace App\Http\Controllers\Api;

use App\Services\Api\PaymentService;
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

        try {
            (new PaymentService())->confirm($request->booking_number);

            return success([], 'Payment confirmed and booking updated successfully.');
        } catch (Exception $e) {
            return error($e->getMessage(), 500);
        }
    }
}
