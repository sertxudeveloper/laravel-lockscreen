<?php

namespace SertxuDeveloper\LockScreen\Tests;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Response;

class LockScreenTest extends TestCase
{
    /**
     * Check if the user stores in session the last activity timesyamp.
     */
    public function test_user_stores_last_activity_timestamp(): void {
        $user = User::factory()->create();
        $this->actingAs($user);

        $middleware = app(LockScreen::class);

        $response = $middleware->handle(
            $request = $this->createRequest('get', '/'),
            fn () => new Response()
        );
        
        $this->assertFalse($request->session()->has('auth.latest_activity_at'));
        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->session()->has('auth.latest_activity_at'));
    }
}