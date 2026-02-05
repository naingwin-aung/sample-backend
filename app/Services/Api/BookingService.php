<?php
namespace App\Services\Api;

use App\Models\Booking;
use App\Models\BoatSeatLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MongoDB\BSON\UTCDateTime as MongoUTCDateTime;

class BookingService
{
    public function detail($booking_number)
    {
        $booking = Booking::with([
            'items.boatItem.boatItemDetail'
        ])
            ->where('booking_number', $booking_number);

        if (Auth::check()) {
            $booking->where('user_id', Auth::id());
        }

        $booking = $booking->firstOrFail();

        return $booking;
    }

    public function bookingsCalendar($product_id, $start_date, $end_date)
    {
        // 1. Prepare your Date Match conditions first
        $matchConditions = [
            'product_id' => (int) $product_id // Ensure type matches DB (int vs string)
        ];

        // Add date filters to the match array
        if ($start_date && $end_date) {
            $matchConditions['date'] = [
                '$gte' => new MongoUTCDateTime(Carbon::parse($start_date)->startOfDay()),
                '$lte' => new MongoUTCDateTime(Carbon::parse($end_date)->endOfDay())
            ];
        } elseif ($start_date) {
            $matchConditions['date'] = ['$gte' => new MongoUTCDateTime(Carbon::parse($start_date)->startOfDay())];
        } elseif ($end_date) {
            $matchConditions['date'] = ['$lte' => new MongoUTCDateTime(Carbon::parse($end_date)->endOfDay())];
        }

        // 2. Run the Aggregation
        /** @var \MongoDB\Laravel\Connection $mongoConnection */
        $mongoConnection = DB::connection('mongodb');
        /** @var \MongoDB\Collection $collection */
        $collection = $mongoConnection->getCollection((new BoatSeatLog)->getTable());
        $cursor = $collection->aggregate([
            // Stage 1: Filter the data (Performance optimization)
            [
                '$match' => $matchConditions
            ],

            // Stage 2: Sort by "latest" (Assuming created_at or _id)
            // -1 means Descending (Newest first), _id ensures stable ordering
            [
                '$sort' => ['logged_at' => -1, '_id' => -1]
            ],

            // Stage 3: Group by your specific unique keys
            [
                '$group' => [
                    '_id'           => [
                        'date'             => '$date',
                        'boat_id'          => '$boat_id',
                        'product_id'       => '$product_id',
                        'schedule_time_id' => '$schedule_time_id',
                        'zone_id'          => '$zone_id',
                        'option_id'        => '$option_id'
                    ],
                    // Since we sorted by newest first, '$first' grabs the latest record
                    'latest_record' => ['$first' => '$$ROOT']
                ]
            ],

            // Stage 4: Clean up the result
            // This promotes the 'latest_record' object to the top level, 
            // so it looks like a normal model result.
            [
                '$replaceRoot' => ['newRoot' => '$latest_record']
            ]
        ]);

        // 3. (Optional) Hydrate back into Eloquent Models if you need Model methods
        $results = $cursor->toArray();
        $seats = BoatSeatLog::hydrate($results);
        return $seats;
    }
}