@extends('layouts.dashboard')

@section('title', 'Hotels - ' . config('app.name'))
@section('page-title', 'Hotels in Cambodia')
@section('page-subtitle', 'Discover amazing places to stay across Cambodia')

@section('content')
<div class="min-h-screen">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    <div class="md:pl-64 flex flex-col">
        <main class="flex-1">
            <!-- Hero Section -->
            <div class="bg-indigo-700">
                <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8 lg:flex lg:justify-between">
                    <div class="max-w-xl">
                        <h1 class="text-4xl font-extrabold text-white sm:text-5xl sm:tracking-tight lg:text-6xl">
                            Hotels in Cambodia
                        </h1>
                        <p class="mt-5 text-xl text-indigo-100">
                            Discover amazing hotels across all provinces of Cambodia.
                        </p>
                    </div>
                    <div class="flex items-center justify-end">
                        <a href="{{ route('packages.index') }}" class="text-white hover:text-indigo-100 text-sm font-medium">
                            Back to Packages
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
                        <div class="w-full sm:w-1/3">
                            <label for="province" class="block text-sm font-medium text-gray-700">Filter by Province</label>
                            <select id="province" name="province" 
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">All Provinces</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->id }}" {{ request('province_id') == $province->id ? 'selected' : '' }}>
                                        {{ $province->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full sm:w-1/3">
                            <label for="sort" class="block text-sm font-medium text-gray-700">Sort By</label>
                            <select id="sort" name="sort" 
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="name_asc">Name (A-Z)</option>
                                <option value="name_desc">Name (Z-A)</option>
                                <option value="price_asc">Price: Low to High</option>
                                <option value="price_desc">Price: High to Low</option>
                                <option value="rating">Highest Rated</option>
                            </select>
                        </div>
                        <div class="w-full sm:w-1/3 flex items-end">
                            <a href="{{ route('hotels.create', request('province_id') ? ['province_id' => request('province_id')] : []) }}" 
                               class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Add New Hotel
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hotels Grid -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                @if($hotels->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No hotels found</h3>
                        <p class="mt-1 text-sm text-gray-500">There are no hotels matching your filters.</p>
                        <div class="mt-6">
                            <a href="{{ route('hotels.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Clear filters
                            </a>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-3 xl:gap-x-8">
                        @foreach($hotels as $hotel)
                            <div class="group relative bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                                <div class="aspect-w-3 aspect-h-2 bg-gray-200 group-hover:opacity-75">
                                    @if($hotel->image_url)
                                        <img src="{{ asset('storage/' . $hotel->image_url) }}" alt="{{ $hotel->name }}" class="w-full h-48 object-cover">
                                    @else
                                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                            <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="absolute top-2 right-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            {{ $hotel->star_rating ? $hotel->star_rating . '★' : 'N/A' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="p-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900">
                                                <a href="{{ route('hotels.show', $hotel->hotel_id) }}">
                                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                                    {{ $hotel->name }}
                                                </a>
                                            </h3>
                                            <p class="mt-1 text-sm text-gray-500">
                                                {{ $hotel->province->name ?? 'Cambodia' }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-medium text-gray-900">
                                                ${{ number_format($hotel->roomTypes->min('price') ?? 0, 2) }}
                                                <span class="text-xs text-gray-500">/night</span>
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $hotel->roomTypes->count() }} room types
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <p class="text-sm text-gray-600 line-clamp-2">
                                            {{ $hotel->description ?? 'No description available.' }}
                                        </p>
                                    </div>
                                    <div class="mt-4 flex items-center justify-between">
                                        <div class="flex items-center">
                                            @if($hotel->contact_phone)
                                                <a href="tel:{{ $hotel->contact_phone }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                                    <svg class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                    </svg>
                                                    Call
                                                </a>
                                            @endif
                                            @if($hotel->website_url)
                                                <a href="{{ $hotel->website_url }}" target="_blank" class="ml-4 text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                                    <svg class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                                    </svg>
                                                    Website
                                                </a>
                                            @endif
                                        </div>
                                        <a href="{{ route('hotels.show', $hotel->hotel_id) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                            View details <span aria-hidden="true">&rarr;</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    @if($hotels->hasPages())
                        <div class="mt-8">
                            {{ $hotels->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </main>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Province filter
        const provinceSelect = document.getElementById('province');
        if (provinceSelect) {
            provinceSelect.addEventListener('change', function() {
                const provinceId = this.value;
                const url = new URL(window.location.href);
                
                if (provinceId) {
                    url.searchParams.set('province_id', provinceId);
                } else {
                    url.searchParams.delete('province_id');
                }
                
                window.location.href = url.toString();
            });
        }

        // Sort by
        const sortSelect = document.getElementById('sort');
        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                const sortValue = this.value;
                const url = new URL(window.location.href);
                
                if (sortValue) {
                    url.searchParams.set('sort', sortValue);
                } else {
                    url.searchParams.delete('sort');
                }
                
                window.location.href = url.toString();
            });
        }
    });
</script>
@endpush

@endsection
