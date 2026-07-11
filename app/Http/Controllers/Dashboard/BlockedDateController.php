<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\BlockedDate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlockedDateController extends Controller
{
    public function index(): View
    {
        $blockedDates = BlockedDate::orderBy('date')->paginate(20);

        return view('dashboard.blocked-dates.index', compact('blockedDates'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        BlockedDate::firstOrCreate(['date' => $data['date']], ['reason' => $data['reason'] ?? null]);

        return redirect()->route('dashboard.blocked-dates.index')->with('status', __('Date blocked.'));
    }

    public function destroy(BlockedDate $blockedDate): RedirectResponse
    {
        $blockedDate->delete();

        return redirect()->route('dashboard.blocked-dates.index')->with('status', __('Blocked date removed.'));
    }
}
