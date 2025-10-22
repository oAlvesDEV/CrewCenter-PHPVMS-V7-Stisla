<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Meta, Title & Favicon -->
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, shrink-to-fit=no">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ public_asset('/assets/frontend/img/apple-icon.png') }}">
        <link rel="icon" type="image/png" href="https://en.gravatar.com/userimage/271610222/21204421de6c2fde4291415afe01795b.png">
        <title>@yield('title') - {{ config('app.name') }}</title>

        <!-- General CSS Files -->
        <link rel="stylesheet" href="{{ public_asset('/assets/frontend/stisla/modules/bootstrap/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ public_asset('/assets/frontend/stisla/modules/fontawesome/css/all.min.css') }}">

        <!-- CSS Libraries -->
        <link rel="stylesheet" href="{{ public_asset('/assets/frontend/stisla/modules/bootstrap-social/bootstrap-social.css') }}">
        <link rel="stylesheet" href="{{ public_asset('/assets/frontend/stisla/modules/select2/dist/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ public_asset('/assets/frontend/stisla/modules/jquery-selectric/selectric.css') }}">
        <link rel="stylesheet" href="{{ public_asset('/assets/frontend/stisla/modules/prism/prism.css') }}">

        <!-- Template CSS -->
        <link rel="stylesheet" href="{{ public_asset('/assets/frontend/stisla/css/style.css') }}">
        <link rel="stylesheet" href="{{ public_asset('/assets/frontend/stisla/css/components.css') }}">

        <!-- Start GA -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-94034622-3');
        </script>
        <!-- /END GA -->

        @yield('css')
    </head>
    <body>
        <div id="app">
            <section class="section">
                @yield('content')
            </section>
        </div>

        <!-- General JS Scripts -->
        <script src="{{ public_asset('/assets/frontend/stisla/modules/jquery.min.js') }}"></script>
        <script src="{{ public_asset('/assets/frontend/stisla/modules/popper.js') }}"></script>
        <script src="{{ public_asset('/assets/frontend/stisla/modules/tooltip.js') }}"></script>
        <script src="{{ public_asset('/assets/frontend/stisla/modules/bootstrap/js/bootstrap.min.js') }}"></script>
        <script src="{{ public_asset('/assets/frontend/stisla/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
        <script src="{{ public_asset('/assets/frontend/stisla/modules/moment.min.js') }}"></script>
        <script src="{{ public_asset('/assets/frontend/stisla/js/stisla.js') }}"></script>
        <script src="{{ public_asset('/assets/frontend/stisla/modules/select2/dist/js/select2.full.min.js') }}"></script>

        <!-- JS Libraies -->
        <script src="{{ public_asset('/assets/frontend/stisla/modules/jquery-pwstrength/jquery.pwstrength.min.js') }}"></script>
        <script src="{{ public_asset('/assets/frontend/stisla/modules/jquery-selectric/jquery.selectric.min.js') }}"></script>
        <script src="{{ public_asset('/assets/frontend/stisla/modules/prism/prism.js') }}"></script>

        <!-- NiceScroll -->
        <script>$("body").niceScroll({fixed:true});</script>

        <!-- Template JS File -->
        <script src="{{ public_asset('/assets/frontend/stisla/js/scripts.js') }}"></script>
        <script src="{{ public_asset('/assets/frontend/stisla/js/custom.js') }}"></script>
        <script src="{{ public_asset('/assets/global/js/jquery.js') }}" type="text/javascript"></script>

        @yield('scripts')
    </body>
</html>

