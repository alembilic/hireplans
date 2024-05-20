<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use Tabuna\Breadcrumbs\Trail;

Route::middleware('guest')->group(function () {
    Volt::route('register', 'pages.auth.register')
        ->name('register');

    Volt::route('login', 'pages.auth.login')
        ->name('login');

    Volt::route('forgot-password', 'pages.auth.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'pages.auth.reset-password')
        ->name('password.reset');
});

Route::middleware('auth')->group(function () {

    // Main
    // Route::screen('/main', PlatformScreen::class)
    //     ->name('platform.main');

    // Platform > Profile
    // Route::screen('profile', UserProfileScreen::class)
    //     ->name('platform.profile')
    //     ->breadcrumbs(fn (Trail $trail) => $trail
    //         ->parent('platform.index')
    //         ->push(__('Profile'), route('platform.profile')));

    Volt::route('verify-email', 'pages.auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'pages.auth.confirm-password')
        ->name('password.confirm');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // require __DIR__.'/platform.php';

});

require __DIR__.'/platform.php';
