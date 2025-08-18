@extends('layouts.dashboard')

@section('title', 'Packages')
@section('page-title', 'Packages Management')
@section('page-subtitle', 'Manage your travel packages.')

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
                        <a href="{{ route('packages.provinces.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md transition-colors duration-300">
                            Add Province
                        </a>
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
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modalTitle">Add New Province</h3>
                        <div class="mt-2">
                            <form id="provinceForm" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="_method" id="formMethod" value="POST">
                                <input type="hidden" name="province_id" id="provinceId">
                                
                                <div class="space-y-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700">Province Name <span class="text-red-500">*</span></label>
                                        <input type="text" name="name" id="name" required
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>
                                    
                                    <div>
                                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                        <textarea name="description" id="description" rows="3"
                                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                                    </div>
                                    
                                    <div>
                                        <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                                        <input type="file" name="image" id="image" accept="image/*"
                                               class="mt-1 block w-full text-sm text-gray-500
                                                      file:mr-4 file:py-2 file:px-4
                                                      file:rounded-md file:border-0
                                                      file:text-sm file:font-semibold
                                                      file:bg-indigo-50 file:text-indigo-700
                                                      hover:file:bg-indigo-100">
                                        <p class="mt-1 text-xs text-gray-500">Upload an image (JPG, PNG, GIF) - Max 2MB</p>
                                        <div id="imagePreview" class="mt-2"></div>
                                    </div>
                                </div>
                                
                                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                                    <button type="button" onclick="closeModal()"
                                            class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-1 sm:text-sm">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                            class="mt-3 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-2 sm:text-sm">
                                        Save
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Province</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Are you sure you want to delete this province? This action cannot be undone.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Delete
                    </button>
                </form>
                <button type="button" onclick="closeModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

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

@push('scripts')
<script>
    let currentModal = null;
    
    function openModal(action, id = null) {
        const modal = document.getElementById('provinceModal');
        const form = document.getElementById('provinceForm');
        const title = document.getElementById('modalTitle');
        const methodInput = document.getElementById('formMethod');
        const provinceId = document.getElementById('provinceId');
        
        if (action === 'edit' && id) {
            // Set form action for editing
            form.action = `/admin/provinces/${id}`;
            methodInput.value = 'PUT';
            title.textContent = 'Edit Province';
            provinceId.value = id;
            
            // Here you would fetch the province data and fill the form
            // For example:
            // fetch(`/api/provinces/${id}`)
            //     .then(response => response.json())
            //     .then(data => {
            //         document.getElementById('name').value = data.name;
            //         document.getElementById('description').value = data.description || '';
            //         // Handle image preview if needed
            //     });
        } else {
            // Set form action for creating new
            form.action = '/admin/provinces';
            methodInput.value = 'POST';
            title.textContent = 'Add New Province';
            form.reset();
            document.getElementById('imagePreview').innerHTML = '';
        }
        
        currentModal = 'province';
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    
    function openDeleteModal(id) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        
        form.action = `/admin/provinces/${id}`;
        currentModal = 'delete';
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    
    function closeModal() {
        const modal = currentModal === 'delete' ? 
            document.getElementById('deleteModal') : 
            document.getElementById('provinceModal');
            
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        currentModal = null;
    }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        if (currentModal) {
            const modal = document.getElementById(currentModal + 'Modal');
            if (event.target === modal) {
                closeModal();
            }
        }
    }
    
    // Handle image preview
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('imagePreview');
                preview.innerHTML = `
                    <div class="mt-2">
                        <img src="${e.target.result}" alt="Preview" class="h-32 w-32 object-cover rounded">
                    </div>
                `;
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
@endsection