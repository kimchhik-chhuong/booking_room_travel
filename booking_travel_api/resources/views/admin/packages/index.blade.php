@extends('layouts.dashboard')

@section('title', 'Packages Management')
@section('page-title', 'Packages Management')
@section('page-subtitle', 'Manage your travel packages and create new offerings.')

@section('content')
<div class="min-h-screen">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    <div class="ml-72 p-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Total Packages</p>
                        <p class="text-3xl font-bold text-dark-800">{{ $stats['total'] ?? 47 }}</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+3 this month</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-box text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Published</p>
                        <p class="text-3xl font-bold text-dark-800">{{ $stats['published'] ?? 42 }}</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">89% published</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Avg. Rating</p>
                        <p class="text-3xl font-bold text-dark-800">{{ number_format($stats['average_rating'] ?? 4.8, 1) }}</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+0.2 this month</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-star text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Total Revenue</p>
                        <p class="text-3xl font-bold text-dark-800">${{ number_format($stats['total_revenue'] ?? 234000) }}</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+18% this month</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-white text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Actions -->
        <div class="card-modern p-8 mb-8">
            <form method="GET" action="{{ route('admin.packages.index') }}" class="space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-dark-800">Filter Packages</h3>
                    <div class="flex items-center space-x-4">
                        <button type="button" id="bulk-actions-btn" class="btn-modern bg-gray-600 hover:bg-gray-700 hidden">
                            <i class="fas fa-tasks mr-2"></i> Bulk Actions
                        </button>
                        <a href="{{ route('admin.packages.create') }}" class="btn-modern">
                            <i class="fas fa-plus mr-2"></i> Add Package
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-dark-700 mb-2">Search</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Search packages..." class="input-modern pl-10 w-full">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-400"></i>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-dark-700 mb-2">Status</label>
                        <select name="status" class="input-modern w-full">
                            <option value="">All Status</option>
                            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-dark-700 mb-2">Category</label>
                        <select name="category_id" class="input-modern w-full">
                            <option value="">All Categories</option>
                            @foreach($categories ?? [] as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-dark-700 mb-2">Destination</label>
                        <select name="destination_id" class="input-modern w-full">
                            <option value="">All Destinations</option>
                            @foreach($destinations ?? [] as $destination)
                                <option value="{{ $destination->id }}" {{ request('destination_id') == $destination->id ? 'selected' : '' }}>
                                    {{ $destination->name }}, {{ $destination->country }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end space-x-2">
                        <button type="submit" class="btn-modern flex-1">
                            <i class="fas fa-filter mr-2"></i> Filter
                        </button>
                        <a href="{{ route('admin.packages.index') }}" class="px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Packages Table -->
        <div class="card-modern overflow-hidden">
            <div class="p-8 border-b border-slate-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-dark-800 mb-2">All Packages</h3>
                        <p class="text-dark-500">Manage your travel packages</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <select id="per-page" class="input-modern text-sm">
                            <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15 per page</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 per page</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 per page</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left">
                                <input type="checkbox" id="select-all" class="rounded border-gray-300">
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-dark-600 uppercase tracking-wider">
                                Package
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-dark-600 uppercase tracking-wider">
                                Category
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-dark-600 uppercase tracking-wider">
                                Destination
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-dark-600 uppercase tracking-wider">
                                Price
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-dark-600 uppercase tracking-wider">
                                Duration
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-dark-600 uppercase tracking-wider">
                                Rating
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-dark-600 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-dark-600 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @forelse($packages ?? [] as $package)
                        <tr class="table-row transition-all duration-200 hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <input type="checkbox" name="selected_packages[]" value="{{ $package->id }}" class="package-checkbox rounded border-gray-300">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-4">
                                    <img src="{{ $package->featured_image ? asset('storage/' . $package->featured_image) : '/placeholder.svg?height=60&width=80&text=No+Image' }}" 
                                         alt="{{ $package->title }}" class="w-20 h-15 rounded-lg object-cover shadow-md">
                                    <div>
                                        <p class="font-semibold text-dark-800">{{ $package->title }}</p>
                                        <p class="text-sm text-dark-500">{{ Str::limit($package->short_description, 50) }}</p>
                                        <div class="flex items-center space-x-2 mt-1">
                                            @if($package->is_featured)
                                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-semibold">Featured</span>
                                            @endif
                                            @if($package->is_popular)
                                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-semibold">Popular</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-{{ $package->category->color ?? 'blue' }}-100 text-{{ $package->category->color ?? 'blue' }}-800 px-3 py-1 rounded-full text-sm font-semibold">
                                    {{ $package->category->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <span class="text-lg">{{ $package->destination->flag_emoji ?? '🌍' }}</span>
                                    <div>
                                        <p class="font-medium text-dark-800">{{ $package->destination->name ?? 'N/A' }}</p>
                                        <p class="text-sm text-dark-500">{{ $package->destination->country ?? '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-bold text-primary-600 text-lg">${{ number_format($package->price, 0) }}</p>
                                    @if($package->original_price && $package->original_price > $package->price)
                                        <p class="text-sm text-gray-500 line-through">${{ number_format($package->original_price, 0) }}</p>
                                        <p class="text-xs text-emerald-600 font-semibold">{{ $package->discount_percentage }}% OFF</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-dark-700">{{ $package->duration_days }} Days</p>
                                <p class="text-sm text-dark-500">{{ $package->duration_nights }} Nights</p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star text-{{ $i <= $package->rating ? 'yellow' : 'gray' }}-400 text-sm"></i>
                                        @endfor
                                    </div>
                                    <span class="text-sm font-semibold text-dark-800">{{ number_format($package->rating, 1) }}</span>
                                    <span class="text-sm text-dark-500">({{ $package->total_reviews }})</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="badge-modern {{ $package->status === 'published' ? 'bg-emerald-100 text-emerald-800' : 
                                    ($package->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($package->status) }}
                                </span>
                                @if(!$package->is_active)
                                    <span class="badge-modern bg-red-100 text-red-800 ml-1">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.packages.show', $package) }}" 
                                       class="p-2 text-dark-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.packages.edit', $package) }}" 
                                       class="p-2 text-dark-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="duplicatePackage({{ $package->id }})" 
                                            class="p-2 text-dark-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                    <button onclick="deletePackage({{ $package->id }})" 
                                            class="p-2 text-dark-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-box-open text-gray-400 text-4xl mb-4"></i>
                                    <p class="text-gray-500 text-lg">No packages found</p>
                                    <p class="text-gray-400 text-sm mb-4">Create your first package to get started</p>
                                    <a href="{{ route('admin.packages.create') }}" class="btn-modern">
                                        <i class="fas fa-plus mr-2"></i> Add Package
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(isset($packages) && $packages->hasPages())
            <div class="px-8 py-6 flex items-center justify-between border-t border-slate-200">
                <div class="flex items-center space-x-2 text-dark-600">
                    <span>Showing {{ $packages->firstItem() }} to {{ $packages->lastItem() }} of {{ $packages->total() }} packages</span>
                </div>
                <div class="flex items-center space-x-2">
                    {{ $packages->appends(request()->query())->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Bulk Actions Modal -->
<div id="bulk-actions-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full">
            <h3 class="text-xl font-bold text-dark-800 mb-4">Bulk Actions</h3>
            <form id="bulk-actions-form" method="POST" action="{{ route('admin.packages.bulk-action') }}">
                @csrf
                <input type="hidden" name="ids" id="selected-ids">
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-dark-700 mb-2">Action</label>
                    <select name="action" class="input-modern w-full" required>
                        <option value="">Select Action</option>
                        <option value="publish">Publish</option>
                        <option value="draft">Move to Draft</option>
                        <option value="activate">Activate</option>
                        <option value="deactivate">Deactivate</option>
                        <option value="feature">Mark as Featured</option>
                        <option value="unfeature">Remove Featured</option>
                        <option value="delete">Delete</option>
                    </select>
                </div>

                <div class="flex items-center space-x-4">
                    <button type="submit" class="btn-modern flex-1">Apply Action</button>
                    <button type="button" onclick="closeBulkModal()" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Bulk actions functionality
document.getElementById('select-all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.package-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    toggleBulkActions();
});

document.querySelectorAll('.package-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', toggleBulkActions);
});

function toggleBulkActions() {
    const checkedBoxes = document.querySelectorAll('.package-checkbox:checked');
    const bulkBtn = document.getElementById('bulk-actions-btn');
    
    if (checkedBoxes.length > 0) {
        bulkBtn.classList.remove('hidden');
    } else {
        bulkBtn.classList.add('hidden');
    }
}

document.getElementById('bulk-actions-btn').addEventListener('click', function() {
    const checkedBoxes = document.querySelectorAll('.package-checkbox:checked');
    const ids = Array.from(checkedBoxes).map(cb => cb.value);
    
    document.getElementById('selected-ids').value = ids.join(',');
    document.getElementById('bulk-actions-modal').classList.remove('hidden');
});

function closeBulkModal() {
    document.getElementById('bulk-actions-modal').classList.add('hidden');
}

// Per page change
document.getElementById('per-page').addEventListener('change', function() {
    const url = new URL(window.location);
    url.searchParams.set('per_page', this.value);
    window.location = url;
});

// Delete package
function deletePackage(id) {
    if (confirm('Are you sure you want to delete this package?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/packages/${id}`;
        form.innerHTML = `
            @csrf
            @method('DELETE')
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

// Duplicate package
function duplicatePackage(id) {
    if (confirm('Are you sure you want to duplicate this package?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/packages/${id}/duplicate`;
        form.innerHTML = `@csrf`;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
@endsection
