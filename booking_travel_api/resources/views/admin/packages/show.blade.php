@extends('layouts.dashboard')

@section('title', $package->title)
@section('page-title', $package->title)
@section('page-subtitle', 'Package Details and Management')

@section('content')
<div class="min-h-screen">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    <div class="ml-72 p-8">
        <!-- Action Bar -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.packages.index') }}" class="p-3 bg-white rounded-lg shadow-md hover:shadow-lg transition-all">
                    <i class="fas fa-arrow-left text-dark-600"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-dark-800">{{ $package->title }}</h1>
                    <div class="flex items-center space-x-2 mt-1">
                        <span class="badge-modern {{ $package->status === 'published' ? 'bg-emerald-100 text-emerald-800' : 
                            ($package->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ ucfirst($package->status) }}
                        </span>
                        @if($package->is_featured)
                            <span class="badge-modern bg-red-100 text-red-800">Featured</span>
                        @endif
                        @if($package->is_popular)
                            <span class="badge-modern bg-blue-100 text-blue-800">Popular</span>
                        @endif
                        @if(!$package->is_active)
                            <span class="badge-modern bg-gray-100 text-gray-800">Inactive</span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="flex items-center space-x-4">
                <a href="{{ route('packages.show', $package->slug) }}" target="_blank" 
                   class="px-4 py-2 bg-gray-100 text-dark-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-external-link-alt mr-2"></i> View Live
                </a>
                <a href="{{ route('admin.packages.edit', $package) }}" class="btn-modern">
                    <i class="fas fa-edit mr-2"></i> Edit Package
                </a>
                <div class="relative">
                    <button onclick="toggleDropdown()" class="p-3 bg-white rounded-lg shadow-md hover:shadow-lg transition-all">
                        <i class="fas fa-ellipsis-v text-dark-600"></i>
                    </button>
                    <div id="actions-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
                        <a href="{{ route('admin.packages.duplicate', $package) }}" class="block px-4 py-2 text-sm text-dark-700 hover:bg-gray-50">
                            <i class="fas fa-copy mr-2"></i> Duplicate
                        </a>
                        <a href="{{ route('admin.packages.analytics', $package) }}" class="block px-4 py-2 text-sm text-dark-700 hover:bg-gray-50">
                            <i class="fas fa-chart-line mr-2"></i> Analytics
                        </a>
                        <hr class="my-1">
                        <button onclick="deletePackage()" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                            <i class="fas fa-trash mr-2"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Package Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Main Image -->
            <div class="lg:col-span-2">
                <div class="card-modern overflow-hidden">
                    <img src="{{ $package->featured_image ? asset('storage/' . $package->featured_image) : '/placeholder.svg?height=400&width=800&text=No+Image' }}" 
                         alt="{{ $package->title }}" class="w-full h-96 object-cover">
                    
                    @if($package->gallery && count($package->gallery) > 0)
                    <div class="p-6">
                        <h4 class="font-semibold text-dark-800 mb-4">Gallery</h4>
                        <div class="grid grid-cols-4 gap-4">
                            @foreach(array_slice($package->gallery, 0, 8) as $image)
                            <img src="{{ asset('storage/' . $image) }}" alt="Gallery" 
                                 class="w-full h-20 object-cover rounded-lg cursor-pointer hover:opacity-80 transition-opacity"
                                 onclick="openImageModal('{{ asset('storage/' . $image) }}')">
                            @endforeach
                            @if(count($package->gallery) > 8)
                            <div class="w-full h-20 bg-gray-100 rounded-lg flex items-center justify-center text-gray-500 text-sm">
                                +{{ count($package->gallery) - 8 }} more
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Package Info -->
            <div class="space-y-6">
                <!-- Price Card -->
                <div class="card-modern p-6">
                    <div class="text-center">
                        <div class="flex items-center justify-center space-x-2 mb-2">
                            <span class="text-3xl font-bold text-primary-600">${{ number_format($package->price, 0) }}</span>
                            @if($package->original_price && $package->original_price > $package->price)
                                <span class="text-lg text-gray-500 line-through">${{ number_format($package->original_price, 0) }}</span>
                            @endif
                        </div>
                        <p class="text-dark-500">per person</p>
                        @if($package->discount_percentage > 0)
                            <div class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold mt-2 inline-block">
                                {{ $package->discount_percentage }}% OFF
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="card-modern p-6">
                    <h4 class="font-semibold text-dark-800 mb-4">Quick Stats</h4>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-dark-600">Duration</span>
                            <span class="font-medium text-dark-800">{{ $package->duration_days }} Days / {{ $package->duration_nights }} Nights</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-dark-600">Group Size</span>
                            <span class="font-medium text-dark-800">{{ $package->min_participants }}-{{ $package->max_participants }} people</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-dark-600">Difficulty</span>
                            <span class="badge-modern bg-{{ $package->difficulty_level === 'easy' ? 'green' : ($package->difficulty_level === 'moderate' ? 'yellow' : 'red') }}-100 text-{{ $package->difficulty_level === 'easy' ? 'green' : ($package->difficulty_level === 'moderate' ? 'yellow' : 'red') }}-800">
                                {{ ucfirst($package->difficulty_level) }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-dark-600">Rating</span>
                            <div class="flex items-center space-x-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star text-{{ $i <= $package->rating ? 'yellow' : 'gray' }}-400 text-sm"></i>
                                @endfor
                                <span class="text-sm font-medium text-dark-800 ml-1">{{ number_format($package->rating, 1) }}</span>
                                <span class="text-sm text-dark-500">({{ $package->total_reviews }})</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-dark-600">Total Bookings</span>
                            <span class="font-medium text-dark-800">{{ $package->total_bookings }}</span>
                        </div>
                    </div>
                </div>

                <!-- Category & Destination -->
                <div class="card-modern p-6">
                    <h4 class="font-semibold text-dark-800 mb-4">Category & Location</h4>
                    <div class="space-y-3">
                        <div>
                            <span class="text-dark-600 text-sm">Category</span>
                            <div class="mt-1">
                                <span class="bg-{{ $package->category->color ?? 'blue' }}-100 text-{{ $package->category->color ?? 'blue' }}-800 px-3 py-1 rounded-full text-sm font-semibold">
                                    {{ $package->category->name }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <span class="text-dark-600 text-sm">Destination</span>
                            <div class="flex items-center space-x-2 mt-1">
                                <span class="text-lg">{{ $package->destination->flag_emoji ?? '🌍' }}</span>
                                <div>
                                    <p class="font-medium text-dark-800">{{ $package->destination->name }}</p>
                                    <p class="text-sm text-dark-500">{{ $package->destination->country }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Package Details -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Description -->
            <div class="card-modern p-8">
                <h3 class="text-xl font-bold text-dark-800 mb-4">Description</h3>
                <div class="prose prose-gray max-w-none">
                    <p class="text-dark-600 leading-relaxed">{{ $package->description }}</p>
                </div>
            </div>

            <!-- Package Details -->
            <div class="card-modern p-8">
                <h3 class="text-xl font-bold text-dark-800 mb-4">Package Details</h3>
                <div class="space-y-4">
                    @if($package->accommodation_type)
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-bed text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-dark-800">Accommodation</p>
                            <p class="text-sm text-dark-500">{{ $package->accommodation_type }}</p>
                        </div>
                    </div>
                    @endif

                    @if($package->meal_plan)
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-utensils text-emerald-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-dark-800">Meals</p>
                            <p class="text-sm text-dark-500">{{ ucfirst(str_replace('-', ' ', $package->meal_plan)) }}</p>
                        </div>
                    </div>
                    @endif

                    @if($package->transportation)
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-car text-purple-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-dark-800">Transportation</p>
                            <p class="text-sm text-dark-500">{{ $package->transportation }}</p>
                        </div>
                    </div>
                    @endif

                    @if($package->advance_booking_days)
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-calendar text-orange-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-dark-800">Advance Booking</p>
                            <p class="text-sm text-dark-500">{{ $package->advance_booking_days }} days minimum</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Inclusions & Exclusions -->
        @if($package->inclusions || $package->exclusions)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            @if($package->inclusions)
            <div class="card-modern p-8">
                <h3 class="text-xl font-bold text-dark-800 mb-4">What's Included</h3>
                <ul class="space-y-2">
                    @foreach($package->inclusions as $inclusion)
                    <li class="flex items-center space-x-3">
                        <i class="fas fa-check text-emerald-600"></i>
                        <span class="text-dark-700">{{ $inclusion }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if($package->exclusions)
            <div class="card-modern p-8">
                <h3 class="text-xl font-bold text-dark-800 mb-4">What's Not Included</h3>
                <ul class="space-y-2">
                    @foreach($package->exclusions as $exclusion)
                    <li class="flex items-center space-x-3">
                        <i class="fas fa-times text-red-600"></i>
                        <span class="text-dark-700">{{ $exclusion }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
        @endif

        <!-- Highlights -->
        @if($package->highlights)
        <div class="card-modern p-8 mb-8">
            <h3 class="text-xl font-bold text-dark-800 mb-4">Package Highlights</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($package->highlights as $highlight)
                <div class="flex items-center space-x-3 p-4 bg-gradient-to-r from-primary-50 to-transparent rounded-lg">
                    <i class="fas fa-star text-primary-600"></i>
                    <span class="text-dark-700">{{ $highlight }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Amenities -->
        @if($package->amenities && $package->amenities->count() > 0)
        <div class="card-modern p-8 mb-8">
            <h3 class="text-xl font-bold text-dark-800 mb-4">Amenities</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($package->amenities as $amenity)
                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                    @if($amenity->icon)
                        <i class="{{ $amenity->icon }} text-primary-600"></i>
                    @endif
                    <span class="text-sm font-medium text-dark-700">{{ $amenity->name }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Itinerary -->
        @if($package->itinerary)
        <div class="card-modern p-8 mb-8">
            <h3 class="text-xl font-bold text-dark-800 mb-6">Itinerary</h3>
            <div class="space-y-6">
                @foreach($package->itinerary as $day)
                <div class="flex space-x-6">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-primary-600 rounded-full flex items-center justify-center text-white font-bold">
                            {{ $day['day'] }}
                        </div>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-lg font-semibold text-dark-800 mb-2">{{ $day['title'] }}</h4>
                        <p class="text-dark-600 mb-3">{{ $day['description'] }}</p>
                        @if(isset($day['meals']) && $day['meals'])
                        <div class="flex items-center space-x-2 text-sm text-dark-500">
                            <i class="fas fa-utensils"></i>
                            <span>Meals: {{ implode(', ', $day['meals']) }}</span>
                        </div>
                        @endif
                        @if(isset($day['accommodation']) && $day['accommodation'])
                        <div class="flex items-center space-x-2 text-sm text-dark-500 mt-1">
                            <i class="fas fa-bed"></i>
                            <span>Stay: {{ $day['accommodation'] }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Tags -->
        @if($package->tags)
        <div class="card-modern p-8 mb-8">
            <h3 class="text-xl font-bold text-dark-800 mb-4">Tags</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($package->tags as $tag)
                <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm font-medium">{{ $tag }}</span>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Reviews -->
        @if($package->reviews && $package->reviews->count() > 0)
        <div class="card-modern p-8 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-dark-800">Customer Reviews</h3>
                <a href="#" class="text-primary-600 hover:text-primary-700 font-medium">View All Reviews</a>
            </div>
            
            <div class="space-y-6">
                @foreach($package->reviews->take(3) as $review)
                <div class="border-b border-gray-200 pb-6 last:border-b-0">
                    <div class="flex items-start space-x-4">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name) }}&background=random&size=48" 
                             alt="{{ $review->user->name }}" class="w-12 h-12 rounded-full">
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <p class="font-semibold text-dark-800">{{ $review->user->name }}</p>
                                    <div class="flex items-center space-x-2">
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star text-{{ $i <= $review->rating ? 'yellow' : 'gray' }}-400 text-sm"></i>
                                            @endfor
                                        </div>
                                        <span class="text-sm text-dark-500">{{ $review->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                                @if($review->is_verified)
                                <span class="bg-emerald-100 text-emerald-800 px-2 py-1 rounded-full text-xs font-semibold">Verified</span>
                                @endif
                            </div>
                            @if($review->title)
                            <h4 class="font-medium text-dark-800 mb-2">{{ $review->title }}</h4>
                            @endif
                            <p class="text-dark-600">{{ $review->comment }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Availability & Booking Settings -->
        <div class="card-modern p-8">
            <h3 class="text-xl font-bold text-dark-800 mb-6">Availability & Settings</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-dark-700 mb-2">Available From</label>
                    <p class="text-dark-800">{{ $package->available_from ? $package->available_from->format('M d, Y') : 'Not set' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-700 mb-2">Available Until</label>
                    <p class="text-dark-800">{{ $package->available_until ? $package->available_until->format('M d, Y') : 'Not set' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-700 mb-2">Advance Booking</label>
                    <p class="text-dark-800">{{ $package->advance_booking_days }} days minimum</p>
                </div>
            </div>

            @if($package->cancellation_policy)
            <div class="mt-6">
                <label class="block text-sm font-medium text-dark-700 mb-2">Cancellation Policy</label>
                <p class="text-dark-600">{{ $package->cancellation_policy }}</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="image-modal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center">
    <div class="max-w-4xl max-h-full p-4">
        <img id="modal-image" src="/placeholder.svg" alt="" class="max-w-full max-h-full object-contain">
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>

@push('scripts')
<script>
function toggleDropdown() {
    const dropdown = document.getElementById('actions-dropdown');
    dropdown.classList.toggle('hidden');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('actions-dropdown');
    const button = event.target.closest('button');
    
    if (!button || !button.onclick || button.onclick.toString().indexOf('toggleDropdown') === -1) {
        dropdown.classList.add('hidden');
    }
});

function deletePackage() {
    if (confirm('Are you sure you want to delete this package? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.packages.destroy", $package) }}';
        form.innerHTML = `
            @csrf
            @method('DELETE')
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

function openImageModal(src) {
    document.getElementById('modal-image').src = src;
    document.getElementById('image-modal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('image-modal').classList.add('hidden');
}

// Close modal with escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeImageModal();
    }
});
</script>
@endpush
@endsection
