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
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Full Calendar View</h2>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">August 2028</h3>
                <div class="flex space-x-2">
                    <button class="text-gray-400 hover:text-gray-600 p-1 rounded hover:bg-gray-100">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="text-gray-400 hover:text-gray-600 p-1 rounded hover:bg-gray-100">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
            <div class="grid grid-cols-7 gap-1 text-center text-xs mb-2">
                @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                    <div class="text-gray-500 py-2 font-medium">{{ $day }}</div>
                @endforeach
            </div>
            <div class="grid grid-cols-7 gap-1 text-center text-sm">
                @for($i = 28; $i <= 31; $i++)
                    <div class="text-gray-300 py-2">{{ $i }}</div>
                @endfor
                
                @for($i = 1; $i <= 31; $i++)
                    <div class="py-2 hover:bg-blue-100 rounded cursor-pointer transition-colors 
                        {{ in_array($i, [5, 12, 19, 25]) ? 'bg-blue-500 text-white rounded-lg font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                        {{ $i }}
                    </div>
                @endfor
            </div>

            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Events for August 2028</h3>
                <div class="space-y-3">
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200 flex items-center space-x-3">
                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">August 5: Tokyo Cultural Adventure Trip Departure</p>
                            <p class="text-xs text-gray-600">9:00 AM - Narita International Airport</p>
                        </div>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg border border-green-200 flex items-center space-x-3">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">August 12: New Customer Onboarding Session</p>
                            <p class="text-xs text-gray-600">2:00 PM - Online Meeting</p>
                        </div>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200 flex items-center space-x-3">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">August 19: Team Brainstorming for New Packages</p>
                            <p class="text-xs text-gray-600">11:00 AM - Office Meeting Room</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Sidebar -->
    @include('partials.right-sidebar')
</div>
@endsection
