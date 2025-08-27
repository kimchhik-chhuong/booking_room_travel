@extends('layouts.dashboard')
@section('title', 'Edit Room Type')
@section('page-title', 'Edit Room Type')
@section('page-subtitle', 'Update room type information for ' . $hotel->name)

@section('content')
<div class="min-h-screen bg-gray-50">
    @include('partials.sidebar')
    @include('partials.header')
    
    <div class="md:pl-64 flex flex-col flex-1">
        <main class="flex-1">
            <div class="py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                    <div class="md:flex md:items-center md:justify-between">
                        <div class="mt-4 flex md:mt-0 md:ml-4">
                            <a href="{{ route('hotels.show', $hotel->hotel_id) }}" 
                               class="text-indigo-600 hover:text-indigo-800 flex items-center">
                                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Back to Hotel
                            </a>
                        </div>
                    </div>
                </div>

                <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 mt-6">
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <form action="{{ route('hotels.room-types.update', ['hotel' => $hotel->hotel_id, 'roomType' => $roomType->id])  }}" 
                                  method="POST" 
                                  enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <div class="space-y-6">
                                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                        <!-- Room Name -->
                                        <div class="sm:col-span-4">
                                            <label for="name" class="block text-sm font-medium text-gray-700">Room Type Name</label>
                                            <div class="mt-1">
                                                <input type="text" name="name" id="name" 
                                                       value="{{ old('name', $roomType->name) }}"
                                                       class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                                @error('name')
                                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Price -->
                                        <div class="sm:col-span-2">
                                            <label for="price" class="block text-sm font-medium text-gray-700">Price per night ($)</label>
                                            <div class="mt-1">
                                                <input type="number" name="price" id="price" 
                                                       value="{{ old('price', $roomType->price) }}" step="0.01" min="0"
                                                       class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                                @error('price')
                                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Description -->
                                        <div class="sm:col-span-6">
                                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                            <div class="mt-1">
                                                <textarea id="description" name="description" rows="3"
                                                          class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md">{{ old('description', $roomType->description) }}</textarea>
                                                @error('description')
                                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Max Occupancy -->
                                        <div class="sm:col-span-2">
                                            <label for="max_occupancy" class="block text-sm font-medium text-gray-700">Max Occupancy</label>
                                            <div class="mt-1">
                                                <input type="number" name="max_occupancy" id="max_occupancy" 
                                                       value="{{ old('max_occupancy', $roomType->max_occupancy) }}" min="1"
                                                       class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                                @error('max_occupancy')
                                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Available Rooms -->
                                        <div class="sm:col-span-2">
                                            <label for="available_rooms" class="block text-sm font-medium text-gray-700">Available Rooms</label>
                                            <div class="mt-1">
                                                <input type="number" name="available_rooms" id="available_rooms" 
                                                       value="{{ old('available_rooms', $roomType->available_rooms) }}" min="0"
                                                       class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                                @error('available_rooms')
                                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Room Image -->
                                        <div class="sm:col-span-6">
                                            <label class="block text-sm font-medium text-gray-700">Room Image</label>
                                            <div class="mt-1 flex items-center">
                                                @if($roomType->image_url)
                                                    <img id="current-image" src="{{ asset('storage/' . $roomType->image_url) }}" 
                                                         alt="Room Image" class="h-32 w-32 object-cover rounded-md">
                                                @else
                                                    <span class="h-32 w-32 rounded-md bg-gray-200 flex items-center justify-center">
                                                        <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </span>
                                                @endif
                                                <div class="ml-4">
                                                    <input type="file" name="image" id="image" 
                                                           class="block w-full text-sm text-gray-500
                                                                  file:mr-4 file:py-2 file:px-4
                                                                  file:rounded-md file:border-0
                                                                  file:text-sm file:font-semibold
                                                                  file:bg-indigo-50 file:text-indigo-700
                                                                  hover:file:bg-indigo-100"
                                                           onchange="previewImage(this)">
                                                    <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                                    @error('image')
                                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Amenities -->
                                        <div class="sm:col-span-6">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Amenities</label>
                                            <div class="grid grid-cols-2 gap-2 sm:grid-cols-3 md:grid-cols-4">
                                                @php
                                                    $commonAmenities = [
                                                        'wifi' => 'WiFi',
                                                        'tv' => 'TV',
                                                        'ac' => 'Air Conditioning',
                                                        'minibar' => 'Minibar',
                                                        'safe' => 'Safe',
                                                        'balcony' => 'Balcony',
                                                        'sea_view' => 'Sea View',
                                                        'mountain_view' => 'Mountain View',
                                                        'bathtub' => 'Bathtub',
                                                        'shower' => 'Shower',
                                                        'coffee_maker' => 'Coffee Maker',
                                                        'kettle' => 'Electric Kettle'
                                                    ];
                                                    $currentAmenities = is_array($roomType->amenities) ? $roomType->amenities : [];
                                                @endphp
                                                
                                                @foreach($commonAmenities as $value => $label)
                                                    <div class="flex items-center">
                                                        <input id="amenity-{{ $value }}" 
                                                               name="amenities[]" 
                                                               type="checkbox" 
                                                               value="{{ $value }}"
                                                               {{ in_array($value, $currentAmenities) ? 'checked' : '' }}
                                                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                        <label for="amenity-{{ $value }}" class="ml-2 block text-sm text-gray-700">
                                                            {{ $label }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                            @error('amenities')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="pt-5">
                                        <div class="flex justify-end">
                                            <a href="{{ route('hotels.show', $hotel->hotel_id) }}" 
                                               class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                Cancel
                                            </a>
                                            <button type="submit" 
                                                    class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                Update Room Type
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

@push('scripts')
<script>
    function previewImage(input) {
        const preview = document.getElementById('current-image');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (!preview) {
                    const img = document.createElement('img');
                    img.id = 'current-image';
                    img.src = e.target.result;
                    img.className = 'h-32 w-32 object-cover rounded-md';
                    input.parentNode.insertBefore(img, input);
                } else {
                    preview.src = e.target.result;
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
