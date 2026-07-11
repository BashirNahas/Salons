<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Stateless JSON API for the mobile apps. Registered first and
            // with no domain constraint so it answers on the central domain
            // and salon subdomains alike; the /api/v1 prefix can never
            // collide with a tenant page path.
            Route::prefix('api/v1')
                ->middleware([
                    'throttle:api',
                    \Illuminate\Routing\Middleware\SubstituteBindings::class,
                    \App\Http\Middleware\SetApiLocale::class,
                ])
                ->group(__DIR__.'/../routes/api.php');

            // Tenant subdomain routes are registered BEFORE the central
            // routes. Laravel matches routes in registration order (first
            // match wins), and the central "/" homepage route has no domain
            // constraint, so it would otherwise capture "/" on every host —
            // including salon subdomains, serving the central welcome page
            // instead of the salon's public booking page. Registering the
            // domain-scoped tenant group first makes it win for
            // {slug}.salons.synaptix.sy hosts.
            Route::domain('{salonSlug}.'.config('tenancy.central_domain'))
                ->middleware('web')
                ->group(__DIR__.'/../routes/tenant.php');

            Route::middleware('web')
                ->group(__DIR__.'/../routes/web.php');

            // File server for the "public" disk (salon logos etc.). Doesn't
            // rely on the public/storage symlink at all — shared hosting
            // often breaks it (zip extractors that drop symlinks, Apache
            // with FollowSymLinks disabled), so this serves the files
            // directly from storage/app/public instead. Named "media/{path}"
            // rather than "storage/{path}" because Laravel's own local disk
            // ('serve' => true in config/filesystems.php) already registers
            // a built-in "storage/{path}" route that would otherwise shadow
            // this one. No domain constraint, so it works on the central
            // domain and every salon subdomain alike.
            Route::get('media/{path}', [\App\Http\Controllers\PublicStorageController::class, 'show'])
                ->where('path', '.*')
                ->name('public.storage');

            // One-time browser installer for hosts without SSH. Registered
            // WITHOUT the web middleware group on purpose: it must work
            // before an APP_KEY exists (cookie encryption would otherwise
            // throw MissingAppKeyException). 404s unless SETUP_TOKEN is set
            // in .env — see App\Http\Controllers\SetupController.
            Route::get('setup/{token}', \App\Http\Controllers\SetupController::class)
                ->name('setup');
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\IdentifyTenant::class,
            \App\Http\Middleware\SetLocale::class,
        ]);

        // IdentifyTenant must resolve currentSalon() before SubstituteBindings
        // runs, otherwise implicit route-model binding (e.g. {service},
        // {booking}) resolves against an unscoped query and can bind a
        // model belonging to a different salon.
        $middleware->prependToPriorityList(
            before: \Illuminate\Routing\Middleware\SubstituteBindings::class,
            prepend: \App\Http\Middleware\IdentifyTenant::class,
        );

        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureUserHasRole::class,
            'tenant.owner' => \App\Http\Middleware\EnsureTenantOwnership::class,
        ]);

        // Two separate login screens exist (central /admin and per-tenant
        // /dashboard); pick the right one based on whether a salon was
        // resolved from the subdomain for this request.
        $middleware->redirectGuestsTo(
            fn () => currentSalon() ? route('dashboard.login') : route('admin.login')
        );

        $middleware->redirectUsersTo(
            fn () => currentSalon() ? route('dashboard.home') : route('admin.dashboard')
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // API consumers always get JSON errors, even without an explicit
        // Accept header (some HTTP clients omit it).
        $exceptions->shouldRenderJsonWhen(
            fn ($request) => $request->is('api/*') || $request->expectsJson()
        );
    })->create();
