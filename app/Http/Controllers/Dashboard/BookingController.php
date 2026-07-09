<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Employee;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
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

    public function create(): View
    {
        $services = Service::where('is_active', true)->orderBy('name')->get();
        $employees = Employee::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();

        return view('dashboard.bookings.create', compact('services', 'employees'));
    }

    public function store(Request $request): RedirectResponse
    {
        $salonId = currentSalon()->id;

        $data = $request->validate([
            'service_id' => ['required', 'integer', Rule::exists('services', 'id')->where('salon_id', $salonId)],
            'employee_id' => ['nullable', 'integer', Rule::exists('employees', 'id')->where('salon_id', $salonId)],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'date' => ['required', 'date'],
            'time' => ['required', 'date_format:H:i'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $service = Service::findOrFail($data['service_id']);
        $employee = ! empty($data['employee_id']) ? Employee::find($data['employee_id']) : null;
        $datetime = Carbon::parse("{$data['date']} {$data['time']}");
        $slotEnd = $datetime->clone()->addMinutes($service->duration_minutes);

        $overlaps = Booking::with('service')
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_APPROVED])
            ->whereDate('datetime', $datetime->toDateString())
            ->when($employee, fn ($q) => $q->where('employee_id', $employee->id))
            ->get()
            ->contains(function (Booking $existing) use ($datetime, $slotEnd) {
                $existingEnd = $existing->datetime->clone()->addMinutes($existing->service->duration_minutes);

                return $datetime->lt($existingEnd) && $slotEnd->gt($existing->datetime);
            });

        if ($overlaps) {
            return back()->withInput()->withErrors([
                'time' => __('This time overlaps with an existing appointment. Please choose another time.'),
            ]);
        }

        Booking::create([
            'service_id' => $service->id,
            'employee_id' => $employee?->id,
            'customer_name' => $data['customer_name'],
            'customer_phone' => $data['customer_phone'],
            'datetime' => $datetime,
            'status' => Booking::STATUS_APPROVED,
            'notes' => $data['notes'] ?? null,
            'source' => Booking::SOURCE_MANUAL,
        ]);

        return redirect()->route('dashboard.bookings.index')->with('status', __('Appointment added.'));
    }

    public function approve(Booking $booking): RedirectResponse
    {
        $booking->update(['status' => Booking::STATUS_APPROVED]);

        return back()->with('status', __('Booking approved.'));
    }

    public function reject(Booking $booking): RedirectResponse
    {
        $booking->update(['status' => Booking::STATUS_REJECTED]);

        return back()->with('status', __('Booking rejected.'));
    }
}
