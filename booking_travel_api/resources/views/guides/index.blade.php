@extends('layouts.dashboard')

@section('title', 'Guides')
@section('page-title', 'Guides')
@section('page-subtitle', 'Manage your tour guides and their availability.')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    <div class="ml-64 mr-80 p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">All Guides</h2>

        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100 mb-8">
            <div class="px-6 py-4 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Guide List</h3>
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            <input type="text" placeholder="Search guides..." 
                                   class="pl-8 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-search absolute left-2.5 top-2.5 text-gray-400 text-sm"></i>
                        </div>
                        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors">
                            Add New Guide
                        </button>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Specialties</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                        $guides = [
                            ['name' => 'Liam Parker', 'role' => 'Tour Guide', 'status' => 'online', 'specialties' => 'History, Culture'],
                            ['name' => 'Emma Johnson', 'role' => 'Adventure Guide', 'status' => 'offline', 'specialties' => 'Hiking, Safari'],
                            ['name' => 'Noah Brown', 'role' => 'Cultural Guide', 'status' => 'online', 'specialties' => 'Art, Museums'],
                            ['name' => 'Ava Davis', 'role' => 'Nature Guide', 'status' => 'busy', 'specialties' => 'Wildlife, Photography'],
                        ];
                        @endphp
                        
                        @foreach($guides as $guide)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($guide['name']) }}&background=random&size=32" 
                                         alt="{{ $guide['name'] }}" 
                                         class="w-8 h-8 rounded-full mr-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $guide['name'] }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $guide['role'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $guide['status'] === 'online' ? 'bg-green-100 text-green-800' : 
                                       ($guide['status'] === 'busy' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($guide['status']) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $guide['specialties'] }}</td>
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
