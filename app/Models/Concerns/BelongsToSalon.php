<?php

namespace App\Models\Concerns;

use App\Models\Salon;
use App\Models\Scopes\SalonScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Applied to every tenant-owned model (Service, Booking, WorkingHour,
 * BlockedDate). Automatically scopes queries to the salon resolved by the
 * tenancy middleware, and fills salon_id on create. This is the mechanism
 * that guarantees full tenant isolation: a query made while a tenant is
 * resolved can never see another salon's rows.
 */
trait BelongsToSalon
{
    public static function bootBelongsToSalon(): void
    {
        static::addGlobalScope(new SalonScope);

        static::creating(function ($model) {
            if (! $model->salon_id && app()->bound('currentSalon')) {
                $model->salon_id = app('currentSalon')->id;
            }
        });
    }

    public function salon(): BelongsTo
    {
        return $this->belongsTo(Salon::class);
    }
}
