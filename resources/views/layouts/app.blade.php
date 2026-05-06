<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data x-init="const theme = localStorage.getItem('theme');
if (theme === 'dark') document.documentElement.classList.add('dark');">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- PWA Meta Tags -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#E8956A">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Van Mierlo">

    <title>{{ config('app.name', 'Familie Van Mierlo') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/js/app.js', 'resources/scss/app.scss'])
    @livewireStyles
</head>

<body>
    <div class="app-wrapper">
        @include('layouts.navigation')
        <main class="app-main">
            {{ $slot }}
        </main>
    </div>
    @livewireScripts
</body>

</html>
