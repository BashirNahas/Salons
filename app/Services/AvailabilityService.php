<?php

namespace App\Services;

use App\Models\BlockedDate;
use App\Models\Booking;
use App\Models\Employee;
use App\Models\Salon;
use App\Models\Service;
use App\Models\WorkingHour;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

class AvailabilityService
{
    private const SLOT_STEP_MINUTES = 15;

    /**
     * Return bookable start times for the given salon/service/date.
     *
     * When $employee is provided, only that employee's existing bookings are
     * considered (other employees' bookings do not block this slot). When
     * $employee is null, all salon bookings are considered (single-resource
     * / "any staff" mode).
     *
     * @return Collection<int, CarbonImmutable>
     */
    public function availableSlots(
        Salon $salon,
        Service $service,
        Carbon|CarbonImmutable $date,
        ?Employee $employee = null,
    ): Collection {
        $date = CarbonImmutable::parse($date)->startOfDay();

        $isBlocked = BlockedDate::where('salon_id', $salon->id)
            ->whereDate('date', $date)
            ->exists();

        if ($isBlocked) {
            return collect();
        }

        $workingHour = WorkingHour::where('salon_id', $salon->id)
            ->where('day_of_week', $date->dayOfWeek)
            ->first();

        if (! $workingHour || $workingHour->is_closed) {
            return collect();
        }

        $windowStart = $date->setTimeFromTimeString($workingHour->start_time->format('H:i:s'));
        $windowEnd = $date->setTimeFromTimeString($workingHour->end_time->format('H:i:s'));

        $query = Booking::with('service')
            ->where('salon_id', $salon->id)
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_APPROVED])
            ->whereBetween('datetime', [$windowStart, $windowEnd]);

        if ($employee !== null) {
            $query->where('employee_id', $employee->id);
        }

        $existingBookings = $query->get()->map(fn (Booking $booking) => [
            'start' => CarbonImmutable::parse($booking->datetime),
            'end' => CarbonImmutable::parse($booking->datetime)->addMinutes($booking->service->duration_minutes),
        ]);

        $duration = $service->duration_minutes;
        $now = CarbonImmutable::now();
        $slots = collect();

        for ($slotStart = $windowStart; $slotStart->addMinutes($duration)->lte($windowEnd); $slotStart = $slotStart->addMinutes(self::SLOT_STEP_MINUTES)) {
            $slotEnd = $slotStart->addMinutes($duration);

            if ($slotStart->lt($now)) {
                continue;
            }

            $overlaps = $existingBookings->contains(
                fn (array $b) => $slotStart->lt($b['end']) && $slotEnd->gt($b['start'])
            );

            if (! $overlaps) {
                $slots->push($slotStart);
            }
        }

        return $slots->values();
    }
}
