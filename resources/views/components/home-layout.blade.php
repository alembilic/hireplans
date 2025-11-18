<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Connect talented individuals with rewarding career opportunities across the globe. Find your ideal role or discover exceptional talent.">
    
    <title>{{ isset($title) ? $title . ' - ' . config('app.name', 'HirePlans') : 'HirePlans — Find the role that fits your plan' }}</title>
    
    <!-- Open Graph -->
    <meta property="og:title" content="HirePlans — Find the role that fits your plan">
    <meta property="og:description" content="Connect talented individuals with rewarding career opportunities across the globe.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="HirePlans — Find the role that fits your plan">
    <meta name="twitter:description" content="Connect talented individuals with rewarding career opportunities across the globe.">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @livewireStyles
    
    <!-- HirePlans Home Page Styles -->
    <link rel="stylesheet" href="{{ asset('css/hireplans-home.css') }}">
</head>
<body class="home-layout">    
    <!-- Header -->
    <x-site-header />
    
    <!-- Main Content -->
    <main id="main">
        {{ $slot }}
    </main>
    
    <!-- Footer -->
    <x-site-footer />
    
    @livewireScripts
    
    <!-- Bootstrap JS (for dropdowns, modals, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
