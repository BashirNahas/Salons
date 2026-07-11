<?php

namespace App\Http\Middleware;

use App\Models\Salon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

/**
 * Resolves the current tenant (salon) from the request's subdomain.
 *
 * - {slug}.salons.synaptix.sy  -> binds the matching Salon as "currentSalon"
 *   in the container, so the SalonScope global scope (see
 *   App\Models\Concerns\BelongsToSalon) automatically restricts every
 *   tenant-owned model query to that salon. This is what guarantees
 *   cross-tenant data isolation.
 * - salons.synaptix.sy / www.salons.synaptix.sy -> central app context
 *   (super admin panel). No salon is bound, so global queries are
 *   unscoped on purpose.
 * - Any other host (e.g. local dev without a matching domain) falls back
 *   to the central context rather than 404ing, so `php artisan serve`
 *   keeps working without DNS set up.
 */
class IdentifyTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $central = config('tenancy.central_domain');

        $suffix = '.'.$central;

        if ($host !== $central && $host !== 'www.'.$central && str_ends_with($host, $suffix)) {
            $slug = substr($host, 0, -strlen($suffix));

            if (in_array($slug, config('tenancy.reserved_slugs'), true)) {
                abort(404);
            }

            $salon = Salon::where('slug', $slug)->first();

            if (! $salon) {
                abort(404, 'Salon not found.');
            }

            // Salon exists but is switched off by the super admin or its
            // subscription has lapsed: serve a friendly unavailable page
            // for EVERY tenant route (public booking, owner login, ajax).
            // Returning here means no tenant context is bound, so nothing
            // downstream can leak data for a suspended salon.
            if (! $salon->isOperational()) {
                return response()->view('tenant-unavailable', ['salon' => $salon], 503);
            }

            app()->instance('currentSalon', $salon);
            view()->share('currentSalon', $salon);

            // Tenant routes are domain-scoped (Route::domain('{salonSlug}.' . ...)),
            // so every route() call to a tenant route needs the salonSlug
            // parameter. Set it as a URL default so callers don't have to
            // pass it explicitly everywhere.
            URL::defaults(['salonSlug' => $slug]);

            // ControllerDispatcher passes route parameters to the controller
            // method positionally (array_values()), not by name. Since the
            // domain wildcard adds "salonSlug" as a route parameter that no
            // controller method actually declares, leaving it in place would
            // shift every other bound parameter (e.g. the resolved Service
            // or Booking model) one position to the right, so the controller
            // would receive the slug string where it expects the model.
            // It's already been consumed above, so it's safe to drop here.
            $request->route()?->forgetParameter('salonSlug');
        }

        return $next($request);
    }
}
