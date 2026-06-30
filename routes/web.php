<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SalonController as AdminSalonController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Central domain routes (salons.synaptix.sy)
|--------------------------------------------------------------------------
|
| These routes are served on the central application domain only — the
| super admin panel lives here. Individual salon subdomains are routed
| separately in routes/tenant.php.
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AdminAuthController::class, 'create'])->name('login');
        Route::post('login', [AdminAuthController::class, 'store']);
    });

    Route::middleware(['auth', 'role:super_admin'])->group(function () {
        Route::post('logout', [AdminAuthController::class, 'destroy'])->name('logout');

        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('salons', AdminSalonController::class)->except(['show']);

        Route::get('bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    });
});
