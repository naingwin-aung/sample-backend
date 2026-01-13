<?php
namespace App\Services\Api\Boat;

use App\Models\Booking;
use App\Models\Product;
use App\Models\BookingItem;
use App\Enums\ProductTypeEnum;
use App\Models\BookingBoat;
use App\Enums\BookingStatusEnum;
use App\Enums\PaymentStatusEnum;
use App\Models\BookingBoatDetail;

class BoatCheckoutService
{
    public function __construct(protected Booking $booking)
    {
    }

    public function create(array $data, int $available_seats)
    {
        $sub_total = 0;

        $ticket_total = collect($data['ticket']['prices'])->reduce(function ($carry, $item) {
            return $carry + ($item['net_price'] * $item['quantity']);
        }, 0);

        $additional_total = collect($data['ticket']['option']['productAdditionalOptions'])->reduce(function ($carry, $item) {
            return $carry + ($item['net_price'] * $item['quantity']);
        }, 0);

        $sub_total = $ticket_total + $additional_total;

        $total_quantity = collect($data['ticket']['prices'])->sum('quantity');

        $grand_total  = $sub_total; // No additional fees for now
        $booking_item = $this->_saveBookingItem($data, $sub_total, $grand_total);
        $booking_boat = $this->_saveBookingBoat($data, $booking_item, $total_quantity, $sub_total, $grand_total);
        $this->_saveBookingBoatDetail($data, $booking_boat);

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

    private function _saveBookingBoat(array $data, BookingItem $booking_item, int $total_quantity, float $sub_total, float $grand_total)
    {
        $booking_boat                   = new BookingBoat();
        $booking_boat->booking_id       = $this->booking->id;
        $booking_boat->booking_item_id  = $booking_item->id;
        $booking_boat->product_id       = $data['ticket']['product_id'];
        $booking_boat->option_id        = $data['ticket']['option']['id'];
        $booking_boat->boat_id          = $data['zone']['boat']['id'];
        $booking_boat->zone_id          = $data['zone']['id'];
        $booking_boat->ticket_id        = $data['ticket']['id'];
        $booking_boat->schedule_time_id = $data['schedule_time']['id'];
        $booking_boat->name             = $data['ticket']['product']['name'];
        $booking_boat->ticket_name      = $data['ticket']['name'];
        $booking_boat->date             = $data['date'];
        $booking_boat->total_quantity   = $total_quantity;
        $booking_boat->sub_total        = $sub_total;
        $booking_boat->grand_total      = $grand_total;
        $booking_boat->payment_status   = PaymentStatusEnum::PENDING->value;
        $booking_boat->booking_status   = BookingStatusEnum::PENDING->value;
        $booking_boat->save();

        return $booking_boat;
    }

    private function _saveBookingBoatDetail(array $data, BookingBoat $booking_boat)
    {
        $booking_boat_detail                     = new BookingBoatDetail();
        $booking_boat_detail->booking_boat_id    = $booking_boat->id;
        $booking_boat_detail->product            = $data['ticket']['product'];
        $booking_boat_detail->option             = $data['ticket']['option'] ?? null;
        $booking_boat_detail->boat               = $data['zone']['boat'] ?? null;
        $booking_boat_detail->zone               = $data['zone'] ?? null;
        $booking_boat_detail->ticket             = $data['ticket'] ?? null;
        $booking_boat_detail->schedule_time      = $data['schedule_time'] ?? null;
        $booking_boat_detail->variations         = $data['ticket']['prices'] ?? null;
        $booking_boat_detail->additional_options = $data['ticket']['option']['productAdditionalOptions'] ?? null;
        $booking_boat_detail->save();

        return $booking_boat_detail;
    }
}