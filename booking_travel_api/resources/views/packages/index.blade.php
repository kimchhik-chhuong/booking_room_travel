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

                    <!-- Provinces Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @forelse($provinces as $province)
                        <a href="{{ route('packages.province', $province->id) }}" class="group">
                            <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
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
                            </div>
                        </a>
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
@endsection

@push('styles')
<style>
    /* Custom styles for the province cards */
    .province-card {
        transition: all 0.3s ease;
    }
    .province-card:hover {
        transform: translateY(-5px);
    }
</style>
@endpush