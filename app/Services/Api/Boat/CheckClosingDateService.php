<?php
namespace App\Services\Api\Boat;

use Carbon\Carbon;

class CheckClosingDateService
{
    public function check($closing_type, $closing_dates, $closing_days, $start_date, $end_date)
    {
        $result_closing_date = [];

        if ($closing_type == 'closing_date') {
            $result_closing_date = array_filter($closing_dates, function ($date) {
                return Carbon::parse($date)->startOfDay()->gte(today());
            });
        } elseif ($closing_type == 'closing_day') {
            $result_closing_date = generateDatesFromClosingDays($closing_days, $start_date, $end_date);
        }

        return $result_closing_date;
    }
}