@extends('layouts.dashboard')
@section('title', 'Packages Dashboard')
@section('page-title', 'Packages Dashboard')
@section('page-subtitle', 'Explore hotels and adventures in Cambodia')

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
                    <!-- Page header -->
                    <div class="mb-8">
                        <h1 class="text-2xl font-semibold text-gray-900">Explore Provinces</h1>
                        <p class="mt-1 text-sm text-gray-600">Discover amazing destinations across Cambodia</p>
                    </div>
                    <div class="flex items-center justify-end">
                        <button onclick="openModal('create')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md transition-colors duration-300">
                            Add Province
                        </button>
                    </div>

                    <!-- Provinces Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mt-6">
                        @forelse($provinces as $province)
                        <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 province-card">
                            <a href="{{ route('packages.province', $province->id) }}" class="block">
                                @if($province->image_url)
                                <div class="h-48 overflow-hidden">
                                    <img src="{{ $province->image_url }}" 
                                         alt="{{ $province->name }}" 
                                         class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                                </div>
                                @else
                                <div class="h-48 bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400">No image available</span>
                                </div>
                                @endif
                                <div class="p-4">
                                    <h3 class="text-lg font-medium text-gray-900 group-hover:text-indigo-600">{{ $province->name }}</h3>
                                    <div class="mt-2 flex justify-between items-center">
                                        <span class="text-sm text-gray-500">
                                            {{ $province->hotels_count ?? 0 }} {{ Str::plural('Hotel', $province->hotels_count ?? 0) }}
                                        </span>
                                        <span class="text-sm text-gray-500">
                                            {{ $province->adventures_count ?? 0 }} {{ Str::plural('Adventure', $province->adventures_count ?? 0) }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                            <div class="px-4 pb-4 flex justify-end space-x-2">
                                <button onclick="openModal('edit', {{ $province->id }})" 
                                        class="text-indigo-600 hover:text-indigo-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button onclick="openDeleteModal({{ $province->id }})" 
                                        class="text-red-600 hover:text-red-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-full text-center py-12">
                            <p class="text-gray-500">No provinces available at the moment.</p>
                        </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($provinces->hasPages())
                    <div class="mt-8">
                        {{ $provinces->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Create/Edit Modal -->
<div id="provinceModal" class="fixed inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="provinceForm" action="{{ route('packages.provinces.store') }}" method="POST" enctype="multipart/form-data" class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                @csrf
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Add New Province
                        </h3>
                        <div class="mt-5">
                            <div id="formErrors" class="hidden bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">
                                <ul id="errorList" class="list-disc pl-5 space-y-1">
                                    <!-- Errors will be inserted here by JavaScript -->
                                </ul>
                            </div>
                            
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Province Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                       required>
                                <div id="name-error" class="mt-1 text-sm text-red-500"></div>
                            </div>

                            <div class="mb-4">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                    Description
                                </label>
                                <textarea name="description" id="description" rows="3"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('description') }}</textarea>
                                <div id="description-error" class="mt-1 text-sm text-red-500"></div>
                            </div>

                            <div class="mb-4">
                                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">
                                    Province Image
                                </label>
                                <div class="mt-1 flex items-center">
                                    <input type="file" name="image" id="image" 
                                           class="block w-full text-sm text-gray-500
                                                  file:mr-4 file:py-2 file:px-4
                                                  file:rounded-md file:border-0
                                                  file:text-sm file:font-medium
                                                  file:bg-indigo-50 file:text-indigo-700
                                                  hover:file:bg-indigo-100">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Upload an image (JPG, PNG, GIF) - Max 2MB</p>
                                <div id="image-error" class="mt-1 text-sm text-red-500"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Save Province
                    </button>
                    <button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 overflow-y-auto hidden" aria-labelledby="deleteModalTitle" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeDeleteModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="deleteModalTitle">
                            Delete Province
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Are you sure you want to delete this province? This action cannot be undone.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <form id="deleteForm" method="POST" class="inline-flex w-full sm:ml-3 sm:w-auto">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Delete
                    </button>
                </form>
                <button type="button" onclick="closeDeleteModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentModal = null;
    
    function openModal(action, id = null) {
        const modal = document.getElementById('provinceModal');
        const form = document.getElementById('provinceForm');
        
        // Reset form and clear any previous errors
        form.reset();
        const errorMessages = document.querySelectorAll('.text-red-500');
        errorMessages.forEach(el => el.remove());
        
        // Set form action based on action type
        if (action === 'edit' && id) {
            // For edit, we'll handle this later
            form.action = `/packages/provinces/${id}`;
            form.insertAdjacentHTML('beforeend', '<input type="hidden" name="_method" value="PUT">');
            document.querySelector('#modal-title').textContent = 'Edit Province';
            // Here you would fetch the province data and populate the form
        } else {
            // For create
            form.action = '{{ route('packages.provinces.store') }}';
            const methodInput = form.querySelector('input[name="_method"]');
            if (methodInput) methodInput.remove();
            document.querySelector('#modal-title').textContent = 'Add New Province';
        }
        
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        currentModal = 'province';
    }
    
    function closeModal() {
        const modal = document.getElementById('provinceModal');
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        currentModal = null;
    }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target.classList.contains('bg-gray-500')) {
            closeModal();
        }
    };
    
    // Handle form submission
    document.getElementById('provinceForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);
        
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();
            
            if (response.ok) {
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    window.location.reload();
                }
            } else {
                // Handle validation errors
                const errorList = document.getElementById('errorList');
                const formErrors = document.getElementById('formErrors');
                
                // Clear previous errors
                errorList.innerHTML = '';
                document.querySelectorAll('[id$="-error"]').forEach(el => el.textContent = '');
                
                if (data.errors) {
                    // Add errors to the error list
                    Object.entries(data.errors).forEach(([field, messages]) => {
                        const errorElement = document.createElement('li');
                        errorElement.textContent = messages[0];
                        errorList.appendChild(errorElement);
                        
                        // Add error message under the specific field
                        const fieldError = document.getElementById(`${field}-error`);
                        if (fieldError) {
                            fieldError.textContent = messages[0];
                        }
                    });
                    
                    formErrors.classList.remove('hidden');
                } else if (data.message) {
                    // Handle other types of errors
                    const errorElement = document.createElement('li');
                    errorElement.textContent = data.message;
                    errorList.appendChild(errorElement);
                    formErrors.classList.remove('hidden');
                }
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        }
    });

    function openDeleteModal(id) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        
        // Set the form action to delete the specific province
        form.action = `/packages/provinces/${id}`;
        
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    
    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
    
    // Handle delete form submission
    document.getElementById('deleteForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ _method: 'DELETE' })
        })
        .then(response => response.json())
        .then(data => {
            if (data.redirect) {
                window.location.href = data.redirect;
            } else if (data.success) {
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the province.');
        });
    });
</script>
@endpush

@push('styles')
<style>
    /* Custom styles for the province cards */
    .province-card {
        transition: all 0.3s ease;
    }
    .province-card:hover {
        transform: translateY(-5px);
    }
    
    /* Modal styles */
    .modal {
        transition: opacity 0.25s ease;
    }
    
    /* Animation for modal */
    @keyframes modalFadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .modal-content {
        animation: modalFadeIn 0.3s ease-out;
    }
</style>
@endpush
@endsection