<div class="fixed right-0 top-0 w-80 h-full bg-white shadow-xl border-l border-gray-100 p-6 overflow-y-auto z-40">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                <i class="fas fa-map-marker-alt text-white text-sm"></i>
            </div>
            <span class="text-lg font-bold text-gray-800">Travelie</span>
        </div>
        <h2 class="text-lg font-semibold text-gray-800">Upcoming Trips</h2>
    </div>

    <!-- Upcoming Trips -->
    <div class="space-y-4 mb-8">
        @php
        $upcomingTrips = [
            [
                'destination' => 'Paris, France',
                'type' => 'Romantic Getaway',
                'participants' => '+2',
                'date' => '5 - 10 July',
                'rating' => '4.8',
                'image' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-uc6iPdsCHz79P4MzBqKKs5OHJDjOwt.png'
            ],
            [
                'destination' => 'Tokyo, Japan',
                'type' => 'Cultural Exploration',
                'participants' => '+17',
                'date' => '12 - 18 July',
                'rating' => '4.9',
                'image' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-uc6iPdsCHz79P4MzBqKKs5OHJDjOwt.png'
            ],
            [
                'destination' => 'Sydney, Australia',
                'type' => 'Adventure Tour',
                'participants' => '+2',
                'date' => '15 - 24 July',
                'rating' => '4.7',
                'image' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-uc6iPdsCHz79P4MzBqKKs5OHJDjOwt.png'
            ],
            [
                'destination' => 'New York, USA',
                'type' => 'City Highlights',
                'participants' => '+22',
                'date' => '20 - 29 July',
                'rating' => '4.6',
                'image' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-uc6iPdsCHz79P4MzBqKKs5OHJDjOwt.png'
            ]
        ];
        @endphp
        
        @foreach($upcomingTrips as $trip)
        <div class="bg-white border border-gray-200 rounded-xl p-4 hover:shadow-lg transition-all duration-300 hover:scale-105 cursor-pointer">
            <div class="flex items-center space-x-3">
                <img src="{{ $trip['image'] }}" 
                     alt="{{ $trip['destination'] }}" 
                     class="w-12 h-12 rounded-xl object-cover shadow-sm">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-800">{{ $trip['destination'] }}</p>
                    <p class="text-xs text-gray-500">{{ $trip['type'] }}</p>
                    <div class="flex items-center space-x-2 mt-1">
                        <div class="flex items-center">
                            @for($i = 0; $i < 5; $i++)
                                <i class="fas fa-star text-yellow-400 text-xs"></i>
                            @endfor
                            <span class="text-xs text-gray-600 ml-1">{{ $trip['rating'] }}</span>
                        </div>
                        <span class="text-xs text-gray-400">•</span>
                        <span class="text-xs text-gray-600">{{ $trip['participants'] }}</span>
                        <span class="text-xs text-gray-400">•</span>
                        <span class="text-xs text-gray-600">{{ $trip['date'] }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Recent Activity -->
    <div class="border-t border-gray-200 pt-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Activity</h3>
        <div class="space-y-4">
            <div class="flex items-start space-x-3">
                <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 animate-pulse"></div>
                <div>
                    <p class="text-sm text-gray-800">
                        <span class="font-medium">John Doe</span> booked a trip to 
                        <span class="font-medium text-blue-600">Paris</span>
                    </p>
                    <p class="text-xs text-gray-500 mt-1">2 hours ago</p>
                </div>
            </div>
            <div class="flex items-start space-x-3">
                <div class="w-2 h-2 bg-green-500 rounded-full mt-2 animate-pulse"></div>
                <div>
                    <p class="text-sm text-gray-800">
                        <span class="font-medium">Sarah Wilson</span> left a review for 
                        <span class="font-medium text-blue-600">Tokyo Tour</span>
                    </p>
                    <p class="text-xs text-gray-500 mt-1">4 hours ago</p>
                </div>
            </div>
            <div class="flex items-start space-x-3">
                <div class="w-2 h-2 bg-red-500 rounded-full mt-2 animate-pulse"></div>
                <div>
                    <p class="text-sm text-gray-800">
                        <span class="font-medium">Mike Johnson</span> cancelled booking for 
                        <span class="font-medium text-blue-600">Sydney</span>
                    </p>
                    <p class="text-xs text-gray-500 mt-1">6 hours ago</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="mt-8 pt-6 border-t border-gray-200">
        <div class="flex items-center justify-center space-x-4 mb-4">
            <a href="#" class="text-gray-400 hover:text-blue-500 transition-colors">
                <i class="fab fa-facebook text-lg"></i>
            </a>
            <a href="#" class="text-gray-400 hover:text-blue-500 transition-colors">
                <i class="fab fa-twitter text-lg"></i>
            </a>
            <a href="#" class="text-gray-400 hover:text-blue-500 transition-colors">
                <i class="fab fa-instagram text-lg"></i>
            </a>
            <a href="#" class="text-gray-400 hover:text-blue-500 transition-colors">
                <i class="fab fa-linkedin text-lg"></i>
            </a>
        </div>
        <p class="text-xs text-gray-500 text-center mb-2">Copyright © 2024 Travelie</p>
        <div class="flex items-center justify-center space-x-4 mb-4">
            <a href="#" class="text-xs text-gray-500 hover:text-gray-700">Privacy Policy</a>
            <a href="#" class="text-xs text-gray-500 hover:text-gray-700">Terms</a>
            <a href="#" class="text-xs text-gray-500 hover:text-gray-700">Contact</a>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full bg-red-500 text-white py-2 rounded-lg text-sm hover:bg-red-600 transition-colors flex items-center justify-center">
                <i class="fas fa-sign-out-alt mr-2"></i>Logout
            </button>
        </form>
    </div>
</div>
