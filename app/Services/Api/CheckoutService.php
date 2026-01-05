<?php
namespace App\Services\Api;

use Exception;
use App\Models\Booking;
use App\Models\BoatZone;
use App\Models\ProductTicket;
use App\Enums\ProductTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Models\ProductScheduleTime;
use Illuminate\Support\Facades\Auth;
use App\Services\Api\Checkout\Boat\BoatCheckoutService;
use App\Services\Api\Checkout\Boat\CheckAvailableSeatService;
use App\Services\Api\Checkout\Boat\ValidateBoatProductService;

class CheckoutService
{
    public array $products = [];

    public function processCheckout(array $data)
    {
        $this->_validateData($data);
        return $this->products;
    }

    public function confirmCheckout(array $data)
    {
        $this->_validateData($data);

        $booking                    = new Booking();
        $booking->booking_number    = 'BN-' . strtoupper(uniqid());
        $booking->payment_reference = 'PR-' . strtoupper(uniqid());
        $booking->user_id           = Auth::user()->id;
        $booking->payment_status    = PaymentStatusEnum::PENDING->value;
        $booking->request_payload   = $data;
        $booking->save();

        $each_booking_total_prices = [];
        foreach ($this->products as $product) {
            if ($product['product_type'] === ProductTypeEnum::BOAT->value) {
                $check_availability = (new CheckAvailableSeatService())->check($product, $product['ticket']->prices->sum('quantity'));

                if (!$check_availability) {
                    throw new Exception('Sorry, there aren’t enough seats available for the quantity you selected.');
                }

                $each_booking_total_prices[] = (new BoatCheckoutService($booking))->create($product); // must return total price
            } else {
                throw new Exception('Unsupported product type: ' . $product['product_type']);
            }
        }

        $total_price = collect($each_booking_total_prices)->reduce(function ($carry, $item) {
            return $carry + $item['total_price'];
        }, 0);

        $booking->sub_total   = $total_price;
        $booking->grand_total = $total_price;
        $booking->update();

        return $booking;
    }

    private function _validateData(array $data)
    {
        foreach ($data['products'] as $product) {
            if ($product['product_type'] === 'boat') {
                $this->products[] = (new ValidateBoatProductService())->validate($product);
            } else {
                throw new Exception('Unsupported product type: ' . $product['product_type']);
            }
        }
    }
}