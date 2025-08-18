{{-- resources/views/packages/index.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Packages Dashboard')

@section('content')
<div class="container">
    <h1 class="my-4">📦 Packages Dashboard</h1>

    {{-- Statistics --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Total Packages</div>
                <div class="card-body">
                    <h4 class="card-title">{{ $totalPackages }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">New This Month</div>
                <div class="card-body">
                    <h4 class="card-title">{{ $newThisMonth }}</h4>
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
                @foreach($packages as $package)
                <div class="package-card group cursor-pointer" 
                     data-title="{{ strtolower($package->title ?? '') }}" 
                     data-location="{{ strtolower($package->location ?? '') }}" 
                     data-category="{{ $package->category ?? '' }}"
                     data-status="{{ $package->status ?? 'Active' }}">
                    <div class="relative overflow-hidden rounded-2xl mb-6">
                        <img src="{{ $package->image ?? 'https://via.placeholder.com/400x300' }}" alt="{{ $package->title ?? 'Package' }}" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute top-4 right-4">
                            <span class="badge-modern {{ ($package->status ?? 'Active') === 'Active' ? 'bg-emerald-500 text-white' : 'bg-yellow-500 text-white' }}">
                                {{ $package->status ?? 'Active' }}
                            </span>
                        </div>
                        <div class="absolute bottom-4 left-4 right-4">
                            <div class="flex items-center justify-between text-white">
                                <div class="flex items-center">
                                    @for($i = 0; $i < 5; $i++)
                                        <i class="fas fa-star text-yellow-400 text-sm"></i>
                                    @endfor
                                    <span class="ml-2 text-sm font-medium">{{ $package->rating ?? '4.5' }}</span>
                                </div>
                                <span class="text-sm">{{ $package->bookings ?? 0 }} bookings</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-xl font-bold text-dark-800 mb-2 group-hover:text-primary-600 transition-colors">{{ $package->title ?? 'Package' }}</h4>
                            <div class="flex items-center text-dark-500 text-sm space-x-4 mb-3">
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    <span>{{ $package->location ?? 'N/A' }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-clock mr-1"></i>
                                    <span>{{ $package->duration ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-2xl font-bold text-primary-600">${{ number_format($package->price ?? 0, 2) }}</span>
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

    {{-- Packages Table --}}
    <div class="card">
        <div class="card-header">
            Package List
        </div>
        <div class="card-body">
            @if($packages && $packages->count() > 0)
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Package Name</th>
                            <th>Price</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($packages as $package)
                            <tr>
                                <td>{{ $package->id ?? '' }}</td>
                                <td>{{ $package->name ?? $package->title ?? 'N/A' }}</td>
                                <td>${{ number_format($package->price ?? 0, 2) }}</td>
                                <td>{{ isset($package->created_at) ? $package->created_at->format('Y-m-d') : 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-muted">No packages available.</p>
            @endif
        </div>
    </div>
</div>
@endsection
