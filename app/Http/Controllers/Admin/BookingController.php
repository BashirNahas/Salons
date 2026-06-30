<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Salon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $bookings = Booking::with(['salon', 'service'])
            ->when($request->filled('salon_id'), fn ($q) => $q->where('salon_id', $request->integer('salon_id')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->latest('datetime')
            ->paginate(20)
            ->withQueryString();

        $salons = Salon::orderBy('name')->get(['id', 'name']);

        return view('admin.bookings.index', compact('bookings', 'salons'));
    }
}
