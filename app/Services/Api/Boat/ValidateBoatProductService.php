<?php
namespace App\Services\Api\Boat;

use Exception;
use App\Models\BoatZone;
use App\Models\ProductTicket;
use App\Enums\ProductTypeEnum;
use App\Models\ProductScheduleTime;

class ValidateBoatProductService
{
    public function validate(array $data)
    {
        $quantitiesById = collect($data['quantities'])->keyBy('id');

        if (isset($data['additional_options']) && count($data['additional_options']) > 0) {
            $additionalOptionsById = collect($data['additional_options'])->keyBy('id');

            if($quantitiesById->values()->sum('quantity') !== $additionalOptionsById->values()->sum('quantity')) {
                throw new Exception('The total quantity of additional options cannot exceed the total quantity of tickets selected.');
            }
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

        return [
            'product_type'  => ProductTypeEnum::BOAT->value,
            'date'          => $data['date'],
            'ticket'        => $ticket,
            'zone'          => $zone,
            'schedule_time' => $scheduleTime,
        ];
    }
}