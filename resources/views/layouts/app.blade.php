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
                /* background-size: cover; */
                background-size: contain;
                background-attachment: fixed;
            }
            .h-screen {
                height: 100vh;
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
    <body class="font-sans antialiased flex flex-col min-h-screen">
        <div class="flex flex-col flex-grow">
            <livewire:layout.navigation />

            <!-- Page Content -->
            <main class="flex-grow content-wrapper">
                {{ $slot }}
            </main>

        </div>

        <!-- <footer class="bg-gray-800 text-white text-center py-3">
            <p>&copy; {{date('Y')}} {{ config('app.name') }}. All rights reserved. <a href="https://barasoft.co.uk" target="_blank" rel="noopener" class="text-sm">Developed by BaraSoft</a></p>
            <div class="flex justify-center space-x-4">
                <a href="/terms-of-use" class="m-5">Terms of Use</a>
                <a href="/privacy-policy" class="m-5">Privacy Policy</a>
            </div>
        </footer> -->

        <!-- <footer class="bg-gray-800 text-white py-6">
            <div class="container mx-auto px-4">
                <div class="flex flex-wrap justify-between items-center">

                    <div class="w-full sm:w-1/3 text-center sm:text-left mb-4 sm:mb-0">
                        <a href="/terms-of-use" class="block mb-2">Terms of Use</a>
                        <a href="/privacy-policy" class="block">Privacy Policy</a>
                    </div>

                    <div class="w-full sm:w-1/3 text-center mb-4 sm:mb-0">
                        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                        <p><a href="https://barasoft.co.uk" target="_blank" rel="noopener" class="text-sm">Developed by BaraSoft</a></p>
                    </div>

                    <div class="w-full sm:w-1/3 text-center sm:text-right">
                        <p>Contact us:</p>
                        <p>Email us at <a href="mailto:email1@example.com">email1@example.com</a> or <a href="mailto:email2@example.com">email2@example.com</a></p>
                        <p>Call us at: <a href="tel:+123456789">+123456789</a> or <a href="tel:+987654321">+987654321</a></p>
                    </div>
                </div>
            </div>
        </footer> -->

        <footer class="bg-gray-800 text-white py-6">
            <div class="container mx-auto px-4">
                <div class="flex flex-col sm:flex-row justify-between items-center">
                    <!-- Left Column -->
                    <div class="w-full sm:w-1/3 text-center sm:text-left mb-4 sm:mb-0">
                        <div class="flex justify-center sm:justify-start space-x-4">
                            <a href="/terms-of-use" class="text-sm hover:underline mr-3">Terms of Use</a>
                            <a href="/privacy-policy" class="text-sm hover:underline">Privacy Policy</a>
                        </div>
                    </div>
                    <!-- Center Column -->
                    <div class="w-full sm:w-1/3 text-center mb-4 sm:mb-0">
                        <p class="text-sm">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                        <p class="text-sm"><a href="https://barasoft.co.uk" target="_blank" rel="noopener" class="hover:underline">Developed by BaraSoft</a></p>
                    </div>
                    <!-- Right Column -->
                    <div class="w-full sm:w-1/3 text-center sm:text-right">
                        <p class="text-md">Contact us:</p>
                        <!-- <p class="text-sm">Email us at: <a href="mailto:email1@example.com" class="hover:underline">email1@example.com</a>
                        Or: <a href="mailto:email2@example.com" class="hover:underline">email2@example.com</a></p>
                        <p class="text-sm mt-4">Call us at:</p>
                        <p class="text-sm"><a href="tel:+123456789" class="hover:underline">+123456789</a></p>
                        <p class="text-sm"><a href="tel:+987654321" class="hover:underline">+987654321</a></p> -->
                        <p class="text-sm">Email us at <a href="mailto:admin@hireplans.com">admin@hireplans.com</a> or <a href="mailto:enquiries@hireplans.com">enquiries@hireplans.com</a></p>
                        <p class="text-sm">Call us at: <a href="tel:+443333031417">+443333031417</a> or <a href="tel:+442032398039">+442032398039</a></p>
                    </div>
                </div>
            </div>
        </footer>




    </body>
</html>
