@extends('layouts.dashboard')

@section('title', 'Feedback')
@section('page-title', 'Customer Feedback')
@section('page-subtitle', 'Monitor reviews and customer satisfaction ratings.')

@section('content')
<div class="min-h-screen">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    <div class="ml-72 pt-32 p-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm font-medium mb-2">Average Rating</p>
                        <p class="text-3xl font-bold text-slate-800">4.8</p>
                        <div class="flex items-center mt-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= 4 ? 'text-yellow-400' : 'text-slate-300' }} text-sm"></i>
                            @endfor
                        </div>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-star text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm font-medium mb-2">Total Reviews</p>
                        <p class="text-3xl font-bold text-slate-800">1,456</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+23 this week</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-comment-dots text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm font-medium mb-2">Positive Reviews</p>
                        <p class="text-3xl font-bold text-slate-800">92%</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">4-5 star ratings</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-thumbs-up text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm font-medium mb-2">Response Rate</p>
                        <p class="text-3xl font-bold text-slate-800">98%</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">Within 24h</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-reply text-white text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Feedback List -->
        <div class="card-modern overflow-hidden">
            <div class="p-8 border-b border-slate-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-slate-800 mb-2">Recent Feedback</h3>
                        <p class="text-slate-500">Customer reviews and ratings</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <select class="input-modern">
                            <option>All Ratings</option>
                            <option>5 Stars</option>
                            <option>4 Stars</option>
                            <option>3 Stars</option>
                            <option>2 Stars</option>
                            <option>1 Star</option>
                        </select>
                        <select class="input-modern">
                            <option>All Packages</option>
                            <option>Tokyo Cultural</option>
                            <option>Bali Beach</option>
                            <option>Safari Adventure</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="p-8">
                <div class="space-y-6">
                    @php
                    $feedbacks = [
                        ['name' => 'Sarah Johnson', 'package' => 'Tokyo Cultural Adventure', 'rating' => 5, 'date' => '2024-08-03', 'review' => 'Absolutely amazing experience! The cultural sites were breathtaking and our guide was incredibly knowledgeable. Every detail was perfectly planned.', 'verified' => true],
                        ['name' => 'Mike Chen', 'package' => 'Bali Beach Paradise', 'rating' => 5, 'date' => '2024-08-02', 'review' => 'Perfect honeymoon destination! The beaches were pristine and the resort exceeded our expectations. Thank you for making our trip unforgettable.', 'verified' => true],
                        ['name' => 'Emma Wilson', 'package' => 'Safari Adventure', 'rating' => 4, 'date' => '2024-08-01', 'review' => 'Great safari experience with amazing wildlife sightings. The accommodation was comfortable, though the food could have been better. Overall highly recommended!', 'verified' => true],
                        ['name' => 'David Brown', 'package' => 'European Grand Tour', 'rating' => 5, 'date' => '2024-07-30', 'review' => 'Exceeded all expectations! Visited 8 countries in 14 days and every moment was magical. The itinerary was well-balanced with perfect timing.', 'verified' => true],
                        ['name' => 'Lisa Garcia', 'package' => 'Caribbean Cruise', 'rating' => 4, 'date' => '2024-07-28', 'review' => 'Lovely cruise with beautiful islands and great entertainment. The cabin was spacious and clean. Would definitely book again for future vacations.', 'verified' => false],
                        ['name' => 'John Smith', 'package' => 'Himalayan Trek', 'rating' => 5, 'date' => '2024-07-25', 'review' => 'Life-changing adventure! The mountain views were spectacular and the trekking guides were professional and supportive throughout the journey.', 'verified' => true]
                    ];
                    @endphp
                    
                    @foreach($feedbacks as $feedback)
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 hover:shadow-md transition-all">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center space-x-4">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($feedback['name']) }}&background=random&size=50" alt="{{ $feedback['name'] }}" class="w-12 h-12 rounded-xl shadow-md">
                                <div>
                                    <div class="flex items-center space-x-2">
                                        <h4 class="font-semibold text-slate-800">{{ $feedback['name'] }}</h4>
                                        @if($feedback['verified'])
                                            <span class="bg-emerald-100 text-emerald-800 px-2 py-1 rounded-full text-xs font-semibold">Verified</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-slate-500">{{ $feedback['package'] }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="flex items-center space-x-1 mb-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $feedback['rating'] ? 'text-yellow-400' : 'text-slate-300' }} text-sm"></i>
                                    @endfor
                                </div>
                                <p class="text-sm text-slate-500">{{ date('M j, Y', strtotime($feedback['date'])) }}</p>
                            </div>
                        </div>
                        
                        <p class="text-slate-700 mb-4">{{ $feedback['review'] }}</p>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <button class="flex items-center space-x-2 text-slate-500 hover:text-blue-600 transition-colors">
                                    <i class="fas fa-thumbs-up"></i>
                                    <span class="text-sm">Helpful</span>
                                </button>
                                <button class="flex items-center space-x-2 text-slate-500 hover:text-blue-600 transition-colors">
                                    <i class="fas fa-reply"></i>
                                    <span class="text-sm">Reply</span>
                                </button>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                    <i class="fas fa-flag"></i>
                                </button>
                                <button class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="flex items-center justify-between mt-8 pt-8 border-t border-slate-200">
                    <div class="flex items-center space-x-2 text-slate-600">
                        <span>Showing</span>
                        <select class="input-modern text-sm px-2 py-1">
                            <option>6</option>
                            <option>12</option>
                            <option>24</option>
                        </select>
                        <span>of 1,456 reviews</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors text-slate-600">
                            <i class="fas fa-chevron-left mr-2"></i> Previous
                        </button>
                        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">1</button>
                        <button class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors text-slate-600">2</button>
                        <button class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors text-slate-600">3</button>
                        <button class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors text-slate-600">
                            Next <i class="fas fa-chevron-right ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
