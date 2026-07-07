<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Resolves the locale for customer-facing pages only (see the
 * "public.*" route-name check below — dashboard/admin/central pages are
 * intentionally left in English regardless of this middleware).
 *
 * Priority: an explicit choice already stored in the session (set by
 * clicking the language switcher) wins; otherwise, on a customer's very
 * first visit, the phone's Accept-Language header decides — Arabic for
 * an Arabic-language phone, English for everything else.
 */
class SetLocale
{
    private const SUPPORTED = ['en', 'ar'];

    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->route() || ! str_starts_with((string) $request->route()->getName(), 'public.')) {
            return $next($request);
        }

        $locale = $request->session()->get('locale');

        if (! in_array($locale, self::SUPPORTED, true)) {
            $locale = $this->detectFromBrowser($request);
            $request->session()->put('locale', $locale);
        }

        app()->setLocale($locale);

        return $next($request);
    }

    private function detectFromBrowser(Request $request): string
    {
        foreach ($request->getLanguages() as $lang) {
            if (str_starts_with($lang, 'ar')) {
                return 'ar';
            }
        }

        return 'en';
    }
}
