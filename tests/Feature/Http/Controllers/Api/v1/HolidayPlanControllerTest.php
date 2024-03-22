<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Api\v1;

use App\Models\HolidayPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;

class HolidayPlanControllerTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    /**
     * Test the index method of the HolidayPlanController.
     *
     * @return void
     */
    public function testIndex()
    {
        $user = User::factory()->create();
        $holidayPlans = HolidayPlan::factory()->count(5)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/api/v1/holiday-plans');

        $response->assertStatus(200);

        foreach ($holidayPlans as $holidayPlan) {
            $response->assertJsonFragment([
                'title' => $holidayPlan->title,
            ]);
        }
    }

    /**
     * Test the store method of the HolidayPlanController.
     *
     * @return void
     */
    public function testStore()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/api/v1/holiday-plans', [
            'title' => 'New Holiday Plan',
            'date' => '2020-01-01',
            'location' => 'place',
            'participants' => []
        ]);

        $response->assertStatus(201);

        $response->assertJson([
            'url' => env('APP_URL') . '/api/v1/holiday-plans/' . $response->json('id'),
        ]);
    }

    /**
     * Test the show method of the HolidayPlanController.
     *
     * @return void
     */
    public function testShow()
    {
        $user = User::factory()->create();
        $holidayPlan = HolidayPlan::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/api/v1/holiday-plans/' . $holidayPlan->id);

        $response->assertStatus(200);

        $response->assertJson([
            'title' => $holidayPlan->title,
        ]);
    }

    /**
     * Test the update method of the HolidayPlanController.
     *
     * @return void
     */
    public function testUpdate()
    {
        $user = User::factory()->create();
        $holidayPlan = HolidayPlan::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->put('/api/v1/holiday-plans/' . $holidayPlan->id, [
            'title' => 'Updated Title',
            'description' => 'description',
            'location' => $holidayPlan->location,
            'date' => $holidayPlan->date->format('Y-m-d')
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('holiday_plans', [
            'id' => $holidayPlan->id,
            'title' => 'Updated Title',
            'description' => 'description'
        ]);
    }

    /**
     * Test the destroy method of the HolidayPlanController.
     *
     * @return void
     */
    public function testDestroy()
    {
        $user = User::factory()->create();
        $holidayPlan = HolidayPlan::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete('/api/v1/holiday-plans/' . $holidayPlan->id);

        $response->assertStatus(204);

        $this->assertSoftDeleted('holiday_plans', ['id' => $holidayPlan->id]);
    }

    public function testPdfDownload()
    {
        $user = User::factory()->create();
        $holidayPlan = HolidayPlan::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/api/v1/holiday-plans/' . $holidayPlan->id . '/pdf');

        $response->assertStatus(Response::HTTP_OK);

        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertHeader('Content-Disposition', 'attachment; filename="report.pdf"');
    }
}

