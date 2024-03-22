<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\HolidayPlanDTO;
use App\Exceptions\DateIsNotAHolidayException;
use App\Exceptions\UnableToCreateHolidayPlanException;
use App\Exceptions\UnableToUpdateHolidayPlanException;
use App\Repositories\HolidayPlanRepositoryInterface;
use NoahNxT\LaravelOpenHolidaysApi\OpenHolidaysApi;

class HolidayPlanService
{
    public function __construct(
        private HolidayPlanRepositoryInterface $holidayPlanRepository,
        private OpenHolidaysApi $openHolidaysApi)
    {
    }

    /**
     * @throws UnableToCreateHolidayPlanException
     * @throws DateIsNotAHolidayException
     */
    public function create(HolidayPlanDTO $holidayPlanDTO): int
    {
        if (!$this->isHoliday($holidayPlanDTO->getDate())) {
            throw new DateIsNotAHolidayException();
        }

        return $this->holidayPlanRepository->create($holidayPlanDTO);
    }

    /**
     * @throws UnableToUpdateHolidayPlanException
     * @throws DateIsNotAHolidayException
     */
    public function update(HolidayPlanDTO $holidayPlanDTO)
    {
        if (!$this->isHoliday($holidayPlanDTO->getDate())) {
            throw new DateIsNotAHolidayException();
        }

        $this->holidayPlanRepository->update($holidayPlanDTO);
    }

    private function isHoliday(string $date): bool
    {
        $holidays =  $this->openHolidaysApi
            ->holidays()
            ->publicHolidaysByDate('PT', $date);

        $targetCountryIndex = array_filter(array_column($holidays->array(), "country"), function ($subArray) {
            return $subArray['isoCode'] == config('holidays.country.iso_code');
        }, ARRAY_FILTER_USE_BOTH );

        // the specified date is a holiday on the configured country?
        return count($targetCountryIndex) > 0;
    }
}
