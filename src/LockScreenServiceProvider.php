<?php

namespace SertxuDeveloper\LockScreen;

use Illuminate\Support\ServiceProvider;

class MediaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void {
        $this->registerPublishables();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void {
        $this->mergeConfigFrom(__DIR__.'/../config/lockscreen.php', 'lockscreen');
    }

    /**
     * Register the publishable resources.
     *
     * @return void
     */
    protected function registerPublishables(): void {
        $this->publishes([
            __DIR__.'/../config/lockscreen.php' => config_path('lockscreen.php'),
        ], 'config');
    }
}