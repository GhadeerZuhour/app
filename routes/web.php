<?php

use Livewire\Volt\Volt;
use Laravel\Fortify\Features;
use Illuminate\Support\Facades\Route;

use App\Livewire\Entries\Index as EntriesIndex;
use App\Livewire\Accounts\Index as AccountsIndex;
use App\Livewire\Entries\Create as EntriesCreate;
use App\Livewire\Accounts\Create as AccountsCreate;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use App\Livewire\Admin\Businesses\Index as BusinessIndex;
use App\Livewire\Admin\Businesses\Create as BusinessCreate;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;


/*
|--------------------------------------------------------------------------
| 1️⃣ Public / Guest Routes (Central)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::view('/login', 'livewire.auth.login')->name('login');
});

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/lang/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['en', 'ar']), 404);

    session(['locale' => $locale]);
    return redirect()->back();
})->name('lang.switch');


foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        require base_path('routes/central.php');
    });
}

// أي دومين غير مركزي = tenant (subdomain)
Route::group([], function () {
    require base_path('routes/tenant.php');
});