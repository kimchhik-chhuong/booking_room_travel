<div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
    <div class="flex flex-col lg:flex-row">
        <!-- Image -->
        <div class="lg:w-1/3 relative">
            <img src="{{ $package->featured_image ? asset('storage/' . $package->featured_image) : '/placeholder.svg?height=250&width=400&text=' . urlencode($package->title) }}" 
                 alt="{{ $package->title }}" 
                 class="w-full h-64 lg:h-full object-cover">
            
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
        </div>

        <!-- Content -->
        <div class="lg:w-2/3 p-8 flex flex-col justify-between">
            <div>
                <!-- Category & Location -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-4">
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $package->category->name ?? 'Travel' }}
                        </span>
                        <div class="flex items-center space-x-1 text-gray-500 text-sm">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $package->destination->name ?? 'Unknown' }}, {{ $package->destination->country ?? '' }}</span>
                        </div>
                    </div>
                    
                    <!-- Rating -->
                    <div class="flex items-center space-x-1">
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star text-{{ $i <= $package->rating ? 'yellow' : 'gray' }}-400 text-sm"></i>
                            @endfor
                        </div>
                        <span class="text-sm font-semibold text-gray-800">{{ number_format($package->rating, 1) }}</span>
                        <span class="text-xs text-gray-600">({{ $package->total_reviews }})</span>
                    </div>
                </div>

                <!-- Title -->
                <h3 class="text-2xl font-bold text-gray-800 mb-3 hover:text-blue-600 transition-colors">
                    {{ $package->title }}
                </h3>

                <!-- Description -->
                <p class="text-gray-600 mb-4 line-clamp-3">
                    {{ $package->short_description }}
                </p>

                <!-- Details -->
                <div class="flex items-center space-x-6 text-sm text-gray-500 mb-6">
                    <div class="flex items-center space-x-1">
                        <i class="fas fa-clock"></i>
                        <span>{{ $package->duration_days }} Days / {{ $package->duration_nights }} Nights</span>
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

                <!-- Highlights -->
                @if($package->highlights && count($package->highlights) > 0)
                <div class="mb-6">
                    <h4 class="font-semibold text-gray-800 mb-2">Highlights:</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach(array_slice($package->highlights, 0, 3) as $highlight)
                            <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">{{ $highlight }}</span>
                        @endforeach
                        @if(count($package->highlights) > 3)
                            <span class="text-blue-600 text-sm font-medium">+{{ count($package->highlights) - 3 }} more</span>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Price & Action -->
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center space-x-2">
                        <span class="text-3xl font-bold text-blue-600">${{ number_format($package->price, 0) }}</span>
                        @if($package->original_price && $package->original_price > $package->price)
                            <span class="text-lg text-gray-500 line-through">${{ number_format($package->original_price, 0) }}</span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-600">per person</p>
                </div>
                
                <a href="{{ route('packages.show', $package->slug) }}" 
                   class="px-8 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                    View Details
                </a>
            </div>
        </div>
    </div>
</div>
