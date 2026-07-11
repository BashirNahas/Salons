<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Session-free locale detection for the stateless API. The mobile app
 * sends its interface language in the standard Accept-Language header
 * (or an explicit ?lang= override), and validation/error messages come
 * back localized to match.
 */
class SetApiLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->query('lang', '');

        if (! in_array($locale, ['en', 'ar'], true)) {
            $locale = str_starts_with((string) $request->header('Accept-Language'), 'ar') ? 'ar' : 'en';
        }

        app()->setLocale($locale);
        Carbon::setLocale($locale);

        return $next($request);
    }
}
