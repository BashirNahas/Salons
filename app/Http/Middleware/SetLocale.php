<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Resolves the interface language for the whole application — customer
 * pages, salon dashboards, and the super admin panel alike.
 *
 * Priority: an explicit choice stored in the session (set by clicking the
 * EN/عربي switcher) wins; otherwise, on the very first visit, the
 * browser/phone Accept-Language header decides — Arabic for an
 * Arabic-language device, English for everything else.
 *
 * Carbon's locale is kept in sync so translatedFormat() renders month and
 * day names in the active language.
 */
class SetLocale
{
    private const SUPPORTED = ['en', 'ar'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->get('locale');

        if (! in_array($locale, self::SUPPORTED, true)) {
            $locale = $this->detectFromBrowser($request);
            $request->session()->put('locale', $locale);
        }

        app()->setLocale($locale);
        Carbon::setLocale($locale);

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
