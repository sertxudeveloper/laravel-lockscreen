<?php

namespace SertxuDeveloper\LockScreen\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class UnlockableController extends Controller
{
    /**
     * Show the confirm password view.
     *
     * @return View
     */
    public function show(): View {
        return view('lockscreen::locked');
    }

    /**
     * Confirm the user's password.
     *
     * @param  Request  $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse {
        if (!Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.latest_activity_at', time());

        return redirect()->intended();
    }
}
