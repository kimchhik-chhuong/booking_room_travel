@extends('layouts.dashboard')

@section('title', 'Feedback')
@section('page-title', 'Customer Feedback')
@section('page-subtitle', 'Review customer feedback and improve services.')

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
                        <p class="text-dark-500 text-sm font-medium mb-2">Total Reviews</p>
                        <p class="text-3xl font-bold text-dark-800">1,847</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+12.3% this month</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-comment-dots text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Average Rating</p>
                        <p class="text-3xl font-bold text-dark-800">4.7</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+0.2 improvement</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-star text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">5-Star Reviews</p>
                        <p class="text-3xl font-bold text-dark-800">1,234</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+18.5% increase</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-thumbs-up text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Response Rate</p>
                        <p class="text-3xl font-bold text-dark-800">94%</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+3.2% improvement</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-reply text-white text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rating Distribution -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
            <div class="lg:col-span-2 card-modern p-8">
                <h3 class="text-2xl font-bold text-dark-800 mb-8">Rating Distribution</h3>
                <div class="space-y-6">
                    @php
                    $ratings = [
                        ['stars' => 5, 'count' => 1234, 'percentage' => 67],
                        ['stars' => 4, 'count' => 423, 'percentage' => 23],
                        ['stars' => 3, 'count' => 128, 'percentage' => 7],
                        ['stars' => 2, 'count' => 45, 'percentage' => 2],
                        ['stars' => 1, 'count' => 17, 'percentage' => 1]
                    ];
                    @endphp

                    @foreach($ratings as $rating)
                    <div class="flex items-center space-x-6">
                        <div class="flex items-center space-x-2 w-20">
                            <span class="text-sm font-semibold text-dark-800">{{ $rating['stars'] }}</span>
                            <i class="fas fa-star text-yellow-400"></i>
                        </div>
                        <div class="flex-1 bg-slate-200 rounded-full h-4">
                            <div class="bg-primary-600 h-4 rounded-full transition-all duration-500" style="width: {{ $rating['percentage'] }}%"></div>
                        </div>
                        <span class="text-sm text-dark-600 w-20">{{ $rating['count'] }}</span>
                        <span class="text-sm text-dark-500 w-16">{{ $rating['percentage'] }}%</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="card-modern p-8">
                <h3 class="text-xl font-bold text-dark-800 mb-8">Recent Highlights</h3>
                <div class="space-y-6">
                    @php
                    $highlights = [
                        ['text' => 'Amazing experience with the Tokyo tour!', 'author' => 'Sarah Wilson', 'rating' => 5],
                        ['text' => 'Professional guides and great service.', 'author' => 'Mike Johnson', 'rating' => 5],
                        ['text' => 'Beautiful destinations, highly recommend!', 'author' => 'Emma Davis', 'rating' => 5],
                        ['text' => 'Well organized trip, exceeded expectations.', 'author' => 'David Lee', 'rating' => 4]
                    ];
                    @endphp

                    @foreach($highlights as $highlight)
                    <div class="bg-gradient-to-r from-slate-50 to-transparent rounded-2xl p-4">
                        <div class="flex items-center mb-3">
                            @for($i = 0; $i < $highlight['rating']; $i++)
                                <i class="fas fa-star text-yellow-400 text-sm"></i>
                            @endfor
                            @for($i = $highlight['rating']; $i < 5; $i++)
                                <i class="far fa-star text-slate-300 text-sm"></i>
                            @endfor
                        </div>
                        <p class="text-sm text-dark-700 mb-3">"{{ $highlight['text'] }}"</p>
                        <p class="text-xs text-dark-500 font-medium">- {{ $highlight['author'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Feedback Table -->
        <div class="card-modern overflow-hidden">
            <div class="p-8 border-b border-slate-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-dark-800 mb-2">All Feedback</h3>
                        <p class="text-dark-500">Manage customer reviews and responses</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" placeholder="Search feedback..." class="input-modern pl-10 w-64">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-400"></i>
                        </div>
                        <select class="input-modern">
                            <option>All Ratings</option>
                            <option>5 Stars</option>
                            <option>4 Stars</option>
                            <option>3 Stars</option>
                            <option>2 Stars</option>
                            <option>1 Star</option>
                        </select>
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
                                Rating
                            </th>
                            <th class="px-8 py-4 text-left text-sm font-semibold text-dark-600 uppercase tracking-wider">
                                Comment
                            </th>
                            <th class="px-8 py-4 text-left text-sm font-semibold text-dark-600 uppercase tracking-wider">
                                Date
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
                        $feedback = [
                            ['customer' => 'Sarah Wilson', 'package' => 'Tokyo Cultural Adventure', 'rating' => 5, 'comment' => 'Amazing experience, highly recommend! The guides were knowledgeable and friendly.', 'date' => '2024-07-20', 'status' => 'Published', 'avatar' => 'https://ui-avatars.com/api/?name=Sarah+Wilson&background=random&size=40'],
                            ['customer' => 'David Lee', 'package' => 'Venice Dreams', 'rating' => 4, 'comment' => 'Beautiful city, but the tour was a bit rushed. Overall good experience.', 'date' => '2024-07-18', 'status' => 'Published', 'avatar' => 'https://ui-avatars.com/api/?name=David+Lee&background=random&size=40'],
                            ['customer' => 'Emily White', 'package' => 'Safari Adventure', 'rating' => 5, 'comment' => 'Unforgettable safari experience! Excellent guides and amazing wildlife sightings.', 'date' => '2024-07-15', 'status' => 'Published', 'avatar' => 'https://ui-avatars.com/api/?name=Emily+White&background=random&size=40'],
                            ['customer' => 'Michael Green', 'package' => 'Parisian Romance', 'rating' => 3, 'comment' => 'Hotel was okay, but the food options were limited. Service could be improved.', 'date' => '2024-07-10', 'status' => 'Pending', 'avatar' => 'https://ui-avatars.com/api/?name=Michael+Green&background=random&size=40'],
                            ['customer' => 'Lisa Brown', 'package' => 'Bali Beach Escape', 'rating' => 5, 'comment' => 'Perfect vacation! Beautiful beaches, great accommodations, and wonderful staff.', 'date' => '2024-07-08', 'status' => 'Published', 'avatar' => 'https://ui-avatars.com/api/?name=Lisa+Brown&background=random&size=40'],
                            ['customer' => 'James Wilson', 'package' => 'Greek Island Hopping', 'rating' => 4, 'comment' => 'Great itinerary and beautiful islands. Would love to do it again!', 'date' => '2024-07-05', 'status' => 'Published', 'avatar' => 'https://ui-avatars.com/api/?name=James+Wilson&background=random&size=40'],
                        ];
                        @endphp
                        
                        @foreach($feedback as $item)
                        <tr class="table-row transition-all duration-200 hover:bg-slate-50">
                            <td class="px-8 py-6">
                                <div class="flex items-center space-x-4">
                                    <img src="{{ $item['avatar'] }}" alt="{{ $item['customer'] }}" class="w-12 h-12 rounded-xl shadow-md">
                                    <div>
                                        <p class="font-semibold text-dark-800">{{ $item['customer'] }}</p>
                                        <p class="text-sm text-dark-500">Verified Customer</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <p class="font-medium text-dark-800">{{ $item['package'] }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center space-x-2">
                                    <div class="flex items-center">
                                        @for($i = 0; $i < $item['rating']; $i++)
                                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                                        @endfor
                                        @for($i = $item['rating']; $i < 5; $i++)
                                            <i class="far fa-star text-slate-300 text-sm"></i>
                                        @endfor
                                    </div>
                                    <span class="text-sm font-semibold text-dark-800">{{ $item['rating'] }}.0</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 max-w-xs">
                                <div class="truncate" title="{{ $item['comment'] }}">
                                    <p class="text-sm text-dark-700">{{ $item['comment'] }}</p>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-dark-700">{{ date('M d, Y', strtotime($item['date'])) }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <span class="badge-modern {{ $item['status'] === 'Published' ? 'bg-emerald-100 text-emerald-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $item['status'] }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center space-x-3">
                                    <button class="p-2 text-dark-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="p-2 text-dark-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all">
                                        <i class="fas fa-reply"></i>
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
                    <span>of 1,847 reviews</span>
                </div>
                <div class="flex items-center space-x-2">
                    <button class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors text-dark-600">
                        <i class="fas fa-chevron-left mr-2"></i> Previous
                    </button>
                    <button class="px-4 py-2 bg-primary-600 text-white rounded-lg">1</button>
                    <button class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors text-dark-600">2</button>
                    <button class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors text-dark-600">3</button>
                    <span class="px-2 text-dark-400">...</span>
                    <button class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors text-dark-600">308</button>
                    <button class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors text-dark-600">
                        Next <i class="fas fa-chevron-right ml-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
