<?php

namespace SertxuDeveloper\LockScreen\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Orchestra\Testbench\TestCase as Orchestra;
use SertxuDeveloper\LockScreen\LockScreenServiceProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class TestCase extends Orchestra
{
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
        $request = SymfonyRequest::create($uri, $method);

        return Request::createFromBase($request);
    }
}