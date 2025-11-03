<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    @vite(['resources/assets/vendors/mdi/css/materialdesignicons.min.css'])
    @vite(['resources/assets/css/styles.css'])
    @vite(['resources/assets/css/custom.css'])

    <link rel="icon" type="image/png" href="{{ asset('/build/images/favicon.ico') }}"/>
</head>
<body @class(['sidebar-icon-only' => request()->cookie('sidebar-status') === 'true'])>
    <div class="container-scroller">

        <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo" href="{{ route('admin') }}">
                    <img src="{{ asset('/build/images/logo.svg') }}" alt="logo" loading="lazy"/>
                </a>
                <a class="navbar-brand brand-logo-mini" href="{{ route('admin') }}">
                    <img src="{{ asset('/build/images/logo-mini.svg') }}" alt="logo" loading="lazy"/>
                </a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-stretch">
                @include('admin.partials.navbar')
            </div>
        </nav>
        <div class="container-fluid page-body-wrapper">
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                @include('admin.partials.menu')
            </nav>

            <div class="main-panel">
                <div class="content-wrapper @yield('wrapper')">
                    @yield('content')
                </div>
                @include('admin.partials.footer')
            </div>
        </div>

        @stack('modals')
    </div>

@vite(['resources/assets/js/main.js'])

@yield('js')

</body>
</html>
