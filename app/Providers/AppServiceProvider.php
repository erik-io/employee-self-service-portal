<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Leave\LeaveEntitlementCalculatorInterface;
use App\Services\Leave\LeaveEntitlementService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LeaveEntitlementCalculatorInterface::class, LeaveEntitlementService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
