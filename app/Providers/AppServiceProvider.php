<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('cron', fn (Request $request) => Limit::perMinute(30)->by($request->ip()));
        RateLimiter::for('biometric', fn (Request $request) => Limit::perMinute(120)->by($request->ip()));

        $this->ensureBuildAssets();
    }

    private function ensureBuildAssets(): void
    {
        $buildDir = public_path('build');
        $assetsDir = public_path('build/assets');

        if (! is_dir($assetsDir)) {
            mkdir($assetsDir, 0755, true);
        }

        $appCssPath = public_path('build/assets/app.css');
        if (! file_exists($appCssPath) || filesize($appCssPath) < 5000) {
            $tailwind = '';
            $cmslvCss = base_path('../cmslv/public/build/assets/app-BCCP5d-X.css');
            if (file_exists($cmslvCss)) {
                $tailwind = file_get_contents($cmslvCss);
            }
            $adminTheme = file_exists(resource_path('css/admin-theme.css')) ? file_get_contents(resource_path('css/admin-theme.css')) : '';
            $frontendTheme = file_exists(resource_path('css/frontend-theme.css')) ? file_get_contents(resource_path('css/frontend-theme.css')) : '';

            $fullCss = ($tailwind ?: '') . "\n\n" . $adminTheme . "\n\n" . $frontendTheme;
            file_put_contents($appCssPath, $fullCss);
        }

        $adminJsPath = public_path('build/assets/admin.js');
        if (! file_exists($adminJsPath) || filesize($adminJsPath) < 100) {
            $adminJs = file_exists(resource_path('js/admin.js')) ? file_get_contents(resource_path('js/admin.js')) : '';
            $adminJs = preg_replace('/import\s+[^;]+;/', '', $adminJs);
            file_put_contents($adminJsPath, trim($adminJs));
        }

        $manifestPath = public_path('build/manifest.json');
        if (! file_exists($manifestPath) || filesize($manifestPath) < 50) {
            $manifest = [
                'resources/css/app.css' => [
                    'file' => 'assets/app.css',
                    'src' => 'resources/css/app.css',
                    'isEntry' => true,
                ],
                'resources/js/admin.js' => [
                    'file' => 'assets/admin.js',
                    'src' => 'resources/js/admin.js',
                    'isEntry' => true,
                ],
                'resources/js/app.tsx' => [
                    'file' => 'assets/app.js',
                    'src' => 'resources/js/app.tsx',
                    'isEntry' => true,
                ],
            ];
            file_put_contents($manifestPath, json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        }
    }
}
