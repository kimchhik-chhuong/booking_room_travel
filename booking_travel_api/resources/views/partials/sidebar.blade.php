<div class="fixed left-0 top-0 w-72 h-full sidebar-modern z-50 shadow-2xl">
    <!-- Logo -->
    <div class="p-8 border-b border-white/10">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-gradient-to-br from-primary-400 to-primary-600 rounded-2xl flex items-center justify-center shadow-lg animate-float">
                <i class="fas fa-plane text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white">Travelie</h1>
                <p class="text-primary-200 text-sm">Travel Management</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="mt-8 px-6">
        <div class="space-y-2">
            <a href="{{ route('dashboard') }}" 
               class="nav-item flex items-center space-x-4 px-4 py-3 text-white/80 hover:text-white {{ request()->routeIs('dashboard') ? 'active text-white' : '' }}">
                <i class="fas fa-th-large w-5 text-center"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            
            <a href="{{ route('packages.index') }}" 
               class="nav-item flex items-center space-x-4 px-4 py-3 text-white/80 hover:text-white {{ request()->routeIs('packages.*') ? 'active text-white' : '' }}">
                <i class="fas fa-box w-5 text-center"></i>
                <span class="font-medium">Packages</span>
            </a>
            
            <a href="{{ route('bookings.index') }}" 
               class="nav-item flex items-center space-x-4 px-4 py-3 text-white/80 hover:text-white {{ request()->routeIs('bookings.*') ? 'active text-white' : '' }}">
                <i class="fas fa-calendar-check w-5 text-center"></i>
                <span class="font-medium">Bookings</span>
            </a>
            
            <a href="{{ route('calendar') }}" 
               class="nav-item flex items-center space-x-4 px-4 py-3 text-white/80 hover:text-white {{ request()->routeIs('calendar') ? 'active text-white' : '' }}">
                <i class="fas fa-calendar w-5 text-center"></i>
                <span class="font-medium">Calendar</span>
            </a>
            
            <a href="{{ route('travelers.index') }}" 
               class="nav-item flex items-center space-x-4 px-4 py-3 text-white/80 hover:text-white {{ request()->routeIs('travelers.*') ? 'active text-white' : '' }}">
                <i class="fas fa-users w-5 text-center"></i>
                <span class="font-medium">Travelers</span>
            </a>
            
            <a href="{{ route('guides.index') }}" 
               class="nav-item flex items-center space-x-4 px-4 py-3 text-white/80 hover:text-white {{ request()->routeIs('guides.*') ? 'active text-white' : '' }}">
                <i class="fas fa-map w-5 text-center"></i>
                <span class="font-medium">Guides</span>
            </a>
            
            <a href="{{ route('gallery.index') }}" 
               class="nav-item flex items-center space-x-4 px-4 py-3 text-white/80 hover:text-white {{ request()->routeIs('gallery.*') ? 'active text-white' : '' }}">
                <i class="fas fa-images w-5 text-center"></i>
                <span class="font-medium">Gallery</span>
            </a>
            
            <a href="{{ route('messages.index') }}" 
               class="nav-item flex items-center space-x-4 px-4 py-3 text-white/80 hover:text-white {{ request()->routeIs('messages.*') ? 'active text-white' : '' }}">
                <i class="fas fa-envelope w-5 text-center"></i>
                <span class="font-medium">Messages</span>
                <span class="bg-red-500 text-white text-xs rounded-full px-2 py-1 ml-auto animate-pulse-slow">5</span>
            </a>
            
            <a href="{{ route('deals.index') }}" 
               class="nav-item flex items-center space-x-4 px-4 py-3 text-white/80 hover:text-white {{ request()->routeIs('deals.*') ? 'active text-white' : '' }}">
                <i class="fas fa-tags w-5 text-center"></i>
                <span class="font-medium">Deals</span>
            </a>
            
            <a href="{{ route('feedback.index') }}" 
               class="nav-item flex items-center space-x-4 px-4 py-3 text-white/80 hover:text-white {{ request()->routeIs('feedback.*') ? 'active text-white' : '' }}">
                <i class="fas fa-comment-dots w-5 text-center"></i>
                <span class="font-medium">Feedback</span>
            </a>
        </div>
    </nav>

    <!-- Upgrade Section -->
    <div class="absolute bottom-8 left-6 right-6">
        <div class="glass-morphism rounded-2xl p-6 text-center">
            <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-crown text-white text-xl"></i>
            </div>
            <h4 class="font-bold text-white mb-2">Go Premium</h4>
            <p class="text-white/80 text-sm mb-4">Unlock advanced features</p>
            <button class="w-full bg-white text-dark-800 px-4 py-2 rounded-xl text-sm font-semibold hover:bg-white/90 transition-colors">
                Upgrade Now
            </button>
        </div>
    </div>
</div>
