<?php

use App\Http\Controllers\Dashboard\AuthController as DashboardAuthController;
use App\Http\Controllers\Dashboard\BlockedDateController;
use App\Http\Controllers\Dashboard\BookingController as DashboardBookingController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\EmployeeController;
use App\Http\Controllers\Dashboard\SalonProfileController;
use App\Http\Controllers\Dashboard\ServiceController;
use App\Http\Controllers\Dashboard\WorkingHourController;
use App\Http\Controllers\Public\BookingController as PublicBookingController;
use App\Http\Controllers\Public\SalonController as PublicSalonController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tenant (salon subdomain) routes — {slug}.salons.synaptix.sy
|--------------------------------------------------------------------------
*/

// Public booking site
Route::get('/', [PublicSalonController::class, 'show'])->name('public.salon.show');
Route::get('book', [PublicBookingController::class, 'create'])->name('public.booking.create');
Route::get('book/slots', [PublicBookingController::class, 'slots'])->name('public.booking.slots');
Route::post('book', [PublicBookingController::class, 'store'])->name('public.booking.store');

// Salon owner dashboard
Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [DashboardAuthController::class, 'create'])->name('login');
        Route::post('login', [DashboardAuthController::class, 'store']);
    });

    Route::middleware(['auth', 'role:salon_owner', 'tenant.owner'])->group(function () {
        Route::post('logout', [DashboardAuthController::class, 'destroy'])->name('logout');

        Route::get('/', [DashboardController::class, 'index'])->name('home');

        Route::get('bookings', [DashboardBookingController::class, 'index'])->name('bookings.index');
        Route::get('bookings/calendar', [DashboardBookingController::class, 'calendar'])->name('bookings.calendar');
        Route::post('bookings/{booking}/approve', [DashboardBookingController::class, 'approve'])->name('bookings.approve');
        Route::post('bookings/{booking}/reject', [DashboardBookingController::class, 'reject'])->name('bookings.reject');

        Route::resource('services', ServiceController::class)->except(['show']);

        Route::resource('employees', EmployeeController::class)->except(['show']);

        Route::get('working-hours', [WorkingHourController::class, 'index'])->name('working-hours.index');
        Route::put('working-hours', [WorkingHourController::class, 'update'])->name('working-hours.update');

        Route::get('blocked-dates', [BlockedDateController::class, 'index'])->name('blocked-dates.index');
        Route::post('blocked-dates', [BlockedDateController::class, 'store'])->name('blocked-dates.store');
        Route::delete('blocked-dates/{blockedDate}', [BlockedDateController::class, 'destroy'])->name('blocked-dates.destroy');

        Route::get('profile', [SalonProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [SalonProfileController::class, 'update'])->name('profile.update');
    });
});
