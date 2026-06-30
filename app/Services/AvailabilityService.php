<?php

namespace App\Services;

use App\Models\BlockedDate;
use App\Models\Booking;
use App\Models\Salon;
use App\Models\Service;
use App\Models\WorkingHour;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

class AvailabilityService
{
    /**
     * Time granularity (in minutes) at which candidate slots are offered.
     */
    private const SLOT_STEP_MINUTES = 15;

    /**
     * Return the list of bookable start times (Carbon instances) for the
     * given salon/service/date, taking working hours, blocked dates, and
     * already-booked (pending or approved) ranges into account.
     *
     * @return Collection<int, CarbonImmutable>
     */
    public function availableSlots(Salon $salon, Service $service, Carbon|CarbonImmutable $date): Collection
    {
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

        $existingBookings = Booking::with('service')
            ->where('salon_id', $salon->id)
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_APPROVED])
            ->whereBetween('datetime', [$windowStart, $windowEnd])
            ->get()
            ->map(fn (Booking $booking) => [
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
                fn (array $booking) => $slotStart->lt($booking['end']) && $slotEnd->gt($booking['start'])
            );

            if (! $overlaps) {
                $slots->push($slotStart);
            }
        }

        return $slots->values();
    }
}
