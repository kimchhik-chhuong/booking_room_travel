@extends('layouts.app')

@section('title', 'Travelers')
@section('page-title', 'Travelers Management')
@section('page-subtitle', 'Manage your customer profiles and travel history.')

@section('content')
<div class="min-h-screen p-8">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
        <x-stat-card title="Total Travelers" value="2,845" change="+12.5%" icon="fas fa-users" bg="from-blue-400 to-blue-600" />
        <x-stat-card title="Active Travelers" value="1,234" change="+8.2%" icon="fas fa-user-check" bg="from-emerald-400 to-emerald-600" />
        <x-stat-card title="New This Month" value="156" change="+15.3%" icon="fas fa-user-plus" bg="from-purple-400 to-purple-600" />
        <x-stat-card title="Avg. Bookings" value="3.2" change="+5.1%" icon="fas fa-chart-line" bg="from-orange-400 to-orange-600" />
    </div>

    <!-- Travelers Table -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h3 class="text-2xl font-bold text-gray-800">All Travelers</h3>
                <p class="text-gray-500">Manage customer profiles and travel history</p>
            </div>
            <div class="flex items-center space-x-4">
                <input type="text" placeholder="Search travelers..." class="border rounded px-4 py-2">
                <select class="border rounded px-4 py-2">
                    <option>All Status</option>
                    <option>Active</option>
                    <option>Inactive</option>
                </select>
                <button class="bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-plus"></i><span>Add Traveler</span>
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">Name</th>
                        <th class="px-6 py-3">Contact</th>
                        <th class="px-6 py-3">Total Bookings</th>
                        <th class="px-6 py-3">Last Booking</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $travelers = [
                            ['name' => 'John Doe', 'email' => 'john@example.com', 'phone' => '+1 123-4567', 'bookings' => 5, 'last_booking' => 'Parisian Romance', 'status' => 'Active'],
                            ['name' => 'Jane Smith', 'email' => 'jane@example.com', 'phone' => '+1 987-6543', 'bookings' => 3, 'last_booking' => 'Safari Adventure', 'status' => 'Active'],
                            ['name' => 'Alice Brown', 'email' => 'alice@example.com', 'phone' => '+1 333-4444', 'bookings' => 2, 'last_booking' => 'Caribbean Cruise', 'status' => 'Inactive'],
                        ];
                    @endphp
                    @foreach($travelers as $traveler)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($traveler['name']) }}&background=random&size=40" alt="{{ $traveler['name'] }}" class="w-10 h-10 rounded-full">
                                <div>
                                    <p class="font-medium text-gray-800">{{ $traveler['name'] }}</p>
                                    <p class="text-sm text-gray-500">Customer ID: #{{ str_pad($loop->index + 1001, 4, '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-800">{{ $traveler['email'] }}</p>
                            <p class="text-sm text-gray-500">{{ $traveler['phone'] }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm">{{ $traveler['bookings'] }}</span>
                        </td>
                        <td class="px-6 py-4">{{ $traveler['last_booking'] }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-sm {{ $traveler['status'] === 'Active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $traveler['status'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 flex space-x-2">
                            <button class="text-blue-600 hover:text-blue-800"><i class="fas fa-eye"></i></button>
                            <button class="text-green-600 hover:text-green-800"><i class="fas fa-edit"></i></button>
                            <button class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
