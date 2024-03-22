<?php

declare(strict_types=1);

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\v1\HolidayPlanController;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('auth/login', AuthController::class . '@__invoke');

Route::group([
    'prefix' => 'v1'
], function (Router $router) {
    $router->group([
        'middleware' => 'auth:api',
        'controller' => HolidayPlanController::class,
        'prefix' => 'holiday-plans'
    ], function (Router $router) {
        $router->get('', 'index');
        $router->get('{holidayPlan}', 'show');
        $router->put('{holidayPlan}', 'update');
        $router->post('', 'store');
        $router->delete('{holidayPlan}', 'destroy');
        $router->get('{holidayPlan}/pdf', 'pdf');
    });
});
