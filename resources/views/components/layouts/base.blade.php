<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">

    <title>Tazreem Dashboard</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />

    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />


    @php
        $softCss = file_exists(public_path('assets/css/soft-ui-dashboard.min.css'))
            ? 'assets/css/soft-ui-dashboard.min.css'
            : 'assets/css/soft-ui-dashboard.css';
    @endphp
    <link id="pagestyle" href="{{ asset($softCss) }}?v=1" rel="stylesheet" />
    <style>
  .main-content {
      margin-left: 8.125rem; /* 274px = width of sidebar */
  }

  @media (max-width: 1199.98px) {
      .main-content {
          margin-left: 0;
      }
  }
</style>


    @livewireStyles
</head>

<body class="g-sidenav-show bg-gray-100">

    {{ $slot }}

    {{-- Core JS --}}
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>

    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), { damping: '0.5' });
        }
    </script>

    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="{{ asset('assets/js/soft-ui-dashboard.js') }}"></script>

    @livewireScripts
</body>
</html>
