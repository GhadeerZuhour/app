<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;


Route::middleware('guest')->group(function () {
    Route::view('/login', 'livewire.auth.login')->name('login');
});
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

    
Route::get('/accounts', fn () => 'Accounts page')
    ->name('accounts.index');

    Route::get('/entries', \App\Livewire\Entries\Index::class)
    ->name('entries.index');  
    Route::get('/entries/create', \App\Livewire\Entries\Create::class)
        ->name('entries.create');


Route::middleware(['auth'])->group(function () {
    
    Route::get('/billing')->name('billing');
    Route::get('/profile')->name('profile');
    Route::get('/tables')->name('tables');
    Route::get('/static-sign-in')->name('sign-in');
    Route::get('/static-sign-up')->name('static-sign-up');
    Route::get('/rtl')->name('rtl');
    Route::get('/laravel-user-profile')->name('user-profile');
    Route::get('/laravel-user-management')->name('user-management');
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
