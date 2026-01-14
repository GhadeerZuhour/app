<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Auth | Soft UI</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <link id="pagestyle" href="{{ asset('assets/css/soft-ui-dashboard.min.css') }}?v=1" rel="stylesheet" />

    @livewireStyles
</head>

<body class="bg-gray-100">

<main class="main-content mt-0">
    <section>
        <div class="page-header min-vh-100 d-flex align-items-center">
            {{-- خلفية خفيفة آمنة (بدون position absolute) --}}
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/soft-ui-dashboard.js') }}"></script>

@livewireScripts
</body>
</html>
