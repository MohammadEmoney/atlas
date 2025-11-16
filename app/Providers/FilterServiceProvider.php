<?php

namespace App\Providers;

use App\Filters\Employee\ManagerFilter;
use App\Filters\Employee\PositionFilter;
use App\Filters\Employee\SearchFilter;
use App\Services\FilterManager;
use Illuminate\Support\ServiceProvider;

class FilterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('employee_filter_manager', function () {
            return new FilterManager(
                new SearchFilter(),
                new PositionFilter(),
                new ManagerFilter()
            );
        });

        $this->app->bind('leave_request_filter_manager', function () {
            return new FilterManager(
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
