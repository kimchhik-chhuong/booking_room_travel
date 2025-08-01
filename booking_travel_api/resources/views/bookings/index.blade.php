@extends('layouts.dashboard')

@section('title', 'Bookings')
@section('page-title', 'Bookings Management')
@section('page-subtitle', 'Track and manage all your travel bookings and reservations.')

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
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Total Bookings</p>
                        <p class="text-3xl font-bold text-dark-800">1,247</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2 flex items-center">
                            <i class="fas fa-arrow-up mr-1"></i> +12.5%
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-calendar-check text-white text-xl"></i>
                    </div>
                </div>
                <div class="chart-container-small">
                    <canvas id="totalBookingsChart"></canvas>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Confirmed</p>
                        <p class="text-3xl font-bold text-dark-800">1,089</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2 flex items-center">
                            <i class="fas fa-arrow-up mr-1"></i> +8.3%
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                </div>
                <div class="chart-container-small">
                    <canvas id="confirmedChart"></canvas>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Pending</p>
                        <p class="text-3xl font-bold text-dark-800">89</p>
                        <p class="text-yellow-600 text-sm font-medium mt-2 flex items-center">
                            <i class="fas fa-clock mr-1"></i> Awaiting
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-hourglass-half text-white text-xl"></i>
                    </div>
                </div>
                <div class="chart-container-small">
                    <canvas id="pendingChart"></canvas>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Revenue</p>
                        <p class="text-3xl font-bold text-dark-800">$234K</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2 flex items-center">
                            <i class="fas fa-arrow-up mr-1"></i> +18.7%
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-white text-xl"></i>
                    </div>
                </div>
                <div class="chart-container-small">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Booking Analytics -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
            <!-- Booking Trends -->
            <div class="lg:col-span-2 card-modern p-8">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-2xl font-bold text-dark-800 mb-2">Booking Trends</h3>
                        <p class="text-dark-500">Monthly booking performance and trends</p>
                    </div>
                    <select class="input-modern text-sm">
                        <option>Last 12 Months</option>
                        <option>Last 6 Months</option>
                        <option>Last 3 Months</option>
                    </select>
                </div>
                <div class="chart-container">
                    <canvas id="bookingTrendsChart"></canvas>
                </div>
            </div>

            <!-- Top Packages -->
            <div class="card-modern p-8">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-xl font-bold text-dark-800 mb-2">Top Packages</h3>
                        <p class="text-dark-500 text-sm">Most booked packages</p>
                    </div>
                </div>
                <div class="space-y-6">
                    @php
                    $topPackages = [
                        ['name' => 'Tokyo Cultural Adventure', 'bookings' => 234, 'percentage' => 35, 'color' => 'bg-blue-500'],
                        ['name' => 'Bali Beach Paradise', 'bookings' => 189, 'percentage' => 28, 'color' => 'bg-emerald-500'],
                        ['name' => 'European Grand Tour', 'bookings' => 156, 'percentage' => 22, 'color' => 'bg-purple-500'],
                        ['name' => 'Safari Adventure', 'bookings' => 98, 'percentage' => 15, 'color' => 'bg-orange-500']
                    ];
                    @endphp
                    
                    @foreach($topPackages as $package)
                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-slate-50 to-transparent rounded-2xl hover:from-slate-100 transition-all">
                        <div class="flex items-center space-x-4">
                            <div class="w-3 h-3 {{ $package['color'] }} rounded-full"></div>
                            <div>
                                <p class="font-semibold text-dark-800">{{ $package['name'] }}</p>
                                <p class="text-sm text-dark-500">{{ $package['bookings'] }} bookings</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-dark-800">{{ $package['percentage'] }}%</p>
                            <div class="w-16 h-2 bg-slate-200 rounded-full mt-2">
                                <div class="{{ $package['color'] }} h-2 rounded-full" style="width: {{ $package['percentage'] }}%"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Bookings Table -->
        <div class="card-modern overflow-hidden">
            <div class="p-8 border-b border-slate-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-dark-800 mb-2">All Bookings</h3>
                        <p class="text-dark-500">Manage and track all customer bookings</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" placeholder="Search bookings..." class="input-modern pl-10 w-64">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-400"></i>
                        </div>
                        <select class="input-modern">
                            <option>All Status</option>
                            <option>Confirmed</option>
                            <option>Pending</option>
                            <option>Cancelled</option>
                        </select>
                        <button class="btn-modern">
                            <i class="fas fa-plus mr-2"></i> New Booking
                        </button>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-8 py-4 text-left text-sm font-semibold text-dark-600 uppercase tracking-wider">
                                Customer
                            </th>
                            <th class="px-8 py-4 text-left text-sm font-semibold text-dark-600 uppercase tracking-wider">
                                Package
                            </th>
                            <th class="px-8 py-4 text-left text-sm font-semibold text-dark-600 uppercase tracking-wider">
                                Dates
                            </th>
                            <th class="px-8 py-4 text-left text-sm font-semibold text-dark-600 uppercase tracking-wider">
                                Amount
                            </th>
                            <th class="px-8 py-4 text-left text-sm font-semibold text-dark-600 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-8 py-4 text-left text-sm font-semibold text-dark-600 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @php
                        $bookings = [
                            ['customer' => 'Sarah Wilson', 'email' => 'sarah@example.com', 'package' => 'Tokyo Cultural Adventure', 'dates' => 'Aug 15 - Aug 22', 'amount' => '$2,450', 'status' => 'Confirmed', 'avatar' => 'https://ui-avatars.com/api/?name=Sarah+Wilson&background=random&size=40'],
                            ['customer' => 'Michael Chen', 'email' => 'michael@example.com', 'package' => 'Bali Beach Paradise', 'dates' => 'Sep 5 - Sep 10', 'amount' => '$1,890', 'status' => 'Pending', 'avatar' => 'https://ui-avatars.com/api/?name=Michael+Chen&background=random&size=40'],
                            ['customer' => 'Emma Davis', 'email' => 'emma@example.com', 'package' => 'European Grand Tour', 'dates' => 'Oct 1 - Oct 15', 'amount' => '$4,200', 'status' => 'Confirmed', 'avatar' => 'https://ui-avatars.com/api/?name=Emma+Davis&background=random&size=40'],
                            ['customer' => 'James Rodriguez', 'email' => 'james@example.com', 'package' => 'Safari Adventure', 'dates' => 'Nov 10 - Nov 18', 'amount' => '$3,650', 'status' => 'Confirmed', 'avatar' => 'https://ui-avatars.com/api/?name=James+Rodriguez&background=random&size=40'],
                            ['customer' => 'Lisa Thompson', 'email' => 'lisa@example.com', 'package' => 'Swiss Alps Retreat', 'dates' => 'Dec 20 - Dec 26', 'amount' => '$2,890', 'status' => 'Cancelled', 'avatar' => 'https://ui-avatars.com/api/?name=Lisa+Thompson&background=random&size=40'],
                            ['customer' => 'David Park', 'email' => 'david@example.com', 'package' => 'New York City Break', 'dates' => 'Jan 15 - Jan 19', 'amount' => '$1,299', 'status' => 'Pending', 'avatar' => 'https://ui-avatars.com/api/?name=David+Park&background=random&size=40']
                        ];
                        @endphp
                        
                        @foreach($bookings as $booking)
                        <tr class="table-row transition-all duration-200 hover:bg-slate-50">
                            <td class="px-8 py-6">
                                <div class="flex items-center space-x-4">
                                    <img src="{{ $booking['avatar'] }}" alt="{{ $booking['customer'] }}" class="w-12 h-12 rounded-xl shadow-md">
                                    <div>
                                        <p class="font-semibold text-dark-800">{{ $booking['customer'] }}</p>
                                        <p class="text-sm text-dark-500">{{ $booking['email'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <p class="font-medium text-dark-800">{{ $booking['package'] }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-dark-700">{{ $booking['dates'] }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <p class="font-bold text-primary-600 text-lg">{{ $booking['amount'] }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <span class="badge-modern {{ $booking['status'] === 'Confirmed' ? 'bg-emerald-100 text-emerald-800' : 
                                    ($booking['status'] === 'Pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $booking['status'] }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center space-x-3">
                                    <button class="p-2 text-dark-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="p-2 text-dark-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="p-2 text-dark-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-8 py-6 flex items-center justify-between border-t border-slate-200">
                <div class="flex items-center space-x-2 text-dark-600">
                    <span>Showing</span>
                    <select class="input-modern text-sm px-2 py-1">
                        <option>6</option>
                        <option>12</option>
                        <option>24</option>
                    </select>
                    <span>of 1,247 bookings</span>
                </div>
                <div class="flex items-center space-x-2">
                    <button class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors text-dark-600">
                        <i class="fas fa-chevron-left mr-2"></i> Previous
                    </button>
                    <button class="px-4 py-2 bg-primary-600 text-white rounded-lg">1</button>
                    <button class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors text-dark-600">2</button>
                    <button class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors text-dark-600">3</button>
                    <span class="px-2 text-dark-400">...</span>
                    <button class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors text-dark-600">208</button>
                    <button class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors text-dark-600">
                        Next <i class="fas fa-chevron-right ml-2"></i>
                    </button>
                </div>
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

    createMiniChart('totalBookingsChart', [20, 35, 25, 45, 38, 52], '#3b82f6');
    createMiniChart('confirmedChart', [18, 32, 23, 42, 35, 48], '#10b981');
    createMiniChart('pendingChart', [5, 8, 6, 12, 9, 15], '#f59e0b');
    createMiniChart('revenueChart', [30, 45, 35, 55, 48, 65], '#8b5cf6');

    // Main booking trends chart
    const trendsCtx = document.getElementById('bookingTrendsChart')?.getContext('2d');
    if (trendsCtx) {
        new Chart(trendsCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [
                    {
                        label: 'Confirmed',
                        data: [85, 92, 88, 105, 98, 115, 125, 118, 135, 142, 155, 168],
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 3,
                        pointRadius: 6
                    },
                    {
                        label: 'Pending',
                        data: [15, 18, 12, 25, 22, 28, 32, 25, 35, 38, 42, 45],
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        tension: 0.4,
                        fill: false,
                        pointBackgroundColor: '#f59e0b',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 3,
                        pointRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: { weight: '600' }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#64748b', font: { weight: '500' } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: { color: '#64748b', font: { weight: '500' } }
                    }
                }
            }
        });
    }
});
</script>
@endpush
@endsection
