<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Named limiters keep separate counters. Inline "throttle:60,1"
        // definitions share a single per-IP counter across every route
        // that uses them, so browsing the API would eat into the booking
        // budget — named limiters avoid that.
        RateLimiter::for('api', fn (Request $request) => Limit::perMinute(60)->by($request->ip()));

        RateLimiter::for('bookings', fn (Request $request) => Limit::perMinute(10)->by($request->ip()));
    }
}
