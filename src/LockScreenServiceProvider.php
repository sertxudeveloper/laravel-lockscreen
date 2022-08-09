<?php

namespace SertxuDeveloper\LockScreen;

use Illuminate\Support\ServiceProvider;

class LockScreenServiceProvider extends ServiceProvider
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
        $this->registerMiddleware();
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

    /**
     * Register the middleware.
     * 
     * @return void
     */
    protected function registerMiddleware(): void {
        $this->app->bind(LockScreen::class, function ($app) {
            return new LockScreen(
                $app[ResponseFactory::class],
                $app[UrlGenerator::class],
                $app['config']->get('lockscreen.ttl'),
            );
        });
    }
}