<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', function () {
        return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
    });
});

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'auth',
    'verified',
    'tenant.active',
])->group(function () {

    Route::view('/dashboard', 'tenant-dashboard')->name('tenant.dashboard');

    Route::get('/accounts', \App\Livewire\Accounts\Index::class)->name('accounts.index');
    Route::get('/accounts/create', \App\Livewire\Accounts\Create::class)->name('accounts.create');

    Route::get('/entries', \App\Livewire\Entries\Index::class)->name('entries.index');
    Route::get('/entries/create', \App\Livewire\Entries\Create::class)->name('entries.create');
});
