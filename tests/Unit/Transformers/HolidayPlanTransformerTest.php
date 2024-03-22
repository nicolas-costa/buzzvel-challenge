<?php

declare(strict_types=1);

namespace Tests\Unit\Transformers;

use App\Models\HolidayPlan;
use App\DTOs\HolidayPlanDTO;
use App\Transformers\HolidayPlanTransformer;
use DateTime;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class HolidayPlanTransformerTest extends TestCase
{
    public function testToDTO()
    {
        $holidayPlan = new HolidayPlan();
        $holidayPlan->id = 1;
        $holidayPlan->title = 'Holiday';
        $holidayPlan->description = 'Holiday description';
        $holidayPlan->date = new DateTime('2024-12-25');
        $holidayPlan->location = 'Beach';
        $holidayPlan->participants = ['Test Participants'];
        $holidayPlan->user_id = 1;

        $holidayPlanDTO = HolidayPlanTransformer::toDTO($holidayPlan);

        $this->assertEquals(1, $holidayPlanDTO->getId());
        $this->assertEquals('Holiday', $holidayPlanDTO->getTitle());
        $this->assertEquals('Holiday description', $holidayPlanDTO->getDescription());
        $this->assertEquals('2024-12-25', $holidayPlanDTO->getDate());
        $this->assertEquals('Beach', $holidayPlanDTO->getLocation());
        $this->assertEquals(['Test Participants'], $holidayPlanDTO->getParticipants());
        $this->assertEquals(1, $holidayPlanDTO->getUserId());
    }

    public function testToModel()
    {
        $holidayPlanDTO = new HolidayPlanDTO(
            1,
            'Holiday',
            'Holiday description',
            '2024-12-25',
            'Beach',
            ['Test Participants'],
            1
        );

        $holidayPlan = HolidayPlanTransformer::toModel($holidayPlanDTO);

        $this->assertEquals(1, $holidayPlan->id);
        $this->assertEquals('Holiday', $holidayPlan->title);
        $this->assertEquals('Holiday description', $holidayPlan->description);
        $this->assertEquals('2024-12-25', $holidayPlan->date->format('Y-m-d'));
        $this->assertEquals('Beach', $holidayPlan->location);
        $this->assertEquals(['Test Participants'], $holidayPlan->participants);
        $this->assertEquals(1, $holidayPlan->user_id);
    }

    public function testFromPaginatedResults()
    {

        $paginator = $this->getMockBuilder(LengthAwarePaginator::class)
            ->disableOriginalConstructor()
            ->getMock();


        $holidayPlans = [
            (object)[
                'title' => 'Holiday 1',
                'description' => 'Description 1',
                'date' => new DateTime('2024-12-25'),
                'location' => 'Beach 1',
                'participants' => [],
                'user_id' => 1
            ],
            (object)[
                'title' => 'Holiday 2',
                'description' => 'Description 2',
                'date' => new DateTime('2024-12-26'),
                'location' => 'Beach 2',
                'participants' => [],
                'user_id' => 2
            ]
        ];


        $paginator->expects($this->once())
            ->method('getCollection')
            ->willReturn(collect($holidayPlans));

        $paginator->expects($this->once())
            ->method('total')
            ->willReturn(2);

        $paginator->expects($this->once())
            ->method('perPage')
            ->willReturn(10);

        $paginator->expects($this->once())
            ->method('currentPage')
            ->willReturn(1);

        $paginator->expects($this->once())
            ->method('lastPage')
            ->willReturn(1);

        $paginator->expects($this->once())
            ->method('firstItem')
            ->willReturn(1);

        $paginator->expects($this->once())
            ->method('lastItem')
            ->willReturn(2);


        $result = HolidayPlanTransformer::fromPaginatedResults($paginator);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('pagination', $result);
        $this->assertCount(2, $result['data']);
        $this->assertEquals(2, $result['pagination']['total']);
        $this->assertEquals(10, $result['pagination']['per_page']);
        $this->assertEquals(1, $result['pagination']['current_page']);
        $this->assertEquals(1, $result['pagination']['last_page']);
        $this->assertEquals(1, $result['pagination']['from']);
        $this->assertEquals(2, $result['pagination']['to']);
    }

    public function testToArray()
    {
        $holidayPlanDTO = new HolidayPlanDTO(
            null,
            'Holiday',
            'Holiday description',
            '2024-12-25',
            'Beach',
            ['Test Participants'],
            null
        );

        $result = HolidayPlanTransformer::toArray($holidayPlanDTO);

        $this->assertIsArray($result);
        $this->assertCount(5, $result);
        $this->assertEquals('Holiday', $result['title']);
        $this->assertEquals('Holiday description', $result['description']);
        $this->assertEquals('2024-12-25', $result['date']);
        $this->assertEquals('Beach', $result['location']);
        $this->assertEquals(['Test Participants'], $result['participants']);
    }
}
