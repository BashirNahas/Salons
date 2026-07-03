<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Employee;
use App\Models\Service;
use App\Services\AvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(private readonly AvailabilityService $availability) {}

    public function create(): View
    {
        $salon = currentSalon();
        $services = Service::where('is_active', true)->orderBy('name')->get();
        $employees = Employee::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();

        return view('public.booking.create', compact('salon', 'services', 'employees'));
    }

    public function slots(Request $request): JsonResponse
    {
        $data = $request->validate([
            'service_id' => ['required', 'integer', 'exists:services,id'],
            'employee_id' => ['nullable', 'integer', 'exists:employees,id'],
            'date' => ['required', 'date'],
        ]);

        $service = Service::findOrFail($data['service_id']);
        $employee = ! empty($data['employee_id']) ? Employee::find($data['employee_id']) : null;

        $slots = $this->availability
            ->availableSlots(currentSalon(), $service, Carbon::parse($data['date']), $employee)
            ->map(fn ($slot) => $slot->format('H:i'));

        return response()->json(['slots' => $slots->values()]);
    }

    public function store(StoreBookingRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $salon = currentSalon();
        $service = Service::findOrFail($data['service_id']);
        $employee = ! empty($data['employee_id']) ? Employee::find($data['employee_id']) : null;

        $requestedStart = Carbon::parse("{$data['date']} {$data['time']}");

        $isAvailable = $this->availability
            ->availableSlots($salon, $service, $requestedStart->clone()->startOfDay(), $employee)
            ->contains(fn ($slot) => $slot->equalTo($requestedStart));

        if (! $isAvailable) {
            return back()->withInput()->withErrors([
                'time' => 'That time slot is no longer available. Please choose another.',
            ]);
        }

        Booking::create([
            'service_id' => $service->id,
            'employee_id' => $employee?->id,
            'customer_name' => $data['customer_name'],
            'customer_phone' => $data['customer_phone'],
            'customer_email' => $data['customer_email'] ?? null,
            'datetime' => $requestedStart,
            'status' => Booking::STATUS_PENDING,
            'notes' => $data['notes'] ?? null,
        ]);

        return redirect()->route('public.booking.create')
            ->with('status', 'Your booking request has been submitted! The salon will confirm it shortly.');
    }
}
