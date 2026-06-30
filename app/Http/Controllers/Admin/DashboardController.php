<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Salon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalSalons = Salon::count();
        $totalBookings = Booking::count();

        $bookingsPerSalon = Salon::withCount('bookings')
            ->orderByDesc('bookings_count')
            ->take(10)
            ->get();

        $bookingsByStatus = Booking::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $recentBookings = Booking::with(['salon', 'service'])->latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'totalSalons',
            'totalBookings',
            'bookingsPerSalon',
            'bookingsByStatus',
            'recentBookings',
        ));
    }
}
