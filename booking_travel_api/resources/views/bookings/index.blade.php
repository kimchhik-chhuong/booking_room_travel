@extends('layouts.dashboard')

@section('title', 'Bookings Management')
@section('page-title', 'Bookings Management')
@section('page-subtitle', 'Track and manage all your travel bookings and reservations')

@section('content')
@php
use Illuminate\Support\Str;
@endphp

<div class="min-h-screen">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    <div class="md:pl-64 ml-8 flex flex-col p-8">

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Bookings -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Bookings</p>
                        <p class="text-2xl font-bold mt-1">{{ number_format($totalBookings) }}</p>
                        @php
                            $bookingChange = $previousMonthBookings > 0 ? 
                                round((($totalBookings - $previousMonthBookings) / $previousMonthBookings) * 100, 1) : 
                                0;
                        @endphp
                        <p class="text-sm {{ $bookingChange >= 0 ? 'text-green-600' : 'text-red-600' }} mt-2 flex items-center">
                            @if($bookingChange != 0)
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12 7a1 1 0 01.707.293l4 4a1 1 0 01-1.414 1.414L13 9.414V17a1 1 0 11-2 0V9.414l-2.293 2.293a1 1 0 01-1.414-1.414l4-4A1 1 0 0112 7z" clip-rule="evenodd" />
                                </svg>
                                {{ abs($bookingChange) }}% {{ $bookingChange >= 0 ? 'increase' : 'decrease' }}
                            @else
                                <span>No change</span>
                            @endif
                        </p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Confirmed -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Confirmed</p>
                        <p class="text-2xl font-bold mt-1">{{ number_format($confirmedBookings) }}</p>
                        @php
                            $confirmedPercentage = $totalBookings > 0 ? round(($confirmedBookings / $totalBookings) * 100, 1) : 0;
                        @endphp
                        <p class="text-sm text-green-600 mt-2 flex items-center">
                            <span>{{ $confirmedPercentage }}% of total</span>
                        </p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Pending</p>
                        <p class="text-2xl font-bold mt-1">{{ number_format($pendingBookings) }}</p>
                        @php
                            $pendingPercentage = $totalBookings > 0 ? round(($pendingBookings / $totalBookings) * 100, 1) : 0;
                        @endphp
                        <p class="text-sm text-yellow-600 mt-2 flex items-center">
                            <span>{{ $pendingPercentage }}% of total</span>
                        </p>
                    </div>
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Revenue -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Revenue</p>
                        <p class="text-2xl font-bold mt-1">${{ number_format($totalRevenue) }}</p>
                        @php
                            $revenueChange = $previousMonthRevenue > 0 ? 
                                round((($totalRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100, 1) : 
                                ($totalRevenue > 0 ? 100 : 0);
                        @endphp
                        <p class="text-sm {{ $revenueChange >= 0 ? 'text-green-600' : 'text-red-600' }} mt-2 flex items-center">
                            @if($revenueChange != 0)
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12 7a1 1 0 01.707.293l4 4a1 1 0 01-1.414 1.414L13 9.414V17a1 1 0 11-2 0V9.414l-2.293 2.293a1 1 0 01-1.414-1.414l4-4A1 1 0 0112 7z" clip-rule="evenodd" />
                                </svg>
                                {{ abs($revenueChange) }}% {{ $revenueChange >= 0 ? 'increase' : 'decrease' }}
                            @else
                                <span>No change</span>
                            @endif
                        </p>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bookings Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Recent Bookings</h2>
                        <p class="text-gray-500">All your recent bookings and reservations</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <div class="flex space-x-2">
                            <div class="relative">
                                <input type="text" placeholder="Search bookings..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>
                            <select class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option>All Status</option>
                                <option>Confirmed</option>
                                <option>Pending</option>
                                <option>Cancelled</option>
                            </select>
                            <a href="{{ route('bookings.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                                <i class="fas fa-plus mr-2"></i> New Booking
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guest</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hotel</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-in / Check-out</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room Type</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guests</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-2 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($bookings as $booking)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" src="{{ $booking->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($booking->user->name) }}" alt="{{ $booking->user->name }}">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $booking->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $booking->user->email }}</div>
                                        <div class="text-xs text-gray-400">Ref: {{ $booking->booking_reference }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($booking->hotelBookings->isNotEmpty() && $booking->hotelBookings[0]->hotel)
                                    @php $hotel = $booking->hotelBookings[0]->hotel; @endphp
                                    <div class="text-sm font-medium text-gray-900">{{ $hotel->name ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $hotel->address ?? '' }}</div>
                                @else
                                    <div class="text-sm text-gray-500">No hotel information</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($booking->hotelBookings->isNotEmpty())
                                    @php
                                        $hotelBooking = $booking->hotelBookings[0];
                                        $checkIn = $hotelBooking->check_in_date ? \Carbon\Carbon::parse($hotelBooking->check_in_date) : null;
                                        $checkOut = $hotelBooking->check_out_date ? \Carbon\Carbon::parse($hotelBooking->check_out_date) : null;
                                    @endphp
                                    <div class="text-sm text-gray-900">
                                        <div class="font-medium">{{ $checkIn ? $checkIn->format('M d, Y') : 'N/A' }}</div>
                                        <div class="text-gray-500 text-xs">to</div>
                                        <div class="font-medium">{{ $checkOut ? $checkOut->format('M d, Y') : 'N/A' }}</div>
                                        @if($checkIn && $checkOut)
                                            @php $nights = $checkIn->diffInDays($checkOut); @endphp
                                            <div class="text-xs text-blue-600 mt-1">{{ $nights }} {{ Str::plural('night', $nights) }}</div>
                                        @endif
                                    </div>
                                @else
                                    <div class="text-sm text-gray-500">No dates available</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($booking->hotelBookings->isNotEmpty() && $booking->hotelBookings[0]->roomType)
                                    @php $roomType = $booking->hotelBookings[0]->roomType; @endphp
                                    <div class="text-sm text-gray-900">{{ $roomType->name ?? 'Standard' }}</div>
                                    <div class="text-sm text-gray-500">
                                        {{ $roomType->max_occupancy ?? 'N/A' }} {{ Str::plural('guest', $roomType->max_occupancy ?? 0) }}
                                    </div>
                                    <div class="text-sm text-blue-600">
                                        {{ number_format($roomType->price ?? 0, 2) }} {{ config('app.currency', '$') }}<span class="text-xs text-gray-500">/night</span>
                                    </div>
                                @else
                                    <div class="text-sm text-gray-500">No room info</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $booking->hotelBookings->sum('num_rooms') ?? 1 }} {{ Str::plural('room', $booking->hotelBookings->sum('num_rooms') ?? 1) }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $booking->participants ?? 1 }} {{ Str::plural('guest', $booking->participants ?? 1) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($booking->total_amount, 2) }} {{ $booking->currency ?? config('app.currency', '$') }}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                @php
                                    $statusClasses = [
                                        'confirmed' => 'bg-green-100 text-green-800',
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                        'completed' => 'bg-blue-100 text-blue-800',
                                        'refunded' => 'bg-purple-100 text-purple-800',
                                        'failed' => 'bg-gray-100 text-gray-800',
                                    ][$booking->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="relative inline-block text-left">
                                    <button type="button" class="dropdown-toggle inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none" onclick="toggleDropdown('dropdown-{{ $booking->id }}')">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                        </svg>
                                    </button>
                                    <div id="dropdown-{{ $booking->id }}" class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                        <div class="py-1">
                                            <a href="{{ route('bookings.show', $booking) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <svg class="mr-3 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                View
                                            </a>

                                            @if($booking->status === 'pending')
                                            <form action="{{ route('bookings.check-in', $booking) }}" method="POST" class="w-full">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="w-full text-left flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="return confirm('Mark this booking as checked-in?')">
                                                    <svg class="mr-3 h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Check-in
                                                </button>
                                            </form>
                                            @endif

                                            @if(in_array($booking->status, ['pending', 'confirmed']))
                                            <a href="{{ route('bookings.edit', $booking) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <svg class="mr-3 h-5 w-5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit
                                            </a>

                                            <form action="{{ route('bookings.cancel', $booking) }}" method="POST" class="w-full">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="w-full text-left flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="return confirm('Are you sure you want to cancel this booking?')">
                                                    <svg class="mr-3 h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Cancel
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                No bookings found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(isset($bookings->links))
            <div class="bg-white px-6 py-4 border-t border-gray-200">
                {{ $bookings->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleDropdown(id) {
        var dropdown = document.getElementById(id);
        dropdown.classList.toggle('hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        console.log('Bookings page loaded');
    });
</script>
@endpush