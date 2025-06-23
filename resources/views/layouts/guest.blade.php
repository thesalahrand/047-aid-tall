<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $pageTitle ?? false ? "$pageTitle - " : '' }} {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    {{-- Favicon  --}}
    <link rel="apple-touch-icon" sizes="180x180" href="https://laravel.com//img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="https://laravel.com//img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="https://laravel.com//img/favicon/favicon-16x16.png">
    <link rel="mask-icon" href="https://laravel.com//img/favicon/safari-pinned-tab.svg" color="#ff2d20">
    <link rel="shortcut icon" href="https://laravel.com//img/favicon/favicon.ico">
    <meta name="msapplication-TileColor" content="#ff2d20">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased dark:bg-neutral-900">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="inline-flex items-center space-x-2">
            <x-application-logo class="w-12 fill-current text-gray-900 dark:text-white" />
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ config('app.name', 'Laravel') }}</h1>
        </div>

        {{ $slot }}
    </div>
</body>

</html>
