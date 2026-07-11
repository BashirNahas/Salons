<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Employee;
use App\Models\Service;
use App\Services\AvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingController extends ApiController
{
    public function __construct(private readonly AvailabilityService $availability) {}

    /**
     * Create a booking request (status: pending) — the exact rules of the
     * web wizard: field validation, then a live availability re-check so
     * two customers can't take the same slot.
     */
    public function store(Request $request, string $slug): JsonResponse
    {
        $salon = $this->resolveSalon($slug);

        $data = $request->validate([
            'service_id' => ['required', 'integer', 'exists:services,id'],
            'employee_id' => ['nullable', 'integer', 'exists:employees,id'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'time' => ['required', 'date_format:H:i'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $service = Service::findOrFail($data['service_id']);
        $employee = ! empty($data['employee_id']) ? Employee::find($data['employee_id']) : null;

        $requestedStart = Carbon::parse("{$data['date']} {$data['time']}");

        $isAvailable = $this->availability
            ->availableSlots($salon, $service, $requestedStart->clone()->startOfDay(), $employee)
            ->contains(fn ($slot) => $slot->equalTo($requestedStart));

        if (! $isAvailable) {
            return response()->json([
                'message' => __('That time slot is no longer available. Please choose another.'),
                'errors' => ['time' => [__('That time slot is no longer available. Please choose another.')]],
            ], 422);
        }

        $booking = Booking::create([
            'service_id' => $service->id,
            'employee_id' => $employee?->id,
            'customer_name' => $data['customer_name'],
            'customer_phone' => $data['customer_phone'],
            'customer_email' => $data['customer_email'] ?? null,
            'datetime' => $requestedStart,
            'status' => Booking::STATUS_PENDING,
            'notes' => $data['notes'] ?? null,
        ]);

        $booking->load(['service', 'employee']);

        return response()->json([
            'message' => __('Your booking request has been submitted! The salon will confirm it shortly.'),
            'data' => new BookingResource($booking),
        ], 201);
    }

    /**
     * Status lookup for "My Bookings". The caller must present the phone
     * number the booking was made with — a mismatch is answered with 404
     * so the endpoint can't be used to enumerate other people's bookings.
     */
    public function show(Request $request, string $slug, int $booking): JsonResponse
    {
        $this->resolveSalon($slug);

        $request->validate(['phone' => ['required', 'string', 'max:30']]);

        $found = Booking::with(['service', 'employee'])->find($booking);

        $normalize = fn (string $phone) => preg_replace('/[^0-9+]/', '', $phone);

        if (! $found || $normalize($found->customer_phone) !== $normalize($request->query('phone'))) {
            return response()->json(['message' => __('Booking not found.')], 404);
        }

        return response()->json(['data' => new BookingResource($found)]);
    }
}
