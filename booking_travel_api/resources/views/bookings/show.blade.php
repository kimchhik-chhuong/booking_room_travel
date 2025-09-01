@extends('layouts.dashboard')

@section('title', 'Booking Confirmation')
@section('page-title', 'Booking Confirmation')
@section('page-subtitle', 'Thank you for your booking!')

@push('styles')
<style>
    .booking-container {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    .booking-header {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }
    .booking-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
        transform: rotate(30deg);
    }
    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: capitalize;
        margin-bottom: 1rem;
    }
    .status-confirmed { background: #10b981; color: white; }
    .status-pending { background: #f59e0b; color: white; }
    .status-cancelled { background: #ef4444; color: white; }
    .info-card {
        border-left: 4px solid #4f46e5;
        background: #f8fafc;
        border-radius: 0 8px 8px 0;
    }
    .section-title {
        position: relative;
        padding-bottom: 0.75rem;
        margin-bottom: 1.5rem;
    }
    .section-title::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 50px;
        height: 3px;
        background: #4f46e5;
        border-radius: 3px;
    }
    .price-breakdown {
        background: #f8fafc;
        border-radius: 8px;
    }
    .price-row {
        border-bottom: 1px solid #e2e8f0;
        padding: 0.75rem 0;
    }
    .price-row:last-child {
        border-bottom: none;
    }
    .total-row {
        background: #4f46e5;
        color: white;
        border-radius: 0 0 8px 8px;
        margin-top: 1rem;
    }
    .hotel-image {
        height: 200px;
        object-fit: cover;
        border-radius: 8px;
        transition: transform 0.3s ease;
    }
    .hotel-image:hover {
        transform: scale(1.02);
    }
    .print-button {
        transition: all 0.3s ease;
    }
    .print-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    @media print {
        .no-print {
            display: none !important;
        }
        body {
            background: white !important;
            color: black !important;
        }
        .booking-container {
            box-shadow: none !important;
            border: none !important;
        }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    <div class="ml-72 p-8">
        <div class="container mx-auto">
            <div class="max-w-6xl mx-auto">
                <div class="booking-container">
                    <!-- Header -->
                    <div class="booking-header relative">
                        <div class="relative z-10">
                            <div class="flex justify-between items-start">
                                <div>
                                    @php
                                        $statusClass = [
                                            'confirmed' => 'status-confirmed',
                                            'pending' => 'status-pending',
                                            'cancelled' => 'status-cancelled'
                                        ][$booking->status ?? 'pending'] ?? 'bg-gray-500';
                                    @endphp
                                    <!-- <span class="status-badge {{ $statusClass }}">
                                        {{ ucfirst($booking->status ?? 'pending') }}
                                    </span> -->
                                    <h1 class="text-3xl font-bold mb-2">Booking {{ $booking->status ?? 'pending' }}</h1>
                                    <p class="text-indigo-100">Your booking has been {{ $booking->status ?? 'pending' }}. A message has been sent to {{ $booking->user->email }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-indigo-100 mb-1">Booking Reference</p>
                                    <p class="text-2xl font-bold">{{ $booking->booking_reference }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="p-0 md:p-0">
                        <!-- Booking Details -->
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                            <!-- Guest Information -->
                            <div class="lg:col-span-2">
                                <h2 class="text-2xl font-bold mb-6 section-title">Your Booking Details</h2>
                                
                                @if(isset($hotel) && $hotel)
                                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                                    <div class="flex flex-col md:flex-row gap-6">
                                    @if(isset($hotel->images) && !empty($hotel->images))
    @php
        // Decode JSON if needed
        $images = is_string($hotel->images) ? json_decode($hotel->images, true) : $hotel->images;

        // Get the first image
        $firstImage = '';
        if(is_array($images) && count($images) > 0) {
            if(is_array($images[0]) && isset($images[0]['url'])) {
                $firstImage = $images[0]['url'];
            } else {
                $firstImage = $images[0];
            }
        }

        // Ensure it points to the public storage folder
        if($firstImage) {
            $firstImage = asset('storage/' . ltrim($firstImage, '/'));
        }
    @endphp

    @if($firstImage)
        <img src="{{ $firstImage }}" alt="{{ $hotel->name }}" class="hotel-image w-full md:w-1/3">
    @endif
@endif

                                        <div class="flex-1">
                                            <h3 class="text-xl font-semibold mb-2">{{ $hotel->name }}</h3>
                                            @if(!empty($hotel->address))
                                                <p class="text-gray-600 mb-4 flex items-center">
                                                    <i class="fas fa-map-marker-alt mr-2 text-indigo-500"></i>
                                                    {{ $hotel->address }}
                                                </p>
                                            @endif
                                            
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">Check-in</p>
                                                    <p class="font-medium">{{ \Carbon\Carbon::parse($hotelBooking->check_in_date)->format('D, M j, Y') }}</p>
                                                    <!-- @if(isset($hotel->check_in_time))
                                                        <p class="text-sm text-gray-500">After {{ $hotel->check_in_time }}</p>
                                                    @endif -->
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-500">Check-out</p>
                                                    <p class="font-medium">{{ \Carbon\Carbon::parse($hotelBooking->check_out_date)->format('D, M j, Y') }}</p>
                                                    <!-- @if(isset($hotel->check_out_time))
                                                        <p class="text-sm text-gray-500">Before {{ $hotel->check_out_time }}</p>
                                                    @endif -->
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-500">Nights</p>
                                                    <p class="font-medium">{{ $nights }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-500">Guests</p>
                                                    <p class="font-medium">
                                                        {{ $hotelBooking->num_guests ?? '1' }} 
                                                        @if(isset($booking->adults) || isset($booking->children))
                                                            ({{ $booking->adults ?? '1' }} Adults, {{ $booking->children ?? '0' }} Children)
                                                        @endif
                                                    </p>
                                                </div>
                                                @if(isset($roomType) && $roomType)
                                                <div class="sm:col-span-2">
                                                    <p class="text-sm text-gray-500">Room Type</p>
                                                    <p class="font-medium">{{ $roomType->name }}</p>
                                                </div>
                                                @endif
                                                @if(!empty($hotelBooking->special_requests))
                                                <div class="sm:col-span-2">
                                                    <p class="text-sm text-gray-500">Special Requests</p>
                                                    <p class="font-medium">{{ $hotelBooking->special_requests }}</p>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Guest Information -->
                                <div class="bg-white rounded-lg shadow-sm p-6">
                                    <h3 class="text-xl font-semibold mb-4 section-title">Guest Information</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Full Name</p>
                                            <p class="font-medium">{{ $booking->guest_first_name }} {{ $booking->guest_last_name }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Email</p>
                                            <p class="font-medium">{{ $booking->guest_email }}</p>
                                        </div>
                                        @if($booking->guest_phone)
                                        <div>
                                            <p class="text-sm text-gray-500">Phone</p>
                                            <p class="font-medium">{{ $booking->guest_phone }}</p>
                                        </div>
                                        @endif
                                        @if(!empty($booking->guest_nationality))
                                        <div>
                                            <p class="text-sm text-gray-500">Nationality</p>
                                            <p class="font-medium">{{ $booking->guest_nationality }}</p>
                                        </div>
                                        @endif
                                        <div class="md:col-span-2">
                                            <p class="text-sm text-gray-500">Booking Date</p>
                                            <p class="font-medium">{{ \Carbon\Carbon::parse($booking->created_at)->format('F d, Y h:i A') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Price Summary -->
                            <div>
                                <div class="sticky top-4">
                                    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                                        <h3 class="text-xl font-semibold mb-4 section-title">Price Summary</h3>
                                        
                                        <div class="space-y-3 mb-4">
                                            @if(isset($roomType) && $roomType && isset($nights))
                                            <div class="flex justify-between">
                                                <div>
                                                    <p class="font-medium">{{ $roomType->name }}</p>
                                                    <p class="text-sm text-gray-500">{{ $nights }} night{{ $nights > 1 ? 's' : '' }} × {{ number_format($roomType->price, 2) }} {{ config('app.currency', '$') }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="font-medium">{{ number_format($roomType->price * $nights, 2) }} {{ config('app.currency', '$') }}</p>
                                                </div>
                                            </div>
                                            @endif
                                            
                                            @if(isset($booking->tax_amount) && $booking->tax_amount > 0)
                                            <div class="flex justify-between pt-3 border-t border-gray-100">
                                                <span class="text-gray-600">Taxes & Fees</span>
                                                <span>{{ number_format($booking->tax_amount, 2) }} {{ config('app.currency', '$') }}</span>
                                            </div>
                                            @endif
                                            
                                            @if(isset($booking->discount_amount) && $booking->discount_amount > 0)
                                            <div class="flex justify-between pt-3 border-t border-gray-100 text-green-600">
                                                <span>Discount</span>
                                                <span>-{{ number_format($booking->discount_amount, 2) }} {{ config('app.currency', '$') }}</span>
                                            </div>
                                            @endif
                                        </div>
                                        
                                        <div class="pt-4 mt-4 border-t border-gray-200">
                                            <div class="flex justify-between items-center">
                                                <span class="font-bold text-lg">Total</span>
                                                <span class="font-bold text-xl text-indigo-600">{{ number_format($booking->total_amount, 2) }} {{ config('app.currency', '$') }}</span>
                                            </div>
                                            
                                            @if($booking->payment_status === 'pending' && $booking->payment_method === 'pay_at_hotel')
                                            <div class="mt-3 p-3 bg-blue-50 text-blue-700 rounded-md text-sm">
                                                <i class="fas fa-info-circle mr-1"></i> Payment will be made at the hotel
                                            </div>
                                            @endif
                                        </div>
                                        
                                        <div class="mt-6 space-y-3">
                                            <a href="{{ route('bookings.show', ['booking' => $booking->id, 'download' => 'pdf']) }}" 
                                               class="w-full flex items-center justify-center px-4 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors print-button no-print">
                                                <i class="fas fa-download mr-2"></i> Download PDF
                                            </a>
                                            <button onclick="window.print()" class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                                                <i class="fas fa-print mr-2"></i> Print Voucher
                                            </button>
                                        </div>
                                    </div>
                                    
                                    @if(isset($hotel) && (!empty($hotel->cancellation_policy) || !empty($hotel->check_in_policy)))
                                    <div class="bg-white rounded-lg shadow-sm p-6">
                                        <h3 class="text-lg font-semibold mb-3">Hotel Policies</h3>
                                        
                                        @if(!empty($hotel->cancellation_policy))
                                        <div class="mb-4">
                                            <h4 class="font-medium text-gray-700 mb-1">Cancellation Policy</h4>
                                            <p class="text-sm text-gray-600">{{ $hotel->cancellation_policy }}</p>
                                        </div>
                                        @endif
                                        
                                        @if(!empty($hotel->check_in_policy))
                                        <div>
                                            <h4 class="font-medium text-gray-700 mb-1">Check-in/Check-out Policy</h4>
                                            <p class="text-sm text-gray-600">{{ $hotel->check_in_policy }}</p>
                                        </div>
                                        @endif
                                    </div>
                                    @endif
                                    
                                    <div class="mt-6 bg-white rounded-lg shadow-sm p-6 text-center no-print">
                                        <h3 class="text-lg font-semibold mb-2">Need Help?</h3>
                                        <p class="text-gray-600 text-sm mb-4">Our customer service team is available 24/7 to assist you with any questions.</p>
                                        <div class="space-y-2">
                                            <div class="flex items-center justify-center text-indigo-600">
                                                <i class="fas fa-phone-alt mr-2"></i>
                                                <span>+855 23 999 999</span>
                                            </div>
                                            <div class="flex items-center justify-center text-indigo-600">
                                                <i class="fas fa-envelope mr-2"></i>
                                                <span>support@bookingtravel.com</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Map Section -->
                        @if(isset($hotel) && isset($hotel->latitude) && isset($hotel->longitude))
                        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                            <h3 class="text-xl font-semibold mb-4 section-title">Location</h3>
                            <div class="h-64 bg-gray-100 rounded-lg overflow-hidden relative">
                                <iframe 
                                    width="100%" 
                                    height="100%" 
                                    frameborder="0" 
                                    style="border:0" 
                                    src="https://www.google.com/maps/embed/v1/place?key={{ config('services.google.maps_api_key') }}&q={{ $hotel->latitude }},{{ $hotel->longitude }}&zoom=15" 
                                    allowfullscreen>
                                </iframe>
                                <div class="absolute bottom-4 right-4">
                                    <a href="https://www.google.com/maps?q={{ $hotel->latitude }},{{ $hotel->longitude }}" 
                                       target="_blank" 
                                       class="inline-flex items-center px-4 py-2 bg-white text-indigo-600 rounded-md shadow-sm hover:bg-gray-50">
                                        <i class="fas fa-directions mr-2"></i> Get Directions
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Add any JavaScript for interactivity here
    document.addEventListener('DOMContentLoaded', function() {
        // Print functionality
        document.querySelectorAll('.print-button').forEach(button => {
            button.addEventListener('click', function(e) {
                if (this.classList.contains('no-print') && window.matchMedia('print').matches) {
                    e.preventDefault();
                    window.print();
                }
            });
        });
    });
</script>
@endpush