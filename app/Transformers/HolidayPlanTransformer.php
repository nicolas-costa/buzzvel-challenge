<?php

declare(strict_types=1);

namespace App\Transformers;

use App\DTOs\HolidayPlanDTO;
use App\Models\HolidayPlan;
use Illuminate\Pagination\LengthAwarePaginator;

class HolidayPlanTransformer
{
    public static function toDTO(HolidayPlan $holidayPlan): HolidayPlanDTO
    {
        return new HolidayPlanDTO(
            $holidayPlan->id,
            $holidayPlan->title,
            $holidayPlan->description,
            $holidayPlan->date->format('Y-m-d'),
            $holidayPlan->location,
            $holidayPlan->participants,
            $holidayPlan->user_id
        );
    }

    public static function toModel(HolidayPlanDTO $holidayPlanDTO): HolidayPlan
    {
        $holidayPlan = new HolidayPlan();

        $holidayPlan->exists = $holidayPlanDTO->getId() != null;

        if ($holidayPlanDTO->getId()) {
            $holidayPlan->id = $holidayPlanDTO->getId();
        }

        if ($holidayPlanDTO->getTitle()) {
            $holidayPlan->title = $holidayPlanDTO->getTitle();
        }

        if ($holidayPlanDTO->getDescription()) {
            $holidayPlan->description = $holidayPlanDTO->getDescription();
        }

        if ($holidayPlanDTO->getDate()) {
            $holidayPlan->date = $holidayPlanDTO->getDate();
        }

        if ($holidayPlanDTO->getLocation()) {
            $holidayPlan->location = $holidayPlanDTO->getLocation();
        }

        if ($holidayPlanDTO->getParticipants()) {
            $holidayPlan->participants = $holidayPlanDTO->getParticipants();
        }

        if ($holidayPlanDTO->getUserId()) {
            $holidayPlan->user_id = $holidayPlanDTO->getUserId();
        }

        return $holidayPlan;
    }

    public static function fromPaginatedResults(LengthAwarePaginator $paginator): array
    {
        $data = $paginator->getCollection()->map(function ($holidayPlan) {
            return [
                'title' => $holidayPlan->title,
                'description' => $holidayPlan->description,
                'date' => $holidayPlan->date->format('Y-m-d'),
                'location' => $holidayPlan->location,
                'participants' => $holidayPlan->participants,
                'user_id' => $holidayPlan->user_id
            ];
        })->toArray();

        return [
            'data' => $data,
            'pagination' => [
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
        ];
    }

    public static function toArray(HolidayPlanDTO $holidayPlanDTO): array
    {
        return [
            'title' => $holidayPlanDTO->getTitle(),
            'description' => $holidayPlanDTO->getDescription(),
            'date' => $holidayPlanDTO->getDate(),
            'location' => $holidayPlanDTO->getLocation(),
            'participants' => $holidayPlanDTO->getParticipants(),
        ];
    }
}
