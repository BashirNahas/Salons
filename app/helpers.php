<?php

use App\Models\Salon;

if (! function_exists('currentSalon')) {
    /**
     * Resolve the salon bound to the current request by the tenancy
     * middleware, or null when the request is not on a tenant subdomain.
     */
    function currentSalon(): ?Salon
    {
        if (! app()->bound('currentSalon')) {
            return null;
        }

        return app('currentSalon');
    }
}
