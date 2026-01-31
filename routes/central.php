<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use Laravel\Fortify\Features;

Route::middleware('guest')->group(function () {
    Route::view('/login', 'livewire.auth.login')->name('login');
});

Route::get('/', fn () => view('welcome'))->name('home');

Route::view('/dashboard', 'dashboard')
    ->middleware(['auth','verified'])
    ->name('dashboard');

// Admin
Route::get('/admin/tenants', \App\Livewire\Admin\Tenants\Index::class)
    ->middleware(['auth','verified'])
    ->name('admin.tenants.index');

Route::get('/admin/tenants/create', \App\Livewire\Admin\Tenants\Create::class)
    ->middleware(['auth','verified'])
    ->name('admin.tenants.create');
