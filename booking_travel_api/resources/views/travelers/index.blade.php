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
    <div class="ml-72 p-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Total Travelers</p>
                        <p class="text-3xl font-bold text-dark-800">2,845</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+12.5% this month</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Active Travelers</p>
                        <p class="text-3xl font-bold text-dark-800">1,234</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+8.2% this month</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-user-check text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">New This Month</p>
                        <p class="text-3xl font-bold text-dark-800">156</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+15.3% growth</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-user-plus text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Avg. Bookings</p>
                        <p class="text-3xl font-bold text-dark-800">3.2</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+5.1% per traveler</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-orange-400 to-orange-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Travelers Table -->
        <div class="card-modern overflow-hidden">
            <div class="p-8 border-b border-slate-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-dark-800 mb-2">All Travelers</h3>
                        <p class="text-dark-500">Manage customer profiles and travel history</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" placeholder="Search travelers..." class="input-modern pl-10 w-64">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-400"></i>
                        </div>
                        <select class="input-modern">
                            <option>All Status</option>
                            <option>Active</option>
                            <option>Inactive</option>
                        </select>
                        <button class="btn-modern">
                            <i class="fas fa-plus mr-2"></i> Add Traveler
                        </button>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-8 py-4 text-left text-sm font-semibold text-dark-600 uppercase tracking-wider">
                                Name
                            </th>
                            <th class="px-8 py-4 text-left text-sm font-semibold text-dark-600 uppercase tracking-wider">
                                Contact
                            </th>
                            <th class="px-8 py-4 text-left text-sm font-semibold text-dark-600 uppercase tracking-wider">
                                Total Bookings
                            </th>
                            <th class="px-8 py-4 text-left text-sm font-semibold text-dark-600 uppercase tracking-wider">
                                Last Booking
                            </th>
                            <th class="px-8 py-4 text-left text-sm font-semibold text-dark-600 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-8 py-4 text-left text-sm font-semibold text-dark-600 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @php
                        $travelers = [
                            ['name' => 'John Doe', 'email' => 'john.doe@example.com', 'phone' => '+1 (555) 123-4567', 'bookings' => 5, 'last_booking' => 'Parisian Romance', 'status' => 'Active', 'avatar' => 'https://ui-avatars.com/api/?name=John+Doe&background=random&size=40'],
                            ['name' => 'Jane Smith', 'email' => 'jane.smith@example.com', 'phone' => '+1 (555) 987-6543', 'bookings' => 3, 'last_booking' => 'Safari Adventure', 'status' => 'Active', 'avatar' => 'https://ui-avatars.com/api/?name=Jane+Smith&background=random&size=40'],
                            ['name' => 'Peter Jones', 'email' => 'peter.j@example.com', 'phone' => '+1 (555) 111-2222', 'bookings' => 8, 'last_booking' => 'Venice Dreams', 'status' => 'Active', 'avatar' => 'https://ui-avatars.com/api/?name=Peter+Jones&background=random&size=40'],
                            ['name' => 'Alice Brown', 'email' => 'alice.b@example.com', 'phone' => '+1 (555) 333-4444', 'bookings' => 2, 'last_booking' => 'Caribbean Cruise', 'status' => 'Inactive', 'avatar' => 'https://ui-avatars.com/api/?name=Alice+Brown&background=random&size=40'],
                            ['name' => 'Michael Wilson', 'email' => 'michael.w@example.com', 'phone' => '+1 (555) 555-6666', 'bookings' => 6, 'last_booking' => 'Tokyo Cultural', 'status' => 'Active', 'avatar' => 'https://ui-avatars.com/api/?name=Michael+Wilson&background=random&size=40'],
                            ['name' => 'Sarah Davis', 'email' => 'sarah.d@example.com', 'phone' => '+1 (555) 777-8888', 'bookings' => 4, 'last_booking' => 'Bali Beach Escape', 'status' => 'Active', 'avatar' => 'https://ui-avatars.com/api/?name=Sarah+Davis&background=random&size=40'],
                        ];
                        @endphp
                        
                        @foreach($travelers as $traveler)
                        <tr class="table-row transition-all duration-200 hover:bg-slate-50">
                            <td class="px-8 py-6">
                                <div class="flex items-center space-x-4">
                                    <img src="{{ $traveler['avatar'] }}" alt="{{ $traveler['name'] }}" class="w-12 h-12 rounded-xl shadow-md">
                                    <div>
                                        <p class="font-semibold text-dark-800">{{ $traveler['name'] }}</p>
                                        <p class="text-sm text-dark-500">Customer ID: #{{ str_pad(array_search($traveler, $travelers) + 1001, 4, '0', STR_PAD_LEFT) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div>
                                    <p class="font-medium text-dark-800">{{ $traveler['email'] }}</p>
                                    <p class="text-sm text-dark-500">{{ $traveler['phone'] }}</p>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">{{ $traveler['bookings'] }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <p class="font-medium text-dark-800">{{ $traveler['last_booking'] }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <span class="badge-modern {{ $traveler['status'] === 'Active' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $traveler['status'] }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center space-x-3">
                                    <button class="p-2 text-dark-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="p-2 text-dark-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="p-2 text-dark-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-8 py-6 flex items-center justify-between border-t border-slate-200">
                <div class="flex items-center space-x-2 text-dark-600">
                    <span>Showing</span>
                    <select class="input-modern text-sm px-2 py-1">
                        <option>6</option>
                        <option>12</option>
                        <option>24</option>
                    </select>
                    <span>of 2,845 travelers</span>
                </div>
                <div class="flex items-center space-x-2">
                    <button class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors text-dark-600">
                        <i class="fas fa-chevron-left mr-2"></i> Previous
                    </button>
                    <button class="px-4 py-2 bg-primary-600 text-white rounded-lg">1</button>
                    <button class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors text-dark-600">2</button>
                    <button class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors text-dark-600">3</button>
                    <span class="px-2 text-dark-400">...</span>
                    <button class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors text-dark-600">475</button>
                    <button class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors text-dark-600">
                        Next <i class="fas fa-chevron-right ml-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
