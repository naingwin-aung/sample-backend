<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\CheckoutService;

class CheckoutController extends Controller
{
    public function __construct(public CheckoutService $service)
    {
        //
    }

    public function index(Request $request)
    {
        $request->validate([
            'product_id'         => 'required|integer',
            'option_id'          => 'required|integer',
            'zone_id'            => 'required|integer',
            'ticket_id'          => 'required|integer',
            'schedule_time_id'   => 'required|integer',
            'date'               => 'required|date',
            'quantities.*'       => 'required|array',
            'quantities.*.id'    => 'required|integer',
            'quantities.*.count' => 'required|integer|min:1',
        ]);

        try {
            [$ticket, $zone, $scheduleTime] = $this->service->processCheckout($request->all());

            return success([
                'ticket'        => $ticket,
                'zone'          => $zone,
                'schedule_time' => $scheduleTime,
            ], 'Checkout processed successfully.');
        } catch (Exception $e) {
            return error($e->getMessage());
        }
    }
}
