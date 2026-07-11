<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\WorkingHour;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkingHourController extends Controller
{
    public function index(): View
    {
        $existing = WorkingHour::orderBy('day_of_week')->get()->keyBy('day_of_week');

        return view('dashboard.working-hours.index', compact('existing'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'days' => ['required', 'array'],
            'days.*.is_closed' => ['sometimes', 'boolean'],
            'days.*.start_time' => ['required_if:days.*.is_closed,0', 'nullable', 'date_format:H:i'],
            'days.*.end_time' => ['required_if:days.*.is_closed,0', 'nullable', 'date_format:H:i', 'after:days.*.start_time'],
        ]);

        foreach ($data['days'] as $day => $values) {
            WorkingHour::updateOrCreate(
                ['day_of_week' => $day],
                [
                    'is_closed' => ! empty($values['is_closed']),
                    'start_time' => $values['start_time'] ?? '09:00',
                    'end_time' => $values['end_time'] ?? '18:00',
                ]
            );
        }

        return redirect()->route('dashboard.working-hours.index')->with('status', __('Working hours updated.'));
    }
}
