@extends('layouts.dashboard')

@section('title', 'Packages')
@section('page-title', 'Packages')
@section('page-subtitle', 'Manage your travel packages and create new offerings.')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    <div class="ml-64 mr-80 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">New Package</h2>
            <button class="bg-blue-600 text-white px-6 py-3 rounded-xl text-base font-medium hover:bg-blue-700 transition-colors shadow-md flex items-center">
                <i class="fas fa-plus mr-2"></i> Add Package
            </button>
        </div>

        <!-- New Package Section -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 mb-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-1">
                    <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-GP7z1nSgCIj2SDO3HWgyYcov2Fgfii.png" alt="Tropical Paradise Retreat" class="w-full h-64 object-cover rounded-xl shadow-md mb-4">
                    <div class="grid grid-cols-3 gap-3">
                        <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-GP7z1nSgCIj2SDO3HWgyYcov2Fgfii.png" alt="Gallery 1" class="w-full h-20 object-cover rounded-lg shadow-sm cursor-pointer hover:scale-105 transition-transform">
                        <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-GP7z1nSgCIj2SDO3HWgyYcov2Fgfii.png" alt="Gallery 2" class="w-full h-20 object-cover rounded-lg shadow-sm cursor-pointer hover:scale-105 transition-transform">
                        <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-GP7z1nSgCIj2SDO3HWgyYcov2Fgfii.png" alt="Gallery 3" class="w-full h-20 object-cover rounded-lg shadow-sm cursor-pointer hover:scale-105 transition-transform">
                    </div>
                </div>
                <div class="lg:col-span-2 flex flex-col justify-between">
                    <div>
                        <h3 class="text-3xl font-bold text-gray-800 mb-2">Tropical Paradise Retreat</h3>
                        <p class="text-gray-500 text-sm mb-4 flex items-center">
                            <i class="fas fa-map-marker-alt text-blue-500 mr-2"></i> Maldives
                        </p>
                        <p class="text-gray-600 text-sm leading-relaxed mb-4">
                            Escape to a tropical haven where pristine beaches, lush greenery, and luxurious accommodations await. Perfect for those looking to unwind and experience the ultimate relaxation.
                        </p>
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-sm text-gray-500">Price:</p>
                                <p class="text-2xl font-bold text-blue-600">$2,100</p>
                                <p class="text-xs text-gray-500">per person</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Duration:</p>
                                <p class="text-2xl font-bold text-gray-800">7 Days / 6 Nights</p>
                            </div>
                        </div>
                        <button class="bg-blue-600 text-white px-6 py-3 rounded-lg text-base font-medium hover:bg-blue-700 transition-colors shadow-md">
                            Edit Detail
                        </button>
                    </div>
                    <div class="mt-6 grid grid-cols-2 gap-4 text-sm text-gray-700">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i> All-Inclusive
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-bed text-blue-500 mr-2"></i> Luxury Accommodation
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-spa text-purple-500 mr-2"></i> Spa Treatments
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-water text-cyan-500 mr-2"></i> Water Sports
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-leaf text-green-600 mr-2"></i> Sustainability
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Featured Packages -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Featured Packages</h3>
                    <button class="text-gray-400 hover:text-gray-600 p-1 rounded hover:bg-gray-100">
                        <i class="fas fa-ellipsis-h"></i>
                    </button>
                </div>
                <div class="space-y-6">
                    @php
                    $featuredPackages = [
                        [
                            'title' => 'Venice Dreams',
                            'rating' => '4.8',
                            'duration' => '6 Days / 5 Nights',
                            'location' => 'Venice, Italy',
                            'price' => '$1,500',
                            'image' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-GP7z1nSgCIj2SDO3HWgyYcov2Fgfii.png',
                            'accommodation' => 'Stay in a charming boutique hotel along the Grand Canal',
                            'includedMeals' => 'Daily breakfast and one traditional Venetian dinner',
                            'extras' => 'Free airport transfers and a complimentary welcome drink',
                            'activities' => [
                                'Gondola ride through the canals',
                                'Guided tour of St. Mark\'s Basilica and Doge\'s Palace',
                                'Visit to the Murano glass-blowing factory',
                                'Leisure time for exploring local markets and cafes'
                            ]
                        ],
                        [
                            'title' => 'Safari Adventure',
                            'rating' => '5',
                            'duration' => '8 Days / 7 Nights',
                            'location' => 'Serengeti, Tanzania',
                            'price' => '$3,200',
                            'image' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-GP7z1nSgCIj2SDO3HWgyYcov2Fgfii.png',
                            'accommodation' => 'Luxury tented camps in the heart of the Serengeti',
                            'includedMeals' => 'Full board with local and international cuisine',
                            'extras' => 'All park fees, airport transfers, and travel insurance',
                            'activities' => [
                                'Daily game drives with experienced safari guides',
                                'Hot air balloon safari with a champagne breakfast',
                                'Visit to a local Maasai village',
                                'Evening sundowners and bush dinners'
                            ]
                        ]
                    ];
                    @endphp

                    @foreach($featuredPackages as $package)
                    <div class="flex flex-col md:flex-row bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 cursor-pointer">
                        <img src="{{ $package['image'] }}" alt="{{ $package['title'] }}" class="w-full md:w-64 h-48 md:h-auto object-cover">
                        <div class="p-6 flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-xl font-bold text-gray-800">{{ $package['title'] }}</h4>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-star text-yellow-400 mr-1"></i> {{ $package['rating'] }}
                                </div>
                            </div>
                            <p class="text-sm text-gray-500 mb-3 flex items-center space-x-2">
                                <span>{{ $package['duration'] }}</span>
                                <span class="text-gray-300">•</span>
                                <span>{{ $package['location'] }}</span>
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mb-4">
                                <div>
                                    <p class="font-semibold text-gray-700 mb-1">Accommodation</p>
                                    <p class="text-gray-600">{{ $package['accommodation'] }}</p>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-700 mb-1">Activities</p>
                                    <ul class="list-disc list-inside text-gray-600">
                                        @foreach($package['activities'] as $activity)
                                            <li>{{ $activity }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-700 mb-1">Included Meals</p>
                                    <p class="text-gray-600">{{ $package['includedMeals'] }}</p>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-700 mb-1">Extras</p>
                                    <p class="text-600">{{ $package['extras'] }}</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-xl font-bold text-blue-600">{{ $package['price'] }}</span>
                                    <p class="text-xs text-gray-500">per person</p>
                                </div>
                                <button class="bg-blue-600 text-white px-5 py-2.5 rounded-lg text-sm hover:bg-blue-700 transition-colors font-medium">
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Popular Packages -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Popular Packages</h3>
                    <button class="text-gray-400 hover:text-gray-600 p-1 rounded hover:bg-gray-100">
                        <i class="fas fa-ellipsis-h"></i>
                    </button>
                </div>
                <div class="space-y-4">
                    @php
                    $popularPackages = [
                        ['title' => 'Alpine Escape', 'location' => 'Swiss Alps, Switzerland', 'rating' => '4.7/5', 'image' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-GP7z1nSgCIj2SDO3HWgyYcov2Fgfii.png'],
                        ['title' => 'Caribbean Cruise', 'location' => 'Caribbean Islands', 'rating' => '5/5', 'image' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-GP7z1nSgCIj2SDO3HWgyYcov2Fgfii.png'],
                        ['title' => 'Parisian Romance', 'location' => 'Paris, France', 'rating' => '4.5/5', 'image' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-GP7z1nSgCIj2SDO3HWgyYcov2Fgfii.png'],
                        ['title' => 'Greek Island Hopping', 'location' => 'Greece (Santorini and Crete)', 'rating' => '4.5/5', 'image' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-GP7z1nSgCIj2SDO3HWgyYcov2Fgfii.png']
                    ];
                    @endphp

                    @foreach($popularPackages as $package)
                    <div class="flex items-center space-x-4 p-3 hover:bg-gray-50 rounded-xl transition-colors cursor-pointer">
                        <img src="{{ $package['image'] }}" alt="{{ $package['title'] }}" class="w-16 h-16 object-cover rounded-xl shadow-sm">
                        <div class="flex-1">
                            <p class="text-base font-semibold text-gray-800">{{ $package['title'] }}</p>
                            <p class="text-xs text-gray-500">{{ $package['location'] }}</p>
                            <div class="flex items-center text-xs text-gray-600 mt-1">
                                @for($i = 0; $i < floor(explode('/', $package['rating'])[0]); $i++)
                                    <i class="fas fa-star text-yellow-400 mr-0.5"></i>
                                @endfor
                                <span class="ml-1">{{ $package['rating'] }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Recommended Packages -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Recommended Packages</h3>
                <button class="text-gray-400 hover:text-gray-600 p-1 rounded hover:bg-gray-100">
                    <i class="fas fa-ellipsis-h"></i>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                @php
                $recommendedPackages = [
                    ['title' => 'Bali Beach Escape', 'location' => 'Bali, Indonesia', 'price' => '$1,600', 'image' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-GP7z1nSgCIj2SDO3HWgyYcov2Fgfii.png'],
                    ['title' => 'Tokyo Cultural Adventure', 'location' => 'Tokyo, Japan', 'price' => '$2,500', 'image' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-GP7z1nSgCIj2SDO3HWgyYcov2Fgfii.png'],
                    ['title' => 'New York City Highlights', 'location' => 'New York, USA', 'price' => '$1,400', 'image' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-GP7z1nSgCIj2SDO3HWgyYcov2Fgfii.png'],
                    ['title' => 'Sydney Explorer', 'location' => 'Sydney, Australia', 'price' => '$2,500', 'image' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-GP7z1nSgCIj2SDO3HWgyYcov2Fgfii.png']
                ];
                @endphp

                @foreach($recommendedPackages as $package)
                <div class="border border-gray-200 rounded-2xl overflow-hidden hover:shadow-lg transition-all duration-300 trip-card cursor-pointer">
                    <img src="{{ $package['image'] }}" alt="{{ $package['title'] }}" class="w-full h-40 object-cover">
                    <div class="p-4">
                        <h4 class="font-semibold text-gray-800 mb-1">{{ $package['title'] }}</h4>
                        <p class="text-sm text-gray-500 mb-3">{{ $package['location'] }}</p>
                        <p class="text-lg font-bold text-blue-600">{{ $package['price'] }}<span class="text-xs text-gray-500 font-normal">/person</span></p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Right Sidebar -->
    @include('partials.right-sidebar')
</div>
@endsection
