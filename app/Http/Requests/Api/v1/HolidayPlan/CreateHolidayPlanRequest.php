<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\v1\HolidayPlan;

class CreateHolidayPlanRequest extends AbstractHolidayPlanRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'date' => 'required|date_format:Y-m-d|after:2023-12-31|before:2025-01-01', // could be dynamic
            'location' => 'required|string|max:255',
            'participants' => 'nullable|array',
            'participants.*' => 'string|max:255',
        ];
    }
}
