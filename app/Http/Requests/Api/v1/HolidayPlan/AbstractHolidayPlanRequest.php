<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\v1\HolidayPlan;


use App\DTOs\HolidayPlanDTO;
use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractHolidayPlanRequest extends FormRequest
{
    public function toDTO(): HolidayPlanDTO
    {
        $parameters = $this->input();

        $holidayPlan = $this->route('holidayPlan');

        return new HolidayPlanDTO(
            $holidayPlan?->getId() ?? null,
            $parameters['title'],
            $parameters['description'] ?? null,
            $parameters['date'],
            $parameters['location'],
            $parameters['participants'] ?? null,
            null//$this->user()->id()
        );
    }
}
