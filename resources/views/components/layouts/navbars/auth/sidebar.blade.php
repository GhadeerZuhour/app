@php
$isTenantApp = function_exists('tenant') && tenant();
$isCentralApp = ! $isTenantApp;
$dashboardRoute = $isTenantApp ? 'tenant.dashboard' : ((auth()->user()->role ?? null) === 'admin' ? 'admin.dashboard' : 'dashboard');
$brandName = $isTenantApp ? (tenant()->meta?->name ?? 'Tazreem') : 'Tazreem';
@endphp

<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3" id="sidenav-main">
    <div class="sidenav-header px-3 pt-3">
        <a class="navbar-brand m-0 d-flex align-items-center" href="{{ route($dashboardRoute) }}">
            <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-dark text-white fw-bold" style="width:38px;height:38px;">T</span>
            <span class="ms-3">
                <span class="d-block font-weight-bold">{{ $brandName }}</span>
                <small class="text-muted">{{ __('general.workspace') }}</small>
            </span>
        </a>
    </div>

    <hr class="horizontal dark mt-3">

    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            @if($isCentralApp && auth()->check() && (auth()->user()->role ?? null) === 'admin')
                <li class="nav-item pb-2">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="ni ni-tv-2 text-dark text-sm"></i></div>
                        <span class="nav-link-text ms-1">{{ __('general.dashboard') }}</span>
                    </a>
                </li>
                <li class="nav-item pb-2">
                    <a class="nav-link {{ request()->routeIs('admin.tenants.index') || request()->routeIs('admin.tenants.show') || request()->routeIs('admin.tenants.edit') ? 'active' : '' }}" href="{{ route('admin.tenants.index') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="ni ni-building text-dark text-sm"></i></div>
                        <span class="nav-link-text ms-1">{{ __('general.tenants') }}</span>
                    </a>
                </li>
                <li class="nav-item pb-2">
                    <a class="nav-link {{ request()->routeIs('admin.tenants.create') ? 'active' : '' }}" href="{{ route('admin.tenants.create') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="ni ni-fat-add text-dark text-sm"></i></div>
                        <span class="nav-link-text ms-1">{{ __('general.add_tenant') }}</span>
                    </a>
                </li>
                <li class="nav-item pb-2">
                    <a class="nav-link {{ request()->routeIs('admin.support.*') ? 'active' : '' }}" href="{{ route('admin.support.index') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="ni ni-chat-round text-dark text-sm"></i></div>
                        <span class="nav-link-text ms-1">{{ __('general.support') }}</span>
                    </a>
                </li>
            @endif

            @if($isTenantApp && auth()->check())
                <li class="nav-item pb-2">
                    <a class="nav-link {{ request()->routeIs('tenant.dashboard') ? 'active' : '' }}" href="{{ route('tenant.dashboard') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="ni ni-chart-bar-32 text-dark text-sm"></i></div>
                        <span class="nav-link-text ms-1">{{ __('general.dashboard') }}</span>
                    </a>
                </li>
                <li class="nav-item pb-2">
                    <a class="nav-link {{ request()->routeIs('accounts.*') ? 'active' : '' }}" href="{{ route('accounts.index') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="ni ni-credit-card text-dark text-sm"></i></div>
                        <span class="nav-link-text ms-1">{{ __('general.accounts') }}</span>
                    </a>
                </li>
                <li class="nav-item pb-2">
                    <a class="nav-link {{ request()->routeIs('entries.*') ? 'active' : '' }}" href="{{ route('entries.index') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="ni ni-money-coins text-dark text-sm"></i></div>
                        <span class="nav-link-text ms-1">{{ __('general.transactions') }}</span>
                    </a>
                </li>
                <li class="nav-item pb-2">
                    <a class="nav-link {{ request()->routeIs('tenant.support.*') ? 'active' : '' }}" href="{{ route('tenant.support.index') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center"><i class="ni ni-chat-round text-dark text-sm"></i></div>
                        <span class="nav-link-text ms-1">{{ __('general.support') }}</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</aside>
