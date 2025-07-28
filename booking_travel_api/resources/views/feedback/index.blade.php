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
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Customer Feedback</h2>

        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100 mb-8">
            <div class="px-6 py-4 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Feedback List</h3>
                    <div class="relative">
                        <input type="text" placeholder="Search feedback..." 
                               class="pl-8 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-search absolute left-2.5 top-2.5 text-gray-400 text-sm"></i>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Package</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comment</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                        $feedback = [
                            ['customer' => 'Sarah Wilson', 'package' => 'Tokyo Tour', 'rating' => 5, 'comment' => 'Amazing experience, highly recommend!', 'date' => '2024-07-20'],
                            ['customer' => 'David Lee', 'package' => 'Venice Dreams', 'rating' => 4, 'comment' => 'Beautiful city, but the tour was a bit rushed.', 'date' => '2024-07-18'],
                            ['customer' => 'Emily White', 'package' => 'Safari Adventure', 'rating' => 5, 'comment' => 'Unforgettable safari, excellent guides!', 'date' => '2024-07-15'],
                            ['customer' => 'Michael Green', 'package' => 'Parisian Romance', 'rating' => 3, 'comment' => 'Hotel was okay, but the food options were limited.', 'date' => '2024-07-10'],
                        ];
                        @endphp
                        
                        @foreach($feedback as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($item['customer']) }}&background=random&size=32" 
                                         alt="{{ $item['customer'] }}" 
                                         class="w-8 h-8 rounded-full mr-3">
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
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">{{ $item['comment'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item['date'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                <a href="#" class="text-red-600 hover:text-red-900">Delete</a>
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
@endsection
