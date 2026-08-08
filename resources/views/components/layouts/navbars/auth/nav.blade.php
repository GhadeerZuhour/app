<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
    <div class="container-fluid py-2 px-3">
        <div>
            <p class="text-xs text-muted mb-0">{{ __('general.workspace') }}</p>
            <h6 class="font-weight-bolder mb-0 text-capitalize">
                @if(request()->routeIs('tenant.dashboard') || request()->routeIs('admin.dashboard'))
                    {{ __('general.dashboard') }}
                @elseif(request()->routeIs('accounts.*'))
                    {{ __('general.accounts') }}
                @elseif(request()->routeIs('entries.*'))
                    {{ __('general.transactions') }}
                @elseif(request()->routeIs('*.support.*'))
                    {{ __('general.support') }}
                @elseif(request()->routeIs('admin.tenants.*'))
                    {{ __('general.tenants') }}
                @else
                    Tazreem
                @endif
            </h6>
        </div>

        <div class="ms-auto d-flex align-items-center gap-2">
            <a class="btn btn-sm btn-outline-dark mb-0" href="{{ route('lang.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') }}">
                {{ __('general.language') }}
            </a>

            <div class="d-none d-md-flex align-items-center px-3 py-2 rounded-3 bg-white shadow-sm">
                <div class="me-2 d-inline-flex align-items-center justify-content-center rounded-circle bg-dark text-white fw-bold" style="width:30px;height:30px;font-size:.75rem;">
                    {{ auth()->user()->initials() }}
                </div>
                <div class="lh-sm">
                    <div class="text-sm font-weight-bold">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-muted">{{ auth()->user()->email }}</div>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="mb-0">
                @csrf
                <button type="submit" class="btn btn-sm bg-gradient-dark mb-0">{{ __('general.logout') }}</button>
            </form>

            <button class="navbar-toggler shadow-none ms-2 d-xl-none" type="button" id="iconNavbarSidenav" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon mt-2"><span class="navbar-toggler-bar bar1"></span><span class="navbar-toggler-bar bar2"></span><span class="navbar-toggler-bar bar3"></span></span>
            </button>
        </div>
    </div>
</nav>
