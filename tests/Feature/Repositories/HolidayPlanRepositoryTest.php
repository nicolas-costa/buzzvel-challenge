<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories;

use App\DTOs\HolidayPlanDTO;
use App\Exceptions\UnableToFindHolidayPlanException;
use App\Models\HolidayPlan;
use App\Repositories\HolidayPlanRepository;
use App\Transformers\HolidayPlanTransformer;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class HolidayPlanRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private HolidayPlanRepository $holidayPlanRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->holidayPlanRepository = new HolidayPlanRepository(new HolidayPlan());
    }

    public function testGetPaginatedFromUser()
    {
        $result = $this->holidayPlanRepository->getPaginatedFromUser(1);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('pagination', $result);
    }

    public function testCreate()
    {
        $holidayPlan = HolidayPlan::factory()->make();
        $holidayPlanDTO = HolidayPlanTransformer::toDTO($holidayPlan);

        $result = $this->holidayPlanRepository->create($holidayPlanDTO);

        $this->assertIsInt($result);
        $this->assertDatabaseHas(HolidayPlan::class, $holidayPlan->toArray());
    }

    public function testUpdate()
    {
        $holidayPlan = HolidayPlan::factory()->create();
        $holidayPlanDTO = HolidayPlanTransformer::toDTO($holidayPlan);

        $this->holidayPlanRepository->update($holidayPlanDTO);

        $this->assertDatabaseHas(HolidayPlan::class, $holidayPlan->toArray());
    }

    public function testDelete()
    {
        $holidayPlan = HolidayPlan::factory()->create();
        $holidayPlanDTO = HolidayPlanTransformer::toDTO($holidayPlan);

        $this->holidayPlanRepository->delete($holidayPlanDTO);

        $this->assertSoftDeleted(HolidayPlan::class, $holidayPlan->toArray());
    }

    public function testDeleteThrowsException()
    {
        $this->expectException(UnableToFindHolidayPlanException::class);

        $holidayPlanDTO = new HolidayPlanDTO(
            random_int(1, 5000),
            'Deleted Holiday',
            'Deleted Description',
            '2024-12-27',
            'Deleted Location',
            [],
            1
        );

        $this->holidayPlanRepository->delete($holidayPlanDTO);
    }
}
