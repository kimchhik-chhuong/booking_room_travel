@extends('layouts.dashboard')

@section('title', 'Travelers')
@section('page-title', 'Travelers')
@section('page-subtitle', 'Manage your customer profiles and travel history.')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    <div class="ml-64 mr-80 p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">All Travelers</h2>

        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100 mb-8">
            <div class="px-6 py-4 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Traveler List</h3>
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            <input type="text" placeholder="Search travelers..." 
                                   class="pl-8 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-search absolute left-2.5 top-2.5 text-gray-400 text-sm"></i>
                        </div>
                        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors">
                            Add New Traveler
                        </button>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Bookings</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Booking</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                        $travelers = [
                            ['name' => 'John Doe', 'email' => 'john.doe@example.com', 'phone' => '+1 (555) 123-4567', 'bookings' => 5, 'last_booking' => 'Parisian Romance'],
                            ['name' => 'Jane Smith', 'email' => 'jane.smith@example.com', 'phone' => '+1 (555) 987-6543', 'bookings' => 3, 'last_booking' => 'Safari Adventure'],
                            ['name' => 'Peter Jones', 'email' => 'peter.j@example.com', 'phone' => '+1 (555) 111-2222', 'bookings' => 8, 'last_booking' => 'Venice Dreams'],
                            ['name' => 'Alice Brown', 'email' => 'alice.b@example.com', 'phone' => '+1 (555) 333-4444', 'bookings' => 2, 'last_booking' => 'Caribbean Cruise'],
                        ];
                        @endphp
                        
                        @foreach($travelers as $traveler)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($traveler['name']) }}&background=random&size=32" 
                                         alt="{{ $traveler['name'] }}" 
                                         class="w-8 h-8 rounded-full mr-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $traveler['name'] }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $traveler['email'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $traveler['phone'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $traveler['bookings'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $traveler['last_booking'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                <a href="#" class="text-red-600 hover:text-red-900">Delete</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Sidebar -->
    @include('partials.right-sidebar')
</div>
@endsection
