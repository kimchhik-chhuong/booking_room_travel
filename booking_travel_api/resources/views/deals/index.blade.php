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
    document.addEventListener('DOMContentLoaded', () => {
    fetchDeals();
    fetchFeaturedDeals();

    document.getElementById('searchDeals').addEventListener('input', fetchDeals);
    document.getElementById('filterStatus').addEventListener('change', fetchDeals);

    const createBtn = document.getElementById('createDealBtn');
    const formContainer = document.getElementById('createDealFormContainer');

    createBtn.addEventListener('click', async () => {
        try {
            const response = await fetch('/api/deals/create');
            const data = await response.json();
            if (!data.success) throw new Error('Failed to load create form');

            formContainer.innerHTML = `
                <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-3xl shadow-xl p-8 card-modern animate-fadeIn">
                    <form id="createDealForm" class="space-y-6">
                        ${Object.entries(data.form).map(([key, field]) => `
                            <div class="flex flex-col">
                                <label class="text-sm font-semibold text-gray-700 mb-2" for="${key}">${field.label}</label>
                                ${field.type === 'textarea' ? `<textarea id="${key}" name="${key}" class="input-modern w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 p-3 transition" rows="3" placeholder="${field.placeholder}" ${field.required ? 'required' : ''}></textarea>` :
                                field.type === 'select' ? `<select id="${key}" name="${key}" class="input-modern w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 p-3 transition" ${field.required ? 'required' : ''}>
                                    ${field.options.map(option => `<option value="${option}">${option.replace('from-', '').replace('to-', ' to ')}</option>`).join('')}
                                </select>` :
                                `<input type="${field.type}" id="${key}" name="${key}" class="input-modern w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 p-3 transition" placeholder="${field.placeholder}" ${field.required ? 'required' : ''}>`}
                            </div>
                        `).join('')}
                        <div class="flex justify-end space-x-4">
                            <button type="button" class="btn-modern bg-gray-300 text-gray-700 hover:bg-gray-400 transition rounded-lg px-5 py-2" id="cancelCreate">Cancel</button>
                            <button type="submit" class="btn-modern bg-gradient-to-r from-indigo-500 to-purple-500 text-white hover:from-indigo-600 hover:to-purple-600 rounded-lg px-5 py-2 transition">Create Deal</button>
                        </div>
                    </form>
                </div>
            `;
            formContainer.classList.remove('hidden');

            document.getElementById('cancelCreate').addEventListener('click', () => {
                formContainer.classList.add('hidden');
            });

            const createForm = document.getElementById('createDealForm');
            createForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(createForm);

                try {
                    const res = await fetch('/api/deals', { method: 'POST', body: formData });
                    const result = await res.json();

                    if (!result.success) throw new Error(result.message || 'Failed to create deal');

                    // Create a modern card with new colors
                    const card = document.createElement('div');
                    card.className = 'bg-gradient-to-br from-purple-400 to-indigo-500 text-white rounded-3xl shadow-lg p-6 mb-6 transition transform hover:scale-105';
                    card.innerHTML = `
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold">${result.data.title}</h3>
                            <span class="text-sm font-semibold px-3 py-1 rounded-full ${result.data.status === 'Active' ? 'bg-green-200 text-green-800' : 'bg-blue-200 text-blue-800'}">${result.data.status}</span>
                        </div>
                        <p class="text-3xl font-bold mb-2">${result.data.discount}</p>
                        <p class="text-white/90 mb-4">${result.data.description || ''}</p>
                        <div class="flex justify-between text-sm">
                            <span>Expires: ${new Date(result.data.valid_until).toLocaleDateString()}</span>
                            <span>Used: ${result.data.used} times</span>
                        </div>
                    `;
                    document.getElementById('featuredDeals').prepend(card);

                    alert(result.message);
                    formContainer.classList.add('hidden');
                    fetchDeals();
                    fetchFeaturedDeals();
                } catch (err) {
                    console.error(err);
                    alert('Error: ' + err.message);
                }
            }, { once: true });

        } catch (err) {
            console.error(err);
            alert('Failed to load create form');
        }
    });
});

</script>
@endpush
@endsection
