<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\RecurringBooking;
use App\Models\Service;
use App\Services\RecurringBookingGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RecurringBookingController extends Controller
{
    public function __construct(private readonly RecurringBookingGenerator $generator) {}

    public function index(): View
    {
        $recurringBookings = RecurringBooking::with(['service', 'employee'])
            ->orderBy('day_of_week')
            ->orderBy('time')
            ->get();

        return view('dashboard.recurring-bookings.index', compact('recurringBookings'));
    }

    public function create(): View
    {
        $services = Service::where('is_active', true)->orderBy('name')->get();
        $employees = Employee::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();

        return view('dashboard.recurring-bookings.create', compact('services', 'employees'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $recurring = RecurringBooking::create($data);

        $result = $this->generator->generate($recurring);

        return redirect()->route('dashboard.recurring-bookings.index')
            ->with('status', $this->summarize('Recurring booking created.', $result));
    }

    public function edit(RecurringBooking $recurringBooking): View
    {
        $services = Service::where('is_active', true)->orderBy('name')->get();
        $employees = Employee::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();

        return view('dashboard.recurring-bookings.edit', compact('recurringBooking', 'services', 'employees'));
    }

    public function update(Request $request, RecurringBooking $recurringBooking): RedirectResponse
    {
        $data = $this->validated($request);

        $recurringBooking->update($data);

        $result = $this->generator->regenerate($recurringBooking);

        return redirect()->route('dashboard.recurring-bookings.index')
            ->with('status', $this->summarize('Recurring booking updated.', $result));
    }

    public function destroy(RecurringBooking $recurringBooking): RedirectResponse
    {
        $this->generator->removeFutureOccurrences($recurringBooking);
        $recurringBooking->delete();

        return redirect()->route('dashboard.recurring-bookings.index')
            ->with('status', 'Recurring booking cancelled and upcoming appointments removed.');
    }

    private function validated(Request $request): array
    {
        $salonId = currentSalon()->id;

        $data = $request->validate([
            'service_id' => ['required', 'integer', Rule::exists('services', 'id')->where('salon_id', $salonId)],
            'employee_id' => ['nullable', 'integer', Rule::exists('employees', 'id')->where('salon_id', $salonId)],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'day_of_week' => ['required', 'integer', 'between:0,6'],
            'time' => ['required', 'date_format:H:i'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        return $data;
    }

    /**
     * @param  array{created: int, skipped: int}  $result
     */
    private function summarize(string $prefix, array $result): string
    {
        $message = "{$prefix} {$result['created']} upcoming appointment(s) scheduled.";

        if ($result['skipped'] > 0) {
            $message .= " {$result['skipped']} occurrence(s) skipped because that time was already booked.";
        }

        return $message;
    }
}
