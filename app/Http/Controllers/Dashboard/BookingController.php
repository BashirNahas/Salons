<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $bookings = Booking::with(['service', 'employee'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->orderBy('datetime')
            ->paginate(20)
            ->withQueryString();

        return view('dashboard.bookings.index', compact('bookings'));
    }

    public function calendar(Request $request): View
    {
        $month = Carbon::parse($request->get('month', now()->format('Y-m-01')))->startOfMonth();

        $bookings = Booking::with('service')
            ->where('status', '!=', Booking::STATUS_REJECTED)
            ->whereBetween('datetime', [$month->clone()->startOfMonth(), $month->clone()->endOfMonth()])
            ->orderBy('datetime')
            ->get()
            ->groupBy(fn (Booking $booking) => $booking->datetime->format('Y-m-d'));

        $weeks = [];
        $cursor = $month->clone()->startOfWeek(Carbon::SUNDAY);
        $end = $month->clone()->endOfMonth()->endOfWeek(Carbon::SATURDAY);

        while ($cursor->lte($end)) {
            $week = [];
            for ($i = 0; $i < 7; $i++) {
                $week[] = $cursor->clone();
                $cursor->addDay();
            }
            $weeks[] = $week;
        }

        return view('dashboard.bookings.calendar', compact('bookings', 'month', 'weeks'));
    }

    public function approve(Booking $booking): RedirectResponse
    {
        $booking->update(['status' => Booking::STATUS_APPROVED]);

        return back()->with('status', 'Booking approved.');
    }

    public function reject(Booking $booking): RedirectResponse
    {
        $booking->update(['status' => Booking::STATUS_REJECTED]);

        return back()->with('status', 'Booking rejected.');
    }
}
