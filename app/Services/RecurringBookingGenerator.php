<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\RecurringBooking;
use Carbon\Carbon;

/**
 * Materializes real Booking rows for a recurring rule (e.g. "every
 * Saturday at 10:00"), rather than computing occurrences on the fly.
 * Real rows are what AvailabilityService and the rest of the booking
 * flow already understand, so a recurring customer's slot blocks online
 * bookings the same way any other approved booking would — no special
 * casing needed elsewhere.
 *
 * Generates a fixed horizon ahead of time (see HORIZON_WEEKS) instead of
 * relying on a cron job, since this app targets shared hosting without
 * guaranteed scheduled task support.
 */
class RecurringBookingGenerator
{
    private const HORIZON_WEEKS = 26;

    /**
     * @return array{created: int, skipped: int}
     */
    public function generate(RecurringBooking $recurring): array
    {
        if (! $recurring->is_active) {
            return ['created' => 0, 'skipped' => 0];
        }

        $horizonEnd = Carbon::today()->addWeeks(self::HORIZON_WEEKS);
        $end = $recurring->end_date
            ? ($recurring->end_date->lt($horizonEnd) ? $recurring->end_date : $horizonEnd)
            : $horizonEnd;

        $cursor = $recurring->start_date->gt(Carbon::today())
            ? $recurring->start_date->copy()
            : Carbon::today();

        while ($cursor->dayOfWeek !== (int) $recurring->day_of_week) {
            $cursor->addDay();
        }

        $time = $recurring->time->format('H:i:s');
        $created = 0;
        $skipped = 0;

        while ($cursor->lte($end)) {
            $datetime = Carbon::parse($cursor->toDateString().' '.$time);

            $alreadyGenerated = Booking::where('recurring_booking_id', $recurring->id)
                ->where('datetime', $datetime)
                ->exists();

            if (! $alreadyGenerated) {
                $conflict = Booking::where('datetime', $datetime)
                    ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_APPROVED])
                    ->exists();

                if ($conflict) {
                    $skipped++;
                } else {
                    Booking::create([
                        'service_id' => $recurring->service_id,
                        'employee_id' => $recurring->employee_id,
                        'customer_name' => $recurring->customer_name,
                        'customer_phone' => $recurring->customer_phone,
                        'datetime' => $datetime,
                        'status' => Booking::STATUS_APPROVED,
                        'notes' => $recurring->notes,
                        'source' => Booking::SOURCE_RECURRING,
                        'recurring_booking_id' => $recurring->id,
                    ]);
                    $created++;
                }
            }

            $cursor->addWeek();
        }

        return ['created' => $created, 'skipped' => $skipped];
    }

    /**
     * Cancels not-yet-happened occurrences of a rule (e.g. after editing
     * its time/day, or disabling/deleting it). Past occurrences are left
     * alone as history.
     */
    public function removeFutureOccurrences(RecurringBooking $recurring): int
    {
        // Cancelled occurrences are kept: they are audit history, and their
        // presence is what stops generate() from re-creating that same
        // datetime after a regenerate() — otherwise editing the rule would
        // silently undo a cancellation the owner made on purpose.
        return Booking::where('recurring_booking_id', $recurring->id)
            ->where('datetime', '>=', now())
            ->where('status', '!=', Booking::STATUS_CANCELLED)
            ->delete();
    }

    /**
     * @return array{created: int, skipped: int}
     */
    public function regenerate(RecurringBooking $recurring): array
    {
        $this->removeFutureOccurrences($recurring);

        return $this->generate($recurring);
    }
}
