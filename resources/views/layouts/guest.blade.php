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
    </head>

    <body class="flex flex-col min-h-screen font-sans text-gray-900 antialiased bg-cover bg-center" style="background-image: url('/images/skyscrapers-3850732_1920.jpg');">
        <div class="nav-container w-full bg-gray-100 relative">
            <livewire:layout.navigation />
        </div>

        <div class="main-content flex-grow">
            <div class="flex flex-col sm:justify-center items-center pt-6 sm:pt-0 w-full">
                {{-- <div>
                    <a href="/" wire:navigate>
                        <x-application-logo class="w-100 h-20 fill-current text-gray-500" />
                    </a>
                </div> --}}

                <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                    <div class="flex justify-center items-center pb-6">
                        <a href="/" wire:navigate>
                            <x-application-logo class="w-100 h-20 fill-current text-gray-500" />
                        </a>
                    </div>

                    {{ $slot }}
                </div>
            </div>
        </div>

        @livewireScripts
    </body>
</html>
