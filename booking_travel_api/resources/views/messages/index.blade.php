{{-- @extends('layouts.dashboard')

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
            <div class="stat-card bg-white p-4 rounded-lg shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium mb-2">Total Messages</p>
                        <p class="text-3xl font-bold text-gray-800">207</p>
                        <p class="text-green-600 text-sm font-medium mt-2">+15.3% this month</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-envelope text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card bg-white p-4 rounded-lg shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium mb-2">Unread Messages</p>
                        <p class="text-3xl font-bold text-gray-800">2</p>
                        <p class="text-red-500 text-sm font-medium mt-2">+8 new messages</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-envelope-open text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card bg-white p-4 rounded-lg shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium mb-2">Response Time</p>
                        <p class="text-3xl font-bold text-gray-800">2.4h</p>
                        <p class="text-green-600 text-sm font-medium mt-2">-0.3h improvement</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-clock text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card bg-white p-4 rounded-lg shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium mb-2">Active Chats</p>
                        <p class="text-3xl font-bold text-gray-800">5</p>
                        <p class="text-green-600 text-sm font-medium mt-2">+5 active now</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-comments text-white text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages Interface -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Message List -->
            <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Conversations</h3>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-plus mr-2"></i> New
                    </button>
                </div>
                <div class="relative mb-6">
                    <input type="text" placeholder="Search conversations..." class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="searchInput" aria-label="Search conversations">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
                
                <!-- Filter Tabs -->
                <div class="flex space-x-1 mb-6 bg-gray-100 rounded-xl p-1">
                    <button class="flex-1 bg-white text-blue-600 px-4 py-2 rounded-lg text-sm font-semibold shadow-sm" id="filterAll" aria-label="Show all conversations">All</button>
                    <button class="flex-1 text-gray-600 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-white hover:shadow-sm transition" id="filterUnread" aria-label="Show unread conversations">Unread</button>
                    <button class="flex-1 text-gray-600 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-white hover:shadow-sm transition" id="filterImportant" aria-label="Show important conversations">Important</button>
                </div>

                <div class="space-y-3 max-h-96 overflow-y-auto scrollbar-thin" id="conversationList">
                    @php
                    $conversations = [
                        ['id' => 'EH', 'name' => 'Europia Hotel', 'message' => 'We are pleased to inform you about the booking confirmation...', 'time' => '10:24 AM', 'unread' => true, 'avatar' => 'https://ui-avatars.com/api/?name=Europia+Hotel&background=random&size=40'],
                        ['id' => 'GC', 'name' => 'Global Travel Co', 'message' => 'We have updated our contract terms and conditions...', 'time' => '2:30 PM', 'unread' => true, 'avatar' => 'https://ui-avatars.com/api/?name=Global+Travel+Co&background=random&size=40'],
                        ['id' => 'KU', 'name' => 'Kalendra Umbara', 'message' => 'Hi, I have some questions about the Venice package...', 'time' => '5:45 AM', 'unread' => false, 'avatar' => 'https://ui-avatars.com/api/?name=Kalendra+Umbara&background=random&size=40'],
                        ['id' => 'OF', 'name' => 'Osman Farooq', 'message' => 'Hello, I had an amazing time on the Tokyo tour...', 'time' => '10:15 AM', 'unread' => false, 'avatar' => 'https://ui-avatars.com/api/?name=Osman+Farooq&background=random&size=40'],
                        ['id' => 'MJ', 'name' => 'Mellinda Jenkins', 'message' => 'Can you send more details about the safari package?', 'time' => '7:24 PM', 'unread' => false, 'avatar' => 'https://ui-avatars.com/api/?name=Mellinda+Jenkins&background=random&size=40'],
                    ];
                    @endphp
                    
                    @foreach($conversations as $conversation)
                    <div class="flex items-center space-x-4 p-4 hover:bg-gray-50 rounded-xl cursor-pointer transition {{ $conversation['unread'] ? 'bg-blue-50 border-l-4 border-blue-500' : '' }}" data-conversation-id="{{ $conversation['id'] }}" role="button" tabindex="0">
                        <div class="relative">
                            <img src="{{ $conversation['avatar'] }}" alt="{{ $conversation['name'] }}" class="w-12 h-12 rounded-xl shadow">
                            @if($conversation['unread'])
                            <div class="absolute -top-1 -right-1 w-3 h-3 bg-blue-500 rounded-full animate-pulse-slow"></div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-semibold text-gray-800 truncate {{ $conversation['unread'] ? 'font-bold' : '' }}">{{ $conversation['name'] }}</p>
                                <p class="text-xs text-gray-500">{{ $conversation['time'] }}</p>
                            </div>
                            <p class="text-xs text-gray-600 truncate {{ $conversation['unread'] ? 'font-medium' : '' }}">{{ $conversation['message'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Chat Area -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow flex flex-col h-[600px]">
                <!-- Chat Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200" id="chatHeader">
                    <div class="flex items-center space-x-4">
                        <img src="https://ui-avatars.com/api/?name=Europia+Hotel&background=random&size=40" alt="Europia Hotel" class="w-12 h-12 rounded-xl shadow">
                        <div>
                            <p class="text-lg font-bold text-gray-800" id="chatName">Europia Hotel</p>
                            <p class="text-sm text-green-600 flex items-center">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>Online
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3" id="callControls">
                        <button class="p-3 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-xl transition" id="callBtn" aria-label="Call">
                            <i class="fas fa-phone"></i>
                        </button>
                        <button class="p-3 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-xl transition" id="videoBtn" aria-label="Video call">
                            <i class="fas fa-video"></i>
                        </button>
                        <button class="p-3 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-xl transition" id="infoBtn" aria-label="Info">
                            <i class="fas fa-info-circle"></i>
                        </button>
                        <button class="p-3 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-xl transition" id="moreBtn" aria-label="More options">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                    </div>
                </div>

                <!-- Chat Messages -->
                <div class="flex-1 overflow-y-auto p-6 space-y-6" id="chatMessages" role="region" aria-live="polite">
                    <!-- Incoming Message (Example) -->
                    <div class="flex items-end space-x-3">
                        <img src="https://ui-avatars.com/api/?name=Europia+Hotel&background=random&size=32" alt="Europia Hotel" class="w-8 h-8 rounded-xl">
                        <div class="bg-gray-100 p-3 rounded-xl rounded-bl-md max-w-xs lg:max-w-md">
                            <p class="text-sm text-gray-800">We are pleased to inform you that your booking for the "Venice Dreams" package has been confirmed for June 25-30.</p>
                            <p class="text-xs text-gray-500 mt-1">10:24 AM</p>
                        </div>
                    </div>

                    <!-- Outgoing Message (Example) -->
                    <div class="flex justify-end items-end space-x-3">
                        <div class="bg-blue-600 text-white p-3 rounded-xl rounded-br-md max-w-xs lg:max-w-md">
                            <p class="text-sm">Thank you for the confirmation! Could you please send over the detailed itinerary and hotel information?</p>
                            <p class="text-xs text-blue-100 mt-1">10:26 AM</p>
                        </div>
                        <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'Admin User' }}&background=0ea5e9&color=fff&size=32" alt="You" class="w-8 h-8 rounded-xl">
                    </div>
                </div>

                <!-- Message Input -->
                <div class="p-6 border-t border-gray-200">
                    <div class="flex items-center space-x-4">
                        <button class="p-3 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-xl transition" id="attachFileBtn" aria-label="Attach file">
                            <i class="fas fa-paperclip"></i>
                        </button>
                        <button class="p-3 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-xl transition" id="attachImageBtn" aria-label="Attach image">
                            <i class="fas fa-image"></i>
                        </button>
                        <div class="flex-1 relative">
                            <input type="text" id="messageInput" placeholder="Type your message..." class="w-full pl-4 pr-12 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" aria-label="Type message">
                            <button class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition" aria-label="Add emoji">
                                <i class="fas fa-smile"></i>
                            </button>
                        </div>
                        <button id="sendButton" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition" aria-label="Send message">
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
    // Conversations data (same as before)
    const conversations = [
        { id: 'EH', name: 'Europia Hotel', message: 'We are pleased to inform you about the booking confirmation...', time: '10:24 AM', unread: true, avatar: 'https://ui-avatars.com/api/?name=Europia+Hotel&background=random&size=40' },
        { id: 'GC', name: 'Global Travel Co', message: 'We have updated our contract terms and conditions...', time: '2:30 PM', unread: true, avatar: 'https://ui-avatars.com/api/?name=Global+Travel+Co&background=random&size=40' },
        { id: 'KU', name: 'Kalendra Umbara', message: 'Hi, I have some questions about the Venice package...', time: '5:45 AM', unread: false, avatar: 'https://ui-avatars.com/api/?name=Kalendra+Umbara&background=random&size=40' },
        { id: 'OF', name: 'Osman Farooq', message: 'Hello, I had an amazing time on the Tokyo tour...', time: '10:15 AM', unread: false, avatar: 'https://ui-avatars.com/api/?name=Osman+Farooq&background=random&size=40' },
        { id: 'MJ', name: 'Mellinda Jenkins', message: 'Can you send more details about the safari package?', time: '7:24 PM', unread: false, avatar: 'https://ui-avatars.com/api/?name=Mellinda+Jenkins&background=random&size=40' },
    ];

    let currentConversation = conversations[0];

    // Function to update unread count in navigation
    function updateUnreadCount() {
        const unreadCount = conversations.filter(conv => conv.unread).length;
        const unreadSpan = document.getElementById('unreadCount');
        if (unreadCount > 0) {
            unreadSpan.textContent = unreadCount;
            unreadSpan.classList.remove('hidden');
        } else {
            unreadSpan.classList.add('hidden');
        }
    }

    // Load conversation (updated to mark as read)
    function loadConversation(conv) {
        currentConversation = conv;
        document.getElementById('chatName').textContent = conv.name;

        let storedMessages = JSON.parse(localStorage.getItem(`chat_${conv.id}`) || '[]');
        if (storedMessages.length === 0 && conv.id === 'EH') {
            storedMessages = [
                { text: 'We are pleased to inform you that your booking for the "Venice Dreams" package has been confirmed for June 25-30.', time: '10:24 AM', isSent: false },
                { text: 'Thank you for the confirmation! Could you please send over the detailed itinerary and hotel information?', time: '10:26 AM', isSent: true }
            ];
            localStorage.setItem(`chat_${conv.id}`, JSON.stringify(storedMessages));
        }

        // Mark as read when opened
        if (conv.unread) {
            conv.unread = false;
        }
        renderMessages(storedMessages);
        updateConversationList();
        updateUnreadCount(); // Update badge after marking as read
    }

    // Render messages (same as before)
    function renderMessages(messages) {
        const chatMessages = document.getElementById('chatMessages');
        chatMessages.innerHTML = messages.map(msg => {
            const content = msg.file ? `<img src="${msg.file}" alt="${msg.isImage ? 'Attached image' : 'Attached file'}" class="max-w-xs lg:max-w-md rounded-lg mt-2">` : `<p class="text-sm">${msg.text}</p>`;
            return `
                <div class="flex ${msg.isSent ? 'justify-end' : 'items-end'} space-x-3">
                    ${msg.isSent ? `
                        <div class="bg-blue-600 text-white p-3 rounded-xl rounded-br-md max-w-xs lg:max-w-md">
                            ${content}
                            <p class="text-xs text-blue-100 mt-1">${msg.time}</p>
                        </div>
                        <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(auth()?.user?.name ?? 'Admin User')}&background=0ea5e9&color=fff&size=32" alt="You" class="w-8 h-8 rounded-xl">
                    ` : `
                        <img src="${currentConversation.avatar}" alt="${currentConversation.name}" class="w-8 h-8 rounded-xl">
                        <div class="bg-gray-100 p-3 rounded-xl rounded-bl-md max-w-xs lg:max-w-md">
                            ${content}
                            <p class="text-xs text-gray-500 mt-1">${msg.time}</p>
                        </div>
                    `}
                </div>
            `;
        }).join('');
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Update conversation list (same as before)
    function updateConversationList(filter = null) {
        const conversationList = document.getElementById('conversationList');
        conversationList.innerHTML = '';
        const convsToShow = filter ? filter : conversations;
        convsToShow.forEach(conv => {
            const div = document.createElement('div');
            div.className = `flex items-center space-x-4 p-4 hover:bg-gray-50 rounded-xl cursor-pointer transition ${conv.unread ? 'bg-blue-50 border-l-4 border-blue-500' : ''}`;
            div.setAttribute('data-conversation-id', conv.id);
            div.setAttribute('role', 'button');
            div.setAttribute('tabindex', '0');
            div.innerHTML = `
                <div class="relative">
                    <img src="${conv.avatar}" alt="${conv.name}" class="w-12 h-12 rounded-xl shadow">
                    ${conv.unread ? '<div class="absolute -top-1 -right-1 w-3 h-3 bg-blue-500 rounded-full animate-pulse-slow"></div>' : ''}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-gray-800 truncate ${conv.unread ? 'font-bold' : ''}">${conv.name}</p>
                        <p class="text-xs text-gray-500">${conv.time}</p>
                    </div>
                    <p class="text-xs text-gray-600 truncate ${conv.unread ? 'font-medium' : ''}">${conv.message}</p>
                </div>
            `;
            div.addEventListener('click', () => loadConversation(conv));
            div.addEventListener('keydown', (e) => { if (e.key === 'Enter') loadConversation(conv); });
            conversationList.appendChild(div);
        });
    }

    // Event listeners (same as before, with added message sending logic)
    document.addEventListener('DOMContentLoaded', () => {
        updateConversationList();
        loadConversation(currentConversation);
        updateUnreadCount(); // Initial count

        // Call, video, info, and more buttons (same as before)
        document.getElementById('callBtn').addEventListener('click', () => {
            if (confirm(`Initiate a call with ${currentConversation.name} at ${new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', timeZone: 'Asia/Bangkok' })}?`)) {
                alert(`Calling ${currentConversation.name}... (This is a placeholder. Implement WebRTC for real calls.)`);
            }
        });

        document.getElementById('videoBtn').addEventListener('click', () => {
            if (confirm(`Start a video call with ${currentConversation.name} at ${new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', timeZone: 'Asia/Bangkok' })}?`)) {
                alert(`Starting video call with ${currentConversation.name}... (This is a placeholder. Implement WebRTC for real calls.)`);
            }
        });

        document.getElementById('infoBtn').addEventListener('click', () => {
            alert(`Info for ${currentConversation.name}: Online status, last active ${currentConversation.time}`);
        });

        document.getElementById('moreBtn').addEventListener('click', () => {
            alert(`More options for ${currentConversation.name} are available. (Placeholder)`);
        });

        // File and image upload (same as before)
        document.getElementById('attachFileBtn').addEventListener('click', () => {
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = '*/*';
            input.onchange = (e) => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        sendFileMessage(file.name, event.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            };
            input.click();
        });

        document.getElementById('attachImageBtn').addEventListener('click', () => {
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*';
            input.onchange = (e) => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        sendFileMessage(file.name, event.target.result, true);
                    };
                    reader.readAsDataURL(file);
                }
            };
            input.click();
        });

        // Send message (updated to simulate incoming messages)
        document.getElementById('sendButton').addEventListener('click', () => {
            const messageInput = document.getElementById('messageInput');
            const message = messageInput.value.trim();
            if (message) {
                sendTextMessage(message);
                messageInput.value = '';

                // Simulate a reply from the user (admin's perspective)
                setTimeout(() => {
                    const reply = { text: `Thank you! I'll get back to you soon about ${message.split(' ')[0]}.`, time: new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', timeZone: 'Asia/Bangkok' }), isSent: false };
                    let storedMessages = JSON.parse(localStorage.getItem(`chat_${currentConversation.id}`) || '[]');
                    storedMessages.push(reply);
                    localStorage.setItem(`chat_${currentConversation.id}`, JSON.stringify(storedMessages));
                    renderMessages(storedMessages);

                    // Mark as unread if it's a new conversation
                    if (!conversations.some(c => c.id === currentConversation.id && c.unread)) {
                        currentConversation.unread = true;
                        updateConversationList();
                        updateUnreadCount();
                    }
                }, 2000); // Simulated delay for reply
            }
        });

        document.getElementById('messageInput').addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                document.getElementById('sendButton').click();
            }
        });

        document.getElementById('searchInput').addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            const filtered = conversations.filter(conv => 
                conv.name.toLowerCase().includes(searchTerm) || 
                conv.message.toLowerCase().includes(searchTerm)
            );
            updateConversationList(filtered);
        });

        document.getElementById('filterAll').addEventListener('click', () => updateConversationList());
        document.getElementById('filterUnread').addEventListener('click', () => updateConversationList(conversations.filter(conv => conv.unread)));
        document.getElementById('filterImportant').addEventListener('click', () => updateConversationList(conversations.filter(conv => conv.unread)));
    });

    // Send text message (same as before)
    function sendTextMessage(text) {
        const now = new Date();
        const time = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', timeZone: 'Asia/Bangkok' });
        const newMessage = { text, time, isSent: true };

        let storedMessages = JSON.parse(localStorage.getItem(`chat_${currentConversation.id}`) || '[]');
        storedMessages.push(newMessage);
        localStorage.setItem(`chat_${currentConversation.id}`, JSON.stringify(storedMessages));
        renderMessages(storedMessages);
    }

    // Send file or image message (same as before)
    function sendFileMessage(filename, dataUrl, isImage = false) {
        const now = new Date();
        const time = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', timeZone: 'Asia/Bangkok' });
        const newMessage = { file: dataUrl, time, isSent: true, isImage };

        let storedMessages = JSON.parse(localStorage.getItem(`chat_${currentConversation.id}`) || '[]');
        storedMessages.push(newMessage);
        localStorage.setItem(`chat_${currentConversation.id}`, JSON.stringify(storedMessages));
        renderMessages(storedMessages);
    }

    // Mock auth function (same as before)
    function auth() {
        return { user: { name: '{{ auth()->user()->name ?? 'Admin User' }}' } };
    }
</script>
@endpush --}}


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
        @if(!Auth::check())
            <p class="text-red-500">Please log in to access messages.</p>
        @else
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
                <div class="stat-card bg-white p-4 rounded-lg shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium mb-2">Total Messages</p>
                            <p class="text-3xl font-bold text-gray-800">207</p>
                            <p class="text-green-600 text-sm font-medium mt-2">+15.3% this month</p>
                        </div>
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-envelope text-white text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="stat-card bg-white p-4 rounded-lg shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium mb-2">Unread Messages</p>
                            <p class="text-3xl font-bold text-gray-800">2</p>
                            <p class="text-red-500 text-sm font-medium mt-2">+8 new messages</p>
                        </div>
                        <div class="w-14 h-14 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-envelope-open text-white text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="stat-card bg-white p-4 rounded-lg shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium mb-2">Response Time</p>
                            <p class="text-3xl font-bold text-gray-800">2.4h</p>
                            <p class="text-green-600 text-sm font-medium mt-2">-0.3h improvement</p>
                        </div>
                        <div class="w-14 h-14 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-clock text-white text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="stat-card bg-white p-4 rounded-lg shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium mb-2">Active Chats</p>
                            <p class="text-3xl font-bold text-gray-800">5</p>
                            <p class="text-green-600 text-sm font-medium mt-2">+5 active now</p>
                        </div>
                        <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-comments text-white text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Messages Interface -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Message List -->
                <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-800">Conversations</h3>
                        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-plus mr-2"></i> New
                        </button>
                    </div>
                    <div class="relative mb-6">
                        <input type="text" placeholder="Search conversations..." class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="searchInput" aria-label="Search conversations">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    
                    <!-- Filter Tabs -->
                    <div class="flex space-x-1 mb-6 bg-gray-100 rounded-xl p-1">
                        <button class="flex-1 bg-white text-blue-600 px-4 py-2 rounded-lg text-sm font-semibold shadow-sm" id="filterAll" aria-label="Show all conversations">All</button>
                        <button class="flex-1 text-gray-600 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-white hover:shadow-sm transition" id="filterUnread" aria-label="Show unread conversations">Unread</button>
                        <button class="flex-1 text-gray-600 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-white hover:shadow-sm transition" id="filterImportant" aria-label="Show important conversations">Important</button>
                    </div>

                    <div class="space-y-3 max-h-96 overflow-y-auto scrollbar-thin" id="conversationList">
                        <!-- Conversations will be loaded dynamically via JavaScript -->
                    </div>
                </div>

                <!-- Chat Area -->
                <div class="lg:col-span-2 bg-white rounded-lg shadow flex flex-col h-[600px]">
                    <!-- Chat Header -->
                    <div class="flex items-center justify-between p-6 border-b border-gray-200" id="chatHeader">
                        <div class="flex items-center space-x-4">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'LoggedInUser') }}&background=random&size=40" alt="{{ Auth::user()->name ?? 'LoggedInUser' }}" class="w-12 h-12 rounded-xl shadow" id="chatAvatar">
                            <div>
                                <p class="text-lg font-bold text-gray-800" id="chatName">{{ Auth::user()->name ?? 'LoggedInUser' }}</p>
                                <p class="text-sm text-green-600 flex items-center">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>Online
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3" id="callControls">
                            <button class="p-3 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-xl transition" id="callBtn" aria-label="Call">
                                <i class="fas fa-phone"></i>
                            </button>
                            <button class="p-3 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-xl transition" id="videoBtn" aria-label="Video call">
                                <i class="fas fa-video"></i>
                            </button>
                            <button class="p-3 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-xl transition" id="infoBtn" aria-label="Info">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            <button class="p-3 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-xl transition" id="moreBtn" aria-label="More options">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Chat Messages -->
                    <div class="flex-1 overflow-y-auto p-6 space-y-6" id="chatMessages" role="region" aria-live="polite">
                        <!-- Messages will be loaded dynamically -->
                    </div>

                    <!-- Message Input -->
                    <div class="p-6 border-t border-gray-200">
                        <div class="flex items-center space-x-4">
                            <button class="p-3 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-xl transition" id="attachFileBtn" aria-label="Attach file">
                                <i class="fas fa-paperclip"></i>
                            </button>
                            <button class="p-3 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-xl transition" id="attachImageBtn" aria-label="Attach image">
                                <i class="fas fa-image"></i>
                            </button>
                            <div class="flex-1 relative">
                                <input type="text" id="messageInput" placeholder="Type your message..." class="w-full pl-4 pr-12 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" aria-label="Type message">
                                <button class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition" aria-label="Add emoji">
                                    <i class="fas fa-smile"></i>
                                </button>
                            </div>
                            <button id="sendButton" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition" aria-label="Send message">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Base URL for API (adjust as needed)
    const BASE_URL = '{{ url('/') }}/api';

    let conversations = [];
    let currentConversation = null;

    // Load conversations from API
    async function loadConversations() {
        try {
            const response = await fetch(`${BASE_URL}/messages`, {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('auth_token') || ''}` // Assuming token is stored
                }
            });
            const data = await response.json();
            if (data.success) {
                conversations = data.data.map(conv => ({
                    id: conv.user_id,
                    name: conv.name,
                    message: conv.last_message,
                    time: conv.updated_at,
                    unread: !conv.is_read,
                    avatar: `https://ui-avatars.com/api/?name=${encodeURIComponent(conv.name)}&background=random&size=40`
                }));
            }
        } catch (error) {
            console.error('Error loading conversations:', error);
        }
    }

    // Save conversations to local storage
    function saveConversations() {
        localStorage.setItem('conversations', JSON.stringify(conversations));
    }

    // Load conversation
    function loadConversation(conv) {
        if (conv.name !== '{{ Auth::user()->name ?? 'LoggedInUser' }}') return; // Restrict to logged-in user
        currentConversation = conv;
        document.getElementById('chatName').textContent = conv.name;
        document.getElementById('chatAvatar').src = conv.avatar;
        loadMessages(conv.id);
    }

    // Load messages for a conversation
    async function loadMessages(convId) {
        try {
            const response = await fetch(`${BASE_URL}/messages/${convId}`, {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('auth_token') || ''}`
                }
            });
            const data = await response.json();
            if (data.success) {
                let storedMessages = data.data.map(msg => ({
                    text: msg.content,
                    time: msg.time,
                    isSent: msg.sender === 'You',
                    file: msg.type === 'image' ? msg.content : null,
                    isImage: msg.type === 'image'
                }));
                renderMessages(storedMessages);
                if (currentConversation.unread) currentConversation.unread = false;
                updateConversationList();
            }
        } catch (error) {
            console.error('Error loading messages:', error);
        }
    }

    // Render messages
    function renderMessages(messages) {
        const chatMessages = document.getElementById('chatMessages');
        chatMessages.innerHTML = messages.map(msg => {
            const content = msg.file ? `<img src="${msg.file}" alt="${msg.isImage ? 'Attached image' : 'Attached file'}" class="max-w-xs lg:max-w-md rounded-lg mt-2">` : `<p class="text-sm">${msg.text}</p>`;
            return `
                <div class="flex ${msg.isSent ? 'justify-end' : 'items-end'} space-x-3">
                    ${msg.isSent ? `
                        <div class="bg-blue-600 text-white p-3 rounded-xl rounded-br-md max-w-xs lg:max-w-md">
                            ${content}
                            <p class="text-xs text-blue-100 mt-1">${msg.time}</p>
                        </div>
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin User') }}&background=0ea5e9&color=fff&size=32" alt="You" class="w-8 h-8 rounded-xl">
                    ` : `
                        <img src="${currentConversation.avatar}" alt="${currentConversation.name}" class="w-8 h-8 rounded-xl">
                        <div class="bg-gray-100 p-3 rounded-xl rounded-bl-md max-w-xs lg:max-w-md">
                            ${content}
                            <p class="text-xs text-gray-500 mt-1">${msg.time}</p>
                        </div>
                    `}
                </div>
            `;
        }).join('');
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Update conversation list
    function updateConversationList(filter = null) {
        const conversationList = document.getElementById('conversationList');
        conversationList.innerHTML = '';
        const convsToShow = filter ? filter : conversations.filter(conv => conv.name === '{{ Auth::user()->name ?? 'LoggedInUser' }}');
        convsToShow.forEach(conv => {
            const div = document.createElement('div');
            div.className = `flex items-center space-x-4 p-4 hover:bg-gray-50 rounded-xl cursor-pointer transition ${conv.unread ? 'bg-blue-50 border-l-4 border-blue-500' : ''}`;
            div.setAttribute('data-conversation-id', conv.id);
            div.setAttribute('role', 'button');
            div.setAttribute('tabindex', '0');
            div.innerHTML = `
                <div class="relative">
                    <img src="${conv.avatar}" alt="${conv.name}" class="w-12 h-12 rounded-xl shadow">
                    ${conv.unread ? '<div class="absolute -top-1 -right-1 w-3 h-3 bg-blue-500 rounded-full animate-pulse-slow"></div>' : ''}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-gray-800 truncate ${conv.unread ? 'font-bold' : ''}">${conv.name}</p>
                        <p class="text-xs text-gray-500">${conv.time}</p>
                    </div>
                    <p class="text-xs text-gray-600 truncate ${conv.unread ? 'font-medium' : ''}">${conv.message}</p>
                </div>
            `;
            div.addEventListener('click', () => loadConversation(conv));
            div.addEventListener('keydown', (e) => { if (e.key === 'Enter') loadConversation(conv); });
            conversationList.appendChild(div);
        });
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', async () => {
        await loadConversations();
        updateConversationList();
        if (conversations.length > 0) loadConversation(conversations[0]);

        document.getElementById('callBtn').addEventListener('click', () => {
            if (confirm(`Initiate a call with ${currentConversation.name} at ${new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', timeZone: 'Asia/Bangkok' })}?`)) {
                alert(`Calling ${currentConversation.name}...`);
            }
        });

        document.getElementById('videoBtn').addEventListener('click', () => {
            if (confirm(`Start a video call with ${currentConversation.name} at ${new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', timeZone: 'Asia/Bangkok' })}?`)) {
                alert(`Starting video call with ${currentConversation.name}...`);
            }
        });

        document.getElementById('infoBtn').addEventListener('click', () => {
            alert(`Info for ${currentConversation.name}: Online status, last active ${currentConversation.time}`);
        });

        document.getElementById('moreBtn').addEventListener('click', () => {
            alert(`More options for ${currentConversation.name} are available.`);
        });

        document.getElementById('attachFileBtn').addEventListener('click', () => {
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = '*/*';
            input.onchange = (e) => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (event) => sendFileMessage(file.name, event.target.result);
                    reader.readAsDataURL(file);
                }
            };
            input.click();
        });

        document.getElementById('attachImageBtn').addEventListener('click', () => {
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*';
            input.onchange = (e) => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (event) => sendFileMessage(file.name, event.target.result, true);
                    reader.readAsDataURL(file);
                }
            };
            input.click();
        });

        document.getElementById('sendButton').addEventListener('click', () => {
            const messageInput = document.getElementById('messageInput');
            const message = messageInput.value.trim();
            if (message && currentConversation && currentConversation.name === '{{ Auth::user()->name ?? 'LoggedInUser' }}') {
                sendTextMessage(message);
                messageInput.value = '';
            } else {
                alert('You can only message the logged-in user: {{ Auth::user()->name ?? 'LoggedInUser' }}');
            }
        });

        document.getElementById('messageInput').addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                document.getElementById('sendButton').click();
            }
        });

        document.getElementById('searchInput').addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            const filtered = conversations.filter(conv => 
                conv.name.toLowerCase().includes(searchTerm) || 
                conv.message.toLowerCase().includes(searchTerm)
            );
            updateConversationList(filtered);
        });

        document.getElementById('filterAll').addEventListener('click', () => updateConversationList());
        document.getElementById('filterUnread').addEventListener('click', () => updateConversationList(conversations.filter(conv => conv.unread)));
        document.getElementById('filterImportant').addEventListener('click', () => updateConversationList(conversations.filter(conv => conv.unread)));
    });

    // Send text message
    async function sendTextMessage(text) {
        const now = new Date();
        const time = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', timeZone: 'Asia/Bangkok' }); // 07:52 PM +07
        const newMessage = { text, time, isSent: true };

        try {
            const response = await fetch(`${BASE_URL}/messages/send`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('auth_token') || ''}`
                },
                body: JSON.stringify({
                    user_id: currentConversation.id,
                    subject: 'Chat Message',
                    content: text,
                    priority: 'medium'
                })
            });
            const data = await response.json();
            if (data.success) {
                let storedMessages = JSON.parse(localStorage.getItem(`chat_${currentConversation.id}`) || '[]');
                storedMessages.push(newMessage);
                localStorage.setItem(`chat_${currentConversation.id}`, JSON.stringify(storedMessages));
                renderMessages(storedMessages);

                // Simulate a reply
                setTimeout(async () => {
                    const reply = { text: `Thank you! I'll get back to you soon about ${text.split(' ')[0]}.`, time: new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', timeZone: 'Asia/Bangkok' }), isSent: false };
                    storedMessages.push(reply);
                    localStorage.setItem(`chat_${currentConversation.id}`, JSON.stringify(storedMessages));
                    renderMessages(storedMessages);
                    if (!conversations.some(c => c.id === currentConversation.id && c.unread)) {
                        currentConversation.unread = true;
                        updateConversationList();
                    }
                }, 2000);
            } else {
                alert('Failed to send message: ' + data.message);
            }
        } catch (error) {
            console.error('Error sending message:', error);
            alert('Error sending message');
        }
    }

    // Send file or image message
    function sendFileMessage(filename, dataUrl, isImage = false) {
        if (!currentConversation || currentConversation.name !== '{{ Auth::user()->name ?? 'LoggedInUser' }}') return;
        const now = new Date();
        const time = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', timeZone: 'Asia/Bangkok' }); // 07:52 PM +07
        const newMessage = { file: dataUrl, time, isSent: true, isImage };
        let storedMessages = JSON.parse(localStorage.getItem(`chat_${currentConversation.id}`) || '[]');
        storedMessages.push(newMessage);
        localStorage.setItem(`chat_${currentConversation.id}`, JSON.stringify(storedMessages));
        renderMessages(storedMessages);
    }
</script>
@endpush