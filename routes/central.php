<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TenantReceiptController;
use App\Livewire\Admin\Tenants\Show;

use App\Models\SupportTicket;

Route::middleware('guest')->group(function () {
    Route::view('/login', 'livewire.auth.login')->name('login');
});

Route::get('/', fn() => view('welcome'))->name('home');

Route::view('/dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Admin
Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('dashboard', \App\Livewire\Admin\Dashboard::class)
            ->name('dashboard');


        Route::get('tenants', \App\Livewire\Admin\Tenants\Index::class)
            ->name('tenants.index');

        Route::get('tenants/create', \App\Livewire\Admin\Tenants\Create::class)
            ->name('tenants.create');
        Route::get('tenants/{tenant}', \App\Livewire\Admin\Tenants\Show::class)
            ->name('tenants.show');
        Route::get('/{tenantId}/edit', \App\Livewire\Admin\Tenants\Edit::class)
            ->name('tenants.edit');



        Route::get('support', \App\Livewire\Admin\Support\Index::class)
            ->name('support.index');

        Route::get('support/{ticket}', \App\Livewire\Admin\Support\Show::class)
            ->name('support.show');





    });
