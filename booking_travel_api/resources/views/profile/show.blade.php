@extends('layouts.dashboard')

@section('title', 'My Profile')
@section('page-title', 'My Profile')
@section('page-subtitle', 'Manage your personal information and view your travel history')

@section('content')
<div class="min-h-screen">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    <div class="ml-72 pt-32 p-8">
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-2xl mb-6 animate-fade-in">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profile Information -->
            <div class="lg:col-span-2">
                <div class="card-modern p-8 mb-8 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-2xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-user-circle mr-3 text-blue-600"></i>
                            Profile Information
                        </h3>
                        <a href="{{ route('profile.edit') }}" class="btn-modern group hover:scale-105 transition-transform">
                            <i class="fas fa-edit mr-2 group-hover:rotate-12 transition-transform"></i>
                            Edit Profile
                        </a>
                    </div>
                    
                    <div class="flex items-start space-x-8">
                        <!-- Enhanced avatar section with upload functionality -->
                        <div class="flex-shrink-0 relative group">
                            <div class="w-32 h-32 bg-gradient-to-br from-blue-500 to-purple-600 rounded-3xl flex items-center justify-center text-white text-4xl font-bold shadow-lg hover:shadow-xl transition-all duration-300 cursor-pointer" onclick="openAvatarModal()">
                                @if($user->avatar)
                                    <img src="{{ Storage::url('avatars/' . $user->avatar) }}" alt="Avatar" class="w-full h-full rounded-3xl object-cover">
                                @else
                                    {{ substr($user->name, 0, 2) }}
                                @endif
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 rounded-3xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                                    <i class="fas fa-camera text-white text-xl"></i>
                                </div>
                            </div>
                            <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center text-white text-sm">
                                <i class="fas fa-check"></i>
                            </div>
                        </div>
                        
                        <!-- Enhanced user details with better styling and animations -->
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="profile-field" data-aos="fade-up" data-aos-delay="100">
                                <label class="text-sm font-semibold text-slate-600 mb-2 block flex items-center">
                                    <i class="fas fa-user mr-2 text-blue-500"></i>
                                    Full Name
                                </label>
                                <p class="text-slate-800 font-medium text-lg">{{ $user->name }}</p>
                            </div>
                            
                            <div class="profile-field" data-aos="fade-up" data-aos-delay="200">
                                <label class="text-sm font-semibold text-slate-600 mb-2 block flex items-center">
                                    <i class="fas fa-envelope mr-2 text-emerald-500"></i>
                                    Email Address
                                </label>
                                <p class="text-slate-800 font-medium">{{ $user->email }}</p>
                            </div>
                            
                            <div class="profile-field" data-aos="fade-up" data-aos-delay="300">
                                <label class="text-sm font-semibold text-slate-600 mb-2 block flex items-center">
                                    <i class="fas fa-phone mr-2 text-purple-500"></i>
                                    Phone Number
                                </label>
                                <p class="text-slate-800 font-medium">{{ $user->phone ?: 'Not provided' }}</p>
                            </div>
                            
                            <div class="profile-field" data-aos="fade-up" data-aos-delay="400">
                                <label class="text-sm font-semibold text-slate-600 mb-2 block flex items-center">
                                    <i class="fas fa-flag mr-2 text-red-500"></i>
                                    Nationality
                                </label>
                                <p class="text-slate-800 font-medium">{{ $user->nationality ?: 'Not provided' }}</p>
                            </div>
                            
                            <div class="md:col-span-2 profile-field" data-aos="fade-up" data-aos-delay="500">
                                <label class="text-sm font-semibold text-slate-600 mb-2 block flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2 text-orange-500"></i>
                                    Address
                                </label>
                                <p class="text-slate-800 font-medium">{{ $user->address ?: 'Not provided' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced recent bookings with better interactions -->
                <div class="card-modern p-8 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-2xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-history mr-3 text-emerald-600"></i>
                            Recent Bookings
                        </h3>
                        <a href="{{ route('bookings.index') }}" class="text-blue-600 hover:text-blue-700 font-semibold hover:underline transition-all">
                            View All <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    
                    @if($recentBookings->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentBookings as $index => $booking)
                                <div class="booking-card flex items-center justify-between p-4 bg-slate-50 rounded-2xl hover:bg-slate-100 hover:shadow-md transition-all duration-300 cursor-pointer" 
                                     data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}"
                                     onclick="showBookingDetails({{ $booking->id }})">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-md">
                                            <i class="fas fa-map-marker-alt text-white"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-slate-800 hover:text-blue-600 transition-colors">{{ $booking->package->name }}</h4>
                                            <p class="text-sm text-slate-500 flex items-center">
                                                <i class="fas fa-location-dot mr-1"></i>
                                                {{ $booking->package->destination }} • 
                                                <i class="fas fa-calendar ml-2 mr-1"></i>
                                                {{ $booking->created_at->format('M d, Y') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-slate-800 text-lg">${{ number_format($booking->total_amount, 2) }}</p>
                                        <span class="badge-modern {{ $booking->status === 'confirmed' ? 'bg-emerald-100 text-emerald-700' : ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-slate-100 text-slate-700') }} pulse-animation">
                                            <i class="fas fa-{{ $booking->status === 'confirmed' ? 'check' : ($booking->status === 'pending' ? 'clock' : 'times') }} mr-1"></i>
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12" data-aos="fade-up">
                            <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-calendar-times text-slate-300 text-3xl"></i>
                            </div>
                            <p class="text-slate-500 mb-4">No bookings yet. Start planning your next adventure!</p>
                            <a href="{{ route('packages.index') }}" class="btn-modern inline-flex items-center">
                                <i class="fas fa-search mr-2"></i>
                                Browse Packages
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Enhanced stats sidebar with charts and animations -->
            <div class="space-y-6">
                <!-- Travel Stats -->
                <div class="card-modern p-6 hover:shadow-xl transition-all duration-300" data-aos="fade-left">
                    <h4 class="text-lg font-bold text-slate-800 mb-6 flex items-center">
                        <i class="fas fa-chart-bar mr-2 text-purple-600"></i>
                        Travel Statistics
                    </h4>
                    
                    <div class="space-y-4">
                        <div class="stat-item flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 transition-all">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-2xl flex items-center justify-center">
                                    <i class="fas fa-calendar-check text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-500">Total Bookings</p>
                                    <p class="font-bold text-slate-800 counter" data-target="{{ $totalBookings }}">0</p>
                                </div>
                            </div>
                            <div class="w-12 h-12">
                                <canvas id="bookingsChart" width="48" height="48"></canvas>
                            </div>
                        </div>
                        
                        <div class="stat-item flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 transition-all">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-emerald-100 rounded-2xl flex items-center justify-center">
                                    <i class="fas fa-check-circle text-emerald-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-500">Completed Trips</p>
                                    <p class="font-bold text-slate-800 counter" data-target="{{ $completedBookings }}">0</p>
                                </div>
                            </div>
                            <div class="w-12 h-12">
                                <canvas id="completedChart" width="48" height="48"></canvas>
                            </div>
                        </div>
                        
                        <div class="stat-item flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 transition-all">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-purple-100 rounded-2xl flex items-center justify-center">
                                    <i class="fas fa-dollar-sign text-purple-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-500">Total Spent</p>
                                    <p class="font-bold text-slate-800 counter" data-target="{{ $totalSpent }}" data-prefix="$">$0</p>
                                </div>
                            </div>
                            <div class="w-12 h-12">
                                <canvas id="spentChart" width="48" height="48"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced quick actions with better styling -->
                <div class="card-modern p-6 hover:shadow-xl transition-all duration-300" data-aos="fade-left" data-aos-delay="200">
                    <h4 class="text-lg font-bold text-slate-800 mb-6 flex items-center">
                        <i class="fas fa-bolt mr-2 text-yellow-500"></i>
                        Quick Actions
                    </h4>
                    
                    <div class="space-y-3">
                        <a href="{{ route('packages.index') }}" class="quick-action w-full flex items-center space-x-3 p-3 text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-2xl transition-all group">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-search text-blue-600"></i>
                            </div>
                            <span class="font-medium">Browse Packages</span>
                            <i class="fas fa-arrow-right ml-auto opacity-0 group-hover:opacity-100 transition-opacity"></i>
                        </a>
                        
                        <a href="{{ route('bookings.index') }}" class="quick-action w-full flex items-center space-x-3 p-3 text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-2xl transition-all group">
                            <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-calendar text-emerald-600"></i>
                            </div>
                            <span class="font-medium">My Bookings</span>
                            <i class="fas fa-arrow-right ml-auto opacity-0 group-hover:opacity-100 transition-opacity"></i>
                        </a>
                        
                        <a href="{{ route('messages.index') }}" class="quick-action w-full flex items-center space-x-3 p-3 text-slate-600 hover:text-purple-600 hover:bg-purple-50 rounded-2xl transition-all group">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-envelope text-purple-600"></i>
                            </div>
                            <span class="font-medium">Messages</span>
                            @if($unreadMessages > 0)
                                <span class="bg-red-500 text-white text-xs rounded-full px-2 py-1 ml-auto animate-pulse">{{ $unreadMessages }}</span>
                            @else
                                <i class="fas fa-arrow-right ml-auto opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            @endif
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Added avatar upload modal -->
<div id="avatarModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-3xl p-8 max-w-md w-full mx-4 transform scale-95 transition-transform" id="avatarModalContent">
        <div class="text-center">
            <h3 class="text-xl font-bold text-slate-800 mb-4">Update Profile Picture</h3>
            <div class="mb-6">
                <input type="file" id="avatarInput" accept="image/*" class="hidden">
                <button onclick="document.getElementById('avatarInput').click()" class="w-full p-4 border-2 border-dashed border-slate-300 rounded-2xl hover:border-blue-500 transition-colors">
                    <i class="fas fa-cloud-upload-alt text-3xl text-slate-400 mb-2"></i>
                    <p class="text-slate-600">Click to upload or drag and drop</p>
                </button>
            </div>
            <div class="flex space-x-3">
                <button onclick="closeAvatarModal()" class="flex-1 px-4 py-2 bg-slate-100 text-slate-600 rounded-xl hover:bg-slate-200 transition-colors">Cancel</button>
                <button onclick="uploadAvatar()" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">Upload</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Added custom CSS for enhanced animations and styling */
.animate-fade-in {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.pulse-animation {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.profile-field {
    padding: 1rem;
    border-radius: 1rem;
    transition: all 0.3s ease;
}

.profile-field:hover {
    background-color: #f8fafc;
    transform: translateY(-2px);
}

.booking-card:hover {
    transform: translateY(-2px);
}

.stat-item:hover {
    transform: scale(1.02);
}

.quick-action:hover {
    transform: translateX(4px);
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS animations
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true
    });

    // Counter animation
    const counters = document.querySelectorAll('.counter');
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        const prefix = counter.getAttribute('data-prefix') || '';
        let current = 0;
        const increment = target / 50;
        
        const updateCounter = () => {
            if (current < target) {
                current += increment;
                counter.textContent = prefix + Math.ceil(current).toLocaleString();
                setTimeout(updateCounter, 30);
            } else {
                counter.textContent = prefix + target.toLocaleString();
            }
        };
        
        setTimeout(updateCounter, 500);
    });

    // Create mini charts
    createMiniChart('bookingsChart', {{ $totalBookings }}, '#3B82F6');
    createMiniChart('completedChart', {{ $completedBookings }}, '#10B981');
    createMiniChart('spentChart', {{ $totalSpent }}, '#8B5CF6');
});

function createMiniChart(canvasId, value, color) {
    const ctx = document.getElementById(canvasId).getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [value, Math.max(100 - value, 0)],
                backgroundColor: [color, '#E5E7EB'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: false,
            plugins: { legend: { display: false } },
            cutout: '70%'
        }
    });
}

function openAvatarModal() {
    document.getElementById('avatarModal').classList.remove('hidden');
    document.getElementById('avatarModal').classList.add('flex');
    setTimeout(() => {
        document.getElementById('avatarModalContent').classList.remove('scale-95');
        document.getElementById('avatarModalContent').classList.add('scale-100');
    }, 10);
}

function closeAvatarModal() {
    document.getElementById('avatarModalContent').classList.remove('scale-100');
    document.getElementById('avatarModalContent').classList.add('scale-95');
    setTimeout(() => {
        document.getElementById('avatarModal').classList.add('hidden');
        document.getElementById('avatarModal').classList.remove('flex');
    }, 200);
}

function showBookingDetails(bookingId) {
    // Add booking details modal functionality
    console.log('Show booking details for ID:', bookingId);
}

function uploadAvatar() {
    const fileInput = document.getElementById('avatarInput');
    if (fileInput.files.length > 0) {
        // Add avatar upload functionality
        console.log('Upload avatar:', fileInput.files[0]);
        closeAvatarModal();
    }
}
</script>
@endsection
