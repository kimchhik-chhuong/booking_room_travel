@extends('layouts.dashboard')

@section('title', 'Guides')
@section('page-title', 'Tour Guides')
@section('page-subtitle', 'Manage your tour guides and their availability.')

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
                        <p class="text-dark-500 text-sm font-medium mb-2">Total Guides</p>
                        <p class="text-3xl font-bold text-dark-800">48</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+6.2% this month</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-map text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Available Now</p>
                        <p class="text-3xl font-bold text-dark-800">32</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">67% availability</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-user-check text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">On Tour</p>
                        <p class="text-3xl font-bold text-dark-800">12</p>
                        <p class="text-yellow-600 text-sm font-medium mt-2">25% active</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-route text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Avg. Rating</p>
                        <p class="text-3xl font-bold text-dark-800">4.8</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+0.3 improvement</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-star text-white text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Guides Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
            @php
            $guides = [
                ['name' => 'Liam Parker', 'role' => 'Adventure Guide', 'status' => 'Available', 'rating' => '4.9', 'tours' => 156, 'specialties' => ['Hiking', 'Safari', 'Photography'], 'languages' => ['English', 'Spanish'], 'image' => 'https://ui-avatars.com/api/?name=Liam+Parker&background=random&size=80'],
                ['name' => 'Emma Johnson', 'role' => 'Cultural Guide', 'status' => 'On Tour', 'rating' => '4.8', 'tours' => 203, 'specialties' => ['History', 'Art', 'Museums'], 'languages' => ['English', 'French', 'Italian'], 'image' => 'https://ui-avatars.com/api/?name=Emma+Johnson&background=random&size=80'],
                ['name' => 'Noah Brown', 'role' => 'City Guide', 'status' => 'Available', 'rating' => '4.7', 'tours' => 89, 'specialties' => ['Architecture', 'Food', 'Nightlife'], 'languages' => ['English', 'German'], 'image' => 'https://ui-avatars.com/api/?name=Noah+Brown&background=random&size=80'],
                ['name' => 'Ava Davis', 'role' => 'Nature Guide', 'status' => 'Available', 'rating' => '5.0', 'tours' => 134, 'specialties' => ['Wildlife', 'Botany', 'Conservation'], 'languages' => ['English', 'Portuguese'], 'image' => 'https://ui-avatars.com/api/?name=Ava+Davis&background=random&size=80'],
                ['name' => 'Oliver Wilson', 'role' => 'Adventure Guide', 'status' => 'Offline', 'rating' => '4.6', 'tours' => 78, 'specialties' => ['Rock Climbing', 'Kayaking'], 'languages' => ['English'], 'image' => 'https://ui-avatars.com/api/?name=Oliver+Wilson&background=random&size=80'],
                ['name' => 'Sophia Garcia', 'role' => 'Cultural Guide', 'status' => 'On Tour', 'rating' => '4.9', 'tours' => 167, 'specialties' => ['Local Traditions', 'Cuisine'], 'languages' => ['English', 'Spanish', 'Portuguese'], 'image' => 'https://ui-avatars.com/api/?name=Sophia+Garcia&background=random&size=80'],
            ];
            @endphp

            @foreach($guides as $guide)
            <div class="card-modern p-8">
                <div class="flex items-center space-x-6 mb-6">
                    <img src="{{ $guide['image'] }}" alt="{{ $guide['name'] }}" class="w-20 h-20 rounded-2xl shadow-lg">
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-dark-800 mb-1">{{ $guide['name'] }}</h3>
                        <p class="text-dark-500 mb-3">{{ $guide['role'] }}</p>
                        <div class="flex items-center mb-2">
                            @for($i = 0; $i < 5; $i++)
                                <i class="fas fa-star text-yellow-400 text-sm"></i>
                            @endfor
                            <span class="text-sm text-dark-600 ml-2 font-medium">{{ $guide['rating'] }} ({{ $guide['tours'] }} tours)</span>
                        </div>
                    </div>
                    <span class="badge-modern {{ $guide['status'] === 'Available' ? 'bg-emerald-100 text-emerald-800' : 
                        ($guide['status'] === 'On Tour' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                        {{ $guide['status'] }}
                    </span>
                </div>

                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-semibold text-dark-700 mb-2">Specialties</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($guide['specialties'] as $specialty)
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-medium">{{ $specialty }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-dark-700 mb-2">Languages</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($guide['languages'] as $language)
                            <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-medium">{{ $language }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-slate-200">
                        <button class="text-primary-600 hover:text-primary-700 text-sm font-semibold transition-colors">View Profile</button>
                        <div class="flex items-center space-x-3">
                            <button class="p-2 text-dark-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all">
                                <i class="fas fa-phone"></i>
                            </button>
                            <button class="p-2 text-dark-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all">
                                <i class="fas fa-envelope"></i>
                            </button>
                            <button class="p-2 text-dark-400 hover:text-gray-600 hover:bg-gray-50 rounded-lg transition-all">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Add Guide Button -->
        <div class="text-center">
            <button class="btn-modern text-lg px-8 py-4">
                <i class="fas fa-plus mr-3"></i> Add New Guide
            </button>
        </div>
    </div>
</div>
@endsection
