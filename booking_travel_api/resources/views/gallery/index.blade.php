@extends('layouts.dashboard')

@section('title', 'Gallery')
@section('page-title', 'Gallery')
@section('page-subtitle', 'Showcase your stunning travel photos and videos.')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    <div class="ml-64 mr-80 p-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-sm p-6 stat-card border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Total Photos</p>
                        <p class="text-3xl font-bold text-gray-800">1,247</p>
                        <p class="text-sm text-green-600 mt-2 flex items-center">
                            <i class="fas fa-arrow-up mr-1"></i> +23.1%
                        </p>
                    </div>
                    <div class="bg-blue-100 p-4 rounded-2xl">
                        <i class="fas fa-images text-blue-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6 stat-card border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Videos</p>
                        <p class="text-3xl font-bold text-gray-800">89</p>
                        <p class="text-sm text-green-600 mt-2 flex items-center">
                            <i class="fas fa-arrow-up mr-1"></i> +12.5%
                        </p>
                    </div>
                    <div class="bg-purple-100 p-4 rounded-2xl">
                        <i class="fas fa-video text-purple-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6 stat-card border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Categories</p>
                        <p class="text-3xl font-bold text-gray-800">12</p>
                        <p class="text-sm text-blue-600 mt-2 flex items-center">
                            <i class="fas fa-arrow-up mr-1"></i> +2
                        </p>
                    </div>
                    <div class="bg-green-100 p-4 rounded-2xl">
                        <i class="fas fa-folder text-green-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6 stat-card border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Storage Used</p>
                        <p class="text-3xl font-bold text-gray-800">2.4GB</p>
                        <p class="text-sm text-yellow-600 mt-2 flex items-center">
                            <i class="fas fa-arrow-up mr-1"></i> +156MB
                        </p>
                    </div>
                    <div class="bg-orange-100 p-4 rounded-2xl">
                        <i class="fas fa-hdd text-orange-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter and Upload Section -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Media Gallery</h3>
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <input type="text" placeholder="Search media..." 
                               class="pl-8 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-search absolute left-2.5 top-2.5 text-gray-400 text-sm"></i>
                    </div>
                    <select class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        <option>All Categories</option>
                        <option>Beach</option>
                        <option>City</option>
                        <option>Nature</option>
                        <option>Culture</option>
                        <option>Adventure</option>
                    </select>
                    <button class="btn-primary text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center">
                        <i class="fas fa-upload mr-2"></i> Upload Media
                    </button>
                </div>
            </div>

            <!-- Category Filters -->
            <div class="flex flex-wrap gap-2 mb-6">
                <button class="bg-blue-600 text-white px-4 py-2 rounded-full text-sm font-medium">All</button>
                <button class="bg-gray-100 text-gray-700 px-4 py-2 rounded-full text-sm font-medium hover:bg-gray-200 transition-colors">Beach</button>
                <button class="bg-gray-100 text-gray-700 px-4 py-2 rounded-full text-sm font-medium hover:bg-gray-200 transition-colors">City</button>
                <button class="bg-gray-100 text-gray-700 px-4 py-2 rounded-full text-sm font-medium hover:bg-gray-200 transition-colors">Nature</button>
                <button class="bg-gray-100 text-gray-700 px-4 py-2 rounded-full text-sm font-medium hover:bg-gray-200 transition-colors">Culture</button>
                <button class="bg-gray-100 text-gray-700 px-4 py-2 rounded-full text-sm font-medium hover:bg-gray-200 transition-colors">Adventure</button>
                <button class="bg-gray-100 text-gray-700 px-4 py-2 rounded-full text-sm font-medium hover:bg-gray-200 transition-colors">Wildlife</button>
            </div>

            <!-- Media Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                @php
                $galleryItems = [
                    ['src' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-uc6iPdsCHz79P4MzBqKKs5OHJDjOwt.png', 'alt' => 'Maldives Paradise', 'category' => 'Beach', 'type' => 'photo'],
                    ['src' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-uc6iPdsCHz79P4MzBqKKs5OHJDjOwt.png', 'alt' => 'Eiffel Tower Sunset', 'category' => 'City', 'type' => 'photo'],
                    ['src' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-uc6iPdsCHz79P4MzBqKKs5OHJDjOwt.png', 'alt' => 'Safari Adventure', 'category' => 'Wildlife', 'type' => 'video'],
                    ['src' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-uc6iPdsCHz79P4MzBqKKs5OHJDjOwt.png', 'alt' => 'Venice Canals', 'category' => 'City', 'type' => 'photo'],
                    ['src' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-uc6iPdsCHz79P4MzBqKKs5OHJDjOwt.png', 'alt' => 'Tokyo Temple', 'category' => 'Culture', 'type' => 'photo'],
                    ['src' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-uc6iPdsCHz79P4MzBqKKs5OHJDjOwt.png', 'alt' => 'Sydney Harbor', 'category' => 'City', 'type' => 'photo'],
                    ['src' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-uc6iPdsCHz79P4MzBqKKs5OHJDjOwt.png', 'alt' => 'Mountain Hiking', 'category' => 'Adventure', 'type' => 'video'],
                    ['src' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-uc6iPdsCHz79P4MzBqKKs5OHJDjOwt.png', 'alt' => 'NYC Skyline', 'category' => 'City', 'type' => 'photo'],
                    ['src' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-uc6iPdsCHz79P4MzBqKKs5OHJDjOwt.png', 'alt' => 'Bali Rice Terraces', 'category' => 'Nature', 'type' => 'photo'],
                    ['src' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-uc6iPdsCHz79P4MzBqKKs5OHJDjOwt.png', 'alt' => 'Greek Islands', 'category' => 'Beach', 'type' => 'photo'],
                    ['src' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-uc6iPdsCHz79P4MzBqKKs5OHJDjOwt.png', 'alt' => 'Swiss Alps', 'category' => 'Nature', 'type' => 'video'],
                    ['src' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-uc6iPdsCHz79P4MzBqKKs5OHJDjOwt.png', 'alt' => 'Caribbean Cruise', 'category' => 'Beach', 'type' => 'photo'],
                    ['src' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-uc6iPdsCHz79P4MzBqKKs5OHJDjOwt.png', 'alt' => 'Machu Picchu', 'category' => 'Culture', 'type' => 'photo'],
                    ['src' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-uc6iPdsCHz79P4MzBqKKs5OHJDjOwt.png', 'alt' => 'Northern Lights', 'category' => 'Nature', 'type' => 'photo'],
                    ['src' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-uc6iPdsCHz79P4MzBqKKs5OHJDjOwt.png', 'alt' => 'Dubai Skyline', 'category' => 'City', 'type' => 'photo'],
                ];
                @endphp

                @foreach($galleryItems as $item)
                <div class="relative group rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 cursor-pointer card-hover">
                    <img src="{{ $item['src'] }}" alt="{{ $item['alt'] }}" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300">
                    
                    <!-- Overlay -->
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="text-center text-white">
                            <p class="text-lg font-semibold mb-2">{{ $item['alt'] }}</p>
                            <div class="flex items-center justify-center space-x-3">
                                <button class="bg-white bg-opacity-20 p-2 rounded-full hover:bg-opacity-30 transition-colors">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="bg-white bg-opacity-20 p-2 rounded-full hover:bg-opacity-30 transition-colors">
                                    <i class="fas fa-download"></i>
                                </button>
                                <button class="bg-white bg-opacity-20 p-2 rounded-full hover:bg-opacity-30 transition-colors">
                                    <i class="fas fa-share"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Category Badge -->
                    <div class="absolute top-2 left-2 bg-blue-600 text-white text-xs px-2 py-1 rounded-full font-medium">
                        {{ $item['category'] }}
                    </div>
                    
                    <!-- Media Type Icon -->
                    @if($item['type'] === 'video')
                    <div class="absolute top-2 right-2 bg-black bg-opacity-50 text-white p-1 rounded-full">
                        <i class="fas fa-play text-xs"></i>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>

            <!-- Load More Button -->
            <div class="text-center mt-8">
                <button class="bg-gray-100 text-gray-700 px-6 py-3 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                    Load More Media
                </button>
            </div>
        </div>
    </div>

    <!-- Right Sidebar -->
    @include('partials.right-sidebar')
</div>
@endsection
