<?php

/**
 * Standalone, one-time APP_KEY generator for hosts where the full Laravel
 * installer can't run yet: EncryptCookies/StartSession (part of the 'web'
 * middleware group that every app route uses for CSRF/session support)
 * throws MissingAppKeyException the instant APP_KEY is empty, before any
 * controller — including the setup installer — gets a chance to run.
 *
 * This script does not bootstrap Laravel at all; it reads and writes .env
 * directly with plain PHP, sidestepping the exception entirely.
 *
 * DELETE THIS FILE after use.
 */

$envPath = __DIR__.'/../.env';

if (! is_file($envPath) || ! is_writable($envPath)) {
    http_response_code(500);
    exit('.env not found or not writable at: '.$envPath);
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

if (preg_match('/^APP_KEY=base64:.+$/m', $env)) {
    exit("APP_KEY is already set — nothing to do.\nYou can proceed to /setup/{$expectedToken}?seed=1 and then delete this file.");
}

$key = 'base64:'.base64_encode(random_bytes(32));

$env = preg_match('/^APP_KEY=.*$/m', $env)
    ? preg_replace('/^APP_KEY=.*$/m', 'APP_KEY='.$key, $env, 1)
    : rtrim($env)."\nAPP_KEY=".$key."\n";

file_put_contents($envPath, $env);

echo "APP_KEY generated and written to .env.\n\n";
echo "Next: visit /setup/{$expectedToken}?seed=1 to run migrations.\n";
echo "Then delete this file (generate-key.php) from the server.\n";
