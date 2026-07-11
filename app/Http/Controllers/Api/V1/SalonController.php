<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\EmployeeResource;
use App\Http\Resources\SalonResource;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\WorkingHourResource;
use App\Models\BlockedDate;
use App\Models\Employee;
use App\Models\Salon;
use App\Models\Service;
use App\Models\WorkingHour;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SalonController extends ApiController
{
    /**
     * Directory of bookable salons. Expired or disabled salons are hidden
     * from discovery entirely — same rule the web enforces per subdomain.
     */
    public function index(Request $request): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));

        $salons = Salon::where('is_active', true)
            ->when($query !== '', fn ($q) => $q->where(function ($w) use ($query) {
                $w->where('name', 'like', "%{$query}%")
                    ->orWhere('address', 'like', "%{$query}%");
            }))
            ->orderBy('name')
            ->get()
            ->filter->isOperational()
            ->values();

        return response()->json(['data' => SalonResource::collection($salons)]);
    }

    /**
     * Everything the app needs to render a salon profile and start a
     * booking: profile, active services, active staff, weekly hours, and
     * upcoming closed dates (so the date picker can disable them).
     */
    public function show(string $slug): JsonResponse
    {
        $salon = $this->resolveSalon($slug);

        $services = Service::where('is_active', true)->orderBy('name')->get();
        $employees = Employee::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        $workingHours = WorkingHour::orderBy('day_of_week')->get();
        $blockedDates = BlockedDate::whereDate('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->limit(90)
            ->pluck('date')
            ->map(fn ($date) => $date->format('Y-m-d'));

        return response()->json([
            'data' => [
                'salon' => new SalonResource($salon),
                'services' => ServiceResource::collection($services),
                'employees' => EmployeeResource::collection($employees),
                'working_hours' => WorkingHourResource::collection($workingHours),
                'blocked_dates' => $blockedDates,
            ],
        ]);
    }
}
