<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSalon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    /** @use HasFactory<\Database\Factories\BookingFactory> */
    use HasFactory, BelongsToSalon;

    public const STATUS_PENDING = 'pending';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_CANCELLED = 'cancelled';

    public const SOURCE_ONLINE = 'online';

    public const SOURCE_MANUAL = 'manual';

    public const SOURCE_RECURRING = 'recurring';

    protected $fillable = [
        'salon_id',
        'service_id',
        'employee_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'datetime',
        'status',
        'notes',
        'source',
        'recurring_booking_id',
    ];

    protected function casts(): array
    {
        return [
            'datetime' => 'datetime',
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function recurringBooking(): BelongsTo
    {
        return $this->belongsTo(RecurringBooking::class);
    }

    /**
     * Cancelling only makes sense for appointments that still hold a slot:
     * pending requests and approved bookings. Rejected/cancelled rows are
     * already terminal.
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_APPROVED], true);
    }
}
