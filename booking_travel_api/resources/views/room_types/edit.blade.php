@extends('layouts.app')

@section('title', 'Edit Room Type - ' . $roomType->name)

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Edit Room Type - {{ $roomType->name }}</h2>
                
                <form action="{{ route('hotels.room-types.update', ['hotel' => $hotel->hotel_id, 'room_type' => $roomType->id]) }}" 
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Room Type Name</label>
                                <input type="text" name="name" id="name" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    value="{{ old('name', $roomType->name) }}">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700">Price per night ($)</label>
                                <input type="number" name="price" id="price" min="0" step="0.01" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    value="{{ old('price', $roomType->price) }}">
                                @error('price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="max_occupancy" class="block text-sm font-medium text-gray-700">Max Occupancy</label>
                                    <input type="number" name="max_occupancy" id="max_occupancy" min="1" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        value="{{ old('max_occupancy', $roomType->max_occupancy) }}">
                                    @error('max_occupancy')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="available_rooms" class="block text-sm font-medium text-gray-700">Available Rooms</label>
                                    <input type="number" name="available_rooms" id="available_rooms" min="0" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        value="{{ old('available_rooms', $roomType->available_rooms) }}">
                                    @error('available_rooms')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Room Image</label>
                                
                                @if($roomType->image_url)
                                    <div class="mb-4">
                                        <img src="{{ Storage::url($roomType->image_url) }}" alt="{{ $roomType->name }}" 
                                             class="h-48 w-full object-cover rounded-md">
                                        <div class="mt-2 flex items-center">
                                            <input type="checkbox" name="remove_image" id="remove_image" 
                                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                            <label for="remove_image" class="ml-2 block text-sm text-gray-700">
                                                Remove current image
                                            </label>
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="mt-1 flex items-center">
                                    <div class="w-full
                                        @error('image') border-red-300 @else border-gray-300 @enderror 
                                        border-2 border-dashed rounded-md px-6 pt-5 pb-6 flex justify-center">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                    <span>Upload a new image</span>
                                                    <input id="image" name="image" type="file" class="sr-only">
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500">
                                                PNG, JPG, GIF up to 2MB
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @error('image')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Description & Amenities -->
                        <div class="space-y-4">
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="description" rows="4" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description', $roomType->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Amenities</label>
                                <div class="grid grid-cols-2 gap-2">
                                    @php
                                        $amenities = [
                                            'wifi' => 'WiFi',
                                            'tv' => 'TV',
                                            'ac' => 'Air Conditioning',
                                            'minibar' => 'Minibar',
                                            'safe' => 'Safe',
                                            'kettle' => 'Electric Kettle',
                                            'fridge' => 'Refrigerator',
                                            'hairdryer' => 'Hair Dryer',
                                            'iron' => 'Iron',
                                            'desk' => 'Work Desk',
                                            'balcony' => 'Balcony',
                                            'view' => 'View',
                                            'sofa' => 'Sitting Area',
                                            'bathrobe' => 'Bathrobe',
                                            'slippers' => 'Slippers',
                                            'toiletries' => 'Toiletries'
                                        ];
                                        
                                        $selectedAmenities = is_array($roomType->amenities) 
                                            ? $roomType->amenities 
                                            : json_decode($roomType->amenities, true) ?? [];
                                    @endphp
                                    
                                    @foreach($amenities as $key => $label)
                                        <div class="flex items-center">
                                            <input type="checkbox" name="amenities[]" id="amenity-{{ $key }}" value="{{ $key }}" 
                                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                                {{ in_array($key, old('amenities', $selectedAmenities)) ? 'checked' : '' }}>
                                            <label for="amenity-{{ $key }}" class="ml-2 block text-sm text-gray-700">
                                                {{ $label }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('amenities')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 flex justify-end space-x-3">
                        <a href="{{ route('hotels.show', $hotel->hotel_id) }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Room Type
                        </button>
                    </div>
                </form>
                
                <!-- Delete Form -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex justify-end">
                        <form action="{{ route('hotels.room-types.destroy', ['hotel' => $hotel->hotel_id, 'room_type' => $roomType->id]) }}" 
                              method="POST" onsubmit="return confirm('Are you sure you want to delete this room type? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Delete Room Type
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Preview image before upload
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Remove any existing preview
                const existingPreview = document.querySelector('.image-preview');
                if (existingPreview) {
                    existingPreview.remove();
                }
                
                // Create preview
                const preview = document.createElement('img');
                preview.src = e.target.result;
                preview.alt = 'Preview';
                preview.className = 'image-preview mt-2 h-32 w-full object-cover rounded-md';
                
                // Insert after file input
                const container = document.querySelector('input[name="image"]').closest('.flex');
                container.parentNode.insertBefore(preview, container.nextSibling);
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
@endsection
