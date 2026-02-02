<?php

use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::view('/login', 'livewire.auth.login')->name('login');
});

Route::get('/', fn () => view('welcome'))->name('home');

Route::view('/dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Admin
Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('tenants', \App\Livewire\Admin\Tenants\Index::class)
            ->name('tenants.index');

        Route::get('tenants/create', \App\Livewire\Admin\Tenants\Create::class)
            ->name('tenants.create');
        Route::get('tenants/{tenant}', \App\Livewire\Admin\Tenants\Show::class)
            ->name('tenants.show');
    });
