<?php

declare(strict_types=1);

namespace Tests\Unit\Transformers\Response;

use App\DTOs\HolidayPlanDTO;
use App\Transformers\Response\HolidayPlanTransformer;
use PHPUnit\Framework\TestCase;

class HolidayPlanTransformerTest extends TestCase
{
    public function testCreated(): void
    {
        $id = 123;
        $expected = [
            'id' => $id,
            'url' => env('APP_URL') . '/api/v1/holiday-plans/' . $id
        ];

        $result = HolidayPlanTransformer::created($id);

        $this->assertEquals($expected, $result);
    }

    public function testShow(): void
    {
        $holidayPlanDTO = new HolidayPlanDTO(
            1,
            'Test Holiday',
            'Test description',
            '2024-12-25',
            'Test Location',
            ['Test Participants'],
            1
        );

        $expected = [
            'title' => 'Test Holiday',
            'description' => 'Test description',
            'date' => '2024-12-25',
            'location' => 'Test Location',
            'participants' => 'Test Participants',
        ];

        $result = HolidayPlanTransformer::show($holidayPlanDTO);

        $this->assertEquals($expected, $result);
    }
}
