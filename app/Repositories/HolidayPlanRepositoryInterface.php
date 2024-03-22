<?php

declare(strict_types=1);

namespace App\Repositories;


use App\DTOs\HolidayPlanDTO;
use App\Exceptions\UnableToCreateHolidayPlanException;
use App\Exceptions\UnableToUpdateHolidayPlanException;

interface HolidayPlanRepositoryInterface
{
    /**
     * @throws UnableToCreateHolidayPlanException
     */
    public function create(HolidayPlanDTO $holidayPlan): int;

    /**
     * @throws UnableToUpdateHolidayPlanException
     */
    public function update(HolidayPlanDTO $holidayPlan): void;
}
