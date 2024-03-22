<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTOs\HolidayPlanDTO;
use App\Exceptions\UnableToDeleteHolidayPlanException;
use App\Exceptions\UnableToCreateHolidayPlanException;
use App\Exceptions\UnableToFindHolidayPlanException;
use App\Exceptions\UnableToUpdateHolidayPlanException;
use App\Models\HolidayPlan;
use App\Transformers\HolidayPlanTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class HolidayPlanRepository implements HolidayPlanRepositoryInterface
{
    public function __construct(private HolidayPlan $holidayPlan)
    {
    }

    public function getPaginatedFromUser(int $userId, int $perPage = 10, int $currentPage = 1): array
    {
        $holidays = $this->holidayPlan
            ->where('user_id', $userId)
            ->paginate(perPage: $perPage, page: $currentPage);

        return HolidayPlanTransformer::fromPaginatedResults($holidays);
    }

    /**
     * @throws UnableToCreateHolidayPlanException
     */
    public function create(HolidayPlanDTO $holidayPlan): int
    {
        $model = HolidayPlanTransformer::toModel($holidayPlan);

        if (!$model->save()) {
            throw new UnableToCreateHolidayPlanException();
        }

        return $model->id;
    }

    /**
     * @throws UnableToUpdateHolidayPlanException
     */
    public function update(HolidayPlanDTO $holidayPlan): void
    {
        $model = HolidayPlanTransformer::toModel($holidayPlan);

        if (!$model->update()) {
            throw new UnableToUpdateHolidayPlanException();
        }
    }

    /**
     * @throws UnableToDeleteHolidayPlanException
     * @throws UnableToFindHolidayPlanException
     */
    public function delete(HolidayPlanDTO $holidayPlan): void
    {
        try {
            $holidayPlan = $this->holidayPlan
                ->where('id', $holidayPlan->getId())
                ->firstOrFail();
        } catch (ModelNotFoundException) {
            throw new UnableToFindHolidayPlanException();
        }

        if (!$holidayPlan->delete()) {
            throw new UnableToDeleteHolidayPlanException();
        }
    }
}
