<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\WorkingHour
 */
class WorkingHourResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'day_of_week' => $this->day_of_week, // 0 = Sunday … 6 = Saturday
            'is_closed' => $this->is_closed,
            'start_time' => $this->is_closed ? null : $this->start_time?->format('H:i'),
            'end_time' => $this->is_closed ? null : $this->end_time?->format('H:i'),
        ];
    }
}
