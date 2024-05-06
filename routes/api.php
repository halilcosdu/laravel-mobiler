<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TokenController;
use Illuminate\Support\Facades\Route;

Route::post('/token', TokenController::class)->name('token.store');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/profile', ProfileController::class)->name('profile.show');
    Route::post('/tickets', TicketController::class)->name('tickets.store');
});

Route::post('/subscriptions', [SubscriptionController::class, 'webhook'])->name('subscriptions.webhook');
