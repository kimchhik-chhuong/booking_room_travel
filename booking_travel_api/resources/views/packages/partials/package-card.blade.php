<div class="group cursor-pointer transform hover:scale-105 transition-all duration-300">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow duration-300">
        <!-- Image -->
        <div class="relative overflow-hidden">
            <img src="{{ $package->featured_image ? asset('storage/' . $package->featured_image) : '/placeholder.svg?height=250&width=400&text=' . urlencode($package->title) }}" 
                 alt="{{ $package->title }}" 
                 class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
            
            <!-- Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
            
            <!-- Badges -->
            <div class="absolute top-4 left-4 flex flex-col space-y-2">
                @if($package->is_featured)
                    <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">Featured</span>
                @endif
                @if($package->is_popular)
                    <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm font-semibold">Popular</span>
                @endif
                @if($package->discount_percentage > 0)
                    <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm font-semibold">{{ $package->discount_percentage }}% OFF</span>
                @endif
            </div>

            <!-- Price -->
            <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm rounded-lg px-3 py-2">
                <div class="text-right">
                    <div class="flex items-center space-x-1">
                        <span class="text-2xl font-bold text-blue-600">${{ number_format($package->price, 0) }}</span>
                        @if($package->original_price && $package->original_price > $package->price)
                            <span class="text-sm text-gray-500 line-through">${{ number_format($package->original_price, 0) }}</span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-600">per person</p>
                </div>
            </div>

            <!-- Rating -->
            <div class="absolute bottom-4 left-4 flex items-center space-x-1 bg-white/90 backdrop-blur-sm rounded-lg px-3 py-2">
                <div class="flex items-center">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star text-{{ $i <= $package->rating ? 'yellow' : 'gray' }}-400 text-sm"></i>
                    @endfor
                </div>
                <span class="text-sm font-semibold text-gray-800">{{ number_format($package->rating, 1) }}</span>
                <span class="text-xs text-gray-600">({{ $package->total_reviews }})</span>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Category & Location -->
            <div class="flex items-center justify-between mb-3">
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                    {{ $package->category->name ?? 'Travel' }}
                </span>
                <div class="flex items-center space-x-1 text-gray-500 text-sm">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{{ $package->destination->name ?? 'Unknown' }}</span>
                </div>
            </div>

            <!-- Title -->
            <h3 class="text-xl font-bold text-gray-800 mb-3 group-hover:text-blue-600 transition-colors">
                {{ $package->title }}
            </h3>

            <!-- Description -->
            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                {{ $package->short_description }}
            </p>

            <!-- Details -->
            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                <div class="flex items-center space-x-1">
                    <i class="fas fa-clock"></i>
                    <span>{{ $package->duration_days }} Days</span>
                </div>
                <div class="flex items-center space-x-1">
                    <i class="fas fa-users"></i>
                    <span>{{ $package->min_participants }}-{{ $package->max_participants }} People</span>
                </div>
                <div class="flex items-center space-x-1">
                    <i class="fas fa-signal"></i>
                    <span class="capitalize">{{ $package->difficulty_level }}</span>
                </div>
            </div>

            <!-- Action Button -->
            <a href="{{ route('packages.show', $package->slug) }}" 
               class="block w-full bg-blue-600 text-white text-center py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                View Details
            </a>
        </div>
    </div>
</div>
