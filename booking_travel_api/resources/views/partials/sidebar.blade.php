<div class="fixed left-0 top-0 w-64 h-full bg-white shadow-2xl z-50 border-r border-gray-100">
    <!-- Logo -->
    <div class="p-6 border-b border-gray-100">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-lg">
                <i class="fas fa-map-marker-alt text-white text-lg"></i>
            </div>
            <span class="text-xl font-bold text-gray-800">Travelie</span>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="mt-6 px-4">
        <div class="space-y-1">
            <a href="{{ route('dashboard') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
                <i class="fas fa-th-large w-5"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            
            <a href="{{ route('packages.index') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('packages.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
                <i class="fas fa-box w-5"></i>
                <span class="font-medium">Packages</span>
            </a>
            
            <a href="{{ route('bookings.index') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('bookings.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
                <i class="fas fa-calendar-check w-5"></i>
                <span class="font-medium">Bookings</span>
            </a>
            
            <a href="{{ route('calendar') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('calendar') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
                <i class="fas fa-calendar w-5"></i>
                <span class="font-medium">Calendar</span>
            </a>
            
            <a href="{{ route('travelers.index') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('travelers.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
                <i class="fas fa-users w-5"></i>
                <span class="font-medium">Travelers</span>
            </a>
            
            <a href="{{ route('guides.index') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('guides.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
                <i class="fas fa-map w-5"></i>
                <span class="font-medium">Guides</span>
            </a>
            
            <a href="{{ route('gallery.index') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('gallery.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
                <i class="fas fa-images w-5"></i>
                <span class="font-medium">Gallery</span>
            </a>
            
            <a href="{{ route('messages.index') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('messages.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
                <i class="fas fa-envelope w-5"></i>
                <span class="font-medium">Messages</span>
                <span class="bg-blue-500 text-white text-xs rounded-full px-2 py-1 ml-auto animate-pulse">5</span>
            </a>
            
            <a href="{{ route('deals.index') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('deals.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
                <i class="fas fa-tags w-5"></i>
                <span class="font-medium">Deals</span>
            </a>
            
            <a href="{{ route('feedback.index') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('feedback.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
                <i class="fas fa-comment-dots w-5"></i>
                <span class="font-medium">Feedback</span>
            </a>
        </div>
    </nav>

    <!-- Upgrade Section -->
    <div class="absolute bottom-6 left-4 right-4">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-4 text-white text-center shadow-lg">
            <h4 class="font-semibold mb-2">Enhance Your Travelie Experience!</h4>
            <button class="bg-white text-blue-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors shadow-sm">
                Upgrade Now
            </button>
        </div>
    </div>
</div>
