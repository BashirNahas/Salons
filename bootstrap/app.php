<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::domain('{salonSlug}.'.config('tenancy.central_domain'))
                ->middleware('web')
                ->group(__DIR__.'/../routes/tenant.php');

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
        //
    })->create();
