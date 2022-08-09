
@php
    $current_route = Route::current()->getName();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Scripts -->
        @routes
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <script src="{{ mix('js/app.js') }}" defer></script>

        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <!-- App css -->
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/app-modern.min.css" rel="stylesheet" type="text/css" id="app-style" />

        <!-- bundle -->
        <script src="assets/js/vendor.min.js" defer></script>
        <script src="assets/js/app.min.js" defer></script>

        @if(auth()->user())

            <link href="assets/css/vendor/quill.bubble.css" rel="stylesheet" type="text/css" />
            <link href="assets/css/vendor/quill.core.css" rel="stylesheet" type="text/css" />
            <link href="assets/css/vendor/quill.snow.css" rel="stylesheet" type="text/css" />
            <!-- quill js -->
            <script src="assets/js/vendor/quill.min.js" defer></script>
            <!-- Init js-->
            <script src="assets/js/pages/demo.tasks.js" defer></script>

        @endif


        @inertiaHead
    </head>
    @if($current_route === 'login' || $current_route === 'register')
        <body
            class="loading authentication-bg"
            data-layout-config='{"darkMode":false}'
        >
    @else
    <body
        class="loading"
        data-layout-color="light"
        data-layout="detached"
        data-rightbar-onstart="false"
        >
    @endif

        @inertia
    </body>
</html>
