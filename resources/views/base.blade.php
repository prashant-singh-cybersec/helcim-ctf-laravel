<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Welcome to Invotek IMS-CTF!')</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 128 128'><text y='1.2em' font-size='96'>⚫️</text></svg>">
    
    @yield('stylesheets')
    <link rel="stylesheet" href="{{ asset('build/app.css') }}">
 {{--    @vite('resources/js/app.js') --}} {{-- Equivalent to encore_entry_script_tags --}} 
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.3.5/dist/alpine.min.js" defer></script>
</head>
<body class="bg-gray-100 h-screen antialiased leading-none font-sans">
    
    @section('header')
    <header>
        <nav
            class="flex items-center justify-between flex-wrap p-6 fixed w-full z-10 top-0 bg-gradient-to-r from-purple-600 to-yellow-400 shadow-lg text-white "
            x-data="{ isOpen: false }"
            @keydown.escape="isOpen = false"
            :class="{ 'shadow-lg': isOpen }"
        >
            <!-- Logo and Title -->
            <div class="flex items-center flex-shrink-0 mr-6">
                <a href="/" class="flex items-center no-underline text-white hover:text-gray-100">
                    <span class="text-2xl font-extrabold tracking-wide">
                        Invotek-Invoice Management System
                    </span>
                </a>
            </div>

            <!-- Hamburger Menu for Mobile -->
            <button
                @click="isOpen = !isOpen"
                type="button"
                class="block lg:hidden px-2 text-gray-200 hover:text-gray-100 focus:outline-none"
            >
                <svg class="h-6 w-6 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path
                        x-show="!isOpen"
                        fill-rule="evenodd"
                        d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2z"
                    />
                    <path
                        x-show="isOpen"
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M18.278 16.864a1 1 0 0 1-1.414 1.414l-4.829-4.828-4.828 4.828a1 1 0 0 1-1.414-1.414l4.828-4.829-4.828-4.828a1 1 0 0 1 1.414-1.414l4.829 4.828 4.828-4.828a1 1 0 1 1 1.414 1.414l-4.828 4.829 4.828 4.828z"
                    />
                </svg>
            </button>

            <!-- Navigation Links -->
            <div
                class="w-full flex-grow lg:flex lg:items-center lg:w-auto transition-all duration-300 ease-in-out"
                :class="{ 'block': isOpen, 'hidden': !isOpen }"
                @click.away="isOpen = false"
            >
                <ul class="pt-6 lg:pt-0 list-reset lg:flex justify-end flex-1 items-center space-x-6">
                    <li>
                        <a href="/" class="text-gray-700 hover:text-gray-900 text-lg font-semibold transition-colors duration-200" @click="isOpen = false">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="{{ Auth::check() ? '/dashboard' : '/register' }}" class="text-gray-700 hover:text-gray-900 text-lg font-semibold transition-colors duration-200" @click="isOpen = false">
                            {{ Auth::check() ? 'Dashboard' : 'Register' }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ Auth::check() ? '/logout' : '/login' }}" class="text-gray-700 hover:text-gray-900 text-lg font-semibold transition-colors duration-200" @click="isOpen = false">
                            {{ Auth::check() ? 'Logout' : 'Login' }}
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    @show

    <main class="pt-24">
        @yield('body')
    </main>

    @section('footer')
    <footer class="py-8 mt-1 bg-gradient-to-r from-purple-600 to-yellow-400 text-white shadow-lg">
        <div class="container mx-auto text-center">
            <p class="text-lg font-bold text-gray-800">
                Copyright &copy; 2024 <span class="font-extrabold">Helcim Security Team</span>. All Rights Reserved.
            </p>
        </div>
    </footer>
    @show

</body>
</html>
