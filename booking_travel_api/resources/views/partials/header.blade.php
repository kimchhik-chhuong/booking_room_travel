<header class="bg-white/80 backdrop-blur-lg border-b border-white/20 px-8 py-6 ml-72 shadow-sm">
    <div class="flex items-center justify-between">
        <!-- Page Title -->
        <div>
            <h1 class="text-3xl font-bold text-dark-800 mb-1">@yield('page-title', 'Dashboard')</h1>
            <p class="text-dark-500">@yield('page-subtitle', 'Welcome back! Here\'s what\'s happening with your travel business today.')</p>
        </div>

        <!-- Header Right Side -->
        <div class="flex items-center space-x-6">
            <!-- Search -->
            <div class="relative">
                <input type="text" 
                       placeholder="Search anything..." 
                       class="input-modern w-80 pl-12 pr-4">
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-dark-400"></i>
            </div>

            <!-- Notifications -->
            <div class="relative">
                <button class="p-3 text-dark-600 hover:text-dark-800 hover:bg-white/50 rounded-xl transition-all relative">
                    <i class="fas fa-bell text-xl"></i>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center animate-pulse-slow">3</span>
                </button>
            </div>

            <!-- User Profile -->
            <div class="flex items-center space-x-4 bg-white/50 rounded-2xl p-3 hover:bg-white/80 transition-all cursor-pointer">
                <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'Admin User' }}&background=0ea5e9&color=fff&size=48" 
                     alt="Profile" 
                     class="w-12 h-12 rounded-xl shadow-md">
                <div class="hidden md:block">
                    <p class="text-sm font-semibold text-dark-800">{{ auth()->user()->name ?? 'Admin User' }}</p>
                    <p class="text-xs text-dark-500">Administrator</p>
                </div>
                <button class="text-dark-600 hover:text-dark-800 ml-2">
                    <i class="fas fa-chevron-down text-sm"></i>
                </button>
            </div>
        </div>
    </div>
</header>
 