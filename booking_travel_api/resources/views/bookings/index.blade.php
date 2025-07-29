@extends('layouts.dashboard')

@section('title', 'Bookings')
@section('page-title', 'Bookings')
@section('page-subtitle', 'Manage all your travel bookings and reservations.')

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
            <!-- Total Booking -->
            <div class="bg-white rounded-2xl shadow-sm p-6 stat-card border border-gray-100 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Total Booking</p>
                        <p class="text-3xl font-bold text-gray-800">1,200</p>
                        <p class="text-sm text-green-600 mt-2 flex items-center">
                            <i class="fas fa-arrow-up mr-1"></i> +2.98% <span class="text-gray-500 ml-1">from last week</span>
                        </p>
                    </div>
                    <div class="bg-blue-100 p-4 rounded-2xl">
                        <i class="fas fa-calendar-check text-blue-600 text-2xl"></i>
                    </div>
                </div>
                <div class="chart-container-small mt-4">
                    <canvas id="totalBookingChart"></canvas>
                </div>
            </div>

            <!-- Total Participants -->
            <div class="bg-white rounded-2xl shadow-sm p-6 stat-card border border-gray-100 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Total Participants</p>
                        <p class="text-3xl font-bold text-gray-800">2,845</p>
                        <p class="text-sm text-red-600 mt-2 flex items-center">
                            <i class="fas fa-arrow-down mr-1"></i> -1.45% <span class="text-gray-500 ml-1">from last week</span>
                        </p>
                    </div>
                    <div class="bg-red-100 p-4 rounded-2xl">
                        <i class="fas fa-users text-red-600 text-2xl"></i>
                    </div>
                </div>
                <div class="chart-container-small mt-4">
                    <canvas id="totalParticipantsChart"></canvas>
                </div>
            </div>

            <!-- Total Earnings -->
            <div class="bg-white rounded-2xl shadow-sm p-6 stat-card border border-gray-100 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Total Earnings</p>
                        <p class="text-3xl font-bold text-gray-800">$14,795</p>
                        <p class="text-sm text-green-600 mt-2 flex items-center">
                            <i class="fas fa-arrow-up mr-1"></i> +3.75% <span class="text-gray-500 ml-1">from last week</span>
                        </p>
                    </div>
                    <div class="bg-yellow-100 p-4 rounded-2xl">
                        <i class="fas fa-dollar-sign text-yellow-600 text-2xl"></i>
                    </div>
                </div>
                <div class="chart-container-small mt-4">
                    <canvas id="totalEarningsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Trips Overview and Top Packages Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Trips Overview Chart -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Trips Overview</h3>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2 text-sm text-gray-600">
                            <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-blue-500 mr-1"></span> Done</span>
                            <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-gray-400 mr-1"></span> Canceled</span>
                        </div>
                        <select class="bg-blue-500 text-white text-sm px-4 py-2 rounded-lg border-0 focus:ring-2 focus:ring-blue-300">
                            <option>Last 12 Months</option>
                            <option>Last 6 Months</option>
                            <option>Last 3 Months</option>
                        </select>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="tripsOverviewChart"></canvas>
                </div>
            </div>

            <!-- Top Packages -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Top Packages</h3>
                    <button class="text-gray-400 hover:text-gray-600 p-1 rounded hover:bg-gray-100">
                        <i class="fas fa-ellipsis-h"></i>
                    </button>
                </div>
                <div class="flex flex-col items-center mb-6">
                    <div class="relative w-40 h-40">
                        <canvas id="topPackagesChart"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <p class="text-lg font-bold text-gray-800">This Week</p>
                            <p class="text-2xl font-bold text-blue-600">1,856</p>
                            <p class="text-xs text-gray-500">Total Participants</p>
                        </div>
                    </div>
                </div>
                <div class="space-y-4">
                    @php
                    $topPackages = [
                        ['name' => 'Tokyo Cultural Adventure', 'participants' => '650 Participants', 'percentage' => 35, 'color' => 'bg-blue-500'],
                        ['name' => 'Bali Beach Escape', 'participants' => '520 Participants', 'percentage' => 28, 'color' => 'bg-cyan-500'],
                        ['name' => 'Safari Adventure', 'participants' => '408 Participants', 'percentage' => 22, 'color' => 'bg-green-500'],
                        ['name' => 'Greek Island Hopping', 'participants' => '278 Participants', 'percentage' => 15, 'color' => 'bg-purple-500']
                    ];
                    @endphp
                    
                    @foreach($topPackages as $package)
                    <div class="flex items-center justify-between hover:bg-gray-50 p-2 rounded-lg transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 {{ $package['color'] }} rounded-full"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $package['name'] }}</p>
                                <p class="text-xs text-gray-500">{{ $package['participants'] }}</p>
                            </div>
                        </div>
                        <span class="text-sm font-bold text-gray-800">{{ $package['percentage'] }}%</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Bookings Table -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100 mb-8">
            <div class="px-6 py-4 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Bookings</h3>
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            <input type="text" placeholder="Search name, package, etc" 
                                   class="pl-8 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-search absolute left-2.5 top-2.5 text-gray-400 text-sm"></i>
                        </div>
                        <select class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            <option>Today</option>
                            <option>This Week</option>
                            <option>This Month</option>
                        </select>
                        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors flex items-center">
                            <i class="fas fa-plus mr-2"></i> Add Booking
                        </button>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Booking Code <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Package <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Duration <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Price <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status <i class="fas fa-sort ml-1"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                        $bookings = [
                            ['name' => 'Cornelia Swan', 'booking_code' => 'BKG12345', 'package' => 'Venice Dreams', 'duration' => '6 Days / 5 Nights', 'date' => 'Jun 25 - Jun 30', 'price' => '$1,500', 'status' => 'Confirmed'],
                            ['name' => 'Raphael Goodman', 'booking_code' => 'BKG12346', 'package' => 'Safari Adventure', 'duration' => '8 Days / 7 Nights', 'date' => 'Jun 25 - Jul 2', 'price' => '$3,200', 'status' => 'Pending'],
                            ['name' => 'Ludwig Contessa', 'booking_code' => 'BKG12347', 'package' => 'Alpine Escape', 'duration' => '7 Days / 6 Nights', 'date' => 'Jun 26 - Jul 2', 'price' => '$2,100', 'status' => 'Confirmed'],
                            ['name' => 'Armina Raul Meyes', 'booking_code' => 'BKG12348', 'package' => 'Caribbean Cruise', 'duration' => '10 Days / 9 Nights', 'date' => 'Jun 26 - Jul 5', 'price' => '$2,800', 'status' => 'Cancelled'],
                            ['name' => 'James Dunn', 'booking_code' => 'BKG12349', 'package' => 'Parisian Romance', 'duration' => '5 Days / 4 Nights', 'date' => 'Jun 26 - Jun 30', 'price' => '$1,200', 'status' => 'Confirmed'],
                            ['name' => 'Hillary Grey', 'booking_code' => 'BKG12350', 'package' => 'Tokyo Cultural Adventure', 'duration' => '7 Days / 6 Nights', 'date' => 'Jun 27 - Jul 3', 'price' => '$1,800', 'status' => 'Confirmed'],
                            ['name' => 'Lucas O\'connor', 'booking_code' => 'BKG12351', 'package' => 'Greek Island Hopping', 'duration' => '10 Days / 9 Nights', 'date' => 'Jun 28 - Jul 7', 'price' => '$2,500', 'status' => 'Pending'],
                            ['name' => 'Layla Linch', 'booking_code' => 'BKG12352', 'package' => 'Bali Beach Escape', 'duration' => '8 Days / 7 Nights', 'date' => 'Jun 29 - Jul 6', 'price' => '$1,600', 'status' => 'Confirmed']
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking['booking_code'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking['package'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking['duration'] }}</td>
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
            <div class="px-6 py-4 flex items-center justify-between text-sm text-gray-600">
                <div class="flex items-center space-x-2">
                    <span>Showing</span>
                    <select class="border border-gray-200 rounded-lg px-2 py-1 text-sm focus:ring-2 focus:ring-blue-500">
                        <option>8</option>
                        <option>16</option>
                        <option>32</option>
                    </select>
                    <span>out of 286</span>
                </div>
                <div class="flex items-center space-x-2">
                    <button class="px-3 py-1 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                        <i class="fas fa-chevron-left"></i> Previous
                    </button>
                    <button class="px-3 py-1 rounded-lg bg-blue-600 text-white">1</button>
                    <button class="px-3 py-1 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">2</button>
                    <button class="px-3 py-1 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">3</button>
                    <span>...</span>
                    <button class="px-3 py-1 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">16</button>
                    <button class="px-3 py-1 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                        Next <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Sidebar -->
    @include('partials.right-sidebar')
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Small Charts for Stats Cards
    const createSmallLineChart = (elementId, data, borderColor, backgroundColor) => {
        const ctx = document.getElementById(elementId)?.getContext('2d');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                    datasets: [{
                        data: data,
                        borderColor: borderColor,
                        backgroundColor: backgroundColor,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 0,
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: false }
                    },
                    scales: {
                        x: { display: false },
                        y: { display: false }
                    },
                    layout: {
                        padding: { left: 0, right: 0, top: 5, bottom: 0 }
                    }
                }
            });
        }
    };

    createSmallLineChart('totalBookingChart', [10, 20, 15, 25, 22, 30, 28], '#3B82F6', 'rgba(59, 130, 246, 0.1)');
    createSmallLineChart('totalParticipantsChart', [30, 25, 28, 20, 23, 18, 20], '#EF4444', 'rgba(239, 68, 68, 0.1)');
    createSmallLineChart('totalEarningsChart', [15, 18, 22, 19, 25, 23, 27], '#F59E0B', 'rgba(245, 158, 11, 0.1)');

    // Trips Overview Chart
    const tripsOverviewCtx = document.getElementById('tripsOverviewChart')?.getContext('2d');
    if (tripsOverviewCtx) {
        new Chart(tripsOverviewCtx, {
            type: 'line',
            data: {
                labels: ['Aug 27', 'Sep 27', 'Oct 27', 'Nov 27', 'Dec 27', 'Jan 28', 'Feb 28', 'Mar 28', 'Apr 28', 'May 28', 'Jun 28', 'Jul 28'],
                datasets: [
                    {
                        label: 'Done',
                        data: [500, 600, 750, 800, 950, 1780, 1600, 1800, 1900, 1700, 1850, 1950],
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#3B82F6',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Canceled',
                        data: [300, 350, 400, 380, 450, 500, 480, 520, 550, 530, 580, 600],
                        borderColor: '#9CA3AF',
                        backgroundColor: 'rgba(156, 163, 175, 0.1)',
                        tension: 0.4,
                        fill: false,
                        borderDash: [5, 5],
                        pointBackgroundColor: '#9CA3AF',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            title: function(context) {
                                return context[0].label;
                            },
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += context.parsed.y;
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#6B7280' }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#F3F4F6' },
                        ticks: { color: '#6B7280' }
                    }
                },
                elements: {
                    point: {
                        hoverBackgroundColor: '#3B82F6'
                    }
                }
            }
        });
    }

    // Top Packages Donut Chart
    const topPackagesCtx = document.getElementById('topPackagesChart')?.getContext('2d');
    if (topPackagesCtx) {
        new Chart(topPackagesCtx, {
            type: 'doughnut',
            data: {
                labels: ['Tokyo Cultural Adventure', 'Bali Beach Escape', 'Safari Adventure', 'Greek Island Hopping'],
                datasets: [{
                    data: [35, 28, 22, 15],
                    backgroundColor: ['#3B82F6', '#06B6D4', '#10B981', '#8B5CF6'],
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '80%', // Makes it a donut chart
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    label += context.parsed + '%';
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }

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
