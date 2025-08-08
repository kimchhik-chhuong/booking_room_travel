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
                        <p class="text-3xl font-bold text-dark-800">207</p>
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
                        <p class="text-3xl font-bold text-dark-800">2</p>
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
                        <p class="text-3xl font-bold text-dark-800">5</p>
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
                    <input type="text" placeholder="Search conversations..." class="input-modern w-full pl-10" id="searchInput">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-400"></i>
                </div>
                
                <!-- Filter Tabs -->
                <div class="flex space-x-1 mb-6 bg-slate-100 rounded-2xl p-1">
                    <button class="flex-1 bg-white text-primary-600 px-4 py-3 rounded-xl text-sm font-semibold shadow-sm" id="filterAll">All</button>
                    <button class="flex-1 text-dark-600 px-4 py-3 rounded-xl text-sm font-semibold hover:bg-white hover:shadow-sm transition-all" id="filterUnread">Unread</button>
                    <button class="flex-1 text-dark-600 px-4 py-3 rounded-xl text-sm font-semibold hover:bg-white hover:shadow-sm transition-all" id="filterImportant">Important</button>
                </div>

                <div class="space-y-3 max-h-96 overflow-y-auto scrollbar-hide" id="conversationList">
                    @php
                    $conversations = [
                        ['name' => 'Europia Hotel', 'message' => 'We are pleased to inform you about the booking confirmation...', 'time' => '10:24 AM', 'unread' => true, 'avatar' => 'https://ui-avatars.com/api/?name=Europia+Hotel&background=random&size=40', 'id' => 'EH'],
                        ['name' => 'Global Travel Co', 'message' => 'We have updated our contract terms and conditions...', 'time' => '2:30 PM', 'unread' => true, 'avatar' => 'https://ui-avatars.com/api/?name=Global+Travel+Co&background=random&size=40', 'id' => 'GC'],
                        ['name' => 'Kalendra Umbara', 'message' => 'Hi, I have some questions about the Venice package...', 'time' => '5:45 AM', 'unread' => false, 'avatar' => 'https://ui-avatars.com/api/?name=Kalendra+Umbara&background=random&size=40', 'id' => 'KU'],
                        ['name' => 'Osman Farooq', 'message' => 'Hello, I had an amazing time on the Tokyo tour...', 'time' => '10:15 AM', 'unread' => false, 'avatar' => 'https://ui-avatars.com/api/?name=Osman+Farooq&background=random&size=40', 'id' => 'OF'],
                        ['name' => 'Mellinda Jenkins', 'message' => 'Can you send more details about the safari package?', 'time' => '7:24 PM', 'unread' => false, 'avatar' => 'https://ui-avatars.com/api/?name=Mellinda+Jenkins&background=random&size=40', 'id' => 'MJ'],
                    ];
                    @endphp
                    
                    @foreach($conversations as $conversation)
                    <div class="flex items-center space-x-4 p-4 hover:bg-slate-50 rounded-2xl cursor-pointer transition-all {{ $conversation['unread'] ? 'bg-blue-50 border-l-4 border-primary-500' : '' }}" data-conversation-id="{{ $conversation['id'] }}">
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
                <div class="flex items-center justify-between p-8 border-b border-slate-200" id="chatHeader">
                    <div class="flex items-center space-x-4">
                        <img src="https://ui-avatars.com/api/?name=Europia+Hotel&background=random&size=40" 
                             alt="Europia Hotel" 
                             class="w-12 h-12 rounded-xl shadow-md">
                        <div>
                            <p class="text-lg font-bold text-dark-800" id="chatName">Europia Hotel</p>
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
                <div class="flex-1 overflow-y-auto p-8 space-y-6" id="chatMessages">
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
                            <input type="text" id="messageInput" placeholder="Type your message..." 
                                   class="input-modern w-full pr-12">
                            <button class="absolute right-3 top-1/2 transform -translate-y-1/2 text-dark-400 hover:text-dark-600 transition-colors">
                                <i class="fas fa-smile"></i>
                            </button>
                        </div>
                        <button id="sendButton" class="btn-modern p-3">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize conversations data
    const conversations = [
        { id: 'EH', name: 'Europia Hotel', message: 'We are pleased to inform you about the booking confirmation...', time: '10:24 AM', unread: true, avatar: 'https://ui-avatars.com/api/?name=Europia+Hotel&background=random&size=40' },
        { id: 'GC', name: 'Global Travel Co', message: 'We have updated our contract terms and conditions...', time: '2:30 PM', unread: true, avatar: 'https://ui-avatars.com/api/?name=Global+Travel+Co&background=random&size=40' },
        { id: 'KU', name: 'Kalendra Umbara', message: 'Hi, I have some questions about the Venice package...', time: '5:45 AM', unread: false, avatar: 'https://ui-avatars.com/api/?name=Kalendra+Umbara&background=random&size=40' },
        { id: 'OF', name: 'Osman Farooq', message: 'Hello, I had an amazing time on the Tokyo tour...', time: '10:15 AM', unread: false, avatar: 'https://ui-avatars.com/api/?name=Osman+Farooq&background=random&size=40' },
        { id: 'MJ', name: 'Mellinda Jenkins', message: 'Can you send more details about the safari package?', time: '7:24 PM', unread: false, avatar: 'https://ui-avatars.com/api/?name=Mellinda+Jenkins&background=random&size=40' },
    ];

    // Current active conversation
    let currentConversation = conversations[0];

    // Load conversation with stored messages
    function loadConversation(conv) {
        currentConversation = conv;
        document.getElementById('chatName').textContent = conv.name;
        
        // Try to get messages from localStorage, fallback to default if none exist
        let storedMessages;
        try {
            storedMessages = JSON.parse(localStorage.getItem(`chat_${conv.id}`)) || [];
        } catch (e) {
            storedMessages = [];
        }
        
        // If no messages in storage and this is the first conversation, add default messages
        if (storedMessages.length === 0 && conv.id === 'EH') {
            storedMessages = [
                { text: 'We are pleased to inform you that your booking for the "Venice Dreams" package has been confirmed for June 25-30.', time: '10:24 AM', isSent: false },
                { text: 'Thank you for the confirmation! Could you please send over the detailed itinerary and hotel information?', time: '10:26 AM', isSent: true }
            ];
            localStorage.setItem(`chat_${conv.id}`, JSON.stringify(storedMessages));
        }
        
        // Render messages
        document.getElementById('chatMessages').innerHTML = storedMessages.map(msg => `
            <div class="flex ${msg.isSent ? 'justify-end' : 'items-end'} space-x-3">
                ${msg.isSent ? `
                    <div class="bg-primary-500 text-white p-4 rounded-2xl rounded-br-md max-w-xs lg:max-w-md">
                        <p class="text-sm">${msg.text}</p>
                        <p class="text-xs text-primary-100 mt-2">${msg.time}</p>
                    </div>
                    <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'Admin User' }}&background=0ea5e9&color=fff&size=32" alt="You" class="w-8 h-8 rounded-xl">
                ` : `
                    <img src="${conv.avatar}" alt="${conv.name}" class="w-8 h-8 rounded-xl">
                    <div class="bg-slate-100 p-4 rounded-2xl rounded-bl-md max-w-xs lg:max-w-md">
                        <p class="text-sm text-dark-800">${msg.text}</p>
                        <p class="text-xs text-dark-500 mt-2">${msg.time}</p>
                    </div>
                `}
            </div>
        `).join('');
        
        // Scroll to bottom
        document.getElementById('chatMessages').scrollTop = document.getElementById('chatMessages').scrollHeight;
        
        // Mark as read
        conv.unread = false;
        updateConversationList();
    }

    // Update conversation list UI
    function updateConversationList() {
        const conversationList = document.getElementById('conversationList');
        conversationList.innerHTML = '';
        
        conversations.forEach(conv => {
            const div = document.createElement('div');
            div.className = `flex items-center space-x-4 p-4 hover:bg-slate-50 rounded-2xl cursor-pointer transition-all ${conv.unread ? 'bg-blue-50 border-l-4 border-primary-500' : ''}`;
            div.setAttribute('data-conversation-id', conv.id);
            div.innerHTML = `
                <div class="relative">
                    <img src="${conv.avatar}" alt="${conv.name}" class="w-12 h-12 rounded-xl shadow-md">
                    ${conv.unread ? '<div class="absolute -top-1 -right-1 w-3 h-3 bg-primary-500 rounded-full animate-pulse-slow"></div>' : ''}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-dark-800 truncate ${conv.unread ? 'font-bold' : ''}">${conv.name}</p>
                        <p class="text-xs text-dark-500">${conv.time}</p>
                    </div>
                    <p class="text-xs text-dark-600 truncate ${conv.unread ? 'font-medium' : ''}">${conv.message}</p>
                </div>
            `;
            div.addEventListener('click', () => loadConversation(conv));
            conversationList.appendChild(div);
        });
    }

    // Event listener for conversation selection
    document.addEventListener('DOMContentLoaded', () => {
        updateConversationList();
        loadConversation(currentConversation);
    });

    // Send message functionality
    document.getElementById('sendButton').addEventListener('click', function() {
        const messageInput = document.getElementById('messageInput');
        const message = messageInput.value.trim();
        
        if (message) {
            const now = new Date();
            const time = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
            const newMessage = { text: message, time: time, isSent: true };
            
            // Get current messages or initialize if none exist
            let storedMessages;
            try {
                storedMessages = JSON.parse(localStorage.getItem(`chat_${currentConversation.id}`)) || [];
            } catch (e) {
                storedMessages = [];
            }
            
            storedMessages.push(newMessage);
            localStorage.setItem(`chat_${currentConversation.id}`, JSON.stringify(storedMessages));
            
            // Render new message
            const chatMessages = document.getElementById('chatMessages');
            const messageDiv = document.createElement('div');
            messageDiv.className = 'flex justify-end items-end space-x-3';
            messageDiv.innerHTML = `
                <div class="bg-primary-500 text-white p-4 rounded-2xl rounded-br-md max-w-xs lg:max-w-md">
                    <p class="text-sm">${message}</p>
                    <p class="text-xs text-primary-100 mt-2">${time}</p>
                </div>
                <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'Admin User' }}&background=0ea5e9&color=fff&size=32" 
                     alt="You" 
                     class="w-8 h-8 rounded-xl">
            `;
            chatMessages.appendChild(messageDiv);
            
            // Clear input and scroll to bottom
            messageInput.value = '';
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    });

    // Allow sending message with Enter key
    document.getElementById('messageInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            document.getElementById('sendButton').click();
        }
    });

    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const filtered = conversations.filter(conv => 
            conv.name.toLowerCase().includes(searchTerm) || 
            conv.message.toLowerCase().includes(searchTerm)
        );
        
        const conversationList = document.getElementById('conversationList');
        conversationList.innerHTML = '';
        
        filtered.forEach(conv => {
            const div = document.createElement('div');
            div.className = `flex items-center space-x-4 p-4 hover:bg-slate-50 rounded-2xl cursor-pointer transition-all ${conv.unread ? 'bg-blue-50 border-l-4 border-primary-500' : ''}`;
            div.setAttribute('data-conversation-id', conv.id);
            div.innerHTML = `
                <div class="relative">
                    <img src="${conv.avatar}" alt="${conv.name}" class="w-12 h-12 rounded-xl shadow-md">
                    ${conv.unread ? '<div class="absolute -top-1 -right-1 w-3 h-3 bg-primary-500 rounded-full animate-pulse-slow"></div>' : ''}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-dark-800 truncate ${conv.unread ? 'font-bold' : ''}">${conv.name}</p>
                        <p class="text-xs text-dark-500">${conv.time}</p>
                    </div>
                    <p class="text-xs text-dark-600 truncate ${conv.unread ? 'font-medium' : ''}">${conv.message}</p>
                </div>
            `;
            div.addEventListener('click', () => loadConversation(conv));
            conversationList.appendChild(div);
        });
    });

    // Filter functionality
    document.getElementById('filterAll').addEventListener('click', () => {
        updateConversationList();
    });

    document.getElementById('filterUnread').addEventListener('click', () => {
        const unreadConversations = conversations.filter(conv => conv.unread);
        renderFilteredConversations(unreadConversations);
    });

    document.getElementById('filterImportant').addEventListener('click', () => {
        // For now, same as unread filter
        document.getElementById('filterUnread').click();
    });

    function renderFilteredConversations(filteredConversations) {
        const conversationList = document.getElementById('conversationList');
        conversationList.innerHTML = '';
        
        filteredConversations.forEach(conv => {
            const div = document.createElement('div');
            div.className = `flex items-center space-x-4 p-4 hover:bg-slate-50 rounded-2xl cursor-pointer transition-all ${conv.unread ? 'bg-blue-50 border-l-4 border-primary-500' : ''}`;
            div.setAttribute('data-conversation-id', conv.id);
            div.innerHTML = `
                <div class="relative">
                    <img src="${conv.avatar}" alt="${conv.name}" class="w-12 h-12 rounded-xl shadow-md">
                    ${conv.unread ? '<div class="absolute -top-1 -right-1 w-3 h-3 bg-primary-500 rounded-full animate-pulse-slow"></div>' : ''}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-dark-800 truncate ${conv.unread ? 'font-bold' : ''}">${conv.name}</p>
                        <p class="text-xs text-dark-500">${conv.time}</p>
                    </div>
                    <p class="text-xs text-dark-600 truncate ${conv.unread ? 'font-medium' : ''}">${conv.message}</p>
                </div>
            `;
            div.addEventListener('click', () => loadConversation(conv));
            conversationList.appendChild(div);
        });
    }
</script>
@endpush