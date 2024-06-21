<?php

namespace SertxuDeveloper\LockScreen;

use Closure;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LockScreen
{
    /**
     * Create a new middleware instance.
     *
     * @return void
     */
    public function __construct(
        protected ResponseFactory $responseFactory,
        protected UrlGenerator $urlGenerator,
        protected ?int $passwordTimeout = null,
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ?string $redirectToRoute = null, ?int $passwordTimeoutSeconds = null): mixed {
        /** Bypass the middleware if the request is to the lock screen */
        if ("/{$request->route()?->uri()}" === route(config('lockscreen.route'), absolute: false)) {
            return $next($request);
        }

        /** Check if the account needs to be locked */
        if (Auth::check()) {
            if ($this->shouldConfirmPassword($request, $passwordTimeoutSeconds)) {
                if ($request->expectsJson()) {
                    return $this->responseFactory->json([
                        'message' => 'Account locked due to inactivity, log in again.',
                    ], 423);
                }

                return $this->responseFactory->redirectGuest(
                    $this->urlGenerator->route($redirectToRoute ?? config('lockscreen.route', 'locked'))
                );
            }

            $request->session()->put('auth.latest_activity_at', now()->timestamp);
        }

        return $next($request);
    }

    /**
     * Determine if the account has been locked due to inactivity.
     */
    protected function shouldConfirmPassword(Request $request, ?int $passwordTimeoutSeconds = null): bool {
        if (!$request->session()->has('auth.latest_activity_at')) {
            return false;
        }

        $confirmedAt = now()->timestamp - $request->session()->get('auth.latest_activity_at', 0);

        return $confirmedAt > ($passwordTimeoutSeconds ?? $this->passwordTimeout ?? config('lockscreen.ttl'));
    }
}
