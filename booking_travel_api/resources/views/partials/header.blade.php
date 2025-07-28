<header class="bg-white shadow-sm border-b border-gray-100 px-6 py-4 ml-64 mr-80">
    <div class="flex items-center justify-between">
        <!-- Page Title -->
        <div>
            <h1 class="text-2xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h1>
            <p class="text-sm text-gray-500 mt-1">@yield('page-subtitle', 'Welcome back! Here\'s what\'s happening with your travel business today.')</p>
        </div>

        <!-- Header Right Side -->
        <div class="flex items-center space-x-6">
            <!-- Search -->
            <div class="relative">
                <input type="text" 
                       placeholder="Search anything..." 
                       class="w-80 pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 transition-all">
                <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
            </div>

            <!-- Notifications -->
            <div class="relative">
                <button class="p-2.5 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-xl transition-all relative">
                    <i class="fas fa-bell text-xl"></i>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center animate-pulse">3</span>
                </button>
            </div>

            <!-- User Profile -->
            <div class="flex items-center space-x-3 bg-gray-50 rounded-xl p-2 hover:bg-gray-100 transition-all cursor-pointer">
                <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'Admin User' }}&background=3B82F6&color=fff&size=40" 
                     alt="Profile" 
                     class="w-10 h-10 rounded-full ring-2 ring-white shadow-sm">
                <div class="hidden md:block">
                    <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name ?? 'Admin User' }}</p>
                    <p class="text-xs text-gray-500">Administrator</p>
                </div>
                <button class="text-gray-600 hover:text-gray-800 ml-2">
                    <i class="fas fa-chevron-down text-sm"></i>
                </button>
            </div>
        </div>
    </div>
</header>
