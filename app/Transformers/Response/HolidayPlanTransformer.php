<?php

declare(strict_types=1);

namespace App\Transformers\Response;


use App\DTOs\HolidayPlanDTO;

class HolidayPlanTransformer
{
    public static function created(int $id): array
    {
        return [
            'id' => $id,
            'url' => env('APP_URL') . '/api/v1/holiday-plans/' . $id
        ];
    }

    public static function show(HolidayPlanDTO $holidayPlanDTO): array
    {
        return \App\Transformers\HolidayPlanTransformer::toArray($holidayPlanDTO);
    }
}
