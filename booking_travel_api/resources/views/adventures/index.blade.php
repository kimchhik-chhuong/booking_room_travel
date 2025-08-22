@extends('layouts.dashboard')

@section('title', 'Adventures - ' . config('app.name'))
@section('page-title', 'Adventures in Cambodia')
@section('page-subtitle', 'Discover amazing adventures across Cambodia')

@section('content')
<div class="min-h-screen">
    @include('partials.sidebar')
    @include('partials.header')
    
    <div class="md:pl-64 flex flex-col">
        <main class="flex-1">
            <!-- Hero Section -->
            <div class="bg-indigo-700">
                <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8 lg:flex lg:justify-between">
                    <div class="max-w-xl">
                        <h1 class="text-4xl font-extrabold text-white sm:text-5xl sm:tracking-tight lg:text-6xl">
                            Adventures in Cambodia
                        </h1>
                        <p class="mt-5 text-xl text-indigo-100">
                            Discover amazing adventures across all provinces of Cambodia.
                        </p>
                    </div>
                    <div class="flex items-center justify-end">
                        <a href="{{ route('packages.index') }}" class="text-white hover:text-indigo-100 text-sm font-medium">
                            View Tour Packages <span aria-hidden="true">→</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Search and Filter -->
            <div class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <form action="{{ route('adventures.index') }}" method="GET" class="space-y-4 sm:space-y-0 sm:flex sm:items-center sm:space-x-4">
                        <!-- Search Input -->
                        <div class="flex-1">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                                    placeholder="Search adventures...">
                            </div>
                        </div>
                        
                        <!-- Province Filter -->
                        <div class="w-full sm:w-64">
                            <select name="province_id" id="province_id" 
                                class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">All Provinces</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->id }}" {{ request('province_id') == $province->id ? 'selected' : '' }}>
                                        {{ $province->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Status Filter -->
                        <div class="w-full sm:w-40">
                            <select name="status" id="status" 
                                class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex space-x-2">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Apply Filters
                            </button>
                            @if(request('search') || request('status') || request('province_id'))
                                <a href="{{ route('adventures.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Adventures List -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                @if($adventures->count() > 0)
                    <div class="grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-3 xl:gap-x-8">
                        @foreach($adventures as $adventure)
                            <div class="group relative bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                                <div class="aspect-w-3 aspect-h-2 bg-gray-200 group-hover:opacity-75">
                                    @if($adventure->image_url)
                                        <img src="{{ Storage::url($adventure->image_url) }}" alt="{{ $adventure->name }}" class="w-full h-48 object-cover">
                                    @else
                                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                            <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="absolute top-2 right-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $adventure->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($adventure->status) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="p-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900">
                                                <a href="{{ route('adventures.show', $adventure) }}" class="hover:text-indigo-600 transition-colors">
                                                    {{ $adventure->name }}
                                                </a>
                                            </h3>
                                            <p class="mt-1 text-sm text-gray-500">
                                                {{ $adventure->province->name }}
                                            </p>
                                        </div>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-500 line-clamp-2">
                                        {{ $adventure->description }}
                                    </p>
                                    <div class="mt-4 flex items-center justify-between">
                                        <span class="text-sm text-gray-500">
                                            Created {{ $adventure->created_at->diffForHumans() }}
                                        </span>
                                        <a href="{{ route('adventures.show', $adventure) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                            View details <span aria-hidden="true">→</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($adventures->hasPages())
                        <div class="mt-8">
                            {{ $adventures->withQueryString()->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No adventures found</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            @if(request('search') || request('status') || request('province_id'))
                                Try adjusting your search or filter criteria.
                            @else
                                Get started by creating a new adventure.
                            @endif
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('adventures.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 01-1 1h-3a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                New Adventure
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </main>
    </div>
</div>
@endsection
