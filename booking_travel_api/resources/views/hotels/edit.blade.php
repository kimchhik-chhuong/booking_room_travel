@extends('layouts.dashboard')
@section('title', 'Edit Hotel')
@section('page-title', 'Edit Hotel')
@section('page-subtitle', 'Update hotel information')

@section('content')

    @include('partials.sidebar')
    @include('partials.header')
    
    <div class="md:ml-64">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="mb-6">
                <a href="{{ route('hotels.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 transition-colors">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Hotels
                </a>
            </div>

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <form id="hotelForm" action="{{ route('hotels.update', ['hotel' => $hotel->hotel_id]) }}" method="POST" enctype="multipart/form-data" class="divide-y divide-gray-200">
                    @csrf
                    @method('PUT')
                    
                    <!-- Basic Information Section -->
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Basic Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-2">
                                <label for="name" class="block text-sm font-medium text-gray-700">Hotel Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" value="{{ old('name', $hotel->name) }}" required 
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                            
                            <div>
                                <label for="province_id" class="block text-sm font-medium text-gray-700">Province <span class="text-red-500">*</span></label>
                                <select name="province_id" id="province_id" required 
                                        class="mt-1 block w-full border border-gray-300 bg-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select Province</option>
                                    @foreach($provinces as $province)
                                        <option value="{{ $province->id }}" {{ $hotel->province_id == $province->id ? 'selected' : '' }}>
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
                                        <option value="{{ $adventure->id }}" {{ $hotel->adventure_id == $adventure->id ? 'selected' : '' }}>
                                            {{ $adventure->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="star_rating" class="block text-sm font-medium text-gray-700">Star Rating</label>
                                <select name="star_rating" id="star_rating" class="mt-1 block w-full border border-gray-300 bg-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @for($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ $hotel->star_rating == $i ? 'selected' : '' }}>
                                            {{ $i }} {{ $i == 1 ? 'Star' : 'Stars' }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div class="col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="description" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('description', $hotel->description) }}</textarea>
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

                                $selectedAmenities = json_decode($hotel->amenities, true) ?? [];
                            @endphp

                            @foreach($amenities as $key => $label)
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="amenity_{{ $key }}" name="amenities[]" type="checkbox" value="{{ $key }}" 
                                               class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                               {{ in_array($key, $selectedAmenities) ? 'checked' : '' }}>
                                    </div>
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
                                <input type="tel" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $hotel->contact_phone) }}" required
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $hotel->email) }}"
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                            
                            <div>
                                <label for="website" class="block text-sm font-medium text-gray-700">Website</label>
                                <input type="url" name="website" id="website" value="{{ old('website', $hotel->website_url) }}"
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       placeholder="https://example.com">
                            </div>
                            
                            <div class="col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700">Address <span class="text-red-500">*</span></label>
                                <textarea name="address" id="address" rows="2" required
                                          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('address', $hotel->address) }}</textarea>
                            </div>
                            
                            <div>
                                <label for="check_in_time" class="block text-sm font-medium text-gray-700">Check-in Time</label>
                                <input type="time" name="check_in_time" id="check_in_time" 
                                       value="{{ old('check_in_time', $hotel->check_in_time ? \Carbon\Carbon::parse($hotel->check_in_time)->format('H:i') : '14:00') }}" 
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       placeholder="14:00">
                                <p class="mt-1 text-xs text-gray-500">Format: HH:MM (24-hour format)</p>
                            </div>
                            
                            <div>
                                <label for="check_out_time" class="block text-sm font-medium text-gray-700">Check-out Time</label>
                                <input type="time" name="check_out_time" id="check_out_time" 
                                       value="{{ old('check_out_time', $hotel->check_out_time ? \Carbon\Carbon::parse($hotel->check_out_time)->format('H:i') : '12:00') }}" 
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       placeholder="12:00">
                                <p class="mt-1 text-xs text-gray-500">Format: HH:MM (24-hour format)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Media Section -->
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Media</h3>
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700">Current Main Image</label>
                                @if($hotel->image_url)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $hotel->image_url) }}" alt="Current hotel image" class="h-40 w-auto object-cover rounded">
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">Upload a new image to replace the current one.</p>
                                @else
                                    <p class="mt-1 text-sm text-gray-500">No main image uploaded yet.</p>
                                @endif
                                <input type="file" name="image" id="image" 
                                       class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Additional Images</label>
                                @if($hotel->additional_images && count(json_decode($hotel->additional_images, true)) > 0)
                                    <div class="mt-2 grid grid-cols-3 gap-2">
                                        @foreach(json_decode($hotel->additional_images, true) as $index => $image)
                                            <div class="relative group">
                                                <img src="{{ asset('storage/' . $image) }}" alt="Additional image {{ $index + 1 }}" class="h-32 w-full object-cover rounded">
                                                <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <a href="#" class="text-white p-2 bg-red-500 rounded-full" onclick="event.preventDefault(); document.getElementById('delete-image-{{ $index }}').submit();">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                            <form id="delete-image-{{ $index }}" action="{{ route('hotels.deleteImage', ['id' => $hotel->id, 'index' => $index]) }}" method="POST" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endforeach
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">Upload additional images (will be added to existing ones).</p>
                                @else
                                    <p class="mt-1 text-sm text-gray-500">No additional images uploaded yet.</p>
                                @endif
                                <input type="file" name="images[]" id="images" multiple
                                       class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="px-4 py-4 bg-gray-50 text-right sm:px-6">
                        <button type="button" onclick="window.history.back()" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </button>
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Hotel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Image preview for main image
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.createElement('div');
                    preview.className = 'mt-2';
                    preview.innerHTML = `
                        <p class="text-sm text-gray-500">New image preview:</p>
                        <img src="${e.target.result}" alt="Preview" class="h-40 w-auto object-cover rounded mt-1">
                    `;
                    
                    const existingPreview = document.querySelector('#image').nextElementSibling;
                    if (existingPreview && existingPreview.className.includes('mt-2')) {
                        existingPreview.remove();
                    }
                    
                    document.getElementById('image').insertAdjacentElement('afterend', preview);
                };
                reader.readAsDataURL(file);
            }
        });

        // Image preview for additional images
        document.getElementById('images').addEventListener('change', function(e) {
            const files = e.target.files;
            if (files.length > 0) {
                const previewContainer = document.createElement('div');
                previewContainer.className = 'mt-2';
                previewContainer.innerHTML = '<p class="text-sm text-gray-500">New additional images preview:</p><div class="grid grid-cols-3 gap-2 mt-1"></div>';
                
                const grid = previewContainer.querySelector('.grid');
                
                Array.from(files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'h-32 w-full object-cover rounded';
                        grid.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
                
                const existingPreview = document.querySelector('#images').nextElementSibling;
                if (existingPreview && existingPreview.className.includes('mt-2')) {
                    existingPreview.remove();
                }
                
                document.getElementById('images').insertAdjacentElement('afterend', previewContainer);
            }
        });
    </script>
    @endpush
@endsection
