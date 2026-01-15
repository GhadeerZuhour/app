<x-layouts.base>
    @auth
        <!-- SIDEBAR -->
        <x-layouts.navbars.auth.sidebar />

        <!-- MAIN CONTENT -->
        <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">

            <!-- TOP NAVBAR -->
            <x-layouts.navbars.auth.nav />

            <!-- PAGE CONTENT -->
            <div class="container-fluid py-4">
                {{ $slot }}

                <x-layouts.footers.auth.footer />
            </div>
        </main>
    @endauth

    @guest
        <div class="container py-5">
            {{ $slot }}
        </div>
    @endguest
</x-layouts.base>
