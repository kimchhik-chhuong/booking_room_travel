@extends('layouts.dashboard')

@section('title', 'Feedback')
@section('page-title', 'Feedback')
@section('page-subtitle', 'Review customer feedback and improve services.')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    <div class="ml-64 mr-80 p-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-sm p-6 stat-card border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Total Reviews</p>
                        <p class="text-3xl font-bold text-gray-800">1,847</p>
                        <p class="text-sm text-green-600 mt-2 flex items-center">
                            <i class="fas fa-arrow-up mr-1"></i> +12.3%
                        </p>
                    </div>
                    <div class="bg-blue-100 p-4 rounded-2xl">
                        <i class="fas fa-comment-dots text-blue-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6 stat-card border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Average Rating</p>
                        <p class="text-3xl font-bold text-gray-800">4.7</p>
                        <p class="text-sm text-green-600 mt-2 flex items-center">
                            <i class="fas fa-arrow-up mr-1"></i> +0.2
                        </p>
                    </div>
                    <div class="bg-yellow-100 p-4 rounded-2xl">
                        <i class="fas fa-star text-yellow-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6 stat-card border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">5-Star Reviews</p>
                        <p class="text-3xl font-bold text-gray-800">1,234</p>
                        <p class="text-sm text-green-600 mt-2 flex items-center">
                            <i class="fas fa-arrow-up mr-1"></i> +18.5%
                        </p>
                    </div>
                    <div class="bg-green-100 p-4 rounded-2xl">
                        <i class="fas fa-thumbs-up text-green-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6 stat-card border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Response Rate</p>
                        <p class="text-3xl font-bold text-gray-800">94%</p>
                        <p class="text-sm text-green-600 mt-2 flex items-center">
                            <i class="fas fa-arrow-up mr-1"></i> +3.2%
                        </p>
                    </div>
                    <div class="bg-purple-100 p-4 rounded-2xl">
                        <i class="fas fa-reply text-purple-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rating Distribution -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800 mb-6">Rating Distribution</h3>
                <div class="space-y-4">
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
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-1 w-16">
                            <span class="text-sm font-medium">{{ $rating['stars'] }}</span>
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                        </div>
                        <div class="flex-1 bg-gray-200 rounded-full h-3">
                            <div class="bg-blue-600 h-3 rounded-full" style="width: {{ $rating['percentage'] }}%"></div>
                        </div>
                        <span class="text-sm text-gray-600 w-16">{{ $rating['count'] }}</span>
                        <span class="text-sm text-gray-500 w-12">{{ $rating['percentage'] }}%</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800 mb-6">Recent Highlights</h3>
                <div class="space-y-4">
                    @php
                    $highlights = [
                        ['text' => 'Amazing experience with the Tokyo tour!', 'author' => 'Sarah Wilson', 'rating' => 5],
                        ['text' => 'Professional guides and great service.', 'author' => 'Mike Johnson', 'rating' => 5],
                        ['text' => 'Beautiful destinations, highly recommend!', 'author' => 'Emma Davis', 'rating' => 5],
                        ['text' => 'Well organized trip, exceeded expectations.', 'author' => 'David Lee', 'rating' => 4]
                    ];
                    @endphp

                    @foreach($highlights as $highlight)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            @for($i = 0; $i < $highlight['rating']; $i++)
                                <i class="fas fa-star text-yellow-400 text-xs"></i>
                            @endfor
                            @for($i = $highlight['rating']; $i < 5; $i++)
                                <i class="far fa-star text-gray-300 text-xs"></i>
                            @endfor
                        </div>
                        <p class="text-sm text-gray-700 mb-2">"{{ $highlight['text'] }}"</p>
                        <p class="text-xs text-gray-500">- {{ $highlight['author'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Feedback Table -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100 mb-8">
            <div class="px-6 py-4 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">All Feedback</h3>
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            <input type="text" placeholder="Search feedback..." 
                                   class="pl-8 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-search absolute left-2.5 top-2.5 text-gray-400 text-sm"></i>
                        </div>
                        <select class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
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
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Customer <i class="fas fa-sort ml-1 cursor-pointer"></i>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Package <i class="fas fa-sort ml-1 cursor-pointer"></i>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Rating <i class="fas fa-sort ml-1 cursor-pointer"></i>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Comment <i class="fas fa-sort ml-1 cursor-pointer"></i>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date <i class="fas fa-sort ml-1 cursor-pointer"></i>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status <i class="fas fa-sort ml-1 cursor-pointer"></i>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                        $feedback = [
                            ['customer' => 'Sarah Wilson', 'package' => 'Tokyo Cultural Adventure', 'rating' => 5, 'comment' => 'Amazing experience, highly recommend! The guides were knowledgeable and friendly.', 'date' => '2024-07-20', 'status' => 'Published'],
                            ['customer' => 'David Lee', 'package' => 'Venice Dreams', 'rating' => 4, 'comment' => 'Beautiful city, but the tour was a bit rushed. Overall good experience.', 'date' => '2024-07-18', 'status' => 'Published'],
                            ['customer' => 'Emily White', 'package' => 'Safari Adventure', 'rating' => 5, 'comment' => 'Unforgettable safari experience! Excellent guides and amazing wildlife sightings.', 'date' => '2024-07-15', 'status' => 'Published'],
                            ['customer' => 'Michael Green', 'package' => 'Parisian Romance', 'rating' => 3, 'comment' => 'Hotel was okay, but the food options were limited. Service could be improved.', 'date' => '2024-07-10', 'status' => 'Pending'],
                            ['customer' => 'Lisa Brown', 'package' => 'Bali Beach Escape', 'rating' => 5, 'comment' => 'Perfect vacation! Beautiful beaches, great accommodations, and wonderful staff.', 'date' => '2024-07-08', 'status' => 'Published'],
                            ['customer' => 'James Wilson', 'package' => 'Greek Island Hopping', 'rating' => 4, 'comment' => 'Great itinerary and beautiful islands. Would love to do it again!', 'date' => '2024-07-05', 'status' => 'Published'],
                        ];
                        @endphp
                        
                        @foreach($feedback as $item)
                        <tr class="table-row transition-all duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($item['customer']) }}&background=random&size=32" 
                                         alt="{{ $item['customer'] }}" 
                                         class="w-8 h-8 rounded-full mr-3 ring-2 ring-gray-100">
                                    <div class="text-sm font-medium text-gray-900">{{ $item['customer'] }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item['package'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center">
                                    @for($i = 0; $i < $item['rating']; $i++)
                                        <i class="fas fa-star text-yellow-400 text-xs"></i>
                                    @endfor
                                    @for($i = $item['rating']; $i < 5; $i++)
                                        <i class="far fa-star text-gray-300 text-xs"></i>
                                    @endfor
                                    <span class="ml-2 text-sm font-medium">{{ $item['rating'] }}.0</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs">
                                <div class="truncate" title="{{ $item['comment'] }}">{{ $item['comment'] }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ date('M d, Y', strtotime($item['date'])) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $item['status'] === 'Published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $item['status'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="#" class="text-blue-600 hover:text-blue-900 transition-colors">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="#" class="text-green-600 hover:text-green-900 transition-colors">
                                        <i class="fas fa-reply"></i>
                                    </a>
                                    <a href="#" class="text-red-600 hover:text-red-900 transition-colors">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 flex items-center justify-between text-sm text-gray-600 border-t border-gray-100">
                <div class="flex items-center space-x-2">
                    <span>Showing</span>
                    <select class="border border-gray-200 rounded-lg px-2 py-1 text-sm focus:ring-2 focus:ring-blue-500">
                        <option>6</option>
                        <option>12</option>
                        <option>24</option>
                    </select>
                    <span>out of 1,847</span>
                </div>
                <div class="flex items-center space-x-2">
                    <button class="px-3 py-1 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                        <i class="fas fa-chevron-left"></i> Previous
                    </button>
                    <button class="px-3 py-1 rounded-lg bg-blue-600 text-white">1</button>
                    <button class="px-3 py-1 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">2</button>
                    <button class="px-3 py-1 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">3</button>
                    <span>...</span>
                    <button class="px-3 py-1 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">308</button>
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
