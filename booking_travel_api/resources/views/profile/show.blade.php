@extends('layouts.dashboard')

@section('title', 'My Profile')


@section('page-subtitle', 'Welcome back! Here\'s what\'s happening with your travel business today.')

@section('content')
<div class="min-h-screen">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Header -->
    {{-- @include('partials.header') --}}
    <div class="ml-72 p-8">
        <meta charset="UTF-8">
        <title>My Profile</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        </head>
        <body class="bg-slate-50">

        <!-- Page Header -->
        <header class="bg-white shadow p-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-slate-800">My Profile</h1>
            <p class="text-slate-500">Manage your personal information and view your travel history</p>
        </header>

        <main class="max-w-7xl mx-auto p-8 grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Profile Information -->
            <div class="lg:col-span-2">
            <div class="bg-white p-8 rounded-2xl shadow hover:shadow-xl transition-all mb-8">
                <div class="flex items-center justify-between mb-8">
                <h3 class="text-2xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-user-circle mr-3 text-blue-600"></i>
                    Profile Information
                </h3>
                <button class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 flex items-center">
                    <i class="fas fa-edit mr-2"></i> Edit Profile
                </button>
                </div>

                <div class="flex items-start space-x-8">
                <!-- Avatar -->
                <div class="relative group">
                    <div class="w-32 h-32 bg-gradient-to-br from-blue-500 to-purple-600 rounded-3xl flex items-center justify-center text-white text-4xl font-bold shadow-lg cursor-pointer">
                    <img src="https://picsum.photos/200" alt="Avatar" class="w-full h-full rounded-3xl object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 rounded-3xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                        <i class="fas fa-camera text-white text-xl"></i>
                    </div>
                    </div>
                </div>

                <!-- User details -->
                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div><label class="text-sm font-semibold text-slate-600">Full Name</label>
                    <p class="text-slate-800 font-medium text-lg">Thavry User</p></div>
                    <div><label class="text-sm font-semibold text-slate-600">Email</label>
                    <p class="text-slate-800 font-medium">thavry@example.com</p></div>
                    <div><label class="text-sm font-semibold text-slate-600">Phone</label>
                    <p class="text-slate-800 font-medium">+855 123456789</p></div>
                    <div><label class="text-sm font-semibold text-slate-600">Nationality</label>
                    <p class="text-slate-800 font-medium">Cambodian</p></div>
                    <div class="md:col-span-2"><label class="text-sm font-semibold text-slate-600">Address</label>
                    <p class="text-slate-800 font-medium">Phnom Penh, Cambodia</p></div>
                </div>
                </div>
            </div>

            <!-- Recent Bookings -->
            <div class="bg-white p-8 rounded-2xl shadow hover:shadow-xl transition-all">
                <div class="flex items-center justify-between mb-8">
                <h3 class="text-2xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-history mr-3 text-emerald-600"></i>
                    Recent Bookings
                </h3>
                <a href="#" class="text-blue-600 font-semibold hover:underline">View All</a>
                </div>

                <div class="space-y-4">
                <!-- Booking Item -->
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl hover:bg-slate-100 transition cursor-pointer">
                    <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-map-marker-alt text-white"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-slate-800">Siem Reap Adventure</h4>
                        <p class="text-sm text-slate-500">
                        <i class="fas fa-location-dot mr-1"></i> Siem Reap • 
                        <i class="fas fa-calendar ml-2 mr-1"></i> Aug 25, 2025
                        </p>
                    </div>
                    </div>
                    <div class="text-right">
                    <p class="font-bold text-slate-800 text-lg">$299.00</p>
                    <span class="px-2 py-1 text-sm bg-emerald-100 text-emerald-700 rounded-full"><i class="fas fa-check mr-1"></i>Confirmed</span>
                    </div>
                </div>
                </div>
            </div>
            </div>

            {{-- <!-- Stats Sidebar -->
            <div class="space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow hover:shadow-xl">
                <h4 class="text-lg font-bold text-slate-800 mb-6 flex items-center"><i class="fas fa-chart-bar mr-2 text-purple-600"></i> Travel Statistics</h4>
                <div class="space-y-4">
                <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50">
                    <div>
                    <p class="text-sm text-slate-500">Total Bookings</p>
                    <p class="font-bold text-slate-800 counter" data-target="12">0</p>
                    </div>
                    <canvas id="bookingsChart" width="48" height="48"></canvas>
                </div>
                <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50">
                    <div>
                    <p class="text-sm text-slate-500">Completed Trips</p>
                    <p class="font-bold text-slate-800 counter" data-target="8">0</p>
                    </div>
                    <canvas id="completedChart" width="48" height="48"></canvas>
                </div>
                <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50">
                    <div>
                    <p class="text-sm text-slate-500">Total Spent</p>
                    <p class="font-bold text-slate-800 counter" data-target="1500" data-prefix="$">$0</p>
                    </div>
                    <canvas id="spentChart" width="48" height="48"></canvas>
                </div>
                </div>
            </div> --}}

            <!-- Quick Actions -->
            <div class="bg-white p-6 rounded-2xl shadow hover:shadow-xl">
                <h4 class="text-lg font-bold text-slate-800 mb-6 flex items-center"><i class="fas fa-bolt mr-2 text-yellow-500"></i> Quick Actions</h4>
                <div class="space-y-3">
                <a href="#" class="flex items-center p-3 text-slate-600 hover:bg-blue-50 rounded-xl hover:text-blue-600">
                    <i class="fas fa-search mr-2 text-blue-600"></i> Browse Packages
                </a>
                <a href="#" class="flex items-center p-3 text-slate-600 hover:bg-emerald-50 rounded-xl hover:text-emerald-600">
                    <i class="fas fa-calendar mr-2 text-emerald-600"></i> My Bookings
                </a>
                <a href="#" class="flex items-center p-3 text-slate-600 hover:bg-purple-50 rounded-xl hover:text-purple-600">
                    <i class="fas fa-envelope mr-2 text-purple-600"></i> Messages <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">3</span>
                </a>
                </div>
            </div>
            </div>

        </main>

        <!-- JS -->
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <script>
            AOS.init({ duration: 800, once: true });

            // Counter animation
            document.querySelectorAll('.counter').forEach(counter => {
            let target = +counter.dataset.target;
            let prefix = counter.dataset.prefix || '';
            let count = 0;
            let step = target / 50;
            function update() {
                if (count < target) {
                count += step;
                counter.textContent = prefix + Math.ceil(count);
                requestAnimationFrame(update);
                } else {
                counter.textContent = prefix + target;
                }
            }
            update();
            });

            // Mini charts
            function createChart(id, value, color) {
            new Chart(document.getElementById(id), {
                type: 'doughnut',
                data: { datasets: [{ data: [value, 100 - value], backgroundColor: [color, '#E5E7EB'], borderWidth: 0 }] },
                options: { plugins: { legend: { display: false } }, cutout: '70%' }
            });
            }
            createChart("bookingsChart", 12, "#3B82F6");
            createChart("completedChart", 8, "#10B981");
            createChart("spentChart", 75, "#8B5CF6");
        </script>
    </div>

</div>
