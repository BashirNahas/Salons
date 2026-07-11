<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Central Domain
    |--------------------------------------------------------------------------
    |
    | The base domain on which the application itself (super admin panel,
    | marketing pages, etc.) is served. Salon tenants are resolved as
    | subdomains of this value, e.g. {slug}.salons.synaptix.sy
    |
    */

    'central_domain' => env('CENTRAL_DOMAIN', 'salons.synaptix.sy'),

    /*
    |--------------------------------------------------------------------------
    | Reserved Subdomains
    |--------------------------------------------------------------------------
    |
    | These subdomains can never be claimed by a salon since they are used
    | by the application itself (e.g. www.salons.synaptix.sy).
    |
    */

    'reserved_slugs' => [
        'www', 'admin', 'dashboard', 'api', 'mail', 'ftp', 'app',
    ],

];
