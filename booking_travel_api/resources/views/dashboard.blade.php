@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome back! Here\'s what\'s happening with your travel business today.')

@section('content')
<div class="min-h-screen">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    <div class="ml-72 p-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <!-- Total Bookings -->
            <div class="stat-card">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Total Bookings</p>
                        <p class="text-4xl font-bold text-dark-800">1,247</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2 flex items-center">
                            <i class="fas fa-arrow-up mr-2"></i> +12.5% from last month
                        </p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-calendar-check text-white text-2xl"></i>
                    </div>
                </div>
                <div class="chart-container-small">
                    <canvas id="bookingsChart"></canvas>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="stat-card">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Total Revenue</p>
                        <p class="text-4xl font-bold text-dark-800">$89,247</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2 flex items-center">
                            <i class="fas fa-arrow-up mr-2"></i> +18.2% from last month
                        </p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-dollar-sign text-white text-2xl"></i>
                    </div>
                </div>
                <div class="chart-container-small">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Active Travelers -->
            <div class="stat-card">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Active Travelers</p>
                        <p class="text-4xl font-bold text-dark-800">2,845</p>
                        <p class="text-red-500 text-sm font-medium mt-2 flex items-center">
                            <i class="fas fa-arrow-down mr-2"></i> -2.1% from last month
                        </p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                </div>
                <div class="chart-container-small">
                    <canvas id="travelersChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Charts and Analytics -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
            <!-- Revenue Analytics -->
            <div class="lg:col-span-2 card-modern p-8">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-2xl font-bold text-dark-800 mb-2">Revenue Analytics</h3>
                        <p class="text-dark-500">Monthly revenue breakdown and trends</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <select class="input-modern text-sm">
                            <option>Last 12 Months</option>
                            <option>Last 6 Months</option>
                            <option>Last 3 Months</option>
                        </select>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="mainRevenueChart"></canvas>
                </div>
            </div>

            <!-- Top Destinations -->
            <div class="card-modern p-8">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-xl font-bold text-dark-800 mb-2">Top Destinations</h3>
                        <p class="text-dark-500 text-sm">Most popular travel spots</p>
                    </div>
                </div>
                <div class="space-y-6">
                    @php
                    $destinations = [
                        ['name' => 'Tokyo, Japan', 'bookings' => '2,845', 'percentage' => 35, 'color' => 'bg-blue-500', 'flag' => '🇯🇵'],
                        ['name' => 'Paris, France', 'bookings' => '2,468', 'percentage' => 28, 'color' => 'bg-purple-500', 'flag' => '🇫🇷'],
                        ['name' => 'Bali, Indonesia', 'bookings' => '1,923', 'percentage' => 22, 'color' => 'bg-emerald-500', 'flag' => '🇮🇩'],
                        ['name' => 'New York, USA', 'bookings' => '1,456', 'percentage' => 15, 'color' => 'bg-orange-500', 'flag' => '🇺🇸']
                    ];
                    @endphp
                    
                    @foreach($destinations as $destination)
                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-slate-50 to-transparent rounded-2xl hover:from-slate-100 transition-all">
                        <div class="flex items-center space-x-4">
                            <div class="text-2xl">{{ $destination['flag'] }}</div>
                            <div>
                                <p class="font-semibold text-dark-800">{{ $destination['name'] }}</p>
                                <p class="text-sm text-dark-500">{{ $destination['bookings'] }} bookings</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-dark-800">{{ $destination['percentage'] }}%</p>
                            <div class="w-16 h-2 bg-slate-200 rounded-full mt-2">
                                <div class="{{ $destination['color'] }} h-2 rounded-full" style="width: {{ $destination['percentage'] }}%"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Recent Activity and Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <!-- Recent Bookings -->
            <div class="card-modern p-8">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-xl font-bold text-dark-800 mb-2">Recent Bookings</h3>
                        <p class="text-dark-500 text-sm">Latest customer reservations</p>
                    </div>
                    <a href="{{ route('bookings.index') }}" class="text-primary-600 hover:text-primary-700 font-medium text-sm">View All</a>
                </div>
                <div class="space-y-4">
                    @php
                    $recentBookings = [
                        ['name' => 'Sarah Wilson', 'package' => 'Tokyo Cultural Adventure', 'amount' => '$2,450', 'status' => 'Confirmed', 'avatar' => 'https://ui-avatars.com/api/?name=Sarah+Wilson&background=random&size=40'],
                        ['name' => 'Michael Chen', 'package' => 'Bali Beach Escape', 'amount' => '$1,890', 'status' => 'Pending', 'avatar' => 'https://ui-avatars.com/api/?name=Michael+Chen&background=random&size=40'],
                        ['name' => 'Emma Davis', 'package' => 'Paris Romance', 'amount' => '$3,200', 'status' => 'Confirmed', 'avatar' => 'https://ui-avatars.com/api/?name=Emma+Davis&background=random&size=40'],
                        ['name' => 'James Rodriguez', 'package' => 'Safari Adventure', 'amount' => '$4,100', 'status' => 'Confirmed', 'avatar' => 'https://ui-avatars.com/api/?name=James+Rodriguez&background=random&size=40']
                    ];
                    @endphp
                    
                    @foreach($recentBookings as $booking)
                    <div class="flex items-center space-x-4 p-4 hover:bg-slate-50 rounded-2xl transition-all">
                        <img src="{{ $booking['avatar'] }}" alt="{{ $booking['name'] }}" class="w-12 h-12 rounded-xl shadow-md">
                        <div class="flex-1">
                            <p class="font-semibold text-dark-800">{{ $booking['name'] }}</p>
                            <p class="text-sm text-dark-500">{{ $booking['package'] }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-dark-800">{{ $booking['amount'] }}</p>
                            <span class="badge-modern {{ $booking['status'] === 'Confirmed' ? 'bg-emerald-100 text-emerald-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $booking['status'] }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card-modern p-8">
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-dark-800 mb-2">Quick Actions</h3>
                    <p class="text-dark-500 text-sm">Frequently used features</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <button class="p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl hover:from-blue-100 hover:to-blue-200 transition-all group">
                        <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <i class="fas fa-plus text-white text-xl"></i>
                        </div>
                        <p class="font-semibold text-dark-800">New Booking</p>
                        <p class="text-sm text-dark-500 mt-1">Create reservation</p>
                    </button>
                    
                    <button class="p-6 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-2xl hover:from-emerald-100 hover:to-emerald-200 transition-all group">
                        <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <i class="fas fa-box text-white text-xl"></i>
                        </div>
                        <p class="font-semibold text-dark-800">Add Package</p>
                        <p class="text-sm text-dark-500 mt-1">New travel package</p>
                    </button>
                    
                    <button class="p-6 bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl hover:from-purple-100 hover:to-purple-200 transition-all group">
                        <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <i class="fas fa-users text-white text-xl"></i>
                        </div>
                        <p class="font-semibold text-dark-800">Add Customer</p>
                        <p class="text-sm text-dark-500 mt-1">New traveler profile</p>
                    </button>
                    
                    <button class="p-6 bg-gradient-to-br from-orange-50 to-orange-100 rounded-2xl hover:from-orange-100 hover:to-orange-200 transition-all group">
                        <div class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <i class="fas fa-chart-line text-white text-xl"></i>
                        </div>
                        <p class="font-semibold text-dark-800">View Reports</p>
                        <p class="text-sm text-dark-500 mt-1">Analytics dashboard</p>
                    </button>
                </div>
            </div>
        </div>

        <!-- Featured Packages -->
        <div class="card-modern p-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-2xl font-bold text-dark-800 mb-2">Featured Packages</h3>
                    <p class="text-dark-500">Most popular travel experiences</p>
                </div>
                <a href="{{ route('packages.index') }}" class="btn-modern">View All Packages</a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @php
                $packages = [
                    [
                        'title' => 'Tokyo Cultural Adventure',
                        'duration' => '7 Days / 6 Nights',
                        'price' => '$2,450',
                        'rating' => '4.9',
                        'image' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-uc6iPdsCHz79P4MzBqKKs5OHJDjOwt.png',
                        'tag' => 'Popular',
                        'tagColor' => 'bg-red-500'
                    ],
                    [
                        'title' => 'Bali Beach Paradise',
                        'duration' => '5 Days / 4 Nights',
                        'price' => '$1,890',
                        'rating' => '4.8',
                        'image' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-uc6iPdsCHz79P4MzBqKKs5OHJDjOwt.png',
                        'tag' => 'New',
                        'tagColor' => 'bg-emerald-500'
                    ],
                    [
                        'title' => 'European Grand Tour',
                        'duration' => '14 Days / 13 Nights',
                        'price' => '$4,200',
                        'rating' => '4.7',
                        'image' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-uc6iPdsCHz79P4MzBqKKs5OHJDjOwt.png',
                        'tag' => 'Premium',
                        'tagColor' => 'bg-purple-500'
                    ]
                ];
                @endphp
                
                @foreach($packages as $package)
                <div class="group cursor-pointer">
                    <div class="relative overflow-hidden rounded-2xl mb-6">
                        <img src="{{ $package['image'] }}" alt="{{ $package['title'] }}" class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                        <div class="absolute top-4 left-4 {{ $package['tagColor'] }} text-white px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $package['tag'] }}
                        </div>
                        <div class="absolute bottom-4 left-4 right-4">
                            <div class="flex items-center text-white mb-2">
                                @for($i = 0; $i < 5; $i++)
                                    <i class="fas fa-star text-yellow-400 text-sm"></i>
                                @endfor
                                <span class="ml-2 text-sm font-medium">{{ $package['rating'] }}</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-dark-800 mb-2 group-hover:text-primary-600 transition-colors">{{ $package['title'] }}</h4>
                        <p class="text-dark-500 text-sm mb-4">{{ $package['duration'] }}</p>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-2xl font-bold text-primary-600">{{ $package['price'] }}</span>
                                <span class="text-dark-500 text-sm ml-1">per person</span>
                            </div>
                            <button class="btn-modern text-sm px-6 py-2">
                                Book Now
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Small charts for stat cards
    const createMiniChart = (elementId, data, color) => {
        const ctx = document.getElementById(elementId)?.getContext('2d');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        data: data,
                        borderColor: color,
                        backgroundColor: color + '20',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 0,
                        borderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { display: false },
                        y: { display: false }
                    }
                }
            });
        }
    };

    createMiniChart('bookingsChart', [20, 35, 25, 45, 38, 52], '#3b82f6');
    createMiniChart('revenueChart', [30, 45, 35, 55, 48, 65], '#10b981');
    createMiniChart('travelersChart', [40, 35, 42, 38, 35, 32], '#8b5cf6');

    // Main revenue chart
    const mainCtx = document.getElementById('mainRevenueChart')?.getContext('2d');
    if (mainCtx) {
        new Chart(mainCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Revenue',
                    data: [45000, 52000, 48000, 61000, 55000, 67000, 73000, 69000, 78000, 82000, 89000, 94000],
                    borderColor: '#0ea5e9',
                    backgroundColor: 'rgba(14, 165, 233, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#0ea5e9',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#64748b', font: { weight: '500' } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            color: '#64748b',
                            font: { weight: '500' },
                            callback: function(value) {
                                return '$' + (value / 1000) + 'K';
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
@endsection
