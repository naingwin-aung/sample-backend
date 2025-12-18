<?php
namespace App\Services\Api\Checkout;

use App\Models\Booking;
use App\Models\Product;
use App\Models\BookingItem;
use App\Enums\ProductTypeEnum;
use App\Models\BookingProduct;
use App\Enums\BookingStatusEnum;
use App\Enums\PaymentStatusEnum;
use App\Models\BookingProductDetail;

class BoatCheckoutService
{
    public function __construct(protected Booking $booking)
    {
    }

    public function create(array $data)
    {
        $sub_total = collect($data['ticket']['prices'])->reduce(function ($carry, $item) {
            return $carry + ($item['selling_price'] * $item['quantity']);
        }, 0);

        $total_quantity = collect($data['ticket']['prices'])->reduce(function ($carry, $item) {
            return $carry + $item['quantity'];
        }, 0);

        $grand_total     = $sub_total; // No additional fees for now
        $booking_item    = $this->_saveBookingItem($data, $sub_total, $grand_total);
        $booking_product = $this->_saveBookingProduct($data, $booking_item, $total_quantity, $sub_total, $grand_total);
        $this->_saveBookingProductDetail($data, $booking_product);

        return [
            'total_price' => $grand_total,
        ];
    }

    private function _saveBookingItem(array $data, float $sub_total, float $grand_total)
    {
        $booking_item                   = new BookingItem();
        $booking_item->booking_id       = $this->booking->id;
        $booking_item->product_type     = ProductTypeEnum::BOAT->value;
        $booking_item->productable_id   = $data['ticket']['product_id'];
        $booking_item->productable_type = Product::class;
        $booking_item->sub_total        = $sub_total;
        $booking_item->grand_total      = $grand_total;
        $booking_item->payment_status   = PaymentStatusEnum::PENDING->value;
        $booking_item->booking_status   = BookingStatusEnum::PENDING->value;
        $booking_item->save();

        return $booking_item;
    }

    private function _saveBookingProduct(array $data, BookingItem $booking_item, int $total_quantity, float $sub_total, float $grand_total)
    {
        $booking_product                   = new BookingProduct();
        $booking_product->booking_id       = $this->booking->id;
        $booking_product->booking_item_id  = $booking_item->id;
        $booking_product->product_id       = $data['ticket']['product_id'];
        $booking_product->option_id        = $data['ticket']['option']['id'];
        $booking_product->zone_id          = $data['zone']['id'];
        $booking_product->ticket_id        = $data['ticket']['id'];
        $booking_product->schedule_time_id = $data['schedule_time']['id'];
        $booking_product->name             = $data['ticket']['product']['name'];
        $booking_product->ticket_name      = $data['ticket']['name'];
        $booking_product->date             = $data['date'];
        $booking_product->total_quantity   = $total_quantity;
        $booking_product->sub_total        = $sub_total;
        $booking_product->grand_total      = $grand_total;
        $booking_product->payment_status   = PaymentStatusEnum::PENDING->value;
        $booking_product->booking_status   = BookingStatusEnum::PENDING->value;
        $booking_product->save();

        return $booking_product;
    }

    private function _saveBookingProductDetail(array $data, BookingProduct $booking_product)
    {
        $booking_product_detail                     = new BookingProductDetail();
        $booking_product_detail->booking_product_id = $booking_product->id;
        $booking_product_detail->product            = $data['ticket']['product'];
        $booking_product_detail->option             = $data['ticket']['option'] ?? null;
        $booking_product_detail->zone               = $data['zone'] ?? null;
        $booking_product_detail->ticket             = $data['ticket'] ?? null;
        $booking_product_detail->schedule_time      = $data['schedule_time'] ?? null;
        $booking_product_detail->variations         = $data['ticket']['prices'] ?? null;
        $booking_product_detail->save();

        return $booking_product_detail;
    }
}