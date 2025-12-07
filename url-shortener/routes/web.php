<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ShortUrlController;
use App\Http\Controllers\UrlController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/', fn () => redirect()->route('dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'show'])->name('dashboard');
    Route::resource('urls', UrlController::class)->only(['index', 'create', 'store']);
    Route::resource('invitations', InvitationController::class)->only(['index', 'create', 'store']);
    Route::get('/s/{slug}', [ShortUrlController::class, 'show'])->name('short-url.show');
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');
