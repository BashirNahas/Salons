<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;

/**
 * One-time, token-protected installer for shared hosting without SSH.
 *
 * Enabled only while SETUP_TOKEN is set in .env. Visiting
 * /setup/{token} runs the database migrations (and optionally the demo
 * seeder with ?seed=1) and creates the public/storage symlink. Once the
 * app is installed, remove SETUP_TOKEN from .env to disable this route
 * entirely — without a configured token every request 404s.
 */
class SetupController extends Controller
{
    public function __invoke(Request $request, string $token): Response
    {
        $expected = (string) config('app.setup_token');

        if ($expected === '' || ! hash_equals($expected, $token)) {
            abort(404);
        }

        $output = [];

        if ((string) config('app.key') === '') {
            $output[] = '== Generating application key ==';
            Artisan::call('key:generate', ['--force' => true]);
            $output[] = trim(Artisan::output());
            $output[] = '';
        }

        $output[] = '== Running database migrations ==';
        Artisan::call('migrate', ['--force' => true]);
        $output[] = trim(Artisan::output());

        if ($request->boolean('seed')) {
            $output[] = '';
            $output[] = '== Seeding demo data ==';
            Artisan::call('db:seed', ['--force' => true]);
            $output[] = trim(Artisan::output());
        }

        $output[] = '';
        $output[] = '== Linking public storage ==';
        try {
            Artisan::call('storage:link', ['--force' => true]);
            $output[] = trim(Artisan::output());
        } catch (\Throwable $e) {
            $output[] = 'storage:link failed: '.$e->getMessage();
            $output[] = '(Logo uploads will not display until the symlink exists — everything else works.)';
        }

        $output[] = '';
        $output[] = '== Clearing cached config/routes/views ==';
        Artisan::call('optimize:clear');
        $output[] = trim(Artisan::output());

        $output[] = '';
        $output[] = 'DONE. Now remove the SETUP_TOKEN line from .env to disable this installer.';

        return response(implode("\n", $output), 200, ['Content-Type' => 'text/plain']);
    }
}
