<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $salon = currentSalon();

        $upcoming = Booking::with('service')
            ->where('status', Booking::STATUS_APPROVED)
            ->where('datetime', '>=', now())
            ->orderBy('datetime')
            ->take(10)
            ->get();

        $pendingCount = Booking::where('status', Booking::STATUS_PENDING)->count();
        $approvedCount = Booking::where('status', Booking::STATUS_APPROVED)->count();
        $totalBookings = Booking::count();

        return view('dashboard.home', compact('salon', 'upcoming', 'pendingCount', 'approvedCount', 'totalBookings'));
    }
}
