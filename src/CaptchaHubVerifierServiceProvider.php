<?php

namespace WebsiteProtection\CaptchaHubVerifier;

use Illuminate\Support\ServiceProvider;

class CaptchaHubVerifierServiceProvider extends ServiceProvider
{
    public function register(): void {
        $this->mergeConfigFrom(__DIR__ . '/../config/cphub-verifier.php', 'cphub-verifier');

        $this->app->singleton('cphub-verifier', function ($app) {
            return new Verifier(
                $app['config']['cphub-verifier.verify_url'],
                $app['config']['cphub-verifier.secret_key']
            );
        });
    }

    public function boot(): void {
        if($this->app->runningInConsole()) {
            $this->publishes([
                                 __DIR__ . '/../config/cphub-verifier.php' => config_path('cphub-verifier.php'),
                             ], 'config');
        }
    }
}