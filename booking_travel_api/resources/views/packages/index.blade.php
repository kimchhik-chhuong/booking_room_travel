@extends('layouts.dashboard')

@section('title', 'Packages')
@section('page-title', 'Travel Packages')
@section('page-subtitle', 'Manage your travel packages and create new offerings.')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    <div class="ml-72 mr-80 p-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Total Packages</p>
                        <p class="text-3xl font-bold text-dark-800">47</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+3 this month</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-box text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Active Packages</p>
                        <p class="text-3xl font-bold text-dark-800">42</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">89% active rate</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Avg. Rating</p>
                        <p class="text-3xl font-bold text-dark-800">4.8</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+0.2 this month</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-star text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Total Revenue</p>
                        <p class="text-3xl font-bold text-dark-800">$234K</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+18% this month</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-white text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Package -->
        <div class="card-modern p-8 mb-12">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-2xl font-bold text-dark-800 mb-2">Featured Package</h3>
                    <p class="text-dark-500">Our most popular travel experience</p>
                </div>
                <button class="btn-modern">Edit Package</button>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <div class="space-y-6">
                    <div class="relative overflow-hidden rounded-2xl">
                        <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-GP7z1nSgCIj2SDO3HWgyYcov2Fgfii.png" alt="Tropical Paradise" class="w-full h-80 object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                        <div class="absolute top-6 left-6 bg-red-500 text-white px-4 py-2 rounded-full text-sm font-semibold">
                            Most Popular
                        </div>
                        <div class="absolute bottom-6 left-6 right-6">
                            <div class="flex items-center text-white mb-2">
                                @for($i = 0; $i < 5; $i++)
                                    <i class="fas fa-star text-yellow-400"></i>
                                @endfor
                                <span class="ml-2 font-medium">4.9 (234 reviews)</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4">
                        @for($i = 1; $i <= 3; $i++)
                        <div class="relative overflow-hidden rounded-xl cursor-pointer hover:scale-105 transition-transform">
                            <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-GP7z1nSgCIj2SDO3HWgyYcov2Fgfii.png" alt="Gallery {{ $i }}" class="w-full h-24 object-cover">
                        </div>
                        @endfor
                    </div>
                </div>
                
                <div class="space-y-8">
                    <div>
                        <h2 class="text-4xl font-bold text-dark-800 mb-4">Tropical Paradise Retreat</h2>
                        <div class="flex items-center space-x-4 text-dark-500 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt text-primary-500 mr-2"></i>
                                <span>Maldives</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-clock text-primary-500 mr-2"></i>
                                <span>7 Days / 6 Nights</span>
                            </div>
                        </div>
                        <p class="text-dark-600 leading-relaxed mb-8">
                            Escape to a tropical haven where pristine beaches, crystal-clear waters, and luxurious overwater bungalows await. This all-inclusive package offers the perfect blend of relaxation and adventure in one of the world's most beautiful destinations.
                        </p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <p class="text-dark-500 text-sm font-medium mb-2">Starting Price</p>
                            <p class="text-4xl font-bold text-primary-600">$3,299</p>
                            <p class="text-dark-500 text-sm">per person</p>
                        </div>
                        <div>
                            <p class="text-dark-500 text-sm font-medium mb-2">Bookings</p>
                            <p class="text-4xl font-bold text-dark-800">234</p>
                            <p class="text-emerald-600 text-sm">+12 this week</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-check text-emerald-600"></i>
                            </div>
                            <span class="text-dark-700 font-medium">All-Inclusive</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-bed text-blue-600"></i>
                            </div>
                            <span class="text-dark-700 font-medium">Luxury Resort</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-spa text-purple-600"></i>
                            </div>
                            <span class="text-dark-700 font-medium">Spa Treatments</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-cyan-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-water text-cyan-600"></i>
                            </div>
                            <span class="text-dark-700 font-medium">Water Sports</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- All Packages -->
        <div class="card-modern p-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-2xl font-bold text-dark-800 mb-2">All Packages</h3>
                    <p class="text-dark-500">Browse and manage your travel packages</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" placeholder="Search packages..." class="input-modern pl-10 w-64">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-400"></i>
                    </div>
                    <select class="input-modern">
                        <option>All Categories</option>
                        <option>Beach</option>
                        <option>Adventure</option>
                        <option>Cultural</option>
                        <option>Luxury</option>
                    </select>
                    <button class="btn-modern">
                        <i class="fas fa-plus mr-2"></i> Add Package
                    </button>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @php
                $packages = [
                    ['title' => 'Tokyo Cultural Adventure', 'location' => 'Tokyo, Japan', 'duration' => '7 Days', 'price' => '$2,450', 'rating' => '4.9', 'bookings' => 156, 'status' => 'Active', 'image' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-GP7z1nSgCIj2SDO3HWgyYcov2Fgfii.png'],
                    ['title' => 'Bali Beach Paradise', 'location' => 'Bali, Indonesia', 'duration' => '5 Days', 'price' => '$1,890', 'rating' => '4.8', 'bookings' => 203, 'status' => 'Active', 'image' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-GP7z1nSgCIj2SDO3HWgyYcov2Fgfii.png'],
                    ['title' => 'European Grand Tour', 'location' => 'Europe', 'duration' => '14 Days', 'price' => '$4,200', 'rating' => '4.7', 'bookings' => 89, 'status' => 'Active', 'image' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-GP7z1nSgCIj2SDO3HWgyYcov2Fgfii.png'],
                    ['title' => 'Safari Adventure', 'location' => 'Kenya', 'duration' => '8 Days', 'price' => '$3,650', 'rating' => '4.9', 'bookings' => 134, 'status' => 'Active', 'image' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-GP7z1nSgCIj2SDO3HWgyYcov2Fgfii.png'],
                    ['title' => 'New York City Break', 'location' => 'New York, USA', 'duration' => '4 Days', 'price' => '$1,299', 'rating' => '4.6', 'bookings' => 78, 'status' => 'Draft', 'image' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-GP7z1nSgCIj2SDO3HWgyYcov2Fgfii.png'],
                    ['title' => 'Swiss Alps Retreat', 'location' => 'Switzerland', 'duration' => '6 Days', 'price' => '$2,890', 'rating' => '4.8', 'bookings' => 167, 'status' => 'Active', 'image' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-GP7z1nSgCIj2SDO3HWgyYcov2Fgfii.png']
                ];
                @endphp
                
                @foreach($packages as $package)
                <div class="group cursor-pointer">
                    <div class="relative overflow-hidden rounded-2xl mb-6">
                        <img src="{{ $package['image'] }}" alt="{{ $package['title'] }}" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute top-4 right-4">
                            <span class="badge-modern {{ $package['status'] === 'Active' ? 'bg-emerald-500 text-white' : 'bg-yellow-500 text-white' }}">
                                {{ $package['status'] }}
                            </span>
                        </div>
                        <div class="absolute bottom-4 left-4 right-4">
                            <div class="flex items-center justify-between text-white">
                                <div class="flex items-center">
                                    @for($i = 0; $i < 5; $i++)
                                        <i class="fas fa-star text-yellow-400 text-sm"></i>
                                    @endfor
                                    <span class="ml-2 text-sm font-medium">{{ $package['rating'] }}</span>
                                </div>
                                <span class="text-sm">{{ $package['bookings'] }} bookings</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-xl font-bold text-dark-800 mb-2 group-hover:text-primary-600 transition-colors">{{ $package['title'] }}</h4>
                            <div class="flex items-center text-dark-500 text-sm space-x-4 mb-3">
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    <span>{{ $package['location'] }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-clock mr-1"></i>
                                    <span>{{ $package['duration'] }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-2xl font-bold text-primary-600">{{ $package['price'] }}</span>
                                <span class="text-dark-500 text-sm ml-1">per person</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button class="p-2 text-dark-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="p-2 text-dark-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="p-2 text-dark-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
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
