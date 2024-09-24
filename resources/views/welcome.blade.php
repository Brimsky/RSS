<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TEST GENIUS</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Koulen&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.6/flowbite.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.x.x/dist/alpine.min.js" defer></script>
    <style>
        .hidden-custom {
            display: none;
        }

        .border-separator {
            border-bottom: 1px solid #ffffff;
        }

        .footer-section {
            border-right: 1px solid #444;
        }

        .footer-section:last-child {
            border-right: none;
        }

        .hidden {
            display: none;
        }

        .product-item {
            border: 1px solid #ccc;
            padding: 16px;
            margin-bottom: 16px;
        }
    </style>
</head>

<body class="bg-red-200">
    <!-- Top container -->
    <div id="top-container" class="bg-red-800 border-separator hidden sm:block">
        <div class="container mx-auto px-4 py-2 flex justify-between items-center text-sm text-gray-200">
            <div>
                <a href="#" class="hover:text-gray-200">RUS</a> | 
                <a href="#" class="hover:text-gray-200">ENG</a>
            </div>
            <div>
                Tāl: 64627387 gsm 26077632 (p.o.t.c.p. 9:00-18:00)
            </div>
            <div class="flex space-x-4">
                <a href="#" class="hover:text-gray-200">Jaunumi</a>
                <a href="#" class="hover:text-gray-200">Kontakti</a>
            </div>
        </div>
    </div>

    <header class="bg-red-800 border-separator">
        <div class="mx-auto max-w-screen-xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <!-- Logo -->
                <a href="/" class="text-white dark:text-white text-3xl tracking-wider font-bold">
                    <span class="block sm:hidden">RSS</span>
                    <span class="hidden sm:block">TEST GENIUS</span>
                </a>
                <div class="flex-grow flex items-center justify-center">
                    <div class="relative w-full max-w-lg">
                        <label for="Search" class="sr-only">Search</label>
                        <input type="text" id="Search" placeholder="Search for..." class="w-full rounded-md border-gray-200 py-2.5 pl-4 pr-10 shadow-sm sm:text-sm">
                        <button type="button" class="absolute inset-y-0 right-0 flex items-center justify-center px-3">
                            <img src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/icons/search.svg" alt="Search" class="h-4 w-4 text-gray-600 hover:text-gray-700">
                        </button>
                    </div>
                </div>
                <div class="flex items-center gap-4 hidden sm:flex">
                    @auth
                        <a class="rounded-md bg-red-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-red-700" href="#">
                            Profile
                        </a>
                    @else
                        <a class="rounded-md bg-red-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-red-700" href="#">
                            Login
                        </a>
                        <a class="rounded-md bg-red-100 px-5 py-2.5 text-sm font-medium text-red-600 transition hover:text-red-600/75" href="#">
                            Register
                        </a>
                    @endauth
                </div>
                <button class="sm:hidden p-2 text-gray-200 hover:text-gray-500" onclick="toggleMenu()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
        <div id="mobile-menu" class="px-4 py-2 hidden sm:hidden">
            <ul class="space-y-1">
                @auth
                    <li>
                        <a href="#" class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-700">Profile</a>
                    </li>
                @else
                    <li>
                        <a href="#" class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-700">Login</a>
                    </li>
                    <li>
                        <a href="#" class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700">Register</a>
                    </li>
                @endauth
                <li>
                    <a href="#" class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700">Jaunumi</a>
                </li>
                <li>
                    <a href="#" class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700">Kontakti</a>
                </li>
            </ul>
        </div>
    </header>

    <main class="container mx-auto px-4 py-4">
        <div class="flex">
            <aside id="sidebar" class="w-1/4 bg-red-800 lg:block">
                <div class="flex h-screen flex-col justify-between border-e bg-red-800">
                    <div class="px-4 py-6">
                        <!-- Product Categories -->
                        <ul id="categoryList" class="mt-6 space-y-1">
                            <li data-category="Kategortija">
                                    <ul class="mt-2 space-y-1 px-4">
                                        <li><a href="#" class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-200 hover:bg-gray-100 hover:text-gray-500">Prece</a></li>
                                        <li><a href="#" class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-200 hover:bg-gray-100 hover:text-gray-500">Prece</a></li>
                                        <li><a href="#" class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-200 hover:bg-gray-100 hover:text-gray-500">Prece</a></li>
                                        <li><a href="#" class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-200 hover:bg-gray-100 hover:text-gray-500">Prece</a></li>
                                        <li><a href="#" class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-200 hover:bg-gray-100 hover:text-gray-500">Prece</a></li>
                                    </ul>
                            </li>
                        </il>
                    </div>
                </div>
            </aside>
            <div class="w-full lg:w-3/4 lg:ml-4">
                <div class="bg-red-900 p-4 shadow">
                    <img src="/path/to/your/image.png" alt="Main Banner" class="w-full h-64 object-cover">
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-red-300 text-neutral-800">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="space-y-4 text-center md:text-left footer-section"> 
                </div>
                <!-- Best choice for search your dream -->
                <div class="space-y-4 text-center md:text-left footer-section">
                    <div class="flex justify-center md:justify-start">
                        <img src="https://img.icons8.com/ios/50/000000/shopping-cart.png" alt="Best choice for Search your dream" class="h-12 mx-auto md:mx-0">
                    </div>
                    <h3 class="text-neutral-900 font-bold">LABĀKĀ IZVĒLE, LAI ATRASTU PIEMĒROTO</h3>
                    <p class="text-sm">Mums ir vairāki pārdevēji, no kuriem jums būs ispējams iegādaties jekburu jums vēlamo preci.</p>
                </div>

                <!-- Market Best Prices Section -->
                <div class="space-y-4 text-center md:text-left footer-section">
                    <div class="flex justify-center md:justify-start">
                        <img src="https://www.clipartmax.com/png/middle/206-2067523_man-face-roblox-roblox-faces.png" alt="Man Face - Roblox - Roblox Faces@clipartmax.com" class="h-12 mx-auto md:mx-0">
                    </div>
                    <h3 class="text-neutral-900 font-bold">AISĀRGĀJAM JUSU NAUDU</h3>
                    <p class="text-sm">Nedosim piekrāpt jūs.</p>
                </div>

                <!-- Support For 24/7 Section -->
                <div class="space-y-4 text-center md:text-left footer-section">
                    <div class="flex justify-center md:justify-start">
                        <img src="https://img.icons8.com/ios/50/000000/phone-not-being-used.png" alt="Support for 24/7" class="h-12 mx-auto md:mx-0">
                    </div>
                    <h3 class="text-neutral-900 font-bold">ATBALSTS 24/7</h3>
                    <p class="text-sm">Saņemiet atbalstu jebkurā diennakts laikā. Mēs esam šeit, lai palīdzētu jums visu diennakti.</p>
                </div>
            </div>

            <div class="mt-8 text-center text-sm text-neutral-600">
                &copy; 2099 TEST GENIUS. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Optional CSS for additional styling -->
    <style>
        .footer-section:not(:last-child)::after {
            content: '';
            display: block;
            width: 1px;
            height: 100%;
            background-color: #721414;
            position: absolute;
            right: 0;
            top: 0;
        }
        .container {
            position: relative;
        }
    </style>

    <script>
        function toggleMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }
    </script>
</body>

</html>
