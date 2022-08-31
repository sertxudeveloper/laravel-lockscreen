<?php

namespace SertxuDeveloper\LockScreen\Tests;

use SertxuDeveloper\LockScreen\LockScreen;
use SertxuDeveloper\LockScreen\Tests\Models\User;
use Illuminate\Http\Response;

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
}