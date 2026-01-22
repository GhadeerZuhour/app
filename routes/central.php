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
Route::get('/admin/businesses', \App\Livewire\Admin\Businesses\Index::class)
    ->middleware(['auth','verified'])
    ->name('admin.businesses.index');

Route::get('/admin/businesses/create', \App\Livewire\Admin\Businesses\Create::class)
    ->middleware(['auth','verified'])
    ->name('admin.businesses.create');
