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
                <div class="mb-8 flex justify-between flex-row">
                    <div class="flex items-start justify-start flex-col">
                        <h2 class="text-2xl font-bold text-gray-900">Hotels in {{ $province->name }}</h2>
                        <p class="mt-1 text-sm text-gray-500">Find the perfect place to stay during your visit</p>
                    </div>
                    <div class="flex items-center justify-end">
                        <a href="{{ route('hotels.create', ['province_id' => $province->id]) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md transition-colors duration-300">
                            Add Hotel
                        </a>
                    </div>
                </div>

                @if($province->hotels->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($province->hotels as $hotel)
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
                    <p class="text-gray-500">No hotels available in {{ $province->name }} yet.</p>
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
                                <img src="{{ $adventure->image_url }}" 
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
    let map, marker;
    
    function initMap() {
        // Default to Phnom Penh coordinates if province coordinates not available
        const defaultLat = {{ $province->latitude ?? 11.5564 }};
        const defaultLng = {{ $province->longitude ?? 104.9282 }};
        
        // Initialize the map
        map = L.map('map').setView([defaultLat, defaultLng], 13);
        
        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19,
        }).addTo(map);
        
        // Add initial marker
        updateMarker(defaultLat, defaultLng);
        
        // Add click handler to update marker position
        map.on('click', function(e) {
            updateMarker(e.latlng.lat, e.latlng.lng);
        });
    }
    
    function updateMarker(lat, lng) {
        // Update input fields
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');
        if (latInput) latInput.value = lat.toFixed(6);
        if (lngInput) lngInput.value = lng.toFixed(6);
        
        // Remove existing marker if it exists
        if (marker) {
            map.removeLayer(marker);
        }
        
        // Add new marker
        marker = L.marker([lat, lng], {
            draggable: true
        }).addTo(map);
        
        // Update marker position on drag
        marker.on('dragend', function(e) {
            const newLatLng = e.target.getLatLng();
            updateMarker(newLatLng.lat, newLatLng.lng);
        });
    }
    
    // Initialize map when page loads
    document.addEventListener('DOMContentLoaded', function() {
        initMap();
    });
</script>
@endpush
