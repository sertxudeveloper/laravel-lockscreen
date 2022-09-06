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
     * @param  ResponseFactory  $responseFactory
     * @param  UrlGenerator  $urlGenerator
     * @param  int|null  $passwordTimeout
     * @return void
     */
    public function __construct(
        protected ResponseFactory $responseFactory,
        protected UrlGenerator $urlGenerator,
        protected ?int $passwordTimeout = null,
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @param  string|null  $redirectToRoute
     * @param  int|null  $passwordTimeoutSeconds
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $redirectToRoute = null, int $passwordTimeoutSeconds = null): mixed {
        /** Bypass the middleware if the account has been already marked as locked */
        if ($request->session()->pull('auth.locked')) {
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

                $request->session()->put('auth.locked', true);

                return $this->responseFactory->redirectGuest(
                    $this->urlGenerator->route($redirectToRoute ?? 'locked')
                );
            }

            $request->session()->put('auth.latest_activity_at', now()->timestamp);
        }

        return $next($request);
    }

    /**
     * Determine if the account has been locked due to inactivity.
     *
     * @param  Request  $request
     * @param  int|null  $passwordTimeoutSeconds
     * @return bool
     */
    protected function shouldConfirmPassword(Request $request, int $passwordTimeoutSeconds = null): bool {
        if (!$request->session()->has('auth.latest_activity_at')) return false;

        $confirmedAt = now()->timestamp - $request->session()->get('auth.latest_activity_at', 0);

        return $confirmedAt > ($passwordTimeoutSeconds ?? $this->passwordTimeout ?? config('lockscreen.ttl'));
    }
}
