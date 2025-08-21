@extends('layouts.dashboard')

@section('title', $province->name . ' - Packages')
@section('page-title', $province->name)
@section('page-subtitle', 'Explore hotels and adventures in ' . $province->name)

@section('content')
<div class="min-h-screen">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    <div class="md:pl-64 flex flex-col">
        <main class="flex-1">
            <!-- Hero Section -->
            <div class="bg-indigo-700">
                <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8 lg:flex lg:justify-between">
                    <div class="max-w-xl">
                        <h1 class="text-4xl font-extrabold text-white sm:text-5xl sm:tracking-tight lg:text-6xl">
                            {{ $province->name }}
                        </h1>
                        <p class="mt-5 text-xl text-indigo-100">
                            Discover amazing hotels and adventures in {{ $province->name }}, Cambodia.
                        </p>
                    </div>
                    <div class="flex items-center justify-end">
                        <a href="{{ route('packages.index') }}" class="text-white hover:text-indigo-100 text-sm font-medium">
                            Back to Packages
                        </a>
                    </div>
                </div>
            </div>

            <!-- Hotels Section -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="mb-8 flex justify-between flex-col sm:flex-row">
                    <div class="flex items-start justify-start flex-col mb-4 sm:mb-0">
                        <h2 class="text-2xl font-bold text-gray-900">Hotels in {{ $province->name }}</h2>
                        <p class="mt-1 text-sm text-gray-500">Find the perfect place to stay during your visit</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('hotels.index', ['province_id' => $province->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            View All Hotels
                        </a>
                        @auth
                            <a href="{{ route('hotels.create', ['province_id' => $province->id]) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md transition-colors duration-300">
                                Add Hotel
                            </a>
                        @endauth
                    </div>
                </div>

                @if($province->hotels->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($province->hotels->take(3) as $hotel)
                    <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
                        @if($hotel->image_url)
                        <div class="h-48 overflow-hidden">
                            <img src="{{ asset('storage/' . $hotel->image_url)}}"
                                 alt="{{ $hotel->name }}" 
                                 class="w-full h-full object-cover">
                        </div>
                        @else
                        <div class="h-48 overflow-hidden bg-gray-200 flex items-center justify-center">
                            <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        @endif
                        <div class="p-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ $hotel->name }}</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 line-clamp-2">{{ $hotel->description }}</p>
                            </div>
                            @if($hotel->star_rating)
                            <div class="mt-2">
                                <span class="text-yellow-400">
                                    @for($i = 0; $i < 5; $i++)
                                        @if($i < $hotel->star_rating)
                                            ★
                                        @else
                                            ☆
                                        @endif
                                    @endfor
                                </span>
                            </div>
                            @endif
                            <div class="mt-4">
                                <a href="{{ route('hotels.show', ['hotel' => $hotel->hotel_id]) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                    View details →
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12">
                    <p class="text-gray-500">No hotels available in {{ $province->name }} yet.</p>
                </div>
                @endif

                @if($province->hotels->count() > 3)
                <div class="mt-8 text-center">
                    <a href="{{ route('hotels.index', ['province_id' => $province->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        View All {{ $province->hotels->count() }} Hotels in {{ $province->name }}
                        <svg class="ml-2 -mr-1 w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
                @endif
            </div>

            <!-- Adventures Section -->
            <div class="bg-gray-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900">Adventures in {{ $province->name }}</h2>
                        <p class="mt-1 text-sm text-gray-500">Exciting activities and experiences waiting for you</p>
                    </div>

                    @if($province->adventures->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($province->adventures as $adventure)
                        <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
                            @if($adventure->image_url)
                            <div class="h-48 overflow-hidden">
                                <img src="{{ $adventure->image_path }}" 
                                     alt="{{ $adventure->name }}" 
                                     class="w-full h-full object-cover">
                            </div>
                            @endif
                            <div class="p-4">
                                <h3 class="text-lg font-medium text-gray-900">{{ $adventure->name }}</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 line-clamp-2">{{ $adventure->description }}</p>
                                </div>
                                <div class="mt-4">
                                    <a href="#" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                        View details →
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-12">
                        <p class="text-gray-500">No adventures available in {{ $province->name }} yet.</p>
                    </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/mZEZPo5pD4+Lj47lA="
      crossorigin=""/>
<style>
    /* Map container */
    .map-container {
        width: 100%;
        height: 300px;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }
    
    #map {
        width: 100%;
        height: 100%;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if map container exists
        const mapElement = document.getElementById('provinceMap');
        if (!mapElement) return;

        // Initialize the map
        const map = L.map('provinceMap').setView([11.5449, 104.8922], 8);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add a marker for the province if coordinates are available
        @if($province->latitude && $province->longitude)
            L.marker([{{ $province->latitude }}, {{ $province->longitude }}])
                .addTo(map)
                .bindPopup('<b>{{ $province->name }}</b>');
        @endif
    });
</script>
@endpush
