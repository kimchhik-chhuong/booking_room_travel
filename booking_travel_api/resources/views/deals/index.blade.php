@extends('layouts.dashboard')

@section('title', 'Deals')
@section('page-title', 'Deals & Offers')
@section('page-subtitle', 'Create and manage special offers and discounts.')

@push('styles')
<style>
    .stat-card {
        @apply bg-white rounded-xl p-6 shadow-sm border border-slate-200;
    }
    
    .deal-card {
        @apply rounded-xl overflow-hidden shadow-sm border border-slate-200 transition-all duration-200 hover:shadow-md;
    }
    
    .deal-card-header {
        @apply px-6 py-4 text-white font-medium text-lg;
    }
    
    .deal-card-body {
        @apply p-6 bg-white;
    }
    
    .status-badge {
        @apply inline-flex items-center px-3 py-1 rounded-full text-xs font-medium;
    }
    
    .status-active {
        @apply bg-green-100 text-green-800;
    }
    
    .status-scheduled {
        @apply bg-blue-100 text-blue-800;
    }
    
    .status-expired {
        @apply bg-red-100 text-red-800;
    }
    
    .btn {
        @apply px-4 py-2 rounded-md font-medium transition-colors duration-200;
    }
    
    .btn-primary {
        @apply bg-blue-600 text-white hover:bg-blue-700 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
    }
    
    .btn-secondary {
        @apply bg-slate-200 text-slate-800 hover:bg-slate-300 focus:ring-2 focus:ring-offset-2 focus:ring-slate-500;
    }
    
    .btn-danger {
        @apply bg-red-600 text-white hover:bg-red-700 focus:ring-2 focus:ring-offset-2 focus:ring-red-500;
    }
    
    .input-search {
        @apply w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500;
    }
    
    .select-filter {
        @apply rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    <div class="ml-72 p-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm font-medium mb-2">Active Deals</p>
                        <p class="text-3xl font-bold text-slate-800">{{ $deals->where('status', 'Active')->count() }}</p>
                        <p class="text-green-600 text-sm font-medium mt-2">Active now</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-tags text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm font-medium mb-2">Scheduled</p>
                        <p class="text-3xl font-bold text-slate-800">{{ $deals->where('status', 'Scheduled')->count() }}</p>
                        <p class="text-blue-600 text-sm font-medium mt-2">Upcoming deals</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center">
                        <i class="far fa-clock text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm font-medium mb-2">Expired</p>
                        <p class="text-3xl font-bold text-slate-800">{{ $deals->where('status', 'Expired')->count() }}</p>
                        <p class="text-slate-600 text-sm font-medium mt-2">Past deals</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-slate-400 to-slate-600 rounded-2xl flex items-center justify-center">
                        <i class="far fa-calendar-times text-white text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Bar -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="w-full md:w-1/3">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-slate-400"></i>
                        </div>
                        <input type="text" id="searchDeals" placeholder="Search deals..." class="input-search pl-10">
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <select id="filterStatus" class="select-filter">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="Scheduled">Scheduled</option>
                        <option value="Expired">Expired</option>
                    </select>
                    
                    <a href="{{ route('deals.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-2"></i> Create Deal
                    </a>
                </div>
            </div>
        </div>

        <!-- Deals Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($deals as $deal)
                <div class="deal-card">
                    <div class="deal-card-header bg-gradient-to-r {{ $deal->color }}">
                        <div class="flex justify-between items-center">
                            <span>{{ $deal->title }}</span>
                            <span class="text-white/80 text-sm">{{ $deal->code }}</span>
                        </div>
                    </div>
                    <div class="deal-card-body">
                        <p class="text-slate-600 mb-4">{{ $deal->description }}</p>
                        
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-2xl font-bold text-slate-800">{{ $deal->discount }}</p>
                                <p class="text-xs text-slate-500">Discount</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-slate-700">Valid until</p>
                                <p class="text-sm text-slate-500">{{ \Carbon\Carbon::parse($deal->valid_until)->format('M d, Y') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between mb-4">
                            <span class="status-badge status-{{ strtolower($deal->status) }}">
                                {{ $deal->status }}
                            </span>
                            <span class="text-sm text-slate-600">
                                {{ $deal->used }}/{{ $deal->limit }} used
                            </span>
                        </div>
                        
                        <div class="w-full bg-slate-200 rounded-full h-2.5 mb-4">
                            <div class="h-2.5 rounded-full {{ 
                                $deal->status === 'Active' ? 'bg-green-500' : 
                                ($deal->status === 'Scheduled' ? 'bg-blue-500' : 'bg-slate-400') 
                            }}" style="width: {{ ($deal->used / $deal->limit) * 100 }}%"></div>
                        </div>
                        
                        <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                            <span class="text-sm text-slate-500">
                                Created {{ $deal->created_at->diffForHumans() }}
                            </span>
                            <div class="flex space-x-2">
                                <a href="{{ route('deals.edit', $deal->id) }}" 
                                   class="text-blue-600 hover:text-blue-800 transition-colors">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('deals.destroy', $deal->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-800 transition-colors"
                                            onclick="return confirm('Are you sure you want to delete this deal?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-50 text-blue-600 mb-4">
                        <i class="fas fa-tag text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-slate-800 mb-1">No deals found</h3>
                    <p class="text-slate-500 mb-6">Get started by creating a new deal</p>
                    <a href="{{ route('deals.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-2"></i> Create Deal
                    </a>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        @if($deals->hasPages())
            <div class="mt-8">
                {{ $deals->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Search and filter functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchDeals');
        const statusFilter = document.getElementById('filterStatus');
        const dealCards = document.querySelectorAll('.deal-card');
        
        function filterDeals() {
            const searchTerm = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value;
            
            dealCards.forEach(card => {
                const title = card.querySelector('.deal-card-header span:first-child').textContent.toLowerCase();
                const code = card.querySelector('.deal-card-header span:last-child').textContent.toLowerCase();
                const status = card.querySelector('.status-badge').textContent;
                
                const matchesSearch = title.includes(searchTerm) || code.includes(searchTerm);
                const matchesStatus = !statusValue || status === statusValue;
                
                if (matchesSearch && matchesStatus) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
        
        searchInput.addEventListener('input', filterDeals);
        statusFilter.addEventListener('change', filterDeals);
    });
</script>
@endpush
@endsection
