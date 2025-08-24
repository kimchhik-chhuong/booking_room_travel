@extends('layouts.dashboard')
@section('title', 'Edit Adventure: ' . $adventure->name)
@section('page-title', 'Edit Adventure')
@section('page-subtitle', 'Update the adventure details')

@section('content')
    @include('partials.sidebar')
    @include('partials.header')
    
    <div class="md:ml-64">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-6 rounded-md bg-green-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 rounded-md bg-red-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                There {{ $errors->count() > 1 ? 'are ' . $errors->count() . ' errors' : 'is 1 error' }} with your submission
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mb-6">
                <a href="{{ url()->previous() }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 transition-colors">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Previous Page
                </a>
            </div>

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <form id="adventureForm" action="{{ route('adventures.update', $adventure) }}" method="POST" enctype="multipart/form-data" class="divide-y divide-gray-200">
                    @csrf
                    @method('PUT')
                    
                    <!-- Basic Information Section -->
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Basic Information</h3>
                        
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Adventure Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    value="{{ old('name', $adventure->name) }}">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="province_id" class="block text-sm font-medium text-gray-700">Province <span class="text-red-500">*</span></label>
                                <select id="province_id" name="province_id" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select a province</option>
                                    @foreach($provinces as $province)
                                        <option value="{{ $province->id }}" {{ old('province_id', $adventure->province_id) == $province->id ? 'selected' : '' }}>{{ $province->name }}</option>
                                    @endforeach
                                </select>
                                @error('province_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                                <select id="status" name="status" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="active" {{ old('status', $adventure->status) === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $adventure->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="description" rows="4"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('description', $adventure->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Image Section -->
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Adventure Image</h3>
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700">Update Image</label>
                                <div class="mt-1 flex items-center">
                                    <input type="file" name="image" id="image"
                                        class="p-2 block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-indigo-50 file:text-indigo-700
                                        hover:file:bg-indigo-100"
                                        accept="image/*"
                                        onchange="previewImage(this)">
                                </div>
                                @error('image')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-sm text-gray-500">Leave empty to keep the current image. Max 2MB, jpeg, png, jpg, gif</p>
                                
                                @php
                                    // Determine the correct image URL
                                    $imageUrl = '';
                                    if ($adventure->image_url) {
                                        if (filter_var($adventure->image_url, FILTER_VALIDATE_URL)) {
                                            $imageUrl = $adventure->image_url;
                                        } elseif (strpos($adventure->image_url, 'storage/') === 0) {
                                            $imageUrl = asset($adventure->image_url);
                                        } else {
                                            $imageUrl = asset('storage/' . ltrim($adventure->image_url, '/'));
                                        }
                                    } else {
                                        $imageUrl = asset('images/default-adventure.jpg');
                                    }
                                @endphp
                                
                                <div class="mt-4">
                                    <p class="text-sm font-medium text-gray-700">Current Image:</p>
                                    <div class="mt-2">
                                        <img id="currentImage" 
                                             src="{{ $imageUrl }}" 
                                             alt="Current adventure image" 
                                             class="h-48 w-full max-w-md object-cover rounded-md shadow-sm"
                                             onerror="this.onerror=null; this.src='{{ asset('images/default-adventure.jpg') }}'">
                                    </div>
                                </div>
                                
                                <div class="mt-4" id="imagePreview"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <a href="{{ route('adventures.show', $adventure) }}" class="mr-3 inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Adventure
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Image preview function
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const currentImage = document.getElementById('currentImage');
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            // Validate file type
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                alert('Please select a valid image file (JPEG, PNG, JPG, GIF)');
                input.value = '';
                return false;
            }
            
            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Image size should not exceed 2MB');
                input.value = '';
                return false;
            }
            
            const reader = new FileReader();
            
            reader.onload = function(e) {
                // Hide current image and show preview
                if (currentImage) {
                    currentImage.style.display = 'none';
                }
                
                preview.innerHTML = `
                    <div class="mt-2">
                        <p class="text-sm font-medium text-gray-700">New Image Preview:</p>
                        <img src="${e.target.result}" class="mt-2 h-48 w-full max-w-md object-cover rounded-md shadow-sm">
                    </div>`;
            }
            
            reader.readAsDataURL(file);
        } else {
            // If no file is selected, show the current image again
            if (currentImage) {
                currentImage.style.display = 'block';
            }
            preview.innerHTML = '';
        }
    }
    
    // Reset file input if user cancels file selection
    document.addEventListener('click', function(e) {
        if (e.target.matches('button[type="reset"]')) {
            const preview = document.getElementById('imagePreview');
            const currentImage = document.getElementById('currentImage');
            const fileInput = document.getElementById('image');
            
            if (fileInput) {
                fileInput.value = '';
            }
            
            if (preview) {
                preview.innerHTML = '';
            }
            
            if (currentImage) {
                currentImage.style.display = 'block';
            }
        }
    });
</script>
@endpush
