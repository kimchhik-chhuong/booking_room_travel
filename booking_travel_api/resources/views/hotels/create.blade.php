@extends('layouts.dashboard')
@section('title', 'Add New Hotel')
@section('page-title', 'Add New Hotel')
@section('page-subtitle', 'Create a new hotel for your travel packages')

@section('content')

    @include('partials.sidebar')

    <!-- Header -->
    @include('partials.header')
    
    <div class="md:ml-64">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="mb-6">
                <a href="{{ url()->previous() }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 transition-colors">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Previous Page
                </a>
            </div>

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <form id="hotelForm" action="{{ route('hotels.store') }}" method="POST" enctype="multipart/form-data" class="divide-y divide-gray-200">
                    @csrf
                    
                    <!-- Basic Information Section -->
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Basic Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-2">
                                <label for="name" class="block text-sm font-medium text-gray-700">Hotel Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" required 
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                            
                            <div>
                                <label for="province_id" class="block text-sm font-medium text-gray-700">Province <span class="text-red-500">*</span></label>
                                <select name="province_id" id="province_id" required 
                                        class="mt-1 block w-full border border-gray-300 bg-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select Province</option>
                                    @foreach($provinces as $province)
                                        <option value="{{ $province->id }}" {{ request('province_id') == $province->id ? 'selected' : '' }}>
                                            {{ $province->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="adventure_id" class="block text-sm font-medium text-gray-700">Adventure</label>
                                <select name="adventure_id" id="adventure_id" 
                                        class="mt-1 block w-full border border-gray-300 bg-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select Adventure (Optional)</option>
                                    @foreach($adventures as $adventure)
                                        <option value="{{ $adventure->id }}">
                                            {{ $adventure->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="star_rating" class="block text-sm font-medium text-gray-700">Star Rating</label>
                                <select name="star_rating" id="star_rating" class="mt-1 block w-full border border-gray-300 bg-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="1">1 Star</option>
                                    <option value="2">2 Stars</option>
                                    <option value="3" selected>3 Stars</option>
                                    <option value="4">4 Stars</option>
                                    <option value="5">5 Stars</option>
                                </select>
                            </div>

                            <div class="col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="description" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Amenities Section -->
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Amenities</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @php
                                $amenities = [
                                    'wifi' => 'WiFi',
                                    'swimming_pool' => 'Swimming Pool',
                                    'spa' => 'Spa',
                                    'gym' => 'Gym',
                                    'restaurant' => 'Restaurant',
                                    'bar' => 'Bar',
                                    'room_service' => '24/7 Room Service',
                                    'air_conditioning' => 'Air Conditioning',
                                    'parking' => 'Free Parking',
                                    'airport_shuttle' => 'Airport Shuttle',
                                    'laundry' => 'Laundry Service',
                                    'concierge' => 'Concierge',
                                    'meeting_rooms' => 'Meeting Rooms',
                                    'business_center' => 'Business Center',
                                    'family_rooms' => 'Family Rooms',
                                    'non_smoking_rooms' => 'Non-Smoking Rooms',
                                    'pet_friendly' => 'Pet Friendly',
                                    'beach_access' => 'Beach Access',
                                    'bicycle_rental' => 'Bicycle Rental',
                                    'car_rental' => 'Car Rental'
                                ];
                            @endphp

                            @foreach($amenities as $key => $label)
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="amenity_{{ $key }}" name="amenities[]" type="checkbox" value="{{ $label }}" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    </div>
                                    <!-- change frist latter to big latter -->
                                    <div class="ml-3 text-sm">
                                        <label for="amenity_{{ $key }}" class="font-medium text-gray-700">{{ $label }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Contact Information Section -->
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Contact Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="contact_phone" class="block text-sm font-medium text-gray-700">Phone Number <span class="text-red-500">*</span></label>
                                <input type="tel" name="contact_phone" id="contact_phone" required
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email"
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                            
                            <div>
                                <label for="website" class="block text-sm font-medium text-gray-700">Website</label>
                                <input type="url" name="website" id="website"
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       placeholder="https://example.com">
                            </div>
                            
                            <div class="col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700">Address <span class="text-red-500">*</span></label>
                                <textarea name="address" id="address" rows="2" required
                                          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Media Section -->
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Media</h3>
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700">Main Image <span class="text-red-500">*</span></label>
                                <p class="mt-1 text-sm text-gray-500 mb-2">This will be the primary image displayed for the hotel.</p>
                                <input type="file" name="image" id="image" accept="image/*" required onchange="previewImage(this)"
                                       class="block w-full text-sm text-gray-500
                                              file:mr-4 file:py-2 file:px-4
                                              file:rounded-md file:border-0
                                              file:text-sm file:font-semibold
                                              file:bg-indigo-50 file:text-indigo-700
                                              hover:file:bg-indigo-100">
                                <div class="mt-2">
                                    <img id="imagePreview" src="" alt="Preview" class="h-32 w-32 object-cover rounded-md hidden">
                                </div>
                            </div>
                            
                            <div>
                                <label for="images" class="block text-sm font-medium text-gray-700">Additional Images</label>
                                <p class="mt-1 text-sm text-gray-500 mb-2">Upload additional images to showcase the hotel (multiple selection allowed).</p>
                                <input type="file" 
                                       name="images[]" 
                                       id="images" 
                                       multiple 
                                       accept="image/*"
                                       class="block w-full text-sm text-gray-500
                                              file:mr-4 file:py-2 file:px-4
                                              file:rounded-md file:border-0
                                              file:text-sm file:font-semibold
                                              file:bg-indigo-50 file:text-indigo-700
                                              hover:file:bg-indigo-100">
                                <p class="mt-2 text-xs text-gray-500">You can select multiple images (JPEG, PNG, JPG, GIF) up to 2MB each.</p>
                                <div id="imagePreviewContainer" class="mt-3 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                                    <!-- Preview images will be added here -->
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="px-4 py-4 bg-gray-50 text-right sm:px-6">
                        <button type="button" onclick="window.history.back()" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </button>
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Save Hotel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/mZEZPo5pD4+Lj47lA="
      crossorigin=""/>
<style>
    .map-container {
        width: 100%;
        height: 400px;
        border-radius: 0.5rem;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        position: relative;
    }
    
    #map {
        width: 100%;
        height: 100%;
        position: relative;
        z-index: 1;
    }
    
    /* Prevent map from capturing scroll events when cursor is over the map */
    .map-container.leaflet-container {
        pointer-events: auto !important;
    }
    
    /* Ensure the map doesn't affect the page scroll */
    .leaflet-grab {
        cursor: grab;
    }
    
    .leaflet-dragging .leaflet-grab {
        cursor: move;
        cursor: -webkit-grabbing;
        cursor:    -moz-grabbing;
    }
    
    /* Fix for touch devices */
    .leaflet-touch .leaflet-control-layers,
    .leaflet-touch .leaflet-bar {
        border: none;
    }
    
    .leaflet-touch .leaflet-bar a:first-child {
        border-top-left-radius: 0.375rem;
        border-top-right-radius: 0.375rem;
    }
    
    .leaflet-touch .leaflet-bar a:last-child {
        border-bottom-left-radius: 0.375rem;
        border-bottom-right-radius: 0.375rem;
    }
    
    /* Custom scrollbar for better UX */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }
    
    /* Better focus states */
    *:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
    }
    
    /* Form field focus states */
    input:focus, select:focus, textarea:focus {
        border-color: #818cf8;
        box-shadow: 0 0 0 1px #818cf8;
    }
    
    #searchControl {
        box-shadow: 0 1px 5px rgba(0,0,0,0.4);
        border-radius: 0.375rem;
    }
    
    #searchInput {
        width: 100%;
        height: 36px;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        line-height: 1.25rem;
    }
    
    #searchResults {
        max-height: 200px;
        overflow-y: auto;
    }
    
    .search-result-item {
        padding: 0.5rem 1rem;
        cursor: pointer;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .search-result-item:hover {
        background-color: #f9fafb;
    }
    
    .search-result-item:last-child {
        border-bottom: none;
    }
    
    .search-result-item h4 {
        font-weight: 500;
        margin-bottom: 0.25rem;
    }
    
    .search-result-item p {
        font-size: 0.75rem;
        color: #6b7280;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>

<script>
    // Image preview for main image
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '';
            preview.classList.add('hidden');
        }
    }

    // Multiple image preview for additional images
    document.getElementById('images').addEventListener('change', function(event) {
        const previewContainer = document.getElementById('imagePreviewContainer');
        previewContainer.innerHTML = ''; // Clear previous previews
        
        const files = event.target.files;
        
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (!file.type.match('image.*')) continue;
            
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const preview = document.createElement('div');
                preview.className = 'relative group';
                preview.innerHTML = `
                    <div class="relative">
                        <img src="${e.target.result}" 
                             alt="Preview ${i + 1}" 
                             class="h-32 w-full object-cover rounded-lg border border-gray-200">
                        <button type="button" 
                                class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity"
                                onclick="removeImagePreview(this, ${i})">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                `;
                previewContainer.appendChild(preview);
            };
            
            reader.readAsDataURL(file);
        }
    });

    // Function to remove image preview and update file input
    function removeImagePreview(button, index) {
        // Remove the preview
        button.closest('.relative').remove();
        
        // Update the file input
        const input = document.getElementById('images');
        const files = Array.from(input.files);
        files.splice(index, 1);
        
        // Create a new DataTransfer to update the files
        const dataTransfer = new DataTransfer();
        files.forEach(file => dataTransfer.items.add(file));
        input.files = dataTransfer.files;
        
        // If no files left, show the default message
        if (files.length === 0) {
            const previewContainer = document.getElementById('imagePreviewContainer');
            previewContainer.innerHTML = '<!-- Preview images will be added here -->';
        }
    }

    // Handle form submission
    document.getElementById('hotelForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitButton = this.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;
        
        // Show loading state
        submitButton.disabled = true;
        submitButton.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Saving...
        `;
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Redirect to hotels index page after successful creation
                window.location.href = '{{ route("hotels.index") }}';
            } else {
                throw new Error(data.message || 'Failed to save hotel');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error: ' + error.message);
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
        });
    });

    let map, marker;
    let searchTimeout;
    
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize the map after the DOM is fully loaded
        setTimeout(initMap, 100);
        setupSearch();
    });
    
    function initMap() {
        // Default to Phnom Penh coordinates if no coordinates are set
        const defaultLat = {{ $province->latitude ?? 11.5564 }};
        const defaultLng = {{ $province->longitude ?? 104.9282 }};
        
        // Initialize the map with tap option set to false for better mobile support
        map = L.map('map', {
    tap: false,  // Fixes 300ms delay on mobile
    dragging: true,
    scrollWheelZoom: false,  // Disable scroll wheel zoom
    touchZoom: true,
    doubleClickZoom: true,
    boxZoom: true,
    zoomSnap: 0.5,
    zoomControl: true,
    wheelPxPerZoomLevel: 60,
    wheelDebounceTime: 40
}).setView([defaultLat, defaultLng], 13);
        
        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: ' OpenStreetMap contributors',
            maxZoom: 19,
            noWrap: true
        }).addTo(map);
        
        // Add initial marker
        updateMarker(defaultLat, defaultLng);
        
        // Add click handler to update marker position
        map.on('click', function(e) {
            updateMarker(e.latlng.lat, e.latlng.lng);
        });
        
        // Fix for map touch events on mobile
        L.DomEvent.on(map._container, 'touchstart', function(e) {
            if (e.touches.length === 1 && map._zoom > 0) {
                map.dragging.enable();
            }
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            map.invalidateSize();
        });
        
        // Force a small delay before invalidating size to ensure container is properly rendered
        setTimeout(function() {
            map.invalidateSize();
        }, 100);
    }
    
    function setupSearch() {
        const searchBtn = document.getElementById('searchLocationBtn');
        const searchControl = document.getElementById('searchControl');
        const closeSearch = document.getElementById('closeSearch');
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');
        
        // Toggle search control visibility
        searchBtn.addEventListener('click', function() {
            searchControl.classList.toggle('hidden');
            if (!searchControl.classList.contains('hidden')) {
                searchInput.focus();
            }
        });
        
        // Close search control
        closeSearch.addEventListener('click', function() {
            searchControl.classList.add('hidden');
        });
        
        // Handle search input
        searchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            const query = e.target.value.trim();
            
            if (query.length < 3) {
                searchResults.innerHTML = '<div class="p-3 text-sm text-gray-500">Enter at least 3 characters to search</div>';
                return;
            }
            
            searchResults.innerHTML = '<div class="p-3 text-sm text-gray-500">Searching...</div>';
            
            searchTimeout = setTimeout(() => {
                searchLocation(query);
            }, 500);
        });
    }
    
    function searchLocation(query) {
        const searchResults = document.getElementById('searchResults');
        
        // Using OpenStreetMap Nominatim for geocoding
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5`)
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    searchResults.innerHTML = '<div class="p-3 text-sm text-gray-500">No results found</div>';
                    return;
                }
                
                let resultsHtml = '';
                data.forEach(result => {
                    resultsHtml += `
                        <div class="search-result-item" data-lat="${result.lat}" data-lon="${result.lon}">
                            <h4>${result.display_name.split(',')[0]}</h4>
                            <p>${result.display_name}</p>
                        </div>
                    `;
                });
                
                searchResults.innerHTML = resultsHtml;
                
                // Add click handlers to search results
                document.querySelectorAll('.search-result-item').forEach(item => {
                    item.addEventListener('click', function() {
                        const lat = parseFloat(this.dataset.lat);
                        const lon = parseFloat(this.dataset.lon);
                        
                        // Update map view and marker
                        map.setView([lat, lon], 15);
                        updateMarker(lat, lon);
                        
                        // Close search and clear results
                        document.getElementById('searchControl').classList.add('hidden');
                        searchResults.innerHTML = '';
                        searchInput.value = '';
                    });
                });
            })
            .catch(error => {
                console.error('Error searching location:', error);
                searchResults.innerHTML = '<div class="p-3 text-sm text-red-500">Error searching location. Please try again.</div>';
            });
    }
    
    function updateMarker(lat, lng) {
        // Update input fields
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');
        
        if (latInput && lngInput) {
            latInput.value = lat.toFixed(6);
            lngInput.value = lng.toFixed(6);
            
            // Remove existing marker if it exists
            if (marker) {
                map.removeLayer(marker);
            }
            
            // Add new marker
            marker = L.marker([lat, lng], {
                draggable: true,
                autoPan: true
            }).addTo(map);
            
            // Update marker position on drag
            marker.on('dragend', function(e) {
                const newLatLng = e.target.getLatLng();
                latInput.value = newLatLng.lat.toFixed(6);
                lngInput.value = newLatLng.lng.toFixed(6);
            });
        }
    }
</script>
@endpush
@endsection
