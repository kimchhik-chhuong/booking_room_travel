<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Travelie - Discover Amazing Destinations')</title>
    <meta name="description" content="@yield('meta_description', 'Discover amazing travel packages and destinations with Travelie. Book your perfect vacation with our curated selection of tours and experiences.')">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        * {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .badge-modern {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-plane text-white text-lg"></i>
                        </div>
                        <span class="text-2xl font-bold text-gray-800">Travelie</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Home</a>
                    <a href="{{ route('packages.index') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Packages</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Destinations</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">About</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Contact</a>
                </div>

                <!-- Auth Links -->
                <div class="flex items-center space-x-4">
                    @auth
                        <div class="relative">
                            <button onclick="toggleUserMenu()" class="flex items-center space-x-2 text-gray-700 hover:text-blue-600">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=0ea5e9&color=fff&size=32" 
                                     alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full">
                                <span class="font-medium">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down text-sm"></i>
                            </button>
                            <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">My Bookings</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Profile</a>
                                <hr class="my-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition-colors">Sign Up</a>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button onclick="toggleMobileMenu()" class="text-gray-700 hover:text-blue-600">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-200">
            <div class="px-4 py-2 space-y-1">
                <a href="{{ route('home') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600 font-medium">Home</a>
                <a href="{{ route('packages.index') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600 font-medium">Packages</a>
                <a href="#" class="block px-3 py-2 text-gray-700 hover:text-blue-600 font-medium">Destinations</a>
                <a href="#" class="block px-3 py-2 text-gray-700 hover:text-blue-600 font-medium">About</a>
                <a href="#" class="block px-3 py-2 text-gray-700 hover:text-blue-600 font-medium">Contact</a>
                @guest
                    <hr class="my-2">
                    <a href="{{ route('login') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600 font-medium">Login</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 text-blue-600 font-medium">Sign Up</a>
                @endguest
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-plane text-white text-xl"></i>
                        </div>
                        <span class="text-3xl font-bold">Travelie</span>
                    </div>
                    <p class="text-gray-300 mb-6 max-w-md">
                        Discover amazing destinations and create unforgettable memories with our carefully curated travel packages.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-blue-600 transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-blue-600 transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-blue-600 transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-blue-600 transition-colors">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-6">Quick Links</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('packages.index') }}" class="text-gray-300 hover:text-white transition-colors">All Packages</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Destinations</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">About Us</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Contact</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Blog</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h3 class="text-lg font-semibold mb-6">Contact Info</h3>
                    <ul class="space-y-3">
                        <li class="flex items-center space-x-3">
                            <i class="fas fa-phone text-blue-500"></i>
                            <span class="text-gray-300">+1 (234) 567-8900</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fas fa-envelope text-blue-500"></i>
                            <span class="text-gray-300">info@travelie.com</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fas fa-map-marker-alt text-blue-500"></i>
                            <span class="text-gray-300">123 Travel Street, City, Country</span>
                        </li>
                    </ul>
                </div>
            </div>

            <hr class="border-gray-800 my-8">

            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm">
                    © {{ date('Y') }} Travelie. All rights reserved.
                </p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Privacy Policy</a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Terms of Service</a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Cookie Policy</a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
    
    <script>
        function toggleUserMenu() {
            const menu = document.getElementById('user-menu');
            menu.classList.toggle('hidden');
        }

        function toggleMobileMenu
