<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="https://en.gravatar.com/userimage/271610222/21204421de6c2fde4291415afe01795b.png">
    <title>@yield('title') &mdash; {{ config('app.name') }}</title>

    <!-- phpVMS 7 REQUIRED -->
    <meta name="base-url" content="{!! url('') !!}">
    <meta name="api-key" content="{!! Auth::check() ? Auth::user()->api_key : '' !!}">
    <meta name="csrf-token" content="{!! csrf_token() !!}">
    <!-- END phpVMS 7 REQUIRED -->

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome e Bootstrap Icons -->
    <link rel="stylesheet" href="{{ public_asset('/assets/frontend/stisla/modules/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Stisla Plugins CSS -->
    <link rel="stylesheet" href="{{ public_asset('/assets/frontend/stisla/modules/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ public_asset('/assets/frontend/stisla/modules/weather-icon/css/weather-icons.min.css') }}">
    <link rel="stylesheet" href="{{ public_asset('/assets/frontend/stisla/modules/weather-icon/css/weather-icons-wind.min.css') }}">
    <link rel="stylesheet" href="{{ public_asset('/assets/frontend/stisla/modules/summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ public_asset('/assets/frontend/stisla/modules/select2/dist/css/select2.min.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ public_asset('/assets/frontend/stisla/css/style.css') }}">
    <link rel="stylesheet" href="{{ public_asset('/assets/frontend/stisla/css/components.css') }}">
    <link rel="stylesheet" href="{{ public_asset('/assets/frontend/stisla/css/custom.css') }}">
    <link rel="stylesheet" href="{{ public_mix('/assets/global/css/vendor.css') }}">

    <style>@yield('css')</style>
    <script>@yield('scripts_head')</script>
</head>
<body>
<div id="app">
    <div class="main-wrapper main-wrapper-1">
        <!-- Navbar -->
        @include('nav')

        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
                @include('flash.message')
                @yield('content')
            </section>
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <div class="footer-left">
                Copyright &copy; {{ date('Y') }} <div class="bullet"></div>
                Design By: Muhamad Nauval Azhar</a>
            </div>
            <div class="footer-right">
                CrewCenter by <a href="https://flyazoresvirtual.com/users/1">Rui Alves</a> |
                Powered by <a href="http://www.phpvms.net" target="_blank">phpVMS</a> & <a href="https://github.com/FatihKoz" target="_blank">DH Addons</a>
            </div>
        </footer>
    </div>
</div>

<!-- phpVMS 7 REQUIRED - JS -->
<script src="{{ public_mix('/assets/global/js/vendor.js') }}"></script>
<script src="{{ public_mix('/assets/frontend/js/vendor.js') }}"></script>
<script src="{{ public_mix('/assets/frontend/js/app.js') }}"></script>

<!-- Bootstrap 5 Bundle (inclui Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

<!-- Stisla Plugins JS -->
<script src="{{ public_asset('/assets/frontend/stisla/modules/nicescroll/jquery.nicescroll.js') }}"></script>
<script src="{{ public_asset('/assets/frontend/stisla/modules/moment.min.js') }}"></script>
<script src="{{ public_asset('/assets/frontend/stisla/js/stisla.js') }}"></script>
<script src="{{ public_asset('/assets/frontend/stisla/modules/simple-weather/jquery.simpleWeather.min.js') }}"></script>
<script src="{{ public_asset('/assets/frontend/stisla/modules/chart.min.js') }}"></script>
<script src="{{ public_asset('/assets/frontend/stisla/modules/jqvmap/dist/jquery.vmap.min.js') }}"></script>
<script src="{{ public_asset('/assets/frontend/stisla/modules/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
<script src="{{ public_asset('/assets/frontend/stisla/modules/summernote/summernote-bs4.js') }}"></script>
<script src="{{ public_asset('/assets/frontend/stisla/modules/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>
<script src="{{ public_asset('/assets/frontend/stisla/modules/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ public_asset('/assets/frontend/stisla/modules/sweetalert/sweetalert2.js') }}"></script>

<!-- Template JS -->
<script src="{{ public_asset('/assets/frontend/stisla/js/scripts.js') }}"></script>
<script src="{{ public_asset('/assets/frontend/stisla/js/custom.js') }}"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Inicializa Select2
    $(".select2").select2({ width: 'resolve' });



    // Cookie Consent
    window.cookieconsent?.initialise({
        palette: { popup: { background: "#edeff5", text: "#838391" }, button: { background: "#067ec1" } },
        position: "bottom",
    });
});
</script>

@yield('scripts')
</body>
</html>
