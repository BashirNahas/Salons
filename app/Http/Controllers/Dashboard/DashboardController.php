<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $salon = currentSalon();
        $today = Carbon::today();

        $todayBookings = Booking::with(['service', 'employee'])
            ->whereDate('datetime', $today)
            ->whereNotIn('status', [Booking::STATUS_REJECTED, Booking::STATUS_CANCELLED])
            ->orderBy('datetime')
            ->get();

        $upcoming = Booking::with(['service', 'employee'])
            ->where('status', Booking::STATUS_APPROVED)
            ->where('datetime', '>', now())
            ->orderBy('datetime')
            ->take(10)
            ->get();

        $pendingCount = Booking::where('status', Booking::STATUS_PENDING)->count();
        $approvedCount = Booking::where('status', Booking::STATUS_APPROVED)->count();
        $totalBookings = Booking::count();
        $todayCount = $todayBookings->count();

        return view('dashboard.home', compact(
            'salon',
            'upcoming',
            'todayBookings',
            'pendingCount',
            'approvedCount',
            'totalBookings',
            'todayCount',
        ));
    }
}
