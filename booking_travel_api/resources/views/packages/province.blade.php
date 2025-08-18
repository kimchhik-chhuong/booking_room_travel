@extends('layouts.dashboard')

@section('title', $province->name . ' - Packages')
@section('page-title', $province->name)
@section('page-subtitle', 'Explore hotels and adventures in ' . $province->name)

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
                            {{ $province->name }}
                        </h1>
                        <p class="mt-5 text-xl text-indigo-100">
                            Discover amazing hotels and adventures in {{ $province->name }}, Cambodia.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Hotels Section -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900">Hotels in {{ $province->name }}</h2>
                    <p class="mt-1 text-sm text-gray-500">Find the perfect place to stay during your visit</p>
                </div>

                @if($province->hotels->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($province->hotels as $hotel)
                    <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
                        @if($hotel->image_url)
                        <div class="h-48 overflow-hidden">
                            <img src="{{ $hotel->image_url }}"
                                 alt="{{ $hotel->name }}" 
                                 class="w-full h-full object-cover">
                        </div>
                        @else
                        <div class="h-48 overflow-hidden">
                            <img src="{{ $province->image_url }}"
                                 alt="{{ $province->name }}" 
                                 class="w-full h-full object-cover">
                        </div>
                        @endif
                        <div class="p-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ $hotel->name }}</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 line-clamp-2">{{ $hotel->description }}</p>
                            </div>
                            <div class="mt-4">
                                <a href="#" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                    View details →
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12">
                    <p class="text-gray-500">No hotels available in {{ $province->name }} yet.</p>
                </div>
                @endif
            </div>

            <!-- Adventures Section -->
            <div class="bg-gray-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900">Adventures in {{ $province->name }}</h2>
                        <p class="mt-1 text-sm text-gray-500">Exciting activities and experiences waiting for you</p>
                    </div>

                    @if($province->adventures->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($province->adventures as $adventure)
                        <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
                            @if($adventure->image_url)
                            <div class="h-48 overflow-hidden">
                                <img src="{{ $adventure->image_url }}" 
                                     alt="{{ $adventure->name }}" 
                                     class="w-full h-full object-cover">
                            </div>
                            @endif
                            <div class="p-4">
                                <h3 class="text-lg font-medium text-gray-900">{{ $adventure->name }}</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 line-clamp-2">{{ $adventure->description }}</p>
                                </div>
                                <div class="mt-4">
                                    <a href="#" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                        View details →
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-12">
                        <p class="text-gray-500">No adventures available in {{ $province->name }} yet.</p>
                    </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
