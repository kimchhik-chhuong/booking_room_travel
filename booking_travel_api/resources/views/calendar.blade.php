@extends('layouts.dashboard')

@section('title', 'Calendar')
@section('page-title', 'Calendar')
@section('page-subtitle', 'View and manage your schedule and events.')

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
                        <p class="text-dark-500 text-sm font-medium mb-2">Total Events</p>
                        <p class="text-3xl font-bold text-dark-800">47</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+8 this week</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-calendar text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">This Week</p>
                        <p class="text-3xl font-bold text-dark-800">12</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+3 new events</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-calendar-week text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Upcoming</p>
                        <p class="text-3xl font-bold text-dark-800">23</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+5 scheduled</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-clock text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Completed</p>
                        <p class="text-3xl font-bold text-dark-800">156</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+12 this month</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-orange-400 to-orange-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar and Events -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
            <!-- Calendar -->
            <div class="lg:col-span-2 card-modern p-8">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-2xl font-bold text-dark-800">August 2024</h3>
                    <div class="flex items-center space-x-4">
                        <button class="p-3 text-dark-400 hover:text-dark-600 hover:bg-slate-50 rounded-xl transition-all">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="p-3 text-dark-400 hover:text-dark-600 hover:bg-slate-50 rounded-xl transition-all">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                        <button class="btn-modern">
                            <i class="fas fa-plus mr-2"></i> Add Event
                        </button>
                    </div>
                </div>
                
                <!-- Calendar Grid -->
                <div class="grid grid-cols-7 gap-2 text-center text-sm mb-6">
                    @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                        <div class="text-dark-500 py-4 font-semibold">{{ $day }}</div>
                    @endforeach
                </div>
                
                <div class="grid grid-cols-7 gap-2 text-center text-sm">
                    @php
                    $eventDays = [5, 12, 19, 25, 28];
                    @endphp
                    
                    @for($i = 28; $i <= 31; $i++)
                        <div class="text-slate-300 py-4 hover:bg-slate-100 rounded-xl cursor-pointer transition-colors">{{ $i }}</div>
                    @endfor
                    
                    @for($i = 1; $i <= 31; $i++)
                        <div class="py-4 hover:bg-blue-100 rounded-xl cursor-pointer transition-colors relative
                            {{ in_array($i, $eventDays) ? 'bg-primary-500 text-white rounded-xl font-bold' : 'text-dark-700 hover:text-primary-600' }}">
                            {{ $i }}
                            @if(in_array($i, $eventDays))
                                <div class="absolute bottom-1 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-white rounded-full"></div>
                            @endif
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Upcoming Events -->
            <div class="card-modern p-8">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-xl font-bold text-dark-800">Upcoming Events</h3>
                    <button class="text-primary-600 text-sm hover:underline font-semibold">View All</button>
                </div>
                <div class="space-y-4">
                    @php
                    $upcomingEvents = [
                        ['title' => 'Tokyo Cultural Adventure', 'date' => 'Aug 5', 'time' => '9:00 AM', 'type' => 'Departure', 'color' => 'bg-blue-500'],
                        ['title' => 'Client Meeting - Venice Package', 'date' => 'Aug 7', 'time' => '2:00 PM', 'type' => 'Meeting', 'color' => 'bg-emerald-500'],
                        ['title' => 'Safari Adventure Briefing', 'date' => 'Aug 12', 'time' => '10:00 AM', 'type' => 'Briefing', 'color' => 'bg-orange-500'],
                        ['title' => 'Team Planning Session', 'date' => 'Aug 15', 'time' => '11:00 AM', 'type' => 'Internal', 'color' => 'bg-purple-500'],
                        ['title' => 'Bali Beach Escape Return', 'date' => 'Aug 19', 'time' => '6:00 PM', 'type' => 'Return', 'color' => 'bg-cyan-500']
                    ];
                    @endphp

                    @foreach($upcomingEvents as $event)
                    <div class="flex items-center space-x-4 p-4 hover:bg-slate-50 rounded-2xl cursor-pointer transition-all card-modern">
                        <div class="w-3 h-3 {{ $event['color'] }} rounded-full"></div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-dark-800">{{ $event['title'] }}</p>
                            <p class="text-xs text-dark-500">{{ $event['date'] }} at {{ $event['time'] }}</p>
                        </div>
                        <span class="bg-slate-100 text-dark-700 px-3 py-1 rounded-full text-xs font-semibold">{{ $event['type'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Today's Schedule -->
        <div class="card-modern p-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-2xl font-bold text-dark-800 mb-2">Today's Schedule</h3>
                    <p class="text-dark-500">August 5, 2024</p>
                </div>
                <div class="flex items-center space-x-4">
                    <select class="input-modern">
                        <option>Today</option>
                        <option>Tomorrow</option>
                        <option>This Week</option>
                    </select>
                </div>
            </div>

            <div class="space-y-6">
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
                <div class="flex items-center space-x-6 p-6 border border-slate-200 rounded-2xl hover:shadow-md transition-all card-modern">
                    <div class="text-center">
                        <p class="text-sm font-bold text-dark-800">{{ $event['time'] }}</p>
                        <p class="text-xs text-dark-500">{{ $event['duration'] }}</p>
                    </div>
                    <div class="w-1 h-16 rounded-full 
                        {{ $event['status'] === 'completed' ? 'bg-emerald-500' : 
                           ($event['status'] === 'upcoming' ? 'bg-primary-500' : 'bg-yellow-500') }}">
                    </div>
                    <div class="flex-1">
                        <h4 class="text-lg font-bold text-dark-800 mb-2">{{ $event['title'] }}</h4>
                        <p class="text-sm text-dark-600">{{ $event['description'] }}</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="badge-modern {{ $event['status'] === 'completed' ? 'bg-emerald-100 text-emerald-800' : 
                            ($event['status'] === 'upcoming' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($event['status']) }}
                        </span>
                        <button class="p-2 text-dark-400 hover:text-dark-600 hover:bg-slate-50 rounded-lg transition-all">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
