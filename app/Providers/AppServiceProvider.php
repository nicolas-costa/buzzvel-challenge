<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\HolidayPlanRepository;
use App\Repositories\HolidayPlanRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(HolidayPlanRepositoryInterface::class, HolidayPlanRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
