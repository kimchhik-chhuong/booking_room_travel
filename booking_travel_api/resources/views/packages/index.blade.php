@extends('layouts.app')

@section('title', 'Travel Packages')
@section('meta_description', 'Discover amazing travel packages and destinations. Book your perfect vacation with our curated selection of tours and experiences.')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-blue-600 to-purple-700 text-white py-20">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">Discover Amazing Destinations</h1>
                <p class="text-xl md:text-2xl mb-8 opacity-90">Find your perfect travel experience from our curated collection</p>
                
                <!-- Search Bar -->
                <div class="max-w-2xl mx-auto">
                    <form method="GET" action="{{ route('packages.index') }}" class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1 relative">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Search destinations, activities..." 
                                   class="w-full px-6 py-4 rounded-full text-gray-800 text-lg focus:outline-none focus:ring-4 focus:ring-white/30">
                            <i class="fas fa-search absolute right-6 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                        <button type="submit" class="px-8 py-4 bg-white text-blue-600 rounded-full font-semibold hover:bg-gray-100 transition-colors">
                            Search
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <form method="GET" action="{{ route('packages.index') }}" class="space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Filter Packages</h3>
                    <button type="button" onclick="toggleFilters()" class="md:hidden text-blue-600 font-medium">
                        <i class="fas fa-filter mr-2"></i> Filters
                    </button>
                </div>

                <div id="filters-content" class="hidden md:block">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        <!-- Category Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Categories</option>
                                @foreach($categories ?? [] as $category)
                                    <option value="{{ $category->slug }}" {{ request('category') === $category->slug ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Destination Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Destination</label>
                            <select name="destination" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Destinations</option>
                                @foreach($destinations ?? [] as $destination)
                                    <option value="{{ $destination->slug }}" {{ request('destination') === $destination->slug ? 'selected' : '' }}>
                                        {{ $destination->name }}, {{ $destination->country }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Max Price</label>
                            <select name="max_price" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Any Price</option>
                                <option value="1000" {{ request('max_price') === '1000' ? 'selected' : '' }}>Under $1,000</option>
                                <option value="2500" {{ request('max_price') === '2500' ? 'selected' : '' }}>Under $2,500</option>
                                <option value="5000" {{ request('max_price') === '5000' ? 'selected' : '' }}>Under $5,000</option>
                                <option value="10000" {{ request('max_price') === '10000' ? 'selected' : '' }}>Under $10,000</option>
                            </select>
                        </div>

                        <!-- Duration -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Duration</label>
                            <select name="duration" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Any Duration</option>
                                <option value="1-3" {{ request('duration') === '1-3' ? 'selected' : '' }}>1-3 Days</option>
                                <option value="4-7" {{ request('duration') === '4-7' ? 'selected' : '' }}>4-7 Days</option>
                                <option value="8-14" {{ request('duration') === '8-14' ? 'selected' : '' }}>1-2 Weeks</option>
                                <option value="15+" {{ request('duration') === '15+' ? 'selected' : '' }}>2+ Weeks</option>
                            </select>
                        </div>

                        <!-- Sort -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                            <select name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Latest</option>
                                <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>Highest Rated</option>
                                <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most Popular</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-6">
                        <div class="flex items-center space-x-4">
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="featured" value="1" class="rounded border-gray-300" {{ request('featured') ? 'checked' : '' }}>
                                <span class="text-sm text-gray-700">Featured Only</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="popular" value="1" class="rounded border-gray-300" {{ request('popular') ? 'checked' : '' }}>
                                <span class="text-sm text-gray-700">Popular Only</span>
                            </label>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('packages.index') }}" class="text-gray-500 hover:text-gray-700">Clear All</a>
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                Apply Filters
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Results Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    @if(request('search'))
                        Search Results for "{{ request('search') }}"
                    @else
                        All Packages
                    @endif
                </h2>
                <p class="text-gray-600 mt-1">
                    {{ $packages->total() ?? 0 }} packages found
                </p>
            </div>
            
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2">
                    <button onclick="setView('grid')" id="grid-view" class="p-2 rounded-lg bg-blue-600 text-white">
                        <i class="fas fa-th-large"></i>
                    </button>
                    <button onclick="setView('list')" id="list-view" class="p-2 rounded-lg text-gray-600 hover:bg-gray-100">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Packages Grid -->
        <div id="packages-container">
            @if(isset($packages) && $packages->count() > 0)
                <div id="packages-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($packages as $package)
                        @include('packages.partials.package-card', ['package' => $package])
                    @endforeach
                </div>

                <div id="packages-list" class="hidden space-y-6">
                    @foreach($packages as $package)
                        @include('packages.partials.package-list-item', ['package' => $package])
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12 flex justify-center">
                    {{ $packages->appends(request()->query())->links() }}
                </div>
            @else
                <!-- No Results -->
                <div class="text-center py-16">
                    <div class="max-w-md mx-auto">
                        <i class="fas fa-search text-6xl text-gray-300 mb-6"></i>
                        <h3 class="text-2xl font-semibold text-gray-800 mb-4">No packages found</h3>
                        <p class="text-gray-600 mb-8">
                            @if(request()->hasAny(['search', 'category', 'destination', 'max_price', 'duration']))
                                Try adjusting your filters or search terms to find what you're looking for.
                            @else
                                We're working on adding new packages. Check back soon!
                            @endif
                        </p>
                        @if(request()->hasAny(['search', 'category', 'destination', 'max_price', 'duration']))
                            <a href="{{ route('packages.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-times mr-2"></i> Clear Filters
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Newsletter Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-700 text-white py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h3 class="text-3xl font-bold mb-4">Stay Updated with New Packages</h3>
            <p class="text-xl mb-8 opacity-90">Get notified about exclusive deals and new destinations</p>
            <form class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
                <input type="email" placeholder="Enter your email" 
                       class="flex-1 px-6 py-3 rounded-full text-gray-800 focus:outline-none focus:ring-4 focus:ring-white/30">
                <button type="submit" class="px-8 py-3 bg-white text-blue-600 rounded-full font-semibold hover:bg-gray-100 transition-colors">
                    Subscribe
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleFilters() {
    const content = document.getElementById('filters-content');
    content.classList.toggle('hidden');
}

function setView(view) {
    const gridView = document.getElementById('packages-grid');
    const listView = document.getElementById('packages-list');
    const gridBtn = document.getElementById('grid-view');
    const listBtn = document.getElementById('list-view');

    if (view === 'grid') {
        gridView.classList.remove('hidden');
        listView.classList.add('hidden');
        gridBtn.classList.add('bg-blue-600', 'text-white');
        gridBtn.classList.remove('text-gray-600', 'hover:bg-gray-100');
        listBtn.classList.remove('bg-blue-600', 'text-white');
        listBtn.classList.add('text-gray-600', 'hover:bg-gray-100');
    } else {
        gridView.classList.add('hidden');
        listView.classList.remove('hidden');
        listBtn.classList.add('bg-blue-600', 'text-white');
        listBtn.classList.remove('text-gray-600', 'hover:bg-gray-100');
        gridBtn.classList.remove('bg-blue-600', 'text-white');
        gridBtn.classList.add('text-gray-600', 'hover:bg-gray-100');
    }
    
    localStorage.setItem('packages-view', view);
}

// Restore view preference
document.addEventListener('DOMContentLoaded', function() {
    const savedView = localStorage.getItem('packages-view') || 'grid';
    setView(savedView);
});
</script>
@endpush
@endsection
