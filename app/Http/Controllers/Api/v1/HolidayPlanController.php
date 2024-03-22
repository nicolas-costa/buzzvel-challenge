<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\DTOs\HolidayPlanDTO;
use App\Exceptions\DateIsNotAHolidayException;
use App\Exceptions\Http\SuppliedDateIsNotAHolidayException;
use App\Exceptions\Http\UnableToCreateHolidayPlanException as UnableToCreateHolidayPlanHttpException;
use App\Exceptions\Http\UnableToUpdateHolidayPlanException as UnableToUpdateHolidayPlanHttpException;
use App\Exceptions\Http\UnableToDeleteHolidayPlanException as UnableToDeleteHolidayPlanHttpException;
use App\Exceptions\Http\UnableToFindHolidayPlanException as UnableToFindHolidayPlanHttpException;
use App\Exceptions\UnableToCreateHolidayPlanException;
use App\Exceptions\UnableToDeleteHolidayPlanException;
use App\Exceptions\UnableToFindHolidayPlanException;
use App\Exceptions\UnableToUpdateHolidayPlanException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\HolidayPlan\CreateHolidayPlanRequest;
use App\Http\Requests\Api\v1\HolidayPlan\UpdateHolidayPlanRequest;
use App\Repositories\HolidayPlanRepository;
use App\Services\HolidayPlanService;
use App\Transformers\Response\HolidayPlanTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class HolidayPlanController extends Controller
{
    public function __construct(
        private HolidayPlanRepository $holidayPlanRepository,
        private HolidayPlanService $holidayPlanService
    ) { }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 10);
        $currentPage = $request->input('current_page', 1);
        $userID = $request->user()->id;


        $holidayPlans = $this->holidayPlanRepository
            ->getPaginatedFromUser(
                $userID,
                $perPage,
                $currentPage
            );

        return response()->json($holidayPlans);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateHolidayPlanRequest $request): JsonResponse
    {
        $holidayPlan = $request->toDTO();
        $holidayPlan->setUserId($request->user()->id);

        try {
            $id = $this->holidayPlanService
                ->create($holidayPlan);

            return response()->json(HolidayPlanTransformer::created($id), Response::HTTP_CREATED);
        } catch (UnableToCreateHolidayPlanException $exception) {
            throw new UnableToCreateHolidayPlanHttpException($exception);
        } catch (DateIsNotAHolidayException $exception) {
            throw new SuppliedDateIsNotAHolidayException($exception);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(HolidayPlanDTO $holidayPlan): JsonResponse
    {
        return response()->json(HolidayPlanTransformer::show($holidayPlan));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHolidayPlanRequest $request): JsonResponse
    {
        $holidayPlan = $request->toDTO();

        try {
            $this->holidayPlanService
                ->update($holidayPlan);

            return response()->json([], Response::HTTP_OK);
        } catch (UnableToUpdateHolidayPlanException $exception) {
            throw new UnableToUpdateHolidayPlanHttpException($exception);
        } catch (DateIsNotAHolidayException $exception) {
            throw new SuppliedDateIsNotAHolidayException($exception);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HolidayPlanDTO $holidayPlan): JsonResponse
    {
        try {
            $this->holidayPlanRepository
                ->delete($holidayPlan);

            return response()->json([], Response::HTTP_NO_CONTENT);
        } catch (UnableToDeleteHolidayPlanException $exception) {
            throw new UnableToDeleteHolidayPlanHttpException($exception);
        } catch (UnableToFindHolidayPlanException $exception) {
            throw new UnableToFindHolidayPlanHttpException(previous: $exception);
        }
    }

    public function pdf(HolidayPlanDTO $holidayPlan): Response
    {
        return Pdf::loadView('holidays.pdf', ['holiday' => $holidayPlan])
            ->download('report.pdf');
    }
}
