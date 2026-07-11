<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Booking
 */
class BookingResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'status_label' => __(ucfirst($this->status)),
            'date' => $this->datetime->format('Y-m-d'),
            'time' => $this->datetime->format('H:i'),
            'customer_name' => $this->customer_name,
            'service' => new ServiceResource($this->whenLoaded('service')),
            'employee' => new EmployeeResource($this->whenLoaded('employee')),
        ];
    }
}
