<?php

use App\Http\Controllers\Api\V1\AvailabilityController;
use App\Http\Controllers\Api\V1\BookingController;
use App\Http\Controllers\Api\V1\SalonController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Mobile API (v1)
|--------------------------------------------------------------------------
|
| Consumed by the Flutter customer app. Stateless JSON — no sessions, no
| CSRF. Salons are addressed by slug in the path (the mobile app has no
| notion of subdomains), and every endpoint enforces the same subscription
| rules as the web: a disabled or expired salon is unavailable.
|
*/

Route::get('salons', [SalonController::class, 'index']);
Route::get('salons/{slug}', [SalonController::class, 'show']);
Route::get('salons/{slug}/slots', [AvailabilityController::class, 'slots']);

// Creating bookings is throttled harder than reads to keep abuse in check.
Route::post('salons/{slug}/bookings', [BookingController::class, 'store'])
    ->middleware('throttle:bookings');

Route::get('salons/{slug}/bookings/{booking}', [BookingController::class, 'show']);
