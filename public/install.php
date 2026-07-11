<?php

/**
 * Standalone, token-protected installer that boots Laravel in CONSOLE mode
 * and runs Artisan commands directly — it never goes through the HTTP
 * kernel, so it never touches routing or the 'web' middleware group
 * (sessions, cookies, CSRF). That matters here specifically: the app's
 * database session driver needs the `sessions` table to exist before any
 * HTTP request can be served at all, which made the earlier HTTP-based
 * /setup/{token} route unusable on a fresh database (chicken-and-egg).
 * Running migrations through the console kernel sidesteps that entirely.
 *
 * Visit /install.php?token=SETUP_TOKEN (add &seed=1 for demo data).
 * DELETE THIS FILE after use, and remove SETUP_TOKEN from .env.
 */

header('Content-Type: text/plain');

$envPath = __DIR__.'/../.env';

if (! is_file($envPath)) {
    http_response_code(500);
    exit('.env not found.');
}

$env = file_get_contents($envPath);

if (! preg_match('/^SETUP_TOKEN=(.*)$/m', $env, $matches) || trim($matches[1]) === '') {
    http_response_code(404);
    exit('Not found.');
}

$expectedToken = trim($matches[1]);
$givenToken = $_GET['token'] ?? '';

if (! hash_equals($expectedToken, $givenToken)) {
    http_response_code(404);
    exit('Not found.');
}

require __DIR__.'/../vendor/autoload.php';

/** @var \Illuminate\Foundation\Application $app */
$app = require __DIR__.'/../bootstrap/app.php';

/** @var \Illuminate\Contracts\Console\Kernel $kernel */
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

if ((string) config('app.key') === '') {
    echo "== Generating application key ==\n";
    \Illuminate\Support\Facades\Artisan::call('key:generate', ['--force' => true]);
    echo trim(\Illuminate\Support\Facades\Artisan::output())."\n\n";
}

echo "== Running database migrations ==\n";
\Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
echo trim(\Illuminate\Support\Facades\Artisan::output())."\n";

if (($_GET['seed'] ?? '') === '1') {
    echo "\n== Seeding demo data ==\n";
    \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
    echo trim(\Illuminate\Support\Facades\Artisan::output())."\n";
}

echo "\n== Linking public storage ==\n";
try {
    \Illuminate\Support\Facades\Artisan::call('storage:link', ['--force' => true]);
    echo trim(\Illuminate\Support\Facades\Artisan::output())."\n";
} catch (\Throwable $e) {
    echo 'storage:link failed: '.$e->getMessage()."\n";
    echo "(Logo uploads won't display until this exists — everything else works.)\n";
}

echo "\n== Clearing cached config/routes/views ==\n";
\Illuminate\Support\Facades\Artisan::call('optimize:clear');
echo trim(\Illuminate\Support\Facades\Artisan::output())."\n";

echo "\nDONE.\n";
echo "Now delete install.php (and generatekey.php, if still present) from the\n";
echo "server, and remove the SETUP_TOKEN line from .env.\n";
