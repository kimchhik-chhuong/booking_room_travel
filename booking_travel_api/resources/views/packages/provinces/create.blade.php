@extends('layouts.dashboard')

@section('title', 'Add New Province')
@section('page-title', 'Add New Province')
@section('page-subtitle', 'Create a new province for your travel packages')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Add New Province</h2>
            
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('packages.provinces.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Province Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                           required>
                </div>

                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('description') }}</textarea>
                </div>

                <div class="mb-6">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Province Image</label>
                    <div class="mt-1 flex items-center">
                        <input type="file" name="image" id="image" 
                               class="block w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-md file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-indigo-50 file:text-indigo-700
                                      hover:file:bg-indigo-100">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Upload an image (JPG, PNG, GIF) - Max 2MB</p>
                </div>

                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('packages.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Create Province
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Image preview functionality
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('image-preview');
                if (!preview) {
                    const previewDiv = document.createElement('div');
                    previewDiv.id = 'image-preview';
                    previewDiv.className = 'mt-2';
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'h-32 w-auto object-cover rounded';
                    previewDiv.appendChild(img);
                    document.getElementById('image').parentNode.appendChild(previewDiv);
                } else {
                    preview.querySelector('img').src = e.target.result;
                }
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
@endsection
