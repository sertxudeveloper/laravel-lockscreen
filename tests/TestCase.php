<?php

namespace SertxuDeveloper\LockScreen\Tests;

use Illuminate\Contracts\Session\Session;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Orchestra\Testbench\TestCase as Orchestra;
use SertxuDeveloper\LockScreen\LockScreenServiceProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class TestCase extends Orchestra
{
    /**
     * Define database migrations.
     */
    protected function defineDatabaseMigrations(): void {
        $this->loadLaravelMigrations();
    }

    /**
     * Get package providers.
     *
     * @param  Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array {
        return [
            LockScreenServiceProvider::class,
        ];
    }

    /**
     * Creates a custom request based on the provided method and URI.
     */
    public function createRequest(string $method, string $uri, array $headers = []): Request {
        $symfonyRequest = SymfonyRequest::create($uri, $method, server: $headers);

        $request = Request::createFromBase($symfonyRequest);
        $request->setLaravelSession(app(Session::class));

        return $request;
    }

    /**
     * Define routes setup.
     *
     * @param  Router  $router
     */
    protected function defineRoutes($router): void {
        $router->get('locked', fn () => 'Account locked')->name('locked');
    }
}
