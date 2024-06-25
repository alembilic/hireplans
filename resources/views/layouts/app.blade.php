<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles

        <!-- Custom Styles -->
        <style>
            .navbar-shrink {
                height: 50px;
            }
            .navbar-expand {
                height: 80px;
            }
            .bg-cover {
                background-size: cover;
                background-attachment: fixed;
            }
            .content-wrapper {
                position: relative;
                z-index: 1;
                background-color: rgba(255, 255, 255, 0.8);
                /* padding-top: 80px; */
            }
        </style>
        <!-- Custom Scripts -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                window.addEventListener('scroll', function() {
                    const navbar = document.querySelector('.navbar');
                    if (window.scrollY > 100) {
                        navbar.classList.add('navbar-shrink');
                        navbar.classList.remove('navbar-expand');
                    } else {
                        navbar.classList.add('navbar-expand');
                        navbar.classList.remove('navbar-shrink');
                    }
                });
            });
        </script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 relative">
            <livewire:layout.navigation />

            <!-- Page Content -->
            <main class="content-wrapper">
                {{ $slot }}
            </main>

            @livewireScripts
        </div>
    </body>
</html>
