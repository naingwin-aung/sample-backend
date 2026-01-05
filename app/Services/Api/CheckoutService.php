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
use App\Services\Api\Checkout\BoatCheckoutService;
use App\Services\Api\Checkout\CheckAvailableSeatService;

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
    }

    private function _validateData(array $data)
    {
        foreach ($data['products'] as $product) {
            if ($product['product_type'] === 'boat') {
                $this->_validateBoatProduct($product);
            } else {
                throw new Exception('Unsupported product type: ' . $product['product_type']);
            }
        }
    }

    private function _validateBoatProduct(array $data)
    {
        $quantitiesById = collect($data['quantities'])->keyBy('id');

        if (isset($data['additional_options'])) {
            $additionalOptionsById = collect($data['additional_options'])->keyBy('id');
        } else {
            $additionalOptionsById = collect();
        }

        $query = ProductTicket::with([
            'product.images',
            'option.productAdditionalOptions' => function ($query) use ($additionalOptionsById) {
                $query->whereIn('id', $additionalOptionsById->keys());
            },
            'option.productAdditionalOptions.additionalOption',
            'prices'                          => function ($query) use ($quantitiesById) {
                $query->whereIn('id', $quantitiesById->keys());
            },
        ])
            ->whereHas('prices', function ($query) use ($quantitiesById) {
                $query->whereIn('id', $quantitiesById->keys());
            })
            ->where('id', $data['ticket_id'])
            ->where('product_id', $data['product_id'])
            ->where('option_id', $data['option_id']);

        if (isset($data['additional_options'])) {
            $query = $query->whereHas('option', function ($query) use ($additionalOptionsById) {
                $query->whereHas('productAdditionalOptions', function ($subQuery) use ($additionalOptionsById) {
                    $subQuery->whereIn('id', $additionalOptionsById->keys());
                });
            });
        }

        $ticket = $query
            ->firstOrFail();

        $ticket->prices->each(function ($price) use ($quantitiesById) {
            $price->quantity = $quantitiesById->get($price->id)['quantity'] ?? 0;
        });

        $ticket->option->productAdditionalOptions->each(function ($additionalOption) use ($additionalOptionsById) {
            $additionalOption->quantity = $additionalOptionsById->get($additionalOption->id)['quantity'] ?? 0;
        });

        $zone = BoatZone::with('boat')
            ->where('id', $data['zone_id'])
            ->firstOrFail();

        $scheduleTime = ProductScheduleTime::where('id', $data['schedule_time_id'])
            ->firstOrFail();

        $this->products[] = [
            'product_type'  => ProductTypeEnum::BOAT->value,
            'date'          => $data['date'],
            'ticket'        => $ticket,
            'zone'          => $zone,
            'schedule_time' => $scheduleTime,
        ];
    }
}