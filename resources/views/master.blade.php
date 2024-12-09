<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="js">

<head>
    <base href="../">
    <meta charset="utf-8">
    <meta name="author" content="{{ config('app.name', 'Laravel') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="{{ config('app.name', 'Laravel') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="{{ asset('images/brand-logo.svg') }}">
    <!-- Page Title  -->
    <title>Panel | {{ config('app.name', 'Laravel') }}</title>
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="{{ asset('assets/css/dashlite.css?ver=3.1.0') }}">
    <link id="skin-default" rel="stylesheet" href="{{ asset('assets/css/theme.css?ver=3.1.0') }}">
     <!-- FontAwesome Icons -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/libs/fontawesome-icons.css') }}">

    <!-- add library -->
    @if(isset($css_library))
    {!! $css_library !!}
    @endif

    <style type="text/css">
        .nk-sidebar.is-compact:not(:hover) .logo-img-small {
            max-height: 20px;
        }
    </style>

    @yield('css')

</head>

<body class="nk-body bg-lighter npc-default has-sidebar ">
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
                <!-- sidebar @s -->
                @include('layouts.sidebar')
                <!-- sidebar @e -->
            <!-- wrap @s -->
            <div class="nk-wrap ">
                <!-- main header @s -->
                @include('layouts.header')
                <!-- main header @e -->
                <!-- content @s -->
                @yield('content')
                <!-- content @e -->
                <!-- footer @s -->
                @include('layouts.footer')
                <!-- footer @e -->
            </div>
            <!-- wrap @e -->
        </div>
        <!-- main @e -->
    </div>
    <!-- app-root @e -->
    <!-- JavaScript -->
    <script src="{{ asset('assets/js/bundle.js?ver=3.1.0') }}"></script>
    <script src="{{ asset('assets/js/scripts.js?ver=3.1.0') }}"></script>

    <!-- add library -->
    @if(isset($js_library))
    {!! $js_library !!}
    @endif

    <script type="text/javascript">
        const token = $("meta[name='csrf-token']").attr("content");
        
        // Check if the user is offline
        if (!navigator.onLine) {
            // User is offline, show SweetAlert notification
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'You are currently offline. Please check your internet connection and try again.',
            });
        }

        window.addEventListener('online', () => {
            Swal.fire({
                icon: 'success',
                title: 'Great!',
                text: 'You are back online. Welcome back!',
            });
        });
    </script>

    <!-- custom js -->
    @if(isset($js))
    <script src="{{ asset($js) }}"></script>
    @endif

</body>
