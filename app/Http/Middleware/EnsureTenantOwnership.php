<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Defense in depth for the salon dashboard: even though the tenancy
 * middleware already resolves the salon strictly from the subdomain, this
 * guarantees a logged-in salon owner can never act on a salon they don't
 * own, even if a session cookie were somehow replayed on another salon's
 * subdomain.
 */
class EnsureTenantOwnership
{
    public function handle(Request $request, Closure $next): Response
    {
        $salon = currentSalon();
        $user = $request->user();

        if (! $salon || ! $user || $user->ownedSalon?->id !== $salon->id) {
            abort(403);
        }

        return $next($request);
    }
}
