@extends('layouts.dashboard')

@section('title', 'Messages')
@section('page-title', 'Messages')
@section('page-subtitle', 'Communicate with your customers and partners.')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    <div class="ml-64 mr-80 p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">All Messages</h2>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Message List -->
            <div class="lg:col-span-1 bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Inbox</h3>
                    <button class="text-blue-600 text-sm hover:underline font-medium">New Message</button>
                </div>
                <div class="relative mb-4">
                    <input type="text" placeholder="Search messages..." 
                           class="w-full pl-8 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-search absolute left-2.5 top-2.5 text-gray-400 text-sm"></i>
                </div>
                <div class="space-y-3">
                    @php
                    $allMessages = [
                        ['name' => 'Europia Hotel', 'message' => 'We are pleased to inform...', 'time' => '10:24 AM', 'unread' => true],
                        ['name' => 'Global Travel Co', 'message' => 'We have updated our cont...', 'time' => '2:30 PM', 'unread' => true],
                        ['name' => 'Kalendra Umbara', 'message' => 'Hi, I have some questions with c...', 'time' => '5:45 AM', 'unread' => true],
                        ['name' => 'Osman Farooq', 'message' => 'Hello, I had an amazing tim...', 'time' => '10:15 AM', 'unread' => false],
                        ['name' => 'Mellinda Jenkins', 'message' => 'Can you send more data...', 'time' => '7:24 PM', 'unread' => false],
                        ['name' => 'David Hernandez', 'message' => 'I would like to upgrade my...', 'time' => '10:06 AM', 'unread' => true],
                        ['name' => 'Alexandra Green', 'message' => 'Our company is interested i...', 'time' => '12:30 PM', 'unread' => true]
                    ];
                    @endphp
                    
                    @foreach($allMessages as $message)
                    <div class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-xl cursor-pointer transition-all message-item {{ $message['unread'] ? 'bg-blue-50' : '' }}">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($message['name']) }}&background=random&size=40" 
                             alt="{{ $message['name'] }}" 
                             class="w-10 h-10 rounded-full ring-2 ring-white shadow-sm">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-800 truncate">{{ $message['name'] }}</p>
                                <p class="text-xs text-gray-500">{{ $message['time'] }}</p>
                            </div>
                            <p class="text-xs text-gray-600 truncate">{{ $message['message'] }}</p>
                        </div>
                        @if($message['unread'])
                        <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Message Detail / Chat Area -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex flex-col h-[600px]">
                <div class="flex items-center space-x-3 border-b border-gray-100 pb-4 mb-4">
                    <img src="https://ui-avatars.com/api/?name=Europia+Hotel&background=random&size=40" 
                         alt="Europia Hotel" 
                         class="w-10 h-10 rounded-full ring-2 ring-white shadow-sm">
                    <div>
                        <p class="text-lg font-semibold text-gray-800">Europia Hotel</p>
                        <p class="text-sm text-gray-500">Online</p>
                    </div>
                    <div class="ml-auto flex space-x-3">
                        <button class="text-gray-400 hover:text-gray-600 p-1 rounded hover:bg-gray-100">
                            <i class="fas fa-phone"></i>
                        </button>
                        <button class="text-gray-400 hover:text-gray-600 p-1 rounded hover:bg-gray-100">
                            <i class="fas fa-video"></i>
                        </button>
                        <button class="text-gray-400 hover:text-gray-600 p-1 rounded hover:bg-gray-100">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                    </div>
                </div>

                <!-- Chat Bubbles -->
                <div class="flex-1 overflow-y-auto space-y-4 pr-2">
                    <!-- Incoming Message -->
                    <div class="flex items-end space-x-2">
                        <img src="https://ui-avatars.com/api/?name=Europia+Hotel&background=random&size=32" 
                             alt="Europia Hotel" 
                             class="w-8 h-8 rounded-full">
                        <div class="bg-gray-100 p-3 rounded-xl max-w-xs">
                            <p class="text-sm text-gray-800">We are pleased to inform you that your booking for the "Venice Dreams" package has been confirmed.</p>
                            <p class="text-xs text-gray-500 mt-1 text-right">10:24 AM</p>
                        </div>
                    </div>

                    <!-- Outgoing Message -->
                    <div class="flex justify-end items-end space-x-2">
                        <div class="bg-blue-500 text-white p-3 rounded-xl max-w-xs">
                            <p class="text-sm">Thank you for the update! Could you please send over the final itinerary?</p>
                            <p class="text-xs mt-1 text-right">10:26 AM</p>
                        </div>
                        <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'Admin User' }}&background=3B82F6&color=fff&size=32" 
                             alt="You" 
                             class="w-8 h-8 rounded-full">
                    </div>

                    <!-- Incoming Message -->
                    <div class="flex items-end space-x-2">
                        <img src="https://ui-avatars.com/api/?name=Europia+Hotel&background=random&size=32" 
                             alt="Europia Hotel" 
                             class="w-8 h-8 rounded-full">
                        <div class="bg-gray-100 p-3 rounded-xl max-w-xs">
                            <p class="text-sm text-gray-800">I've just sent it to your registered email address. Please let us know if you have any further questions.</p>
                            <p class="text-xs text-gray-500 mt-1 text-right">10:28 AM</p>
                        </div>
                    </div>
                </div>

                <!-- Message Input -->
                <div class="mt-4 pt-4 border-t border-gray-100 flex items-center space-x-3">
                    <button class="text-gray-400 hover:text-gray-600 p-2 rounded-full hover:bg-gray-100">
                        <i class="fas fa-paperclip"></i>
                    </button>
                    <input type="text" placeholder="Type your message..." 
                           class="flex-1 px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button class="bg-blue-600 text-white p-3 rounded-full hover:bg-blue-700 transition-colors">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Sidebar -->
    @include('partials.right-sidebar')
</div>
@endsection
