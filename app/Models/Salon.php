<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Salon extends Model
{
    /** @use HasFactory<\Database\Factories\SalonFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'owner_id',
        'phone',
        'email',
        'address',
        'description',
        'is_active',
        'logo',
        'instagram',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function workingHours(): HasMany
    {
        return $this->hasMany(WorkingHour::class);
    }

    public function blockedDates(): HasMany
    {
        return $this->hasMany(BlockedDate::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class)->orderBy('sort_order')->orderBy('name');
    }

    public function subdomain(): string
    {
        return "{$this->slug}.".config('tenancy.central_domain');
    }

    public function url(string $path = ''): string
    {
        $scheme = request()?->isSecure() ? 'https' : (str(config('app.url'))->startsWith('https') ? 'https' : 'http');

        return rtrim("{$scheme}://{$this->subdomain()}/{$path}", '/');
    }

    public function logoUrl(): ?string
    {
        return $this->logo ? route('public.storage', ['path' => $this->logo]) : null;
    }
}
