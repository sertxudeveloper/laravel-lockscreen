<?php

use App\Http\Controllers\Auth\UnlockableController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('locked', [UnlockableController::class, 'show'])
        ->name('locked');

    Route::post('locked', [UnlockableController::class, 'store']);
});
