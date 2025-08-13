@extends('layouts.dashboard')

@section('title', 'Packages')
@section('page-title', 'Travel Packages')
@section('page-subtitle', 'Manage your travel packages and create new offerings.')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    <div class="ml-72 mr-80 p-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Total Packages</p>
                        <p class="text-3xl font-bold text-dark-800">47</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+3 this month</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-box text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Active Packages</p>
                        <p class="text-3xl font-bold text-dark-800">42</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">89% active rate</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Avg. Rating</p>
                        <p class="text-3xl font-bold text-dark-800">4.8</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+0.2 this month</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-star text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-dark-500 text-sm font-medium mb-2">Total Revenue</p>
                        <p class="text-3xl font-bold text-dark-800">$234K</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+18% this month</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-white text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Package -->
        <div class="card-modern p-8 mb-12">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-2xl font-bold text-dark-800 mb-2">Featured Package</h3>
                    <p class="text-dark-500">Our most popular travel experience</p>
                </div>
                <button class="btn-modern">Edit Package</button>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <div class="space-y-6">
                    <div class="relative overflow-hidden rounded-2xl">
                        <img src="https://i0.wp.com/www.cambodialifestyle.com/wp-content/uploads/2024/04/Siem-Reap-5.jpg?fit=1024%2C683&ssl=1">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                        <div class="absolute top-6 left-6 bg-red-500 text-white px-4 py-2 rounded-full text-sm font-semibold">
                            Most Popular
                        </div>
                        <div class="absolute bottom-6 left-6 right-6">
                            <div class="flex items-center text-white mb-2">
                                @for($i = 0; $i < 5; $i++)
                                    <i class="fas fa-star text-yellow-400"></i>
                                @endfor
                                <span class="ml-2 font-medium">4.9 (234 reviews)</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4">
                        @for($i = 1; $i <= 3; $i++)
                        <div class="relative overflow-hidden rounded-xl cursor-pointer hover:scale-105 transition-transform">
                            <img src="https://myflyingleap.com/wp-content/uploads/2023/04/siem-reap-feature_depositphotos.jpg" alt="Gallery {{ $i }}" class="w-full h-24 object-cover">
                        </div>
                        @endfor
                    </div>
                </div>
                
                <div class="space-y-8">
                    <div>
                        <h2 class="text-4xl font-bold text-dark-800 mb-4">Angkor Wat</h2>
                        <div class="flex items-center space-x-4 text-dark-500 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt text-primary-500 mr-2"></i>
                                <span>Siem Reap</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-clock text-primary-500 mr-2"></i>
                                <span>7 Days / 1 Nights</span>
                            </div>
                        </div>
                        <p class="text-dark-600 leading-relaxed mb-8">
                            Siem Reap province is the tenth largest province in Cambodia. Having reached a population of one million in 2019, it ranks as the nation's fourth most populous province.
                        </p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <p class="text-dark-500 text-sm font-medium mb-2">Starting Price</p>
                            <p class="text-4xl font-bold text-primary-600">$80</p>
                            <p class="text-dark-500 text-sm">per person</p>
                        </div>
                        <div>
                            <p class="text-dark-500 text-sm font-medium mb-2">Bookings</p>
                            <p class="text-4xl font-bold text-dark-800">234</p>
                            <p class="text-emerald-600 text-sm">+12 this week</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-check text-emerald-600"></i>
                            </div>
                            <span class="text-dark-700 font-medium">All-Inclusive</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-bed text-blue-600"></i>
                            </div>
                            <span class="text-dark-700 font-medium">Luxury Resort</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-spa text-purple-600"></i>
                            </div>
                            <span class="text-dark-700 font-medium">Spa Treatments</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-cyan-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-water text-cyan-600"></i>
                            </div>
                            <span class="text-dark-700 font-medium">Water Sports</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- All Packages -->
        <div class="card-modern p-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-2xl font-bold text-dark-800 mb-2">All Packages</h3>
                    <p class="text-dark-500">Browse and manage your travel packages</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" id="searchPackages" placeholder="Search packages..." class="input-modern pl-10 w-64">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-400"></i>
                    </div>
                    <select id="categoryFilter" class="input-modern">
                        <option value="All">All Categories</option>
                        <option value="Beach">Beach</option>
                        <option value="Adventure">Adventure</option>
                        <option value="Cultural">Cultural</option>
                        <option value="Luxury">Luxury</option>
                    </select>
                    <button class="btn-modern">
                        <i class="fas fa-plus mr-2"></i> Add Package
                    </button>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="packagesContainer">
                @php
                $packages = [
                    ['title' => 'Phnom Penh', 'location' => 'Garden City Hotel, Cambodia', 'duration' => '7 Days', 'price' => '$250', 'rating' => '4.9', 'bookings' => 156, 'status' => 'Active', 'image' => 'https://ik.imgkit.net/3vlqs5axxjf/external/ik-seo/http://images.ntmllc.com/v4/hotel/U81/U81105/U81105_EXT_Z2DD62/Garden-City-Hotel-Exterior.JPG?tr=w-780%2Ch-437%2Cfo-auto', 'category' => 'Cultural'],
                    ['title' => 'Battambang', 'location' => 'V CROWN HOTEL, Cambodia', 'duration' => '5 Days', 'price' => '$130', 'rating' => '4.8', 'bookings' => 203, 'status' => 'Active', 'image' => 'https://q-xx.bstatic.com/xdata/images/hotel/max500/430685389.jpg?k=9a2c933bd4c8ec0f4c1438ca63be07939ec45b3db549dd26a31c7aeb3ad1ee16&o=', 'category' => 'Beach'],
                    ['title' => 'Kompot', 'location' => 'RiverTree Villa & Resort', 'duration' => '7 Days', 'price' => '$250', 'rating' => '4.7', 'bookings' => 89, 'status' => 'Active', 'image' => 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/350284085.jpg?k=57b1872b633f1044b73f3c87d160936d64981ccaf3d6935b21a4f8717fe9ee1a&o=&hp=1', 'category' => 'Cultural'],
                    ['title' => 'Kep', 'location' => 'Vakara Hotel', 'duration' => '7 Days', 'price' => '$230', 'rating' => '4.9', 'bookings' => 134, 'status' => 'Active', 'image' => 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/373267903.jpg?k=24a5a08ae735e6b26e33f355265b854a4d15af8d671dac6f8c5718c41425047b&o=&hp=1', 'category' => 'Adventure'],
                    ['title' => 'Pursat', 'location' => 'Pursat Riverside Hotel & Spa, Cambodia', 'duration' => '4 Days', 'price' => '$1,299', 'rating' => '4.6', 'bookings' => 78, 'status' => 'Draft', 'image' => 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/296140217.jpg?k=ec31ffd80f3688c3773080f874d45b4407312249df72217c7e95343890e47f35&o=&hp=1', 'category' => 'Cultural'],
                    ['title' => 'Prey Veng', 'location' => 'Arthur & Paul', 'duration' => '6 Days', 'price' => '$2,890', 'rating' => '4.8', 'bookings' => 167, 'status' => 'Active', 'image' => 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0e/4e/e4/fe/arthur-et-paul.jpg?w=500&h=-1&s=1', 'category' => 'Luxury'],
                    ['title' => 'Prey Veng', 'location' => 'Arthur & Paul', 'duration' => '6 Days', 'price' => '$2,890', 'rating' => '4.8', 'bookings' => 167, 'status' => 'Active', 'image' => 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0e/4e/e4/fe/arthur-et-paul.jpg?w=500&h=-1&s=1', 'category' => 'Luxury'],
                    ['title' => 'Prey Veng', 'location' => 'Arthur & Paul', 'duration' => '6 Days', 'price' => '$2,890', 'rating' => '4.8', 'bookings' => 167, 'status' => 'Active', 'image' => 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0e/4e/e4/fe/arthur-et-paul.jpg?w=500&h=-1&s=1', 'category' => 'Luxury'],
                    ['title' => 'Prey Veng', 'location' => 'Arthur & Paul', 'duration' => '6 Days', 'price' => '$2,890', 'rating' => '4.8', 'bookings' => 167, 'status' => 'Active', 'image' => 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0e/4e/e4/fe/arthur-et-paul.jpg?w=500&h=-1&s=1', 'category' => 'Luxury'],
                    ['title' => 'Prey Veng', 'location' => 'Arthur & Paul', 'duration' => '6 Days', 'price' => '$2,890', 'rating' => '4.8', 'bookings' => 167, 'status' => 'Active', 'image' => 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0e/4e/e4/fe/arthur-et-paul.jpg?w=500&h=-1&s=1', 'category' => 'Luxury'],
                    ['title' => 'Prey Veng', 'location' => 'Arthur & Paul', 'duration' => '6 Days', 'price' => '$2,890', 'rating' => '4.8', 'bookings' => 167, 'status' => 'Active', 'image' => 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0e/4e/e4/fe/arthur-et-paul.jpg?w=500&h=-1&s=1', 'category' => 'Luxury'],
                    ['title' => 'Prey Veng', 'location' => 'Arthur & Paul', 'duration' => '6 Days', 'price' => '$2,890', 'rating' => '4.8', 'bookings' => 167, 'status' => 'Active', 'image' => 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0e/4e/e4/fe/arthur-et-paul.jpg?w=500&h=-1&s=1', 'category' => 'Luxury'],
                    ['title' => 'Prey Veng', 'location' => 'Arthur & Paul', 'duration' => '6 Days', 'price' => '$2,890', 'rating' => '4.8', 'bookings' => 167, 'status' => 'Active', 'image' => 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0e/4e/e4/fe/arthur-et-paul.jpg?w=500&h=-1&s=1', 'category' => 'Luxury'],
                    ['title' => 'Prey Veng', 'location' => 'Arthur & Paul', 'duration' => '6 Days', 'price' => '$2,890', 'rating' => '4.8', 'bookings' => 167, 'status' => 'Active', 'image' => 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0e/4e/e4/fe/arthur-et-paul.jpg?w=500&h=-1&s=1', 'category' => 'Luxury'],
                    ['title' => 'Prey Veng', 'location' => 'Arthur & Paul', 'duration' => '6 Days', 'price' => '$2,890', 'rating' => '4.8', 'bookings' => 167, 'status' => 'Active', 'image' => 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0e/4e/e4/fe/arthur-et-paul.jpg?w=500&h=-1&s=1', 'category' => 'Luxury'],
                    ['title' => 'Prey Veng', 'location' => 'Arthur & Paul', 'duration' => '6 Days', 'price' => '$2,890', 'rating' => '4.8', 'bookings' => 167, 'status' => 'Active', 'image' => 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0e/4e/e4/fe/arthur-et-paul.jpg?w=500&h=-1&s=1', 'category' => 'Luxury'],
                    ['title' => 'Prey Veng', 'location' => 'Arthur & Paul', 'duration' => '6 Days', 'price' => '$2,890', 'rating' => '4.8', 'bookings' => 167, 'status' => 'Active', 'image' => 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0e/4e/e4/fe/arthur-et-paul.jpg?w=500&h=-1&s=1', 'category' => 'Luxury'],
                    ['title' => 'Prey Veng', 'location' => 'Arthur & Paul', 'duration' => '6 Days', 'price' => '$2,890', 'rating' => '4.8', 'bookings' => 167, 'status' => 'Active', 'image' => 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0e/4e/e4/fe/arthur-et-paul.jpg?w=500&h=-1&s=1', 'category' => 'Luxury'],
                ];
                @endphp
                
                @foreach($packages as $package)
                <div class="package-card group cursor-pointer" 
                     data-title="{{ strtolower($package['title']) }}" 
                     data-location="{{ strtolower($package['location']) }}" 
                     data-category="{{ $package['category'] }}"
                     data-status="{{ $package['status'] }}">
                    <div class="relative overflow-hidden rounded-2xl mb-6">
                        <img src="{{ $package['image'] }}" alt="{{ $package['title'] }}" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute top-4 right-4">
                            <span class="badge-modern {{ $package['status'] === 'Active' ? 'bg-emerald-500 text-white' : 'bg-yellow-500 text-white' }}">
                                {{ $package['status'] }}
                            </span>
                        </div>
                        <div class="absolute bottom-4 left-4 right-4">
                            <div class="flex items-center justify-between text-white">
                                <div class="flex items-center">
                                    @for($i = 0; $i < 5; $i++)
                                        <i class="fas fa-star text-yellow-400 text-sm"></i>
                                    @endfor
                                    <span class="ml-2 text-sm font-medium">{{ $package['rating'] }}</span>
                                </div>
                                <span class="text-sm">{{ $package['bookings'] }} bookings</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-xl font-bold text-dark-800 mb-2 group-hover:text-primary-600 transition-colors">{{ $package['title'] }}</h4>
                            <div class="flex items-center text-dark-500 text-sm space-x-4 mb-3">
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    <span>{{ $package['location'] }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-clock mr-1"></i>
                                    <span>{{ $package['duration'] }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-2xl font-bold text-primary-600">{{ $package['price'] }}</span>
                                <span class="text-dark-500 text-sm ml-1">per person</span>
                            </div>
                            <div class="flex items-center space-x-2">
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
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Right Sidebar -->
    @include('partials.right-sidebar')
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('searchPackages');
    const categoryFilter = document.getElementById('categoryFilter');
    const packageCards = document.querySelectorAll('.package-card');
    
    function filterPackages() {
        const searchTerm = searchInput.value.toLowerCase();
        const category = categoryFilter.value;
        
        packageCards.forEach(card => {
            const title = card.getAttribute('data-title');
            const location = card.getAttribute('data-location');
            const cardCategory = card.getAttribute('data-category');
            const cardStatus = card.getAttribute('data-status');
            
            const matchesSearch = title.includes(searchTerm) || location.includes(searchTerm);
            const matchesCategory = category === 'All' || cardCategory === category;
            
            if (matchesSearch && matchesCategory) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
    
    // Event listeners for search and filter
    searchInput.addEventListener('input', filterPackages);
    categoryFilter.addEventListener('change', filterPackages);
    
    // Action buttons functionality
    document.querySelectorAll('.package-card .fa-eye').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const packageTitle = this.closest('.package-card').querySelector('h4').textContent;
            alert(`Viewing details for ${packageTitle}`);
        });
    });
    
    document.querySelectorAll('.package-card .fa-edit').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const packageTitle = this.closest('.package-card').querySelector('h4').textContent;
            alert(`Editing package ${packageTitle}`);
        });
    });
    
    document.querySelectorAll('.package-card .fa-trash').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const packageTitle = this.closest('.package-card').querySelector('h4').textContent;
            if (confirm(`Are you sure you want to delete ${packageTitle}?`)) {
                alert(`Package ${packageTitle} deleted`);
                this.closest('.package-card').remove();
            }
        });
    });
    
    // Click on card to view details
    document.querySelectorAll('.package-card').forEach(card => {
        card.addEventListener('click', function() {
            const packageTitle = this.querySelector('h4').textContent;
            alert(`Viewing details for ${packageTitle}`);
        });
    });
});
</script>
@endpush
@endsection