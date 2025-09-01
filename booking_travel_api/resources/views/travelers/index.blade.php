@extends('layouts.dashboard')

@section('title', 'Travelers')
@section('page-title', 'Travelers Management')

@section('content')
<div class="min-h-screen">
    @include('partials.sidebar')
    @include('partials.header')

    <div class="ml-72 pt-32 p-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm font-medium mb-2">Total Travelers</p>
                        <p class="text-3xl font-bold text-slate-800">{{ $totalTravelers }}</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+{{ $newGrowthRate }}% this month</p>
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
                        <p class="text-slate-500 text-sm font-medium mt-2">per traveler</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-calendar-check text-white text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Travelers Table -->
        <div class="card-modern overflow-hidden">
            <div class="p-6 border-b border-slate-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-slate-800 mb-1">All Travelers</h3>
                        <p class="text-slate-500">Manage your travelers and their booking information</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" placeholder="Search travelers..." class="pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="searchInput">
                            <i class="fas fa-search absolute left-3 top-3 text-slate-400"></i>
                        </div>
                        <a href="{{ route('travelers.create') }}" class="btn-primary">
                            <i class="fas fa-plus mr-2"></i> Add Traveler
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Booking</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nationality</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($travelers as $traveler)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-slate-200 rounded-full flex items-center justify-center">
                                            <span class="text-slate-600 font-medium">{{ substr($traveler->first_name, 0, 1) }}{{ substr($traveler->last_name, 0, 1) }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-slate-900">{{ $traveler->first_name }} {{ $traveler->last_name }}</div>
                                            <div class="text-sm text-slate-500">{{ $traveler->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-900">
                                        @if($traveler->booking)
                                            <a href="{{ route('bookings.show', $traveler->booking) }}" class="text-blue-600 hover:text-blue-800 hover:underline">
                                                #{{ $traveler->booking->id }}
                                            </a>
                                        @else
                                            <span class="text-slate-400">No booking</span>
                                        @endif
                                    </div>
                                    <div class="text-sm text-slate-500">
                                        {{ $traveler->created_at->format('M d, Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-900">{{ $traveler->phone ?? 'N/A' }}</div>
                                    <div class="text-sm text-slate-500">{{ $traveler->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-slate-100 text-slate-800">
                                        {{ $traveler->nationality ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($traveler->status === 'active')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-slate-100 text-slate-800">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-3">
                                        <a href="{{ route('travelers.show', $traveler) }}" class="text-blue-600 hover:text-blue-900">
                                            <i class="far fa-eye"></i>
                                        </a>
                                        <a href="{{ route('travelers.edit', $traveler) }}" class="text-indigo-600 hover:text-indigo-900">
                                            <i class="far fa-edit"></i>
                                        </a>
                                        <form action="{{ route('travelers.destroy', $traveler) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this traveler?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <i class="far fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-slate-500">
                                    No travelers found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($travelers->hasPages())
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $travelers->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
</script>
@endpush
@endsection
