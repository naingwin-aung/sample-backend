<?php

use Carbon\Carbon;
function generateDatesFromClosingDays($closingDays, $startDate, $endDate)
{
    $dates     = [];
    $realStart = Carbon::parse($startDate);

    $start = $realStart->isPast() ? today() : $realStart->startOfDay();
    $end   = Carbon::parse($endDate)->startOfDay();

    if ($end->lt(today())) {
        return [];
    }

    foreach ($closingDays as $closingDay) {
        $dayName = $closingDay['name'];

        $dayMapping = [
            'Every Sunday'    => Carbon::SUNDAY,
            'Every Monday'    => Carbon::MONDAY,
            'Every Tuesday'   => Carbon::TUESDAY,
            'Every Wednesday' => Carbon::WEDNESDAY,
            'Every Thursday'  => Carbon::THURSDAY,
            'Every Friday'    => Carbon::FRIDAY,
            'Every Saturday'  => Carbon::SATURDAY
        ];

        if (isset($dayMapping[$dayName])) {
            $targetDay = $dayMapping[$dayName];
            $current   = $start->copy();

            if ($current->dayOfWeek !== $targetDay) {
                $current->next($targetDay);
            }

            while ($current->lte($end)) {
                if ($current->gte(today())) {
                    $dates[] = $current->format('Y-m-d');
                }
                $current->addWeek();
            }
        }
    }

    sort($dates);
    return array_values(array_unique($dates));
}