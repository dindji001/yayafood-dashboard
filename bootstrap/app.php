<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Définir le répertoire temporaire AVANT de configurer l'application
$tempDir = dirname(__DIR__) . '/storage/tmp';
if (!is_dir($tempDir)) {
    mkdir($tempDir, 0755, true);
}
putenv('TMPDIR=' . $tempDir);
putenv('TEMP=' . $tempDir);
putenv('TMP=' . $tempDir);
@ini_set('upload_tmp_dir', $tempDir);
@ini_set('sys_temp_dir', $tempDir);

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\ForcePasswordChange::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Ignorer le notice spécifique sur tempnam()
        $exceptions->report(function (ErrorException $e) {
            if (str_contains($e->getMessage(), 'tempnam(): file created in the system's temporary directory')) {
                return false;
            }
        });
    })->create();
