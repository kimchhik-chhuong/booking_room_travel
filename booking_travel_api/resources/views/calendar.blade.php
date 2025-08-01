@extends('layouts.dashboard')

@section('title', 'Calendar')
@section('page-title', 'Calendar')
@section('page-subtitle', 'View and manage your schedule and events.')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    <div class="ml-64 mr-80 p-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-sm p-6 stat-card border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Total Events</p>
                        <p class="text-3xl font-bold text-gray-800">47</p>
                        <p class="text-sm text-green-600 mt-2 flex items-center">
                            <i class="fas fa-arrow-up mr-1"></i> +8
                        </p>
                    </div>
                    <div class="bg-blue-100 p-4 rounded-2xl">
                        <i class="fas fa-calendar text-blue-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6 stat-card border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">This Week</p>
                        <p class="text-3xl font-bold text-gray-800">12</p>
                        <p class="text-sm text-blue-600 mt-2 flex items-center">
                            <i class="fas fa-arrow-up mr-1"></i> +3
                        </p>
                    </div>
                    <div class="bg-green-100 p-4 rounded-2xl">
                        <i class="fas fa-calendar-week text-green-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6 stat-card border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Upcoming</p>
                        <p class="text-3xl font-bold text-gray-800">23</p>
                        <p class="text-sm text-green-600 mt-2 flex items-center">
                            <i class="fas fa-arrow-up mr-1"></i> +5
                        </p>
                    </div>
                    <div class="bg-purple-100 p-4 rounded-2xl">
                        <i class="fas fa-clock text-purple-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6 stat-card border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Completed</p>
                        <p class="text-3xl font-bold text-gray-800">156</p>
                        <p class="text-sm text-green-600 mt-2 flex items-center">
                            <i class="fas fa-arrow-up mr-1"></i> +12
                        </p>
                    </div>
                    <div class="bg-orange-100 p-4 rounded-2xl">
                        <i class="fas fa-check-circle text-orange-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar and Events -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Calendar -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">August 2024</h3>
                    <div class="flex items-center space-x-3">
                        <button class="text-gray-400 hover:text-gray-600 p-2 rounded-full hover:bg-gray-100 transition-all">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="text-gray-400 hover:text-gray-600 p-2 rounded-full hover:bg-gray-100 transition-all">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                        <button class="btn-primary text-white px-4 py-2 rounded-lg text-sm font-medium">
                            <i class="fas fa-plus mr-2"></i> Add Event
                        </button>
                    </div>
                </div>
                
                <!-- Calendar Grid -->
                <div class="grid grid-cols-7 gap-1 text-center text-sm mb-4">
                    @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                        <div class="text-gray-500 py-3 font-medium">{{ $day }}</div>
                    @endforeach
                </div>
                
                <div class="grid grid-cols-7 gap-1 text-center text-sm">
                    @php
                    $eventDays = [5, 12, 19, 25, 28];
                    @endphp
                    
                    @for($i = 28; $i <= 31; $i++)
                        <div class="text-gray-300 py-3 hover:bg-gray-100 rounded cursor-pointer transition-colors">{{ $i }}</div>
                    @endfor
                    
                    @for($i = 1; $i <= 31; $i++)
                        <div class="py-3 hover:bg-blue-100 rounded cursor-pointer transition-colors relative
                            {{ in_array($i, $eventDays) ? 'bg-blue-500 text-white rounded-lg font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                            {{ $i }}
                            @if(in_array($i, $eventDays))
                                <div class="absolute bottom-1 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-white rounded-full"></div>
                            @endif
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Upcoming Events -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Upcoming Events</h3>
                    <button class="text-blue-600 text-sm hover:underline font-medium">View All</button>
                </div>
                <div class="space-y-4">
                    @php
                    $upcomingEvents = [
                        ['title' => 'Tokyo Cultural Adventure', 'date' => 'Aug 5', 'time' => '9:00 AM', 'type' => 'Departure', 'color' => 'bg-blue-500'],
                        ['title' => 'Client Meeting - Venice Package', 'date' => 'Aug 7', 'time' => '2:00 PM', 'type' => 'Meeting', 'color' => 'bg-green-500'],
                        ['title' => 'Safari Adventure Briefing', 'date' => 'Aug 12', 'time' => '10:00 AM', 'type' => 'Briefing', 'color' => 'bg-orange-500'],
                        ['title' => 'Team Planning Session', 'date' => 'Aug 15', 'time' => '11:00 AM', 'type' => 'Internal', 'color' => 'bg-purple-500'],
                        ['title' => 'Bali Beach Escape Return', 'date' => 'Aug 19', 'time' => '6:00 PM', 'type' => 'Return', 'color' => 'bg-cyan-500']
                    ];
                    @endphp

                    @foreach($upcomingEvents as $event)
                    <div class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-xl cursor-pointer transition-all card-hover">
                        <div class="w-3 h-3 {{ $event['color'] }} rounded-full"></div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">{{ $event['title'] }}</p>
                            <p class="text-xs text-gray-500">{{ $event['date'] }} at {{ $event['time'] }}</p>
                        </div>
                        <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded-full text-xs font-medium">{{ $event['type'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Today's Schedule -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Today's Schedule - August 5, 2024</h3>
                <div class="flex items-center space-x-3">
                    <select class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        <option>Today</option>
                        <option>Tomorrow</option>
                        <option>This Week</option>
                    </select>
                </div>
            </div>

            <div class="space-y-4">
                @php
                $todayEvents = [
                    ['time' => '9:00 AM', 'title' => 'Tokyo Cultural Adventure - Group Departure', 'description' => 'Meet clients at airport for departure to Tokyo', 'status' => 'upcoming', 'duration' => '2 hours'],
                    ['time' => '11:30 AM', 'title' => 'Venice Package Consultation', 'description' => 'Phone consultation with potential clients', 'status' => 'completed', 'duration' => '1 hour'],
                    ['time' => '2:00 PM', 'title' => 'Team Lunch Meeting', 'description' => 'Discuss new package proposals over lunch', 'status' => 'upcoming', 'duration' => '1.5 hours'],
                    ['time' => '4:00 PM', 'title' => 'Safari Adventure Planning', 'description' => 'Final preparations for next week\'s safari tour', 'status' => 'upcoming', 'duration' => '2 hours'],
                    ['time' => '6:30 PM', 'title' => 'Client Follow-up Calls', 'description' => 'Follow up with recent travelers for feedback', 'status' => 'pending', 'duration' => '1 hour']
                ];
                @endphp

                @foreach($todayEvents as $event)
                <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-xl hover:shadow-md transition-all card-hover">
                    <div class="text-center">
                        <p class="text-sm font-semibold text-gray-800">{{ $event['time'] }}</p>
                        <p class="text-xs text-gray-500">{{ $event['duration'] }}</p>
                    </div>
                    <div class="w-1 h-12 rounded-full 
                        {{ $event['status'] === 'completed' ? 'bg-green-500' : 
                           ($event['status'] === 'upcoming' ? 'bg-blue-500' : 'bg-yellow-500') }}">
                    </div>
                    <div class="flex-1">
                        <h4 class="text-base font-semibold text-gray-800 mb-1">{{ $event['title'] }}</h4>
                        <p class="text-sm text-gray-600">{{ $event['description'] }}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $event['status'] === 'completed' ? 'bg-green-100 text-green-800' : 
                               ($event['status'] === 'upcoming' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($event['status']) }}
                        </span>
                        <button class="text-gray-400 hover:text-gray-600 p-1 rounded hover:bg-gray-100 transition-all">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Right Sidebar -->
    @include('partials.right-sidebar')
</div>
@endsection
