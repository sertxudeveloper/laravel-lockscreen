<?php

namespace SertxuDeveloper\LockScreen\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Orchestra\Testbench\TestCase as Orchestra;
use SertxuDeveloper\LockScreen\LockScreenServiceProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Illuminate\Contracts\Session\Session;

class TestCase extends Orchestra
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void {
        parent::setUp();
    
        //$this->withFactories(__DIR__ . '/database/factories');
    }

    /**
     * Define database migrations.
     *
     * @return void
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
     * 
     * @param  string  $method
     * @param  string  $uri
     * @return Request
     */
    public function createRequest(string $method, string $uri): Request {
        $symfonyRequest = SymfonyRequest::create($uri, $method);

        $request = Request::createFromBase($symfonyRequest);
        $request->setLaravelSession(app(Session::class));

        return $request;
    }
}