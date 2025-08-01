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
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Travel Gallery</h2>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">All Media</h3>
                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors">
                    Upload New Media
                </button>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @php
                $galleryItems = [
                    ['src' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-uc6iPdsCHz79P4MzBqKKs5OHJDjOwt.png', 'alt' => 'Maldives Beach', 'category' => 'Beach'],
                    ['src' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-uc6iPdsCHz79P4MzBqKKs5OHJDjOwt.png', 'alt' => 'Eiffel Tower', 'category' => 'City'],
                    ['src' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-uc6iPdsCHz79P4MzBqKKs5OHJDjOwt.png', 'alt' => 'Safari Animals', 'category' => 'Wildlife'],
                    ['src' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-uc6iPdsCHz79P4MzBqKKs5OHJDjOwt.png', 'alt' => 'Venice Canal', 'category' => 'City'],
                    ['src' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-uc6iPdsCHz79P4MzBqKKs5OHJDjOwt.png', 'alt' => 'Japanese Temple', 'category' => 'Culture'],
                    ['src' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-uc6iPdsCHz79P4MzBqKKs5OHJDjOwt.png', 'alt' => 'Sydney Opera House', 'category' => 'City'],
                    ['src' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-uc6iPdsCHz79P4MzBqKKs5OHJDjOwt.png', 'alt' => 'Mountain View', 'category' => 'Nature'],
                    ['src' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-uc6iPdsCHz79P4MzBqKKs5OHJDjOwt.png', 'alt' => 'New York Skyline', 'category' => 'City'],
                ];
                @endphp

                @foreach($galleryItems as $item)
                <div class="relative group rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-all duration-300 cursor-pointer">
                    <img src="{{ $item['src'] }}" alt="{{ $item['alt'] }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <p class="text-white text-lg font-semibold">{{ $item['alt'] }}</p>
                    </div>
                    <div class="absolute bottom-2 left-2 bg-blue-600 text-white text-xs px-2 py-1 rounded-full">
                        {{ $item['category'] }}
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
