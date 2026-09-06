<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.ico') }}">
    <title>Tazreem | {{ __('general.workspace') }}</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />

    @php
        $softCss = file_exists(public_path('assets/css/soft-ui-dashboard.min.css'))
            ? 'assets/css/soft-ui-dashboard.min.css'
            : 'assets/css/soft-ui-dashboard.css';
    @endphp
    <link id="pagestyle" href="{{ asset($softCss) }}?v=1" rel="stylesheet" />

    @if(app()->getLocale() === 'ar')
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    @endif

    <style>
        :root { --tazreem-ink: #111827; --tazreem-muted: #64748b; }
        body { font-family: {{ app()->getLocale() === 'ar' ? "'Cairo', sans-serif" : "'Open Sans', sans-serif" }}; }
        .main-content { margin-left: 8.125rem; margin-right: 0; }
        .sidenav { box-shadow: 0 14px 40px rgba(15, 23, 42, .08); }
        .navbar-main { background: rgba(248, 250, 252, .82); backdrop-filter: blur(12px); }
        .card { border-radius: 18px; }

        [dir="rtl"] .main-content { margin-left: 0 !important; margin-right: 8.125rem !important; }
        [dir="rtl"] body { direction: rtl; text-align: right; }
        [dir="rtl"] .ms-auto { margin-right: auto !important; margin-left: 0 !important; }
        [dir="rtl"] .me-2 { margin-left: .5rem !important; margin-right: 0 !important; }
        [dir="rtl"] .me-3 { margin-left: 1rem !important; margin-right: 0 !important; }
        [dir="rtl"] .ms-1 { margin-right: .25rem !important; margin-left: 0 !important; }
        [dir="rtl"] .ms-3 { margin-right: 1rem !important; margin-left: 0 !important; }
        [dir="rtl"] .text-start { text-align: right !important; }
        [dir="rtl"] .text-end { text-align: left !important; }
        [dir="rtl"] .sidenav { left: auto !important; right: 0 !important; }
        [dir="rtl"] .navbar-nav { padding-right: 0; }
        [dir="rtl"] .dropdown-menu { right: 0; left: auto; text-align: right; }
        [dir="rtl"] input::placeholder, [dir="rtl"] textarea::placeholder { text-align: right; }

        @media (max-width: 1199.98px) {
            .main-content { margin-left: 0 !important; margin-right: 0 !important; }
        }
    </style>

    @livewireStyles
</head>
<body class="g-sidenav-show bg-gray-100">
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show mx-3 mt-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{ $slot }}

    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), { damping: '0.5' });
        }
    </script>
    <script src="{{ asset('assets/js/soft-ui-dashboard.js') }}"></script>
    @livewireScripts
</body>
</html>
