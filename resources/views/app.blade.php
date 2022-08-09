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
        <link rel="shortcut icon" href="modern-demo-assets/images/favicon.ico">

        <!-- App css -->
        <link href="modern-demo-assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="modern-demo-assets/css/app-modern.min.css" rel="stylesheet" type="text/css" id="app-style" />

        <!-- bundle -->
        <script src="modern-demo-assets/js/vendor.min.js" defer></script>
        <script src="modern-demo-assets/js/app.min.js" defer></script>



        @inertiaHead
    </head>
    <body class="font-sans antialiased loading authentication-bg" data-layout-config='{"darkMode":false}'>
        @inertia
    </body>
</html>
