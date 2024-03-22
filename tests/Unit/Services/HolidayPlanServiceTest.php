<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\DTOs\HolidayPlanDTO;
use App\Services\HolidayPlanService;
use App\Repositories\HolidayPlanRepositoryInterface;
use App\Exceptions\DateIsNotAHolidayException;
use NoahNxT\LaravelOpenHolidaysApi\OpenHolidaysApi;
use NoahNxT\LaravelOpenHolidaysApi\Resource\Holidays;
use PHPUnit\Framework\MockObject\MockObject;
use Saloon\Http\Response;
use Tests\TestCase;

class HolidayPlanServiceTest extends TestCase
{
    protected HolidayPlanRepositoryInterface | MockObject $holidayPlanRepositoryMock;
    protected OpenHolidaysApi | MockObject $openHolidaysApiMock;
    protected HolidayPlanService | MockObject $holidayPlanService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->holidayPlanRepositoryMock = $this->createMock(HolidayPlanRepositoryInterface::class);
        $this->openHolidaysApiMock = $this->createMock(OpenHolidaysApi::class);

        $this->holidayPlanService = new HolidayPlanService(
            $this->holidayPlanRepositoryMock,
            $this->openHolidaysApiMock
        );
    }

    public function testCreateHolidayPlan()
    {
        $holidayPlanDTO = new HolidayPlanDTO(
            1,
            'Holiday',
            'Holiday description',
            '2023-12-25',
            'Beach',
            ['Test Participants'],
            1
        );
        $holidaysMock = $this->createMock(Holidays::class);
        $responseMock = $this->createMock(Response::class);

        $responseMock->expects($this->once())
            ->method('array')
            ->willReturn(json_decode($this->getMockedJsonResponse('open-holidays/christmas.json'), true));

        $holidaysMock->expects($this->once())
            ->method('publicHolidaysByDate')
            ->willReturn($responseMock);

        $this->openHolidaysApiMock->expects($this->once())
            ->method('holidays')
            ->willReturn($holidaysMock);

        $this->holidayPlanRepositoryMock->expects($this->once())
            ->method('create')
            ->with($holidayPlanDTO)
            ->willReturn(1); // Assuming a created holiday plan has an ID of 1

        $result = $this->holidayPlanService->create($holidayPlanDTO);

        // Assertions
        $this->assertEquals(1, $result);
    }

    public function testUpdateHolidayPlan()
    {
        $holidayPlanDTO = new HolidayPlanDTO(
            1,
            'Holiday',
            'Holiday description',
            '2023-02-21',
            'Beach',
            ['Test Participants'],
            1
        );

        $holidaysMock = $this->createMock(Holidays::class);
        $responseMock = $this->createMock(Response::class);

        $responseMock->expects($this->once())
            ->method('array')
            ->willReturn(json_decode($this->getMockedJsonResponse('open-holidays/carnival.json'), true));

        $holidaysMock->expects($this->once())
            ->method('publicHolidaysByDate')
            ->willReturn($responseMock);

        $this->openHolidaysApiMock->expects($this->once())
            ->method('holidays')
            ->willReturn($holidaysMock);

        $this->holidayPlanRepositoryMock->expects($this->once())
            ->method('update')
            ->with($holidayPlanDTO);

        $this->holidayPlanService->update($holidayPlanDTO);

    }

    public function testCreateHolidayPlanWithNonHolidayDate()
    {
        $holidayPlanDTO = new HolidayPlanDTO(
            1,
            'Holiday',
            'Holiday description',
            '2023-02-15',
            'Beach',
            ['Test Participants'],
            1
        );

        $holidaysMock = $this->createMock(Holidays::class);
        $responseMock = $this->createMock(Response::class);

        $responseMock->expects($this->once())
            ->method('array')
            ->willReturn([]);

        $holidaysMock->expects($this->once())
            ->method('publicHolidaysByDate')
            ->willReturn($responseMock);

        $this->openHolidaysApiMock->expects($this->once())
            ->method('holidays')
            ->willReturn($holidaysMock);

        // Call the method under test
        $this->expectException(DateIsNotAHolidayException::class);
        $this->holidayPlanService->create($holidayPlanDTO);
    }

    public function testUpdateHolidayPlanWithNonHolidayDate()
    {
        $holidayPlanDTO = new HolidayPlanDTO(
            1,
            'Holiday',
            'Holiday description',
            '2023-02-15',
            'Beach',
            ['Test Participants'],
            1
        );

        $holidaysMock = $this->createMock(Holidays::class);
        $responseMock = $this->createMock(Response::class);

        $responseMock->expects($this->once())
            ->method('array')
            ->willReturn([]);

        $holidaysMock->expects($this->once())
            ->method('publicHolidaysByDate')
            ->willReturn($responseMock);

        $this->openHolidaysApiMock->expects($this->once())
            ->method('holidays')
            ->willReturn($holidaysMock);

        // Call the method under test
        $this->expectException(DateIsNotAHolidayException::class);
        $this->holidayPlanService->update($holidayPlanDTO);
    }
}
