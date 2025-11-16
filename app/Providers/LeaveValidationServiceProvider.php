<?php

namespace App\Providers;

use App\Services\LeaveValidationService;
use App\Strategies\LeaveValidation\DurationValidator;
use App\Strategies\LeaveValidation\LeaveBalanceValidator;
use App\Strategies\LeaveValidation\RecentRequestValidator;
use App\Strategies\LeaveValidation\TimeOverlapValidator;
use Illuminate\Support\ServiceProvider;

class LeaveValidationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(LeaveValidationService::class, function(){
            return new LeaveValidationService([
                new LeaveBalanceValidator(),
                new DurationValidator(),
                new RecentRequestValidator(),
                new TimeOverlapValidator(),
            ]);
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
