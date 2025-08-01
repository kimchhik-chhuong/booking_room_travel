@extends('layouts.dashboard')

@section('title', 'Messages')
@section('page-title', 'Messages')
@section('page-subtitle', 'Communicate with your customers and partners.')

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
                        <p class="text-dark-500 text-sm font-medium mb-2">Total Messages</p>
                        <p class="text-3xl font-bold text-dark-800">2,847</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+15.3% this month</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-envelope text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Unread Messages</p>
                        <p class="text-3xl font-bold text-dark-800">47</p>
                        <p class="text-red-500 text-sm font-medium mt-2">+8 new messages</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-red-400 to-red-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-envelope-open text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Response Time</p>
                        <p class="text-3xl font-bold text-dark-800">2.4h</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">-0.3h improvement</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-clock text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Active Chats</p>
                        <p class="text-3xl font-bold text-dark-800">23</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+5 active now</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-comments text-white text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages Interface -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Message List -->
            <div class="lg:col-span-1 card-modern p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-dark-800">Conversations</h3>
                    <button class="btn-modern text-sm px-4 py-2">
                        <i class="fas fa-plus mr-2"></i> New
                    </button>
                </div>
                <div class="relative mb-6">
                    <input type="text" placeholder="Search conversations..." class="input-modern w-full pl-10">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-400"></i>
                </div>
                
                <!-- Filter Tabs -->
                <div class="flex space-x-1 mb-6 bg-slate-100 rounded-2xl p-1">
                    <button class="flex-1 bg-white text-primary-600 px-4 py-3 rounded-xl text-sm font-semibold shadow-sm">All</button>
                    <button class="flex-1 text-dark-600 px-4 py-3 rounded-xl text-sm font-semibold hover:bg-white hover:shadow-sm transition-all">Unread</button>
                    <button class="flex-1 text-dark-600 px-4 py-3 rounded-xl text-sm font-semibold hover:bg-white hover:shadow-sm transition-all">Important</button>
                </div>

                <div class="space-y-3 max-h-96 overflow-y-auto scrollbar-hide">
                    @php
                    $conversations = [
                        ['name' => 'Europia Hotel', 'message' => 'We are pleased to inform you about the booking confirmation...', 'time' => '10:24 AM', 'unread' => true, 'avatar' => 'https://ui-avatars.com/api/?name=Europia+Hotel&background=random&size=40'],
                        ['name' => 'Global Travel Co', 'message' => 'We have updated our contract terms and conditions...', 'time' => '2:30 PM', 'unread' => true, 'avatar' => 'https://ui-avatars.com/api/?name=Global+Travel+Co&background=random&size=40'],
                        ['name' => 'Kalendra Umbara', 'message' => 'Hi, I have some questions about the Venice package...', 'time' => '5:45 AM', 'unread' => true, 'avatar' => 'https://ui-avatars.com/api/?name=Kalendra+Umbara&background=random&size=40'],
                        ['name' => 'Osman Farooq', 'message' => 'Hello, I had an amazing time on the Tokyo tour...', 'time' => '10:15 AM', 'unread' => false, 'avatar' => 'https://ui-avatars.com/api/?name=Osman+Farooq&background=random&size=40'],
                        ['name' => 'Mellinda Jenkins', 'message' => 'Can you send more details about the safari package?', 'time' => '7:24 PM', 'unread' => false, 'avatar' => 'https://ui-avatars.com/api/?name=Mellinda+Jenkins&background=random&size=40'],
                        ['name' => 'David Hernandez', 'message' => 'I would like to upgrade my booking to premium...', 'time' => '10:06 AM', 'unread' => true, 'avatar' => 'https://ui-avatars.com/api/?name=David+Hernandez&background=random&size=40'],
                        ['name' => 'Alexandra Green', 'message' => 'Our company is interested in group bookings...', 'time' => '12:30 PM', 'unread' => true, 'avatar' => 'https://ui-avatars.com/api/?name=Alexandra+Green&background=random&size=40']
                    ];
                    @endphp
                    
                    @foreach($conversations as $conversation)
                    <div class="flex items-center space-x-4 p-4 hover:bg-slate-50 rounded-2xl cursor-pointer transition-all {{ $conversation['unread'] ? 'bg-blue-50 border-l-4 border-primary-500' : '' }}">
                        <div class="relative">
                            <img src="{{ $conversation['avatar'] }}" 
                                 alt="{{ $conversation['name'] }}" 
                                 class="w-12 h-12 rounded-xl shadow-md">
                            @if($conversation['unread'])
                            <div class="absolute -top-1 -right-1 w-3 h-3 bg-primary-500 rounded-full animate-pulse-slow"></div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-semibold text-dark-800 truncate {{ $conversation['unread'] ? 'font-bold' : '' }}">{{ $conversation['name'] }}</p>
                                <p class="text-xs text-dark-500">{{ $conversation['time'] }}</p>
                            </div>
                            <p class="text-xs text-dark-600 truncate {{ $conversation['unread'] ? 'font-medium' : '' }}">{{ $conversation['message'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Chat Area -->
            <div class="lg:col-span-2 card-modern flex flex-col h-[600px]">
                <!-- Chat Header -->
                <div class="flex items-center justify-between p-8 border-b border-slate-200">
                    <div class="flex items-center space-x-4">
                        <img src="https://ui-avatars.com/api/?name=Europia+Hotel&background=random&size=40" 
                             alt="Europia Hotel" 
                             class="w-12 h-12 rounded-xl shadow-md">
                        <div>
                            <p class="text-lg font-bold text-dark-800">Europia Hotel</p>
                            <p class="text-sm text-emerald-600 flex items-center">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></span>Online
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button class="p-3 text-dark-400 hover:text-dark-600 hover:bg-slate-50 rounded-xl transition-all">
                            <i class="fas fa-phone"></i>
                        </button>
                        <button class="p-3 text-dark-400 hover:text-dark-600 hover:bg-slate-50 rounded-xl transition-all">
                            <i class="fas fa-video"></i>
                        </button>
                        <button class="p-3 text-dark-400 hover:text-dark-600 hover:bg-slate-50 rounded-xl transition-all">
                            <i class="fas fa-info-circle"></i>
                        </button>
                        <button class="p-3 text-dark-400 hover:text-dark-600 hover:bg-slate-50 rounded-xl transition-all">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                    </div>
                </div>

                <!-- Chat Messages -->
                <div class="flex-1 overflow-y-auto p-8 space-y-6">
                    <!-- Incoming Message -->
                    <div class="flex items-end space-x-3">
                        <img src="https://ui-avatars.com/api/?name=Europia+Hotel&background=random&size=32" 
                             alt="Europia Hotel" 
                             class="w-8 h-8 rounded-xl">
                        <div class="bg-slate-100 p-4 rounded-2xl rounded-bl-md max-w-xs lg:max-w-md">
                            <p class="text-sm text-dark-800">We are pleased to inform you that your booking for the "Venice Dreams" package has been confirmed for June 25-30.</p>
                            <p class="text-xs text-dark-500 mt-2">10:24 AM</p>
                        </div>
                    </div>

                    <!-- Outgoing Message -->
                    <div class="flex justify-end items-end space-x-3">
                        <div class="bg-primary-500 text-white p-4 rounded-2xl rounded-br-md max-w-xs lg:max-w-md">
                            <p class="text-sm">Thank you for the confirmation! Could you please send over the detailed itinerary and hotel information?</p>
                            <p class="text-xs text-primary-100 mt-2">10:26 AM</p>
                        </div>
                        <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'Admin User' }}&background=0ea5e9&color=fff&size=32" 
                             alt="You" 
                             class="w-8 h-8 rounded-xl">
                    </div>

                    <!-- Incoming Message with Attachment -->
                    <div class="flex items-end space-x-3">
                        <img src="https://ui-avatars.com/api/?name=Europia+Hotel&background=random&size=32" 
                             alt="Europia Hotel" 
                             class="w-8 h-8 rounded-xl">
                        <div class="bg-slate-100 p-4 rounded-2xl rounded-bl-md max-w-xs lg:max-w-md">
                            <p class="text-sm text-dark-800 mb-4">I've attached the complete itinerary and hotel details. Please review and let us know if you have any questions.</p>
                            <div class="bg-white p-4 rounded-xl border border-slate-200 flex items-center space-x-3">
                                <div class="bg-blue-100 p-3 rounded-xl">
                                    <i class="fas fa-file-pdf text-blue-600"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-dark-800">Venice_Itinerary.pdf</p>
                                    <p class="text-xs text-dark-500">2.4 MB</p>
                                </div>
                                <button class="text-primary-600 hover:text-primary-700 transition-colors">
                                    <i class="fas fa-download"></i>
                                </button>
                            </div>
                            <p class="text-xs text-dark-500 mt-2">10:28 AM</p>
                        </div>
                    </div>

                    <!-- Typing Indicator -->
                    <div class="flex items-end space-x-3">
                        <img src="https://ui-avatars.com/api/?name=Europia+Hotel&background=random&size=32" 
                             alt="Europia Hotel" 
                             class="w-8 h-8 rounded-xl">
                        <div class="bg-slate-100 p-4 rounded-2xl rounded-bl-md">
                            <div class="flex space-x-1">
                                <div class="w-2 h-2 bg-dark-400 rounded-full animate-bounce"></div>
                                <div class="w-2 h-2 bg-dark-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                                <div class="w-2 h-2 bg-dark-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Message Input -->
                <div class="p-8 border-t border-slate-200">
                    <div class="flex items-center space-x-4">
                        <button class="p-3 text-dark-400 hover:text-dark-600 hover:bg-slate-50 rounded-xl transition-all">
                            <i class="fas fa-paperclip"></i>
                        </button>
                        <button class="p-3 text-dark-400 hover:text-dark-600 hover:bg-slate-50 rounded-xl transition-all">
                            <i class="fas fa-image"></i>
                        </button>
                        <div class="flex-1 relative">
                            <input type="text" placeholder="Type your message..." 
                                   class="input-modern w-full pr-12">
                            <button class="absolute right-3 top-1/2 transform -translate-y-1/2 text-dark-400 hover:text-dark-600 transition-colors">
                                <i class="fas fa-smile"></i>
                            </button>
                        </div>
                        <button class="btn-modern p-3">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
