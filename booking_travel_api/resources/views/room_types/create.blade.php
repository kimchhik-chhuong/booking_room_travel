@extends('layouts.dashboard')

@section('title', 'Add Room Type - ' . $hotel->name)
@section('page-title', 'Add New Room Type')
@section('page-subtitle', 'Add a new room type for ' . $hotel->name)

@section('content')
<div class="min-h-screen">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    <div class="md:pl-64 flex flex-col">
        <main class="flex-1">
            <!-- Page header -->
            <div class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center">
                        <h1 class="text-2xl font-bold text-gray-900">Add New Room Type</h1>
                        <a href="{{ route('hotels.show', $hotel->hotel_id) }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Back to Hotel
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form content -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <form action="{{ route('hotels.room-types.store', $hotel->hotel_id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Left Column -->
                                <div class="space-y-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700">Room Type Name</label>
                                        <input type="text" name="name" id="name" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            value="{{ old('name') }}">
                                        @error('name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="price" class="block text-sm font-medium text-gray-700">Price per night ($)</label>
                                        <input type="number" name="price" id="price" min="0" step="0.01" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            value="{{ old('price') }}">
                                        @error('price')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="max_occupancy" class="block text-sm font-medium text-gray-700">Max Occupancy</label>
                                        <input type="number" name="max_occupancy" id="max_occupancy" min="1" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            value="{{ old('max_occupancy', 2) }}">
                                        @error('max_occupancy')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="available_rooms" class="block text-sm font-medium text-gray-700">Available Rooms</label>
                                        <input type="number" name="available_rooms" id="available_rooms" min="0" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            value="{{ old('available_rooms', 1) }}">
                                        @error('available_rooms')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Room Image</label>
                                        <div class="mt-1 flex items-center">
                                            <div class="w-full
                                                @error('image') border-red-300 @else border-gray-300 @enderror 
                                                border-2 border-dashed rounded-md p-4 text-center">
                                                <div class="space-y-1">
                                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                    <div class="flex text-sm text-gray-600">
                                                        <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                            <span>Upload an image</span>
                                                            <input id="image" name="image" type="file" class="sr-only" onchange="previewImage(this)">
                                                        </label>
                                                        <p class="pl-1">or drag and drop</p>
                                                    </div>
                                                    <p class="text-xs text-gray-500">
                                                        PNG, JPG, GIF up to 5MB
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="imagePreview" class="mt-2 hidden">
                                            <p class="text-sm text-gray-500">Preview:</p>
                                            <img id="preview" class="mt-1 h-32 w-auto rounded-md">
                                        </div>
                                        @error('image')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="space-y-4">
                                    <div>
                                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                        <textarea name="description" id="description" rows="4"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        >{{ old('description') }}</textarea>
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
                                                    'heating' => 'Heating',
                                                    'kitchen' => 'Kitchen',
                                                    'workspace' => 'Workspace',
                                                    'hairdryer' => 'Hairdryer',
                                                    'iron' => 'Iron',
                                                    'shampoo' => 'Shampoo',
                                                    'breakfast' => 'Breakfast',
                                                    'parking' => 'Free Parking',
                                                    'pool' => 'Swimming Pool',
                                                    'gym' => 'Gym',
                                                    'spa' => 'Spa',
                                                    'laundry' => 'Laundry',
                                                    'airport_shuttle' => 'Airport Shuttle',
                                                    'restaurant' => 'Restaurant',
                                                    'bar' => 'Bar',
                                                    'room_service' => '24/7 Room Service',
                                                    'safe' => 'In-room Safe'
                                                ];
                                                $selectedAmenities = old('amenities', []);
                                            @endphp

                                            @foreach($amenities as $value => $label)
                                                <div class="flex items-center">
                                                    <input id="amenity-{{ $value }}" name="amenities[]" type="checkbox" value="{{ $value }}"
                                                        {{ in_array($value, $selectedAmenities) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                    <label for="amenity-{{ $value }}" class="ml-2 block text-sm text-gray-700">
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
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Save Room Type
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

@push('scripts')
<script>
    // Preview image before upload
    function previewImage(input) {
        const preview = document.getElementById('preview');
        const imagePreview = document.getElementById('imagePreview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                imagePreview.classList.remove('hidden');
            }
            
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '';
            imagePreview.classList.add('hidden');
        }
    }
</script>
@endpush
@endsection
