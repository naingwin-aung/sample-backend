<?php

namespace App\Http\Controllers\Api;

use App\Services\Api\PaymentService;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
