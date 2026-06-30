# Salons System

A multi-tenant salon booking SaaS built with Laravel, Blade, and Tailwind CSS. A
single codebase serves three audiences:

- **Super Admin** (`salons.synaptix.sy/admin`) — creates and manages salons,
  views all bookings, sees platform-wide analytics.
- **Salon Owners** (`{slug}.salons.synaptix.sy/dashboard`) — manage their own
  bookings, services, working hours, and blocked dates.
- **Customers** (`{slug}.salons.synaptix.sy`) — browse a salon's services and
  book an appointment with no login required.

Each salon is a tenant identified by its subdomain. All tenant data
(services, bookings, working hours, blocked dates) is automatically scoped to
the resolved salon via an Eloquent global scope, so cross-tenant data access
is not possible even if a controller forgets to filter explicitly.

## Requirements

- PHP 8.2+
- MySQL 5.7+ / 8.0+ (or MariaDB)
- Composer 2.x
- A web server (Apache/LiteSpeed via DirectAdmin, or Nginx) able to serve the
  `public/` directory and pass requests through `public/index.php`
- **No Node.js / npm is required in production.** Tailwind CSS is loaded via
  the CDN script tag (`https://cdn.tailwindcss.com`) in `resources/views/layouts/*.blade.php`,
  so there is no asset build step to run on the server. See
  [Optional: compiling Tailwind locally](#optional-compiling-tailwind-locally-instead-of-the-cdn)
  if you'd rather ship compiled CSS.

## How tenancy works

- `config/tenancy.php` defines `central_domain` (e.g. `salons.synaptix.sy`)
  and a list of `reserved_slugs` that can never be used as a salon slug
  (`www`, `admin`, `dashboard`, `api`, `mail`, `ftp`, `app`).
- `App\Http\Middleware\IdentifyTenant` runs on every request (registered
  globally on the `web` middleware group in `bootstrap/app.php`). It inspects
  the request host:
  - If the host is exactly the central domain (or doesn't match the tenant
    pattern, e.g. local dev without DNS), the request is treated as a
    **central** request — no tenant is set.
  - If the host is `{slug}.salons.synaptix.sy`, it looks up an active
    `Salon` by slug. If found, it's bound into the container as
    `currentSalon()` and shared with all views. If the slug is reserved or no
    matching active salon exists, the request 404s.
- `routes/web.php` holds **central-domain-only** routes (the `/admin` panel).
  `routes/tenant.php` holds **tenant-subdomain-only** routes (public booking
  site + `/dashboard`) and is mounted in `bootstrap/app.php` via
  `Route::domain('{salonSlug}.'.config('tenancy.central_domain'))`, so the
  router itself separates the two route sets by Host header — there's no
  ambiguity between `salons.synaptix.sy/` and `issa.salons.synaptix.sy/`.
- All tenant-owned models (`Service`, `Booking`, `WorkingHour`,
  `BlockedDate`) use the `App\Models\Concerns\BelongsToSalon` trait, which
  applies `App\Models\Scopes\SalonScope` as a global scope (auto-filters
  every query by `salon_id`) and auto-fills `salon_id` on creation. Route
  model binding (`{booking}`, `{service}`, etc.) inherits the scope
  automatically, so requesting another salon's record by ID returns a 404
  instead of leaking data.
- `App\Http\Middleware\EnsureTenantOwnership` is applied to all
  `/dashboard` routes as defense-in-depth: it confirms the authenticated
  user's `ownedSalon` matches the salon resolved from the subdomain.
- Sessions are host-only cookies (`SESSION_DOMAIN=null`), so a login on one
  salon's subdomain can never be reused on another subdomain or on the
  central `/admin` panel.

## Local setup

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Edit `.env` and set your database credentials (MySQL recommended; SQLite
works fine for local development — just touch a `database/database.sqlite`
file and set `DB_CONNECTION=sqlite`).

```bash
php artisan migrate --seed
php artisan serve
```

The seeder (`database/seeders/DatabaseSeeder.php`) creates:

| Role | Email | Password |
|---|---|---|
| Super Admin | `admin@salons.synaptix.sy` | `password` |
| Owner of "Issa's Salon" (slug `issa`) | `issa@salons.synaptix.sy` | `password` |
| Owner of "Glow Beauty Studio" (slug `glow`) | `lina@salons.synaptix.sy` | `password` |

Both demo salons get a handful of services, Mon–Sat 09:00–18:00 working
hours, and a couple of sample bookings.

For local subdomain testing without real DNS, add entries to your
`/etc/hosts` (or test with `curl -H "Host: issa.salons.synaptix.sy" ...`):

```
127.0.0.1 salons.test
127.0.0.1 issa.salons.test
127.0.0.1 glow.salons.test
```

and set `CENTRAL_DOMAIN=salons.test` / `APP_URL=http://salons.test` in
`.env` for that session.

## Deploying on DirectAdmin (shared hosting)

1. **DNS** — In DirectAdmin's DNS management for `synaptix.sy`, add:
   - An `A` record for `salons` pointing to your server IP.
   - A **wildcard** `A` record for `*.salons` pointing to the same IP, so
     every `{slug}.salons.synaptix.sy` resolves without creating a DNS entry
     per salon.

2. **Domain / document root** — Create the subdomain `salons.synaptix.sy`
   in DirectAdmin. Because all tenant traffic also lands on this same
   vhost (via the wildcard DNS record above), you do **not** need to create
   a DirectAdmin subdomain per salon — one vhost handles every
   `*.salons.synaptix.sy` host. Point the document root to the project's
   `public/` directory (e.g. `public_html/salons/public`, with the rest of
   the Laravel app stored one level above `public_html` if your hosting
   plan allows it, otherwise alongside it but outside the web-accessible
   path).

3. **PHP version** — In DirectAdmin's "Select PHP Version" tool, choose PHP
   8.2 or newer and enable the extensions Laravel needs: `mbstring`,
   `openssl`, `pdo`, `pdo_mysql`, `tokenizer`, `xml`, `ctype`, `json`,
   `bcmath`, `fileinfo`.

4. **MySQL database** — In DirectAdmin's MySQL Management, create a
   database and a user with full privileges on it (e.g. database
   `salons_system`, user `salons_user`).

5. **Upload the code** — Upload the project (excluding `vendor/` and
   `node_modules/` if present) via Git, SFTP, or DirectAdmin's File Manager,
   then SSH in (or use DirectAdmin's terminal if available) and run:

   ```bash
   cd /path/to/project
   composer install --no-dev --optimize-autoloader
   cp .env.example .env
   ```

6. **Configure `.env`** for production:

   ```env
   APP_NAME="Salons System"
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://salons.synaptix.sy
   CENTRAL_DOMAIN=salons.synaptix.sy

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=salons_system
   DB_USERNAME=salons_user
   DB_PASSWORD=your-password

   SESSION_DRIVER=database
   SESSION_DOMAIN=null
   ```

   `SESSION_DOMAIN=null` is intentional — see [How tenancy works](#how-tenancy-works).

7. **Generate the app key, migrate, and seed**:

   ```bash
   php artisan key:generate
   php artisan migrate --force
   php artisan db:seed --force   # optional: creates the demo super admin + salons
   ```

   In production you'll likely want to skip the demo seeder and instead log
   in as the super admin you create manually, or adapt
   `DatabaseSeeder` to only create your real super-admin account.

8. **Cache config/routes/views** for performance (re-run after every
   deploy):

   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

9. **Storage permissions** — ensure `storage/` and
   `bootstrap/cache/` are writable by the web server user:

   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

10. **HTTPS** — issue an SSL certificate for `salons.synaptix.sy` covering
    the wildcard (`*.salons.synaptix.sy`) via DirectAdmin's Let's Encrypt
    integration, so every salon subdomain is served over HTTPS too.

11. **Queue / cron (optional)** — this app doesn't currently dispatch any
    queued jobs, so a worker isn't required. If you later add notifications
    or emails as queued jobs, set `QUEUE_CONNECTION=database` (already the
    default) and add a DirectAdmin cron entry running
    `php artisan schedule:run` every minute, plus a `php artisan queue:work`
    process (or `queue:listen` via a supervisor/cron-based restarter, since
    most shared hosts don't allow long-running daemons).

No `npm install` or `npm run build` step is required anywhere in this
process — Tailwind is loaded from the CDN at request time.

## Optional: compiling Tailwind locally instead of the CDN

The original Laravel scaffold files (`package.json`, `vite.config.js`,
`tailwind.config.js`, `postcss.config.js`) are still present and untouched
if you'd prefer a compiled, offline-capable stylesheet instead of the CDN
script. To switch:

```bash
npm install
npm run build
```

Then replace the `<script src="https://cdn.tailwindcss.com">` tag in
`resources/views/layouts/base.blade.php` with `@vite('resources/css/app.css')`
and update `tailwind.config.js`'s `content` array to include
`./resources/**/*.blade.php`. This is entirely optional — the app runs
correctly without it.

## Project structure highlights

```
app/Http/Controllers/Admin/        Super admin panel controllers
app/Http/Controllers/Dashboard/    Salon owner dashboard controllers
app/Http/Controllers/Public/       Public salon page + booking controllers
app/Http/Middleware/IdentifyTenant.php   Subdomain -> currentSalon() resolution
app/Models/Scopes/SalonScope.php   Global query scope enforcing tenant isolation
app/Models/Concerns/BelongsToSalon.php   Trait applied to all tenant-owned models
app/Services/AvailabilityService.php     Computes bookable time slots
routes/web.php                     Central domain routes (admin panel)
routes/tenant.php                  Tenant subdomain routes (public site + dashboard)
resources/views/admin/             Super admin Blade views
resources/views/dashboard/         Salon owner Blade views
resources/views/public/            Public booking site Blade views
database/seeders/DatabaseSeeder.php   Demo super admin + 2 demo salons
```

## Tests

```bash
php artisan test
```
