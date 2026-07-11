<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Employee;
use App\Models\Service;
use App\Services\AvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AvailabilityController extends ApiController
{
    public function __construct(private readonly AvailabilityService $availability) {}

    /**
     * Bookable start times for a service on a given date — identical
     * output to the web wizard because it calls the same service class.
     */
    public function slots(Request $request, string $slug): JsonResponse
    {
        $salon = $this->resolveSalon($slug);

        $data = $request->validate([
            'service_id' => ['required', 'integer', 'exists:services,id'],
            'employee_id' => ['nullable', 'integer', 'exists:employees,id'],
            'date' => ['required', 'date'],
        ]);

        $service = Service::findOrFail($data['service_id']);
        $employee = ! empty($data['employee_id']) ? Employee::find($data['employee_id']) : null;

        $slots = $this->availability
            ->availableSlots($salon, $service, Carbon::parse($data['date']), $employee)
            ->map(fn ($slot) => $slot->format('H:i'));

        return response()->json(['data' => ['slots' => $slots->values()]]);
    }
}
