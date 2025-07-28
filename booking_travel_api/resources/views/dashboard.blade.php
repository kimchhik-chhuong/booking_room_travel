@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome back! Here\'s what\'s happening with your travel business today.')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    <div class="ml-64 mr-80 p-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Bookings -->
            <div class="bg-white rounded-2xl shadow-sm p-6 stat-card border border-gray-100 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Total Booking</p>
                        <p class="text-3xl font-bold text-gray-800">1,200</p>
                        <p class="text-sm text-green-600 mt-2 flex items-center">
                            <i class="fas fa-arrow-up mr-1"></i> +2.98%
                        </p>
                    </div>
                    <div class="bg-blue-100 p-4 rounded-2xl">
                        <i class="fas fa-calendar-check text-blue-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total New Customers -->
            <div class="bg-white rounded-2xl shadow-sm p-6 stat-card border border-gray-100 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Total New Customers</p>
                        <p class="text-3xl font-bold text-gray-800">2,845</p>
                        <p class="text-sm text-red-600 mt-2 flex items-center">
                            <i class="fas fa-arrow-down mr-1"></i> -1.45%
                        </p>
                    </div>
                    <div class="bg-green-100 p-4 rounded-2xl">
                        <i class="fas fa-users text-green-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Earnings -->
            <div class="bg-white rounded-2xl shadow-sm p-6 stat-card border border-gray-100 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Total Earnings</p>
                        <p class="text-3xl font-bold text-gray-800">$12,890</p>
                        <p class="text-sm text-green-600 mt-2 flex items-center">
                            <i class="fas fa-arrow-up mr-1"></i> +3.75%
                        </p>
                    </div>
                    <div class="bg-yellow-100 p-4 rounded-2xl">
                        <i class="fas fa-dollar-sign text-yellow-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Calendar Row -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
            <!-- Revenue Overview -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Revenue Overview</h3>
                    <select class="bg-blue-500 text-white text-sm px-4 py-2 rounded-lg border-0 focus:ring-2 focus:ring-blue-300">
                        <option>Weekly</option>
                        <option>Monthly</option>
                        <option>Yearly</option>
                    </select>
                </div>
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Top Destinations -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Top Destinations</h3>
                    <select class="bg-blue-500 text-white text-sm px-3 py-2 rounded-lg border-0">
                        <option>This Month</option>
                        <option>Last Month</option>
                    </select>
                </div>
                <div class="space-y-4">
                    @php
                    $destinations = [
                        ['name' => 'Tokyo, Japan', 'participants' => '2,845', 'percentage' => 35, 'color' => 'bg-blue-500'],
                        ['name' => 'Sydney, Australia', 'participants' => '2,468', 'percentage' => 28, 'color' => 'bg-green-500'],
                        ['name' => 'Paris, France', 'participants' => '2,468', 'percentage' => 22, 'color' => 'bg-yellow-500'],
                        ['name' => 'Venice, Italy', 'participants' => '2,468', 'percentage' => 15, 'color' => 'bg-purple-500']
                    ];
                    @endphp
                    
                    @foreach($destinations as $destination)
                    <div class="flex items-center justify-between hover:bg-gray-50 p-2 rounded-lg transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 {{ $destination['color'] }} rounded-full"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $destination['name'] }}</p>
                                <p class="text-xs text-gray-500">{{ $destination['participants'] }} Participants</p>
                            </div>
                        </div>
                        <span class="text-sm font-bold text-gray-800">{{ $destination['percentage'] }}%</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Calendar Widget -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">July 2028</h3>
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
                    @for($i = 26; $i <= 31; $i++)
                        <div class="text-gray-300 py-2">{{ $i }}</div>
                    @endfor
                    
                    @for($i = 1; $i <= 31; $i++)
                        <div class="py-2 hover:bg-blue-100 rounded cursor-pointer transition-colors {{ in_array($i, [12, 19]) ? 'bg-blue-500 text-white rounded-lg font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                            {{ $i }}
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Travel Packages and Messages Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Travel Packages -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-4">
                        <h3 class="text-lg font-semibold text-gray-800">Travel Packages</h3>
                        <div class="flex items-center space-x-2 text-sm text-gray-600">
                            <i class="fas fa-plane text-blue-500"></i>
                            <span>Total Trips: 1,200</span>
                            <span class="mx-2">•</span>
                            <span class="text-green-600">Done: 620</span>
                            <span class="mx-2">•</span>
                            <span class="text-blue-600">Booked: 465</span>
                            <span class="mx-2">•</span>
                            <span class="text-red-600">Cancelled: 115</span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <select class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            <option>Latest</option>
                            <option>Popular</option>
                        </select>
                        <a href="{{ route('packages.index') }}" class="text-blue-600 text-sm hover:underline font-medium">View All</a>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @php
                    $packages = [
                        [
                            'title' => 'Seoul, South Korea',
                            'duration' => '10 Days | 9 nights',
                            'price' => '$2,100',
                            'image' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-uc6iPdsCHz79P4MzBqKKs5OHJDjOwt.png',
                            'tag' => 'Cultural Exploration',
                            'tagColor' => 'bg-red-500'
                        ],
                        [
                            'title' => 'Venice, Italy',
                            'duration' => '5 Days | 5 nights',
                            'price' => '$1,500',
                            'image' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-uc6iPdsCHz79P4MzBqKKs5OHJDjOwt.png',
                            'tag' => 'Venice Dreams',
                            'tagColor' => 'bg-blue-500'
                        ],
                        [
                            'title' => 'Serengeti, Tanzania',
                            'duration' => '8 Days | 7 nights',
                            'price' => '$3,200',
                            'image' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-uc6iPdsCHz79P4MzBqKKs5OHJDjOwt.png',
                            'tag' => 'Safari Adventure',
                            'tagColor' => 'bg-orange-500'
                        ]
                    ];
                    @endphp
                    
                    @foreach($packages as $package)
                    <div class="border border-gray-200 rounded-2xl overflow-hidden hover:shadow-lg transition-all duration-300 trip-card cursor-pointer">
                        <div class="relative">
                            <img src="{{ $package['image'] }}" alt="{{ $package['title'] }}" class="w-full h-40 object-cover">
                            <div class="absolute top-3 left-3 {{ $package['tagColor'] }} text-white text-xs px-2 py-1 rounded-full font-medium">
                                {{ $package['tag'] }}
                            </div>
                        </div>
                        <div class="p-4">
                            <h4 class="font-semibold text-gray-800 mb-2">{{ $package['title'] }}</h4>
                            <p class="text-sm text-gray-600 mb-3">{{ $package['duration'] }}</p>
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-lg font-bold text-blue-600">{{ $package['price'] }}</span>
                                    <p class="text-xs text-gray-500">per person</p>
                                </div>
                                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors font-medium">
                                    See Detail
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Messages -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Messages</h3>
                    <button class="text-gray-400 hover:text-gray-600 p-1 rounded hover:bg-gray-100">
                        <i class="fas fa-ellipsis-h"></i>
                    </button>
                </div>
                <div class="space-y-3">
                    @php
                    $messages = [
                        ['name' => 'Europia Hotel', 'message' => 'We are pleased to inform...', 'time' => '10:24 AM', 'unread' => true],
                        ['name' => 'Global Travel Co', 'message' => 'We have updated our cont...', 'time' => '2:30 PM', 'unread' => true],
                        ['name' => 'Kalendra Umbara', 'message' => 'Hi, I have some questions with c...', 'time' => '5:45 AM', 'unread' => true],
                        ['name' => 'Osman Farooq', 'message' => 'Hello, I had an amazing tim...', 'time' => '10:15 AM', 'unread' => true],
                        ['name' => 'Mellinda Jenkins', 'message' => 'Can you send more data...', 'time' => '7:24 PM', 'unread' => false],
                        ['name' => 'David Hernandez', 'message' => 'I would like to upgrade my...', 'time' => '10:06 AM', 'unread' => true]
                    ];
                    @endphp
                    
                    @foreach($messages as $message)
                    <div class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-xl cursor-pointer transition-all message-item">
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
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <a href="{{ route('messages.index') }}" class="text-blue-600 text-sm hover:underline font-medium">View All Messages</a>
                </div>
            </div>
        </div>

        <!-- Recent Bookings Table -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100 mb-8">
            <div class="px-6 py-4 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Recent Bookings</h3>
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            <input type="text" placeholder="Search anything..." 
                                   class="pl-8 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-search absolute left-2.5 top-2.5 text-gray-400 text-sm"></i>
                        </div>
                        <a href="{{ route('bookings.index') }}" class="text-blue-600 text-sm hover:underline font-medium">View All</a>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Package</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destination</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                        $bookings = [
                            ['name' => 'Cornelia Swan', 'package' => 'Venice Dreams', 'destination' => 'VGDN', 'date' => 'Jun 25 - Jun 30', 'price' => '$1,500', 'status' => 'Confirmed'],
                            ['name' => 'Raphael Goodman', 'package' => 'Safari Adventure', 'destination' => 'RGTM', 'date' => 'Jun 25 - Jul 2', 'price' => '$3,200', 'status' => 'Pending'],
                            ['name' => 'Ludwig Contessa', 'package' => 'Alpine Escape', 'destination' => 'TSGN', 'date' => 'Jun 26 - Jul 2', 'price' => '$2,500', 'status' => 'Confirmed'],
                            ['name' => 'Armina Raul Meyes', 'package' => 'Caribbean Cruise', 'destination' => 'HGDN', 'date' => 'Jun 26 - Jul 8', 'price' => '$2,800', 'status' => 'Cancelled'],
                            ['name' => 'James Dunn', 'package' => 'Parisian Romance', 'destination' => 'SGHN', 'date' => 'Jun 26 - Jun 30', 'price' => '$1,500', 'status' => 'Confirmed']
                        ];
                        @endphp
                        
                        @foreach($bookings as $booking)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($booking['name']) }}&background=random&size=32" 
                                         alt="{{ $booking['name'] }}" 
                                         class="w-8 h-8 rounded-full mr-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $booking['name'] }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking['package'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking['destination'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking['date'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $booking['price'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $booking['status'] === 'Confirmed' ? 'bg-green-100 text-green-800' : 
                                       ($booking['status'] === 'Pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $booking['status'] }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Sidebar -->
    @include('partials.right-sidebar')
</div>

@push('scripts')
<script>
// Revenue Chart
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            datasets: [{
                label: 'Revenue',
                data: [400, 450, 320, 500, 635, 540, 580],
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#3B82F6',
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
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#6B7280'
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#F3F4F6'
                    },
                    ticks: {
                        color: '#6B7280',
                        callback: function(value) {
                            return '$' + value;
                        }
                    }
                }
            },
            elements: {
                point: {
                    hoverBackgroundColor: '#3B82F6'
                }
            }
        }
    });

    // Add smooth scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });

    // Add loading states for buttons
    document.querySelectorAll('button[type="submit"], .btn-loading').forEach(button => {
        button.addEventListener('click', function() {
            if (!this.disabled && !this.classList.contains('no-loading')) {
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';
                this.disabled = true;
                
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                }, 2000);
            }
        });
    });

    // Add hover effects for cards
    document.querySelectorAll('.stat-card, .trip-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>
@endpush
@endsection
