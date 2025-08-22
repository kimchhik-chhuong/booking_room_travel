@extends('layouts.dashboard')

@section('title', $adventure->name . ' - ' . config('app.name'))
@section('page-title', $adventure->name)

@section('content')
<div class="min-h-screen">
    @include('partials.sidebar')
    @include('partials.header')
    
    <div class="md:pl-64 flex flex-col">
        <main class="flex-1">
            <!-- Back Button -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <a href="{{ route('adventures.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Adventures
                </a>
            </div>

            <!-- Main Content -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Adventure Header -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                    <div class="px-4 py-5 sm:px-6 flex justify-between items-center border-b border-gray-200">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $adventure->name }}</h1>
                            <div class="mt-1 flex items-center text-sm text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                                {{ $adventure->province->name }}
                                <span class="mx-2">•</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $adventure->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($adventure->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('adventures.edit', $adventure) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                                Edit
                            </a>
                            <form action="{{ route('adventures.destroy', $adventure) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this adventure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Adventure Content -->
                    <div class="px-4 py-5 sm:p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <!-- Main Content -->
                            <div class="lg:col-span-2">
                                <!-- Image Gallery -->
                                <div class="mb-6 rounded-lg overflow-hidden bg-gray-100">
                                    <img src="{{ $adventure->image_path }}" alt="{{ $adventure->name }}" class="w-full h-96 object-cover">
                                </div>

                                <!-- Description -->
                                <div class="prose max-w-none">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">About this adventure</h3>
                                    <div class="text-gray-600">
                                        {!! nl2br(e($adventure->description ?? 'No description available.')) !!}
                                    </div>
                                </div>
                            </div>

                            <!-- Sidebar -->
                            <div class="lg:col-span-1">
                                <div class="bg-gray-50 rounded-lg p-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Adventure Details</h3>
                                    
                                    <div class="space-y-4">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                                            <dd class="mt-1 text-sm text-gray-900">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $adventure->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ ucfirst($adventure->status) }}
                                                </span>
                                            </dd>
                                        </div>
                                        
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Location</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $adventure->province->name }}</dd>
                                        </div>
                                        
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Created</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $adventure->created_at->format('M d, Y') }}</dd>
                                        </div>
                                        
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $adventure->updated_at->format('M d, Y') }}</dd>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
