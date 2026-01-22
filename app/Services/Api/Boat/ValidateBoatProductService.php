<?php
namespace App\Services\Api\Boat;

use Exception;
use App\Models\BoatZone;
use App\Models\ProductTicket;
use App\Enums\ProductTypeEnum;
use App\Exceptions\MyException;
use App\Models\ProductTicketPrice;
use App\Models\ProductScheduleTime;

class ValidateBoatProductService
{
    public function validate(array $data)
    {
        $quantitiesById = collect($data['quantities'])->keyBy('id');

        if (isset($data['additional_options']) && count($data['additional_options']) > 0) {
            $additionalOptionsById = collect($data['additional_options'])->keyBy('id');

            $adult_quantity = $quantitiesById[ProductTicketPrice::whereIn('id', $quantitiesById->keys())->where('name', ProductTicketPrice::ADULT)->first()->id]['quantity'] ?? 0;

            foreach ($additionalOptionsById as $additionalOption) {
                if ($additionalOption['quantity'] !== $adult_quantity) {
                    throw new MyException('The quantity of each additional option cannot exceed the total quantity of adult tickets selected.');
                }
            }
        } else {
            $additionalOptionsById = collect();
        }

        $query = ProductTicket::with([
            'product.piers',
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

        if (isset($data['additional_options']) && count($data['additional_options']) > 0) {
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

        [$available_seats, $check_availability] = (new CheckAvailableSeatService())->check(
            $zone['capacity'] ?? 0,
            $ticket['product']['id'],
            $ticket['option']['id'],
            $zone['id'],
            $ticket['id'],
            $scheduleTime['id'],
            $data['date'],
            $ticket->prices->sum('quantity')
        );

        if (!$check_availability) {
            throw new MyException('Sorry, there aren’t enough seats available for the quantity you selected. Please select another date or reduce the number of tickets.');
        }

        $closing_dates = (new CheckClosingDateService())->check(
            $ticket['option']['closing_type'],
            $ticket['option']['closing_dates'],
            $ticket['option']['closing_days'],
            $ticket['option']['start_date'],
            $ticket['option']['end_date']
        );

        if (in_array($data['date'], $closing_dates)) {
            throw new MyException('The selected date is not available for booking. Please choose another date.');
        }

        return [
            'product_type'  => ProductTypeEnum::BOAT->value,
            'date'          => $data['date'],
            'ticket'        => $ticket,
            'zone'          => $zone,
            'schedule_time' => $scheduleTime,
            'travelers'     => $data['travelers'] ?? [],
        ];
    }
}