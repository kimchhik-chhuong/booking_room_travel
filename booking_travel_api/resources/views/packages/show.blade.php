@extends('layouts.app')

@section('title', $package->title)
@section('meta_description', $package->short_description)

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="relative h-96 lg:h-[500px] overflow-hidden">
        <img src="{{ $package->featured_image ? asset('storage/' . $package->featured_image) : '/placeholder.svg?height=500&width=1200&text=' . urlencode($package->title) }}" 
             alt="{{ $package->title }}" 
             class="w-full h-full object-cover">
        
        <!-- Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
        
        <!-- Content -->
        <div class="absolute bottom-0 left-0 right-0 p-8">
            <div class="max-w-7xl mx-auto">
                <!-- Breadcrumb -->
                <nav class="flex items-center space-x-2 text-white/80 text-sm mb-4">
                    <a href="{{ route('home') }}" class="hover:text-white">Home</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <a href="{{ route('packages.index') }}" class="hover:text-white">Packages</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span class="text-white">{{ $package->title }}</span>
                </nav>

                <!-- Title & Info -->
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between">
                    <div class="mb-4 lg:mb-0">
                        <div class="flex items-center space-x-4 mb-4">
                            <span class="bg-{{ $package->category->color ?? 'blue' }}-500 text-white px-4 py-2 rounded-full text-sm font-semibold">
                                {{ $package->category->name ?? 'Travel' }}
                            </span>
                            @if($package->is_featured)
                                <span class="bg-red-500 text-white px-4 py-2 rounded-full text-sm font-semibold">Featured</span>
                            @endif
                            @if($package->is_popular)
                                <span class="bg-purple-500 text-white px-4 py-2 rounded-full text-sm font-semibold">Popular</span>
                            @endif
                        </div>
                        
                        <h1 class="text-4xl lg:text-5xl font-bold text-white mb-4">{{ $package->title }}</h1>
                        
                        <div class="flex items-center space-x-6 text-white/90">
                            <div class="flex items-center space-x-1">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $package->destination->name ?? 'Unknown' }}, {{ $package->destination->country ?? '' }}</span>
                            </div>
                            <div class="flex items-center space-x-1">
                                <i class="fas fa-clock"></i>
                                <span>{{ $package->duration_days }} Days / {{ $package->duration_nights }} Nights</span>
                            </div>
                            <div class="flex items-center space-x-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star text-{{ $i <= $package->rating ? 'yellow' : 'gray' }}-400"></i>
                                @endfor
                                <span class="ml-1">{{ number_format($package->rating, 1) }} ({{ $package->total_reviews }} reviews)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 text-white">
                        <div class="text-center">
                            <div class="flex items-center justify-center space-x-2 mb-2">
                                <span class="text-4xl font-bold">${{ number_format($package->price, 0) }}</span>
                                @if($package->original_price && $package->original_price > $package->price)
                                    <span class="text-xl text-white/70 line-through">${{ number_format($package->original_price, 0) }}</span>
                                @endif
                            </div>
                            <p class="text-white/80 mb-4">per person</p>
                            @if($package->discount_percentage > 0)
                                <div class="bg-green-500 text-white px-3 py-1 rounded-full text-sm font-semibold mb-4">
                                    Save {{ $package->discount_percentage }}%
                                </div>
                            @endif
                            <button onclick="openBookingModal()" class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                                Book Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-12">
                <!-- Description -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">About This Package</h2>
                    <div class="prose prose-gray max-w-none">
                        <p class="text-gray-600 leading-relaxed">{{ $package->description }}</p>
                    </div>
                </div>

                <!-- Highlights -->
                @if($package->highlights && count($package->highlights) > 0)
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Package Highlights</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($package->highlights as $highlight)
                        <div class="flex items-center space-x-3 p-4 bg-gradient-to-r from-blue-50 to-transparent rounded-lg">
                            <i class="fas fa-star text-blue-600"></i>
                            <span class="text-gray-700">{{ $highlight }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Inclusions & Exclusions -->
                @if($package->inclusions || $package->exclusions)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @if($package->inclusions)
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                            <i class="fas fa-check-circle text-green-600 mr-3"></i>
                            What's Included
                        </h3>
                        <ul class="space-y-3">
                            @foreach($package->inclusions as $inclusion)
                            <li class="flex items-center space-x-3">
                                <i class="fas fa-check text-green-600 text-sm"></i>
                                <span class="text-gray-700">{{ $inclusion }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if($package->exclusions)
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                            <i class="fas fa-times-circle text-red-600 mr-3"></i>
                            What's Not Included
                        </h3>
                        <ul class="space-y-3">
                            @foreach($package->exclusions as $exclusion)
                            <li class="flex items-center space-x-3">
                                <i class="fas fa-times text-red-600 text-sm"></i>
                                <span class="text-gray-700">{{ $exclusion }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Itinerary -->
                @if($package->itinerary && count($package->itinerary) > 0)
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-8">Day by Day Itinerary</h2>
                    <div class="space-y-8">
                        @foreach($package->itinerary as $day)
                        <div class="flex space-x-6">
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                    {{ $day['day'] }}
                                </div>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-xl font-semibold text-gray-800 mb-3">{{ $day['title'] }}</h4>
                                <p class="text-gray-600 mb-4">{{ $day['description'] }}</p>
                                
                                <div class="flex flex-wrap gap-4 text-sm">
                                    @if(isset($day['meals']) && $day['meals'])
                                    <div class="flex items-center space-x-2 text-gray-500">
                                        <i class="fas fa-utensils text-orange-500"></i>
                                        <span>Meals: {{ implode(', ', $day['meals']) }}</span>
                                    </div>
                                    @endif
                                    @if(isset($day['accommodation']) && $day['accommodation'])
                                    <div class="flex items-center space-x-2 text-gray-500">
                                        <i class="fas fa-bed text-blue-500"></i>
                                        <span>Stay: {{ $day['accommodation'] }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Gallery -->
                @if($package->gallery && count($package->gallery) > 0)
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Photo Gallery</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($package->gallery as $image)
                        <img src="{{ asset('storage/' . $image) }}" alt="Gallery" 
                             class="w-full h-48 object-cover rounded-lg cursor-pointer hover:opacity-80 transition-opacity"
                             onclick="openImageModal('{{ asset('storage/' . $image) }}')">
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Reviews -->
                @if($package->reviews && $package->reviews->count() > 0)
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-2xl font-bold text-gray-800">Customer Reviews</h2>
                        <div class="flex items-center space-x-2">
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star text-{{ $i <= $package->rating ? 'yellow' : 'gray' }}-400"></i>
                                @endfor
                            </div>
                            <span class="text-lg font-semibold text-gray-800">{{ number_format($package->rating, 1) }}</span>
                            <span class="text-gray-600">({{ $package->total_reviews }} reviews)</span>
                        </div>
                    </div>
                    
                    <div class="space-y-8">
                        @foreach($package->reviews->take(5) as $review)
                        <div class="border-b border-gray-200 pb-8 last:border-b-0">
                            <div class="flex items-start space-x-4">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name) }}&background=random&size=48" 
                                     alt="{{ $review->user->name }}" class="w-12 h-12 rounded-full">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $review->user->name }}</p>
                                            <div class="flex items-center space-x-2 mt-1">
                                                <div class="flex items-center">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star text-{{ $i <= $review->rating ? 'yellow' : 'gray' }}-400 text-sm"></i>
                                                    @endfor
                                                </div>
                                                <span class="text-sm text-gray-500">{{ $review->created_at->format('M d, Y') }}</span>
                                            </div>
                                        </div>
                                        @if($review->is_verified)
                                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">Verified Purchase</span>
                                        @endif
                                    </div>
                                    @if($review->title)
                                    <h4 class="font-medium text-gray-800 mb-2">{{ $review->title }}</h4>
                                    @endif
                                    <p class="text-gray-600">{{ $review->comment }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @if($package->total_reviews > 5)
                    <div class="text-center mt-8">
                        <button class="text-blue-600 hover:text-blue-700 font-medium">
                            View All {{ $package->total_reviews }} Reviews
                        </button>
                    </div>
                    @endif
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-8">
                <!-- Booking Card -->
                <div class="bg-white rounded-2xl shadow-lg p-8 sticky top-8">
                    <div class="text-center mb-6">
                        <div class="flex items-center justify-center space-x-2 mb-2">
                            <span class="text-4xl font-bold text-blue-600">${{ number_format($package->price, 0) }}</span>
                            @if($package->original_price && $package->original_price > $package->price)
                                <span class="text-xl text-gray-500 line-through">${{ number_format($package->original_price, 0) }}</span>
                            @endif
                        </div>
                        <p class="text-gray-600">per person</p>
                        @if($package->discount_percentage > 0)
                            <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold mt-2 inline-block">
                                Save {{ $package->discount_percentage }}%
                            </div>
                        @endif
                    </div>

                    <div class="space-y-4 mb-6">
                        <div class="flex items-center justify-between py-3 border-b border-gray-200">
                            <span class="text-gray-600">Duration</span>
                            <span class="font-medium text-gray-800">{{ $package->duration_days }} Days / {{ $package->duration_nights }} Nights</span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-200">
                            <span class="text-gray-600">Group Size</span>
                            <span class="font-medium text-gray-800">{{ $package->min_participants }}-{{ $package->max_participants }} People</span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-200">
                            <span class="text-gray-600">Difficulty</span>
                            <span class="badge-modern bg-{{ $package->difficulty_level === 'easy' ? 'green' : ($package->difficulty_level === 'moderate' ? 'yellow' : 'red') }}-100 text-{{ $package->difficulty_level === 'easy' ? 'green' : ($package->difficulty_level === 'moderate' ? 'yellow' : 'red') }}-800">
                                {{ ucfirst($package->difficulty_level) }}
                            </span>
                        </div>
                        @if($package->advance_booking_days > 0)
                        <div class="flex items-center justify-between py-3">
                            <span class="text-gray-600">Advance Booking</span>
                            <span class="font-medium text-gray-800">{{ $package->advance_booking_days }} days</span>
                        </div>
                        @endif
                    </div>

                    <button onclick="openBookingModal()" class="w-full bg-blue-600 text-white py-4 px-6 rounded-lg font-semibold hover:bg-blue-700 transition-colors mb-4">
                        Book This Package
                    </button>

                    <div class="text-center">
                        <p class="text-sm text-gray-500 mb-2">Questions about this package?</p>
                        <button onclick="openContactModal()" class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                            Contact Our Travel Experts
                        </button>
                    </div>
                </div>

                <!-- Package Details -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Package Details</h3>
                    <div class="space-y-4">
                        @if($package->accommodation_type)
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-bed text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Accommodation</p>
                                <p class="text-sm text-gray-600">{{ $package->accommodation_type }}</p>
                            </div>
                        </div>
                        @endif

                        @if($package->meal_plan)
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-utensils text-green-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Meals</p>
                                <p class="text-sm text-gray-600">{{ ucfirst(str_replace('-', ' ', $package->meal_plan)) }}</p>
                            </div>
                        </div>
                        @endif

                        @if($package->transportation)
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-car text-purple-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Transportation</p>
                                <p class="text-sm text-gray-600">{{ $package->transportation }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Amenities -->
                @if($package->amenities && $package->amenities->count() > 0)
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Amenities</h3>
                    <div class="grid grid-cols-2 gap-4">
                        @foreach($package->amenities as $amenity)
                        <div class="flex items-center space-x-2">
                            @if($amenity->icon)
                                <i class="{{ $amenity->icon }} text-blue-600"></i>
                            @endif
                            <span class="text-sm text-gray-700">{{ $amenity->name }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Similar Packages -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">You Might Also Like</h3>
                    <div class="space-y-4">
                        @php
                        $similarPackages = \App\Models\Package::where('id', '!=', $package->id)
                            ->where(function($query) use ($package) {
                                $query->where('category_id', $package->category_id)
                                      ->orWhere('destination_id', $package->destination_id);
                            })
                            ->published()
                            ->active()
                            ->take(3)
                            ->get();
                        @endphp

                        @foreach($similarPackages as $similar)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex space-x-4">
                                <img src="{{ $similar->featured_image ? asset('storage/' . $similar->featured_image) : '/placeholder.svg?height=80&width=80&text=' . urlencode($similar->title) }}" 
                                     alt="{{ $similar->title }}" 
                                     class="w-20 h-20 object-cover rounded-lg">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-800 mb-1">{{ Str::limit($similar->title, 40) }}</h4>
                                    <p class="text-sm text-gray-600 mb-2">{{ $similar->duration_days }} Days</p>
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-blue-600">${{ number_format($similar->price, 0) }}</span>
                                        <a href="{{ route('packages.show', $similar->slug) }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                            View
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Booking Modal -->
<div id="booking-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Book {{ $package->title }}</h3>
            <button onclick="closeBookingModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form action="#" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="package_id" value="{{ $package->id }}">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Travel Date</label>
                <input type="date" name="travel_date" required 
                       min="{{ now()->addDays($package->advance_booking_days)->format('Y-m-d') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Number of Travelers</label>
                <select name="travelers" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @for($i = $package->min_participants; $i <= $package->max_participants; $i++)
                        <option value="{{ $i }}">{{ $i }} {{ $i === 1 ? 'Person' : 'People' }}</option>
                    @endfor
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                <input type="text" name="name" required 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" name="email" required 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                <input type="tel" name="phone" required 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Special Requests (Optional)</label>
                <textarea name="special_requests" rows="3" 
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
            </div>

            <div class="border-t border-gray-200 pt-6">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-lg font-semibold text-gray-800">Total Cost:</span>
                    <span class="text-2xl font-bold text-blue-600" id="total-cost">${{ number_format($package->price, 0) }}</span>
                </div>
                <p class="text-sm text-gray-600 mb-6">Final price will be calculated based on number of travelers</p>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-4 px-6 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                Confirm Booking
            </button>
        </form>
    </div>
</div>

<!-- Contact Modal -->
<div id="contact-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Contact Our Experts</h3>
            <button onclick="closeContactModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <div class="space-y-6">
            <div class="text-center">
                <i class="fas fa-headset text-4xl text-blue-600 mb-4"></i>
                <p class="text-gray-600">Have questions about this package? Our travel experts are here to help!</p>
            </div>

            <div class="space-y-4">
                <a href="tel:+1234567890" class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-phone text-blue-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">Call Us</p>
                        <p class="text-sm text-gray-600">+1 (234) 567-8900</p>
                    </div>
                </a>

                <a href="mailto:info@travelie.com" class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-envelope text-green-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">Email Us</p>
                        <p class="text-sm text-gray-600">info@travelie.com</p>
                    </div>
                </a>

                <button class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors w-full text-left">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-comments text-purple-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">Live Chat</p>
                        <p class="text-sm text-gray-600">Chat with our experts now</p>
                    </div>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="image-modal" class="fixed inset-0 bg-black bg-opacity-90 hidden z-50 flex items-center justify-center p-4">
    <div class="max-w-4xl max-h-full">
        <img id="modal-image" src="/placeholder.svg" alt="" class="max-w-full max-h-full object-contain">
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>

@push('scripts')
<script>
function openBookingModal() {
    document.getElementById('booking-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeBookingModal() {
    document.getElementById('booking-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function openContactModal() {
    document.getElementById('contact-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeContactModal() {
    document.getElementById('contact-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function openImageModal(src) {
    document.getElementById('modal-image').src = src;
    document.getElementById('image-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    document.getElementById('image-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Update total cost based on number of travelers
document.querySelector('select[name="travelers"]')?.addEventListener('change', function() {
    const travelers = parseInt(this.value);
    const pricePerPerson = {{ $package->price }};
    const totalCost = travelers * pricePerPerson;
    document.getElementById('total-cost').textContent = '$' + totalCost.toLocaleString();
});

// Close modals with escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeBookingModal();
        closeContactModal();
        closeImageModal();
    }
});

// Close modals when clicking outside
document.getElementById('booking-modal').addEventListener('click', function(event) {
    if (event.target === this) {
        closeBookingModal();
    }
});

document.getElementById('contact-modal').addEventListener('click', function(event) {
    if (event.target === this) {
        closeContactModal();
    }
});

document.getElementById('image-modal').addEventListener('click', function(event) {
    if (event.target === this) {
        closeImageModal();
    }
});
</script>
@endpush
@endsection
