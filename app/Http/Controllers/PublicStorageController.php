<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

/**
 * Fallback file server for the "public" disk, used when the public/storage
 * symlink doesn't exist or doesn't resolve — common on shared hosting where
 * a zip extractor drops symlinks, or Apache has FollowSymLinks disabled.
 *
 * public/.htaccess only rewrites a request into Laravel when no physical
 * file answers it directly (RewriteCond %{REQUEST_FILENAME} !-f), so a
 * healthy symlink is still served by Apache directly and never reaches
 * this controller — it only kicks in as an automatic fallback.
 */
class PublicStorageController extends Controller
{
    public function show(string $path): Response
    {
        if (str_contains($path, '..')) {
            abort(404);
        }

        $disk = Storage::disk('public');

        if (! $disk->exists($path)) {
            abort(404);
        }

        return response($disk->get($path), 200, [
            'Content-Type' => $disk->mimeType($path) ?: 'application/octet-stream',
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
}
