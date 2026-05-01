<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Définir le répertoire temporaire de l'application
        $tempDir = storage_path('tmp');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        putenv('TMPDIR=' . $tempDir);
        putenv('TEMP=' . $tempDir);
        putenv('TMP=' . $tempDir);
        ini_set('upload_tmp_dir', $tempDir);
        ini_set('sys_temp_dir', $tempDir);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
