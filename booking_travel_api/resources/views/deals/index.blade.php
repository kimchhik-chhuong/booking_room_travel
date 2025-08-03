@extends('layouts.dashboard')

@section('title', 'Deals')
@section('page-title', 'Deals & Offers')
@section('page-subtitle', 'Create and manage special offers and discounts.')

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
                        <p class="text-dark-500 text-sm font-medium mb-2">Active Deals</p>
                        <p class="text-3xl font-bold text-dark-800">12</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+3 new deals</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-tags text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Total Savings</p>
                        <p class="text-3xl font-bold text-dark-800">$45,230</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+18.2% this month</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Deal Usage</p>
                        <p class="text-3xl font-bold text-dark-800">1,456</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+24.1% usage rate</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Avg. Discount</p>
                        <p class="text-3xl font-bold text-dark-800">18%</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+2% increase</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-orange-400 to-orange-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-percentage text-white text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Deals -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
            @php
            $featuredDeals = [
                [
                    'title' => 'Summer Escape Sale',
                    'discount' => '25% OFF',
                    'description' => 'Get 25% off on all beach destinations',
                    'code' => 'SUMMER25',
                    'valid_until' => '2024-08-31',
                    'used' => 234,
                    'limit' => 500,
                    'status' => 'Active',
                    'color' => 'from-orange-400 to-pink-500'
                ],
                [
                    'title' => 'Early Bird Special',
                    'discount' => '$300 OFF',
                    'description' => 'Book 60 days in advance and save big',
                    'code' => 'EARLY300',
                    'valid_until' => '2024-12-31',
                    'used' => 89,
                    'limit' => 200,
                    'status' => 'Active',
                    'color' => 'from-blue-400 to-purple-500'
                ],
                [
                    'title' => 'Group Adventure',
                    'discount' => '15% OFF',
                    'description' => 'Special discount for groups of 4 or more',
                    'code' => 'GROUP15',
                    'valid_until' => '2024-09-30',
                    'used' => 156,
                    'limit' => 300,
                    'status' => 'Active',
                    'color' => 'from-emerald-400 to-blue-500'
                ]
            ];
            @endphp

            @foreach($featuredDeals as $deal)
            <div class="bg-gradient-to-br {{ $deal['color'] }} rounded-2xl p-8 text-white card-modern">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold">{{ $deal['title'] }}</h3>
                    <span class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-semibold">
                        {{ $deal['status'] }}
                    </span>
                </div>
                
                <div class="mb-6">
                    <p class="text-4xl font-bold mb-3">{{ $deal['discount'] }}</p>
                    <p class="text-white/90">{{ $deal['description'] }}</p>
                </div>

                <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-4 mb-6">
                    <p class="text-sm font-semibold mb-1">Promo Code</p>
                    <p class="text-xl font-bold tracking-wider">{{ $deal['code'] }}</p>
                </div>

                <div class="flex items-center justify-between text-sm mb-4">
                    <span>Valid until {{ date('M d, Y', strtotime($deal['valid_until'])) }}</span>
                    <span>{{ $deal['used'] }}/{{ $deal['limit'] }} used</span>
                </div>

                <div class="bg-white/20 backdrop-blur-sm rounded-full h-3">
                    <div class="bg-white rounded-full h-3" style="width: {{ ($deal['used'] / $deal['limit']) * 100 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Deals Table -->
        <div class="card-modern overflow-hidden">
            <div class="p-8 border-b border-slate-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-dark-800 mb-2">All Deals</h3>
                        <p class="text-dark-500">Manage all your promotional offers</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" placeholder="Search deals..." class="input-modern pl-10 w-64">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-400"></i>
                        </div>
                        <select class="input-modern">
                            <option>All Status</option>
                            <option>Active</option>
                            <option>Expired</option>
                            <option>Scheduled</option>
                        </select>
                        <button class="btn-modern">
                            <i class="fas fa-plus mr-2"></i> Create Deal
                        </button>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-8 py-4 text-left text-sm font-semibold text-dark-600 uppercase tracking-wider">
                                Deal Name
                            </th>
                            <th class="px-8 py-4 text-left text-sm font-semibold text-dark-600 uppercase tracking-wider">
                                Discount
                            </th>
                            <th class="px-8 py-4 text-left text-sm font-semibold text-dark-600 uppercase tracking-wider">
                                Code
                            </th>
                            <th class="px-8 py-4 text-left text-sm font-semibold text-dark-600 uppercase tracking-wider">
                                Usage
                            </th>
                            <th class="px-8 py-4 text-left text-sm font-semibold text-dark-600 uppercase tracking-wider">
                                Valid Until
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
                        $allDeals = [
                            ['name' => 'Summer Escape Sale', 'discount' => '25% Off', 'code' => 'SUMMER25', 'used' => 234, 'limit' => 500, 'valid_until' => '2024-08-31', 'status' => 'Active'],
                            ['name' => 'Early Bird Special', 'discount' => '$300 Off', 'code' => 'EARLY300', 'used' => 89, 'limit' => 200, 'valid_until' => '2024-12-31', 'status' => 'Active'],
                            ['name' => 'Group Adventure', 'discount' => '15% Off', 'code' => 'GROUP15', 'used' => 156, 'limit' => 300, 'valid_until' => '2024-09-30', 'status' => 'Active'],
                            ['name' => 'Last Minute Deal', 'discount' => '20% Off', 'code' => 'LASTMIN20', 'used' => 67, 'limit' => 150, 'valid_until' => '2024-07-15', 'status' => 'Expired'],
                            ['name' => 'Holiday Special', 'discount' => '$500 Off', 'code' => 'HOLIDAY500', 'used' => 0, 'limit' => 100, 'valid_until' => '2024-12-25', 'status' => 'Scheduled'],
                            ['name' => 'Student Discount', 'discount' => '10% Off', 'code' => 'STUDENT10', 'used' => 345, 'limit' => 1000, 'valid_until' => '2024-12-31', 'status' => 'Active'],
                        ];
                        @endphp
                        
                        @foreach($allDeals as $deal)
                        <tr class="table-row transition-all duration-200 hover:bg-slate-50">
                            <td class="px-8 py-6">
                                <p class="font-semibold text-dark-800">{{ $deal['name'] }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <span class="bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-sm font-semibold">{{ $deal['discount'] }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <code class="bg-slate-100 text-dark-800 px-3 py-1 rounded-lg text-sm font-mono">{{ $deal['code'] }}</code>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm font-medium text-dark-800">{{ $deal['used'] }}/{{ $deal['limit'] }}</span>
                                    <div class="w-20 bg-slate-200 rounded-full h-2">
                                        <div class="bg-primary-600 h-2 rounded-full" style="width: {{ ($deal['used'] / $deal['limit']) * 100 }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-dark-700">{{ date('M d, Y', strtotime($deal['valid_until'])) }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <span class="badge-modern {{ $deal['status'] === 'Active' ? 'bg-emerald-100 text-emerald-800' : 
                                    ($deal['status'] === 'Scheduled' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $deal['status'] }}
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
                    <span>of 24 deals</span>
                </div>
                <div class="flex items-center space-x-2">
                    <button class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors text-dark-600">
                        <i class="fas fa-chevron-left mr-2"></i> Previous
                    </button>
                    <button class="px-4 py-2 bg-primary-600 text-white rounded-lg">1</button>
                    <button class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors text-dark-600">2</button>
                    <button class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors text-dark-600">3</button>
                    <button class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors text-dark-600">4</button>
                    <button class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors text-dark-600">
                        Next <i class="fas fa-chevron-right ml-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
