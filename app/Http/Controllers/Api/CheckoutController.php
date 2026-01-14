<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\TestLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Services\Api\CheckoutService;
use App\Mail\Boat\BookingConfirmEmail;
use App\Http\Resources\Api\Checkout\CheckoutResource;

class CheckoutController extends Controller
{
    public function __construct(public CheckoutService $service)
    {
        //
    }

    public function index(Request $request)
    {
        $rules = [
            'products' => 'required|array',
        ];

        $messages = [
            'products.required' => 'You must add at least one product.',
        ];

        if ($request->has('products')) {
            foreach ($request->input('products') as $key => $product) {
                $index                               = $key + 1;
                $rules["products.$key.product_type"] = 'required|string';
                $condition                           = "required_if:products.$key.product_type,boat";

                $rules["products.$key.product_id"]            = "$condition|integer";
                $rules["products.$key.option_id"]             = "$condition|integer";
                $rules["products.$key.zone_id"]               = "$condition|integer";
                $rules["products.$key.ticket_id"]             = "$condition|integer";
                $rules["products.$key.schedule_time_id"]      = "$condition|integer";
                $rules["products.$key.date"]                  = "$condition|date|after:today";
                $rules["products.$key.quantities"]            = "$condition|array";
                $rules["products.$key.quantities.*.id"]       = "$condition|integer";
                $rules["products.$key.quantities.*.quantity"] = "$condition|integer|min:1";
                // additional options can be optional
                $rules["products.$key.additional_options"]            = "nullable|array";
                $rules["products.$key.additional_options.*.id"]       = "integer";
                $rules["products.$key.additional_options.*.quantity"] = "integer|min:1";

                // Custom Messages
                $messages["products.$key.product_type.required"] = "Product #$index: Please check product type.";
            }
        }

        $validatedData = $request->validate($rules, $messages);

        try {
            $products        = $this->service->processCheckout($validatedData);
            $data_collection = CheckoutResource::collection($products)->toArray($request);

            $total_price = collect($data_collection)->reduce(function ($carry, $item) {
                return $carry + $item['total_price'];
            }, 0);

            return success([
                'data'        => $data_collection,
                'total_price' => $total_price,
            ], 'Checkout processed successfully.');
        } catch (Exception $e) {
            return error($e->getMessage());
        }
    }

    public function confirm(Request $request)
    {
        $rules = [
            'products' => 'required|array',
        ];

        if ($request->has('products')) {
            foreach ($request->input('products') as $key => $product) {
                $rules["products.$key.product_type"] = 'required|string';
                $condition                           = "required_if:products.$key.product_type,boat";

                $rules["products.$key.product_id"]            = "$condition|integer";
                $rules["products.$key.option_id"]             = "$condition|integer";
                $rules["products.$key.zone_id"]               = "$condition|integer";
                $rules["products.$key.ticket_id"]             = "$condition|integer";
                $rules["products.$key.schedule_time_id"]      = "$condition|integer";
                $rules["products.$key.date"]                  = "$condition|date|after:today";
                $rules["products.$key.quantities"]            = "$condition|array";
                $rules["products.$key.quantities.*.id"]       = "$condition|integer";
                $rules["products.$key.quantities.*.quantity"] = "$condition|integer|min:1";

                // additional options can be optional
                $rules["products.$key.additional_options"]            = "nullable|array";
                $rules["products.$key.additional_options.*.id"]       = "integer";
                $rules["products.$key.additional_options.*.quantity"] = "integer|min:1";
            }
        }

        $validatedData = $request->validate($rules);

        DB::beginTransaction();
        try {
            $booking = $this->service->confirmCheckout($validatedData);

            // sending confirmation email temporarily
            // Mail::to($booking->user->email)->send(new BookingConfirmEmail());

            DB::commit();
            return success([
                'data' => [
                    'booking_id'        => $booking->id,
                    'booking_number'    => $booking->booking_number,
                    'payment_reference' => $booking->payment_reference,
                    'grand_total'       => $booking->grand_total,
                ]
            ], 'Checkout confirmed successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return error($e->getMessage());
        }
    }
}
