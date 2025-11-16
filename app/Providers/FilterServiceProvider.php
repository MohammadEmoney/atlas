<?php

namespace App\Providers;

use App\Filters\ManagerFilter;
use App\Filters\PositionFilter;
use App\Filters\SearchFilter;
use App\Services\EmployeeFilterManager;
use Illuminate\Support\ServiceProvider;

class FilterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(EmployeeFilterManager::class, function () {
            return new EmployeeFilterManager(
                new SearchFilter(),
                new PositionFilter(),
                new ManagerFilter()
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
