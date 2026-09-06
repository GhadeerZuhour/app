<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Tazreem | {{ __('Sign in') }}</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    @if(app()->getLocale() === 'ar')
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    @endif
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <link id="pagestyle" href="{{ asset('assets/css/soft-ui-dashboard.min.css') }}?v=1" rel="stylesheet" />

    <style>
        body { font-family: {{ app()->getLocale() === 'ar' ? "'Cairo', sans-serif" : "'Open Sans', sans-serif" }}; }
        .tazreem-auth-shell { min-height: 100vh; background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 52%, #f8fafc 100%); }
        .tazreem-brand-mark { width: 48px; height: 48px; border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; background: #111827; color: #fff; font-weight: 700; font-size: 1.2rem; }
        .tazreem-auth-card { border: 0; border-radius: 24px; box-shadow: 0 24px 70px rgba(15, 23, 42, .12); }
        .tazreem-value-card { border-radius: 24px; background: #111827; color: #fff; min-height: 100%; }
    </style>

    @livewireStyles
</head>
<body>
<main class="tazreem-auth-shell d-flex align-items-center py-5">
    <div class="container">
        {{ $slot }}
    </div>
</main>

<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/soft-ui-dashboard.js') }}"></script>
@livewireScripts
</body>
</html>
