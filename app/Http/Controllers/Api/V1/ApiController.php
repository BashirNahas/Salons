<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class ApiController extends Controller
{
    /**
     * Resolve a salon by slug and enforce the same availability rules as
     * the web tenant middleware: unknown slug → 404, disabled or expired
     * subscription → 403 with the customer-facing unavailable message.
     *
     * The resolved salon is bound into the container so the SalonScope
     * global scope and the BelongsToSalon trait behave exactly as they do
     * for web requests — services, employees, and bookings are scoped to
     * this salon automatically, and created bookings get its salon_id.
     */
    protected function resolveSalon(string $slug): Salon
    {
        $salon = Salon::where('slug', $slug)->first();

        if (! $salon) {
            throw new HttpException(404, __('Salon not found.'));
        }

        if (! $salon->isOperational()) {
            throw new HttpException(403, __('This salon is currently unavailable. Please contact the salon for more information.'));
        }

        app()->instance('currentSalon', $salon);

        return $salon;
    }
}
