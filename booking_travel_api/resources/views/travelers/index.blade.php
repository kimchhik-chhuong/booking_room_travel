@extends('layouts.dashboard')

@section('title', 'Travelers')
@section('page-title', 'Travelers Management')
@section('page-subtitle', 'Manage your customer profiles and travel history.')

@section('content')
<div class="min-h-screen">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    <div class="ml-72 pt-32 p-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm font-medium mb-2">Total Travelers</p>
                        <p class="text-3xl font-bold text-slate-800">{{ $totalTravelers }}</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+{{ $growthRate }}% this month</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm font-medium mb-2">Active Travelers</p>
                        <p class="text-3xl font-bold text-slate-800">{{ $activeTravelers }}</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+{{ $activeGrowthRate }}% this month</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-user-check text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm font-medium mb-2">New This Month</p>
                        <p class="text-3xl font-bold text-slate-800">{{ $newThisMonth }}</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+{{ $newGrowthRate }}% growth</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-amber-400 to-amber-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-user-plus text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm font-medium mb-2">Avg. Bookings</p>
                        <p class="text-3xl font-bold text-slate-800">{{ number_format($avgBookings, 1) }}</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">per traveler</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-calendar-check text-white text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Travelers Table -->
        <div class="card-modern overflow-hidden">
            <div class="p-8 border-b border-slate-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-slate-800 mb-2">All Travelers</h3>
                        <p class="text-slate-500">Manage your travelers and their booking history</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" placeholder="Search travelers..." class="pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ request('search') }}" id="searchInput">
                            <i class="fas fa-search absolute left-3 top-3 text-slate-400"></i>
                        </div>
                        <a href="{{ route('travelers.create') }}" class="btn-primary">
                            <i class="fas fa-plus mr-2"></i> Add Traveler
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-8 py-4 text-left text-sm font-semibold text-slate-600 uppercase tracking-wider">Name</th>
                            <th class="px-8 py-4 text-left text-sm font-semibold text-slate-600 uppercase tracking-wider">Contact</th>
                            <th class="px-8 py-4 text-left text-sm font-semibold text-slate-600 uppercase tracking-wider">Bookings</th>
                            <th class="px-8 py-4 text-left text-sm font-semibold text-slate-600 uppercase tracking-wider">Last Booking</th>
                            <th class="px-8 py-4 text-left text-sm font-semibold text-slate-600 uppercase tracking-wider">Nationality</th>
                            <th class="px-8 py-4 text-right text-sm font-semibold text-slate-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @forelse($travelers as $traveler)
                        <tr class="hover:bg-slate-50">
                            <td class="px-8 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-blue-600 font-medium">{{ substr($traveler->first_name, 0, 1) }}{{ substr($traveler->last_name, 0, 1) }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-slate-900">{{ $traveler->first_name }} {{ $traveler->last_name }}</div>
                                        <div class="text-sm text-slate-500">ID: {{ $traveler->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900">{{ $traveler->email }}</div>
                                <div class="text-sm text-slate-500">{{ $traveler->phone ?? 'N/A' }}</div>
                            </td>
                            <td class="px-8 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900">{{ $traveler->bookings_count ?? 0 }}</div>
                                <div class="text-xs text-slate-500">bookings</div>
                            </td>
                            <td class="px-8 py-4 whitespace-nowrap">
                                @if($traveler->latestBooking)
                                <div class="text-sm text-slate-900">{{ $traveler->latestBooking->package ? $traveler->latestBooking->package->name : 'N/A' }}</div>
                                <div class="text-xs text-slate-500">{{ $traveler->latestBooking->created_at->format('M d, Y') }}</div>
                                @else
                                <div class="text-sm text-slate-500">No bookings yet</div>
                                @endif
                            </td>
                            <td class="px-8 py-4 whitespace-nowrap">
                                {{ $traveler->nationality }}
                            </td>
                            <td class="px-8 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('travelers.show', $traveler) }}" class="text-blue-600 hover:text-blue-900 mr-4">View</a>
                                <a href="{{ route('travelers.edit', $traveler) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-8 py-12 text-center text-slate-500">
                                <i class="fas fa-users-slash text-4xl mb-2 text-slate-300"></i>
                                <p class="text-lg font-medium">No travelers found</p>
                                <p class="mt-1">Get started by adding a new traveler</p>
                                <a href="{{ route('travelers.create') }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="fas fa-plus mr-2"></i> Add Traveler
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($travelers->hasPages())
            <div class="px-8 py-6 flex items-center justify-between border-t border-slate-200">
                <div class="flex items-center space-x-2 text-slate-600">
                    <span>Showing</span>
                    <span class="font-medium">{{ $travelers->firstItem() }}</span>
                    <span>to</span>
                    <span class="font-medium">{{ $travelers->lastItem() }}</span>
                    <span>of</span>
                    <span class="font-medium">{{ $travelers->total() }}</span>
                    <span>results</span>
                </div>
                <div class="flex space-x-2">
                    @if($travelers->onFirstPage())
                    <button class="px-3 py-1.5 rounded-md border border-slate-300 bg-white text-slate-400 cursor-not-allowed" disabled>
                        Previous
                    </button>
                    @else
                    <a href="{{ $travelers->previousPageUrl() }}" class="px-3 py-1.5 rounded-md border border-slate-300 bg-white text-slate-700 hover:bg-slate-50">
                        Previous
                    </a>
                    @endif
                    
                    @if($travelers->hasMorePages())
                    <a href="{{ $travelers->nextPageUrl() }}" class="px-3 py-1.5 rounded-md border border-slate-300 bg-white text-slate-700 hover:bg-slate-50">
                        Next
                    </a>
                    @else
                    <button class="px-3 py-1.5 rounded-md border border-slate-300 bg-white text-slate-400 cursor-not-allowed" disabled>
                        Next
                    </button>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Handle search input with debounce
    const searchInput = document.getElementById('searchInput');
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const searchValue = this.value.trim();
        
        searchTimeout = setTimeout(() => {
            const url = new URL(window.location.href);
            if (searchValue) {
                url.searchParams.set('search', searchValue);
            } else {
                url.searchParams.delete('search');
            }
            window.location.href = url.toString();
        }, 500);
    });
    
    // Restore search value on page load
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const searchParam = urlParams.get('search');
        if (searchParam) {
            searchInput.value = searchParam;
        }
    });
</script>
@endpush
@endsection
