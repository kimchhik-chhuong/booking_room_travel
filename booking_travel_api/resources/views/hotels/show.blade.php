@extends('layouts.dashboard')

@php
use Illuminate\Support\Str;
@endphp

@section('title', $hotel->name . ' - ' . config('app.name'))
@section('page-title', $hotel->name)
@section('page-subtitle', $hotel->location ?? 'Hotel Details')

@section('content')
<div class="min-h-screen">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    <div class="md:pl-64 flex flex-col">
        <main class="flex-1">
            <div class="py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                    <!-- Page header with back button -->
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <a href="{{ route('hotels.index') }}" class="text-indigo-600 hover:text-indigo-800 flex items-center">
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Back to Hotels
                            </a>
                        </div>
                        <div class="flex items-center">
                            <a href="{{ route('hotels.edit', $hotel) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-2">
                                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                                Edit
                            </a>
                            <form action="{{ route('hotels.destroy', ['hotel' => $hotel->hotel_id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this hotel? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    Delete
                                </button>
                            </form>
                            <a href="{{ route('hotels.room-types.create', $hotel->hotel_id) }}" 
                               class="ml-2 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 01-1 1h-3a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Add Room Type
                            </a>
                        </div>
                    </div>

                    <!-- Hotel Details -->
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <!-- Image Gallery -->
                        @if($hotel->image_url)
                        <div class="h-96 overflow-hidden">
                            <img src="{{ asset('storage/' . $hotel->image_url) }}" 
                                 alt="{{ $hotel->name }}" 
                                 class="w-full h-full object-cover">
                        </div>
                        @endif

                        <!-- Additional Images -->
                        @php
                            $additionalImages = is_string($hotel->additional_images) 
                                ? json_decode($hotel->additional_images, true) 
                                : $hotel->additional_images;
                        @endphp
                        
                        @if(!empty($additionalImages) && is_array($additionalImages) && count($additionalImages) > 0)
                        <div class="p-4 bg-gray-50">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Gallery</h4>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                @foreach($additionalImages as $index => $image)
                                    @php
                                        // Handle both full URLs and relative paths
                                        if (str_starts_with($image, 'http')) {
                                            // If it's already a full URL, use it directly
                                            $imageUrl = $image;
                                        } else {
                                            // If it's a relative path, prepend storage path
                                            $imageUrl = asset('storage/' . ltrim($image, '/'));
                                        }
                                    @endphp
                                    <div class="relative group">
                                        <img src="{{ $imageUrl }}" 
                                             alt="Gallery image {{ $index + 1 }}" 
                                             class="w-full h-40 object-cover rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Hotel Info -->
                        <div class="px-4 py-5 sm:px-6">
                            <div class="flex items-center justify-between">
                                <h3 class="text-2xl leading-6 font-medium text-gray-900">
                                    {{ $hotel->name }}
                                </h3>
                                <div class="flex items-center">
                                    @for($i = 0; $i < 5; $i++)
                                        @if($i < ($hotel->star_rating ?? 0))
                                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            <div class="mt-1 max-w-2xl text-sm text-gray-500">
                                {{ $hotel->location }}
                            </div>
                        </div>

                        <!-- Details Section -->
                        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Description
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $hotel->description ?? 'No description available.' }}
                                    </dd>
                                </div>
                                
                                @if($hotel->contact_phone || $hotel->email)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">
                                        Contact Information
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if($hotel->contact_phone)
                                            <div class="flex items-center">
                                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                                </svg>
                                                {{ $hotel->contact_phone }}
                                            </div>
                                        @endif
                                        @if($hotel->email)
                                            <div class="mt-2 flex items-center">
                                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                                </svg>
                                                {{ $hotel->email }}
                                            </div>
                                        @endif
                                    </dd>
                                </div>
                                @endif

                                @if($hotel->website_url)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">
                                        Website
                                    </dt>
                                    <dd class="mt-1 text-sm">
                                        <a href="{{ $hotel->website_url }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">
                                            {{ parse_url($hotel->website_url, PHP_URL_HOST) }}
                                        </a>
                                    </dd>
                                </div>
                                @endif

                                @if(!empty($hotel->amenities) && is_array($hotel->amenities))
                                <div class="mt-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Amenities
                                    </dt>
                                    <dd class="mt-2">
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($hotel->amenities as $amenity)
                                                @php
                                                    // Map amenity keys to display names and icons
                                                    $amenityIcons = [
                                                        'wifi' => ['icon' => 'wifi', 'label' => 'Free WiFi'],
                                                        'swimming_pool' => ['icon' => 'swimmer', 'label' => 'Swimming Pool'],
                                                        'restaurant' => ['icon' => 'utensils', 'label' => 'Restaurant'],
                                                        'parking' => ['icon' => 'parking', 'label' => 'Free Parking'],
                                                        'air_conditioning' => ['icon' => 'snowflake', 'label' => 'Air Conditioning'],
                                                        'bar' => ['icon' => 'glass-martini-alt', 'label' => 'Bar'],
                                                        'spa' => ['icon' => 'spa', 'label' => 'Spa'],
                                                        'gym' => ['icon' => 'dumbbell', 'label' => 'Gym'],
                                                    ];
                                                    
                                                    $amenityData = $amenityIcons[$amenity] ?? ['icon' => 'check', 'label' => ucfirst(str_replace('_', ' ', $amenity))];
                                                @endphp
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                    <i class="fas fa-{{ $amenityData['icon'] }} mr-1"></i>
                                                    {{ $amenityData['label'] }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Room Types Section -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Available Rooms</h3>
                        <div class="bg-white shadow overflow-hidden sm:rounded-md">
                            <ul class="divide-y divide-gray-200">
                                @forelse($hotel->roomTypes as $roomType)
                                <li class="hover:bg-gray-50 transition duration-150 ease-in-out">
                                    <div class="px-4 py-4 sm:px-6">
                                        <div class="flex flex-col md:flex-row">
                                            @if($roomType->image_url)
                                            <div class="md:w-1/3 mb-4 md:mb-0 md:mr-6">
                                                <div class="h-48 rounded-lg overflow-hidden">
                                                    <img src="{{ asset('storage/' . $roomType->image_url) }}" 
                                                         alt="{{ $roomType->name }}" 
                                                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                                </div>
                                            </div>
                                            @endif
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between">
                                                    <p class="text-lg font-medium text-gray-900 truncate">
                                                        {{ $roomType->name }}
                                                    </p>
                                                    <div class="ml-2 flex-shrink-0">
                                                        <p class="px-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                            ${{ number_format($roomType->price, 2) }} / night
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="mt-2">
                                                    <p class="text-sm text-gray-600">
                                                        {{ $roomType->description }}
                                                    </p>
                                                </div>
                                                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                    <div class="flex items-center text-sm text-gray-500">
                                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                                        </svg>
                                                        Max Occupancy: {{ $roomType->max_occupancy }} {{ Str::plural('guest', $roomType->max_occupancy) }}
                                                    </div>
                                                    <div class="flex items-center text-sm text-gray-500">
                                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                                        </svg>
                                                        Available: {{ $roomType->available_rooms }} {{ Str::plural('room', $roomType->available_rooms) }}
                                                    </div>
                                                    @if(!empty($roomType->amenities) && is_array($roomType->amenities))
                                                    <div class="sm:col-span-2 mt-2">
                                                        <div class="flex flex-wrap items-center gap-2">
                                                            @php
                                                                // Map amenity keys to display names and icons
                                                                $amenityData = [
                                                                    'wifi' => ['icon' => 'wifi', 'label' => 'WiFi'],
                                                                    'tv' => ['icon' => 'tv', 'label' => 'TV'],
                                                                    'ac' => ['icon' => 'snowflake', 'label' => 'Air Conditioning'],
                                                                    'minibar' => ['icon' => 'wine-bottle', 'label' => 'Minibar'],
                                                                    'safe' => ['icon' => 'shield-alt', 'label' => 'Safe'],
                                                                    'balcony' => ['icon' => 'door-open', 'label' => 'Balcony'],
                                                                    'sea_view' => ['icon' => 'water', 'label' => 'Sea View'],
                                                                    'mountain_view' => ['icon' => 'mountain', 'label' => 'Mountain View'],
                                                                    'bathtub' => ['icon' => 'bath', 'label' => 'Bathtub'],
                                                                    'shower' => ['icon' => 'shower', 'label' => 'Shower'],
                                                                    'coffee_maker' => ['icon' => 'coffee', 'label' => 'Coffee Maker'],
                                                                    'kettle' => ['icon' => 'mug-hot', 'label' => 'Electric Kettle']
                                                                ];
                                                                
                                                                // Get the first 4 amenities to display
                                                                $displayAmenities = array_slice($roomType->amenities, 0, 4);
                                                                $remainingCount = count($roomType->amenities) - count($displayAmenities);
                                                            @endphp
                                                            
                                                            @foreach($displayAmenities as $amenity)
                                                                @php
                                                                    $data = $amenityData[$amenity] ?? ['icon' => 'check', 'label' => ucwords(str_replace('_', ' ', $amenity))];
                                                                @endphp
                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800" 
                                                                      title="{{ $data['label'] }}">
                                                                    <i class="fas fa-{{ $data['icon'] }} mr-1"></i>
                                                                    {{ $data['label'] }}
                                                                </span>
                                                            @endforeach
                                                            
                                                            @if($remainingCount > 0)
                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                                    +{{ $remainingCount }} more
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4 flex justify-end space-x-3">
                                            <a href="{{ route('hotels.room-types.edit', ['hotel' => $hotel->hotel_id, 'roomType' => $roomType->id]) }}" 
                                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                Edit Room
                                            </a>
                                            <form action="{{ route('hotels.room-types.destroy', ['hotel' => $hotel->hotel_id, 'roomType' => $roomType->id]) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Are you sure you want to delete this room type? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                    Delete Room
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                                @empty
                                <li class="px-4 py-4 text-center text-gray-500">
                                    No room types available for this hotel.
                                </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    <!-- Map Section -->
                    @if($hotel->latitude && $hotel->longitude)
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Location</h3>
                        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                            <div class="h-64 w-full bg-gray-200" id="map"></div>
                            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                                <a href="https://www.google.com/maps?q={{ $hotel->latitude }},{{ $hotel->longitude }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                    </svg>
                                    View on Map
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</div>

@push('scripts')
@if($hotel->latitude && $hotel->longitude)
<script>
    // Initialize and display the map
    function initMap() {
        const location = { lat: {{ $hotel->latitude }}, lng: {{ $hotel->longitude }} };
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 15,
            center: location,
            styles: [
                {
                    featureType: "poi",
                    elementType: "labels",
                    stylers: [{ visibility: "off" }],
                },
            ],
        });
        
        new google.maps.Marker({
            position: location,
            map: map,
            title: "{{ $hotel->name }}"
        });
    }
</script>
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&callback=initMap">
</script>
@endif
@endpush
@endsection
