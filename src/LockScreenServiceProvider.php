<?php

namespace SertxuDeveloper\LockScreen;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class LockScreenServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(Router $router): void {
        $this->defineAssetPublishing();
        $this->offerPublishing();
        $this->registerCommands();

        $router->aliasMiddleware('lockscreen', LockScreen::class);

        if (config('lockscreen.append_middleware')) {
            $router->pushMiddlewareToGroup('web', LockScreen::class);
        }
    }

    /**
     * Define the asset publishing configuration.
     */
    public function defineAssetPublishing(): void {
        //
    }

    /**
     * Register any application services.
     */
    public function register(): void {
        $this->app->bind(LockScreen::class, function ($app) {
            return new LockScreen(
                $app[ResponseFactory::class],
                $app[UrlGenerator::class],
                $app['config']->get('lockscreen.ttl'),
            );
        });

        $this->configure();
    }

    /**
     * Set up the configuration.
     */
    protected function configure(): void {
        $this->mergeConfigFrom(__DIR__.'/../config/lockscreen.php', 'lockscreen');
    }

    /**
     * Set up the resource publishing groups.
     */
    protected function offerPublishing(): void {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/lockscreen.php' => config_path('lockscreen.php'),
            ], 'lockscreen-config');
        }
    }

    /**
     * Register the Artisan commands.
     */
    protected function registerCommands(): void {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\InstallCommand::class,
            ]);
        }
    }
}
