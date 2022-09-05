<?php

namespace SertxuDeveloper\LockScreen\Tests;

use SertxuDeveloper\LockScreen\LockScreen;
use SertxuDeveloper\LockScreen\Tests\Models\User;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class LockScreenTest extends TestCase
{
    /**
     * Check if the user stores in session the last activity timestamp.
     *
     * @return void
     */
    public function test_user_stores_last_activity_timestamp(): void {
        $user = User::factory()->create();
        $this->actingAs($user);

        $middleware = app(LockScreen::class);

        $request = $this->createRequest('get', '/');

        $this->assertFalse($request->session()->has('auth.latest_activity_at'));

        $response = $middleware->handle(
            $request,
            fn () => new Response()
        );

        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($request->session()->has('auth.latest_activity_at'));
    }

    /**
     * Check if the acount does not lock if the user interacts with the app.
     *
     * @return void
     */
    public function test_account_not_locked_if_using_app(): void {
        $user = User::factory()->create();
        $this->actingAs($user);

        $middleware = app(LockScreen::class);

        $request = $this->createRequest('get', '/');

        $response = $middleware->handle(
            $request,
            fn () => new Response()
        );

        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($request->session()->has('auth.latest_activity_at'));

        $latestActivity = $request->session()->get('auth.latest_activity_at');

        $this->travelTo(now()->addMinutes(3));

        $response = $middleware->handle(
            $request,
            fn () => new Response()
        );

        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($request->session()->has('auth.latest_activity_at'));

        $this->assertGreaterThan($latestActivity, $request->session()->get('auth.latest_activity_at'));
    }

    /**
     * Check if the account locks if the user last activity exceeds the specified TTL.
     *
     * @return void
     */
    public function test_account_locks_exceeded_ttl(): void {
        $user = User::factory()->create();
        $this->actingAs($user);

        $middleware = app(LockScreen::class);

        $request = $this->createRequest('get', '/');

        $response = $middleware->handle(
            $request,
            fn () => new Response()
        );

        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($request->session()->has('auth.latest_activity_at'));

        $this->travelTo(now()->addSeconds(config('lockscreen.ttl') + 1));

        $response = $middleware->handle(
            $request,
            fn () => new Response()
        );

        $this->assertEquals(302, $response->status());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('locked'), $response->headers->get('location'));
    }

    /**
     * Check if the account locks if the user last activity exceeds the specified TTL (JSON response).
     *
     * @return void
     */
    public function test_account_locks_exceeded_ttl_json(): void {
        $user = User::factory()->create();
        $this->actingAs($user);

        $middleware = app(LockScreen::class);

        $request = $this->createRequest('get', '/', [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $response = $middleware->handle(
            $request,
            fn () => new Response()
        );

        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($request->session()->has('auth.latest_activity_at'));

        $this->travelTo(now()->addSeconds(config('lockscreen.ttl') + 1));

        $response = $middleware->handle(
            $request,
            fn () => new Response()
        );

        $this->assertEquals(423, $response->status());
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertIsObject(json_decode($response->content()));
    }
}
