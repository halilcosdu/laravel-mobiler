<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TokenController;
use Illuminate\Support\Facades\Route;

Route::post('/token', TokenController::class)->name('token.store');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/profile', ProfileController::class)->name('profile.show');

});