@extends('layouts.dashboard')

@section('title', 'Gallery')
@section('page-title', 'Media Gallery')
@section('page-subtitle', 'Showcase your stunning travel photos and videos.')

@section('content')
<div class="min-h-screen">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    <div class="ml-72 p-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Total Photos</p>
                        <p class="text-3xl font-bold text-dark-800">1,247</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+23.1% this month</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-images text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Videos</p>
                        <p class="text-3xl font-bold text-dark-800">89</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+12.5% this month</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-video text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Categories</p>
                        <p class="text-3xl font-bold text-dark-800">12</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+2 new categories</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-folder text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Storage Used</p>
                        <p class="text-3xl font-bold text-dark-800">2.4GB</p>
                        <p class="text-yellow-600 text-sm font-medium mt-2">+156MB this week</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-orange-400 to-orange-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-hdd text-white text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter and Upload Section -->
        <div class="card-modern p-8 mb-12">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-2xl font-bold text-dark-800 mb-2">Media Gallery</h3>
                    <p class="text-dark-500">Browse and manage your travel media collection</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" placeholder="Search media..." class="input-modern pl-10 w-64">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-400"></i>
                    </div>
                    <select class="input-modern">
                        <option>All Categories</option>
                        <option>Beach</option>
                        <option>City</option>
                        <option>Nature</option>
                        <option>Culture</option>
                        <option>Adventure</option>
                    </select>
                    <button class="btn-modern">
                        <i class="fas fa-upload mr-2"></i> Upload Media
                    </button>
                </div>
            </div>

            <!-- Category Filters -->
            <div class="flex flex-wrap gap-3 mb-8">
                <button class="bg-primary-600 text-white px-6 py-3 rounded-full text-sm font-semibold">All</button>
                <button class="bg-slate-100 text-dark-700 px-6 py-3 rounded-full text-sm font-semibold hover:bg-slate-200 transition-colors">Beach</button>
                <button class="bg-slate-100 text-dark-700 px-6 py-3 rounded-full text-sm font-semibold hover:bg-slate-200 transition-colors">City</button>
                <button class="bg-slate-100 text-dark-700 px-6 py-3 rounded-full text-sm font-semibold hover:bg-slate-200 transition-colors">Nature</button>
                <button class="bg-slate-100 text-dark-700 px-6 py-3 rounded-full text-sm font-semibold hover:bg-slate-200 transition-colors">Culture</button>
                <button class="bg-slate-100 text-dark-700 px-6 py-3 rounded-full text-sm font-semibold hover:bg-slate-200 transition-colors">Adventure</button>
                <button class="bg-slate-100 text-dark-700 px-6 py-3 rounded-full text-sm font-semibold hover:bg-slate-200 transition-colors">Wildlife</button>
            </div>

            <!-- Media Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
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
                <div class="relative group rounded-2xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-300 cursor-pointer card-modern">
                    <img src="{{ $item['src'] }}" alt="{{ $item['alt'] }}" class="w-full h-56 object-cover group-hover:scale-110 transition-transform duration-500">
                    
                    <!-- Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent flex items-end justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="text-center text-white p-6 w-full">
                            <p class="text-lg font-semibold mb-4">{{ $item['alt'] }}</p>
                            <div class="flex items-center justify-center space-x-4">
                                <button class="bg-white/20 backdrop-blur-sm p-3 rounded-full hover:bg-white/30 transition-colors">
                                    <i class="fas fa-eye text-white"></i>
                                </button>
                                <button class="bg-white/20 backdrop-blur-sm p-3 rounded-full hover:bg-white/30 transition-colors">
                                    <i class="fas fa-download text-white"></i>
                                </button>
                                <button class="bg-white/20 backdrop-blur-sm p-3 rounded-full hover:bg-white/30 transition-colors">
                                    <i class="fas fa-share text-white"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Category Badge -->
                    <div class="absolute top-4 left-4 bg-primary-600 text-white text-xs px-3 py-1 rounded-full font-semibold">
                        {{ $item['category'] }}
                    </div>
                    
                    <!-- Media Type Icon -->
                    @if($item['type'] === 'video')
                    <div class="absolute top-4 right-4 bg-black/50 backdrop-blur-sm text-white p-2 rounded-full">
                        <i class="fas fa-play text-sm"></i>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>

            <!-- Load More Button -->
            <div class="text-center mt-12">
                <button class="bg-slate-100 text-dark-700 px-8 py-4 rounded-2xl font-semibold hover:bg-slate-200 transition-colors">
                    Load More Media
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
