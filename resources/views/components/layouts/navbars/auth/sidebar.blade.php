@php
$isTenantApp = function_exists('tenant') && tenant(); // داخل subdomain
$isCentralApp = ! $isTenantApp; // داخل central/admin domain

// routes
$dashboardRoute = $isTenantApp ? 'tenant.dashboard' : 'dashboard';

// name shown in sidebar
$brandName = $isTenantApp
? (tenant()->business_name ?? 'Tazreem')
: 'Tazreem';
@endphp

<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3"
    id="sidenav-main">

    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>

        <a class="align-items-center d-flex m-0 navbar-brand text-wrap" href="{{ route($dashboardRoute) }}">
            <img src="../assets/img/logo-ct.png" class="navbar-brand-img h-100" alt="...">
            <span class="ms-3 font-weight-bold">{{ $brandName }}</span>
        </a>
    </div>

    <hr class="horizontal dark mt-0">

    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item pb-2">
                <div class="text-xs text-secondary ms-3">
                    Mode: {{ $isTenantApp ? 'TENANT' : 'CENTRAL' }}
                    {{ auth()->user()->id }}
                    | Role: {{ auth()->user()->role ?? '-' }}
                    @if($isTenantApp)
                    | Tenant: {{ tenant()->id }}
                    @endif
                </div>
            </li>

            {{-- ===================== --}}
            {{-- CENTRAL SYSTEM ADMIN --}}
            {{-- ===================== --}}
            @if($isCentralApp && auth()->check() && (auth()->user()->role ?? null) === 'admin')

            {{-- Dashboard --}}
            <li class="nav-item pb-2">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-tv-2 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>

            {{-- Tenants / tenants --}}
            <li class="nav-item pb-2">
                <a class="nav-link {{ request()->routeIs('admin.tenants.*') ? 'active' : '' }}"
                    href="{{ route('admin.tenants.index') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-building text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Tenants</span>
                </a>
            </li>

            {{-- Create Tenant --}}
            <li class="nav-item pb-2">
                <a class="nav-link {{ request()->routeIs('admin.tenants.create') ? 'active' : '' }}"
                    href="{{ route('admin.tenants.create') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-fat-add text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Add Tenant</span>
                </a>
            </li>
      <li class="nav-item pb-2">
                <a class="nav-link {{ request()->routeIs('admin.support.*') ? 'active' : '' }}"
                    href="{{ route('admin.support.index') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-building text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Support</span>
                </a>
            </li>
      

            <hr class="horizontal dark my-2">

            @endif


            {{-- ✅ TENANT MENU --}}

            @if($isCentralApp && auth()->check() && (auth()->user()->role ?? null) === 'subscriber')
            {{-- Dashboard --}}
            <li class="nav-item pb-2">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        {{-- keep your svg --}}
                        <i class="ni ni-shop text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>

            {{-- Accounts --}}
            <li class="nav-item pb-2">
                <a class="nav-link {{ request()->routeIs('accounts.*') ? 'active' : '' }}"
                    href="{{ route('accounts.index') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-credit-card text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Accounts</span>
                </a>
            </li>

            {{-- Entries --}}
            <li class="nav-item pb-2">
                <a class="nav-link {{ request()->routeIs('entries.*') ? 'active' : '' }}"
                    href="{{ route('entries.index') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-money-coins text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Entries</span>
                </a>
            </li>

            @endif

        </ul>
    </div>

</aside>