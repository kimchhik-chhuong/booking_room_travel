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
                        <p class="text-3xl font-bold text-dark-800">15</p>
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
                        <p class="text-3xl font-bold text-dark-800">$52,730</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+20.5% this month</p>
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
                        <p class="text-3xl font-bold text-dark-800">1,856</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+26.7% usage rate</p>
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
                        <p class="text-3xl font-bold text-dark-800">20%</p>
                        <p class="text-emerald-600 text-sm font-medium mt-2">+3% increase</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-orange-400 to-orange-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-percentage text-white text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Deals -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12" id="featuredDeals">
            <!-- Populated by JavaScript -->
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
                            <input type="text" id="searchDeals" placeholder="Search deals..." class="input-modern pl-10 w-64">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-400"></i>
                        </div>
                        <select id="filterStatus" class="input-modern">
                            <option value="">All Status</option>
                            <option value="Active">Active</option>
                            <option value="Expired">Expired</option>
                            <option value="Scheduled">Scheduled</option>
                        </select>
                        <button id="createDealBtn" class="btn-modern">
                            <i class="fas fa-plus mr-2"></i> Create Deal
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full" id="dealsTable">
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
                        <tbody class="bg-white divide-y divide-slate-200" id="dealsTableBody">
                            <!-- Populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="px-8 py-6 flex items-center justify-between border-t border-slate-200" id="pagination">
                    <!-- Populated by JavaScript -->
                </div>
            </div>
        </div>

        <!-- Dynamic Form Container -->
        <div id="createDealFormContainer" class="hidden mt-8"></div>
    </div>
</div>


@push('scripts')
<script>
    // Fetch and display deals
    function fetchDeals() {
        const search = document.getElementById('searchDeals').value;
        const status = document.getElementById('filterStatus').value;
        fetch(`/api/deals?search=${search}&status=${status}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const deals = data.data;
                    const tableBody = document.getElementById('dealsTableBody');
                    tableBody.innerHTML = '';
                    deals.forEach(deal => {
                        const row = document.createElement('tr');
                        row.className = 'table-row transition-all duration-200 hover:bg-slate-50';
                        row.innerHTML = `
                            <td class="px-8 py-6"><p class="font-semibold text-dark-800">${deal.title}</p></td>
                            <td class="px-8 py-6"><span class="bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-sm font-semibold">${deal.discount}</span></td>
                            <td class="px-8 py-6"><code class="bg-slate-100 text-dark-800 px-3 py-1 rounded-lg text-sm font-mono">${deal.code}</code></td>
                            <td class="px-8 py-6">
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm font-medium text-dark-800">${deal.used}/${deal.limit}</span>
                                    <div class="w-20 bg-slate-200 rounded-full h-2">
                                        <div class="bg-primary-600 h-2 rounded-full" style="width: ${(deal.used / deal.limit) * 100}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6"><p class="text-dark-700">${new Date(deal.valid_until).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</p></td>
                            <td class="px-8 py-6">
                                <span class="badge-modern ${deal.status === 'Active' ? 'bg-emerald-100 text-emerald-800' : deal.status === 'Scheduled' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800'}">
                                    ${deal.status}
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
                        `;
                        tableBody.appendChild(row);
                    });
                }
            })
            .catch(error => console.error('Error fetching deals:', error));
    }


    // Fetch featured deals (e.g., scheduled ones)
    function fetchFeaturedDeals() {
        fetch('/api/deals?status=Scheduled')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const deals = data.data;
                    const container = document.getElementById('featuredDeals');
                    container.innerHTML = '';
                    deals.forEach(deal => {
                        const div = document.createElement('div');
                        div.className = `bg-gradient-to-br ${deal.color} rounded-2xl p-8 text-white card-modern`;
                        div.innerHTML = `
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-2xl font-bold">${deal.title}</h3>
                                <span class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-semibold">${deal.status}</span>
                            </div>
                            <div class="mb-6">
                                <p class="text-4xl font-bold mb-3">${deal.discount}</p>
                                <p class="text-white/90">${deal.description}</p>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-4 mb-6">
                                <p class="text-sm font-semibold mb-1">Promo Code</p>
                                <p class="text-xl font-bold tracking-wider">${deal.code}</p>
                            </div>
                            <div class="flex items-center justify-between text-sm mb-4">
                                <span>Valid until ${new Date(deal.valid_until).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</span>
                                <span>${deal.used}/${deal.limit} used</span>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm rounded-full h-3">
                                <div class="bg-white rounded-full h-3" style="width: ${(deal.used / deal.limit) * 100}%"></div>
                            </div>
                        `;
                        container.appendChild(div);
                    });
                }
            })
            .catch(error => console.error('Error fetching featured deals:', error));
    }


    // Fetch and display create form
    document.getElementById('createDealBtn').addEventListener('click', function() {
        fetch('/api/deals/create')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const container = document.getElementById('createDealFormContainer');
                    container.innerHTML = `
                        <div class="bg-white rounded-2xl p-8 card-modern">
                            <form id="createDealForm">
                                ${Object.entries(data.form).map(([key, field]) => `
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-dark-600 mb-2" for="${key}">${field.label}</label>
                                        ${field.type === 'textarea' ? `<textarea id="${key}" name="${key}" class="input-modern w-full" rows="4" placeholder="${field.placeholder}" ${field.required ? 'required' : ''}></textarea>` : 
                                        field.type === 'select' ? `
                                            <select id="${key}" name="${key}" class="input-modern w-full" ${field.required ? 'required' : ''}>
                                                ${field.options.map(option => `<option value="${option}">${option.replace('from-', '').replace('to-', ' to ')}</option>`).join('')}
                                            </select>` : 
                                        `<input type="${field.type}" id="${key}" name="${key}" class="input-modern w-full" placeholder="${field.placeholder}" ${field.required ? 'required' : ''}>`}
                                    </div>
                                `).join('')}
                                <div class="flex justify-end space-x-4">
                                    <a href="{{ url('/deals') }}" class="btn-modern bg-slate-200 text-dark-600">Cancel</a>
                                    <button type="submit" class="btn-modern bg-primary-600 text-white">Create Deal</button>
                                </div>
                            </form>
                        </div>
                    `;
                    container.classList.remove('hidden');

                    // Handle form submission
                    document.getElementById('createDealForm').addEventListener('submit', function(e) {
                        e.preventDefault();
                        const formData = new FormData(this);
                        fetch('/api/deals', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.message);
                                container.classList.add('hidden');
                                fetchDeals();
                                fetchFeaturedDeals();
                            } else {
                                alert('Error: ' + (data.message || 'Failed to create deal'));
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred. Please try again.');
                        });
                    });
                }
            })
            .catch(error => console.error('Error fetching create form:', error));
    });

    // Initial load
    document.addEventListener('DOMContentLoaded', () => {
        fetchDeals();
        fetchFeaturedDeals();
    });

    // Search and filter
    document.getElementById('searchDeals').addEventListener('input', fetchDeals);
    document.getElementById('filterStatus').addEventListener('change', fetchDeals);
</script>
@endpush
@endsection
