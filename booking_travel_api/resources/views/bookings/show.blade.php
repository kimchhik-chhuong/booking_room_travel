@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="fas fa-check-circle me-2"></i>Booking Confirmation</h4>
                        <span class="badge bg-light text-dark fs-6">#{{ $booking->booking_reference ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                        </div>
                        <h3 class="text-success mb-2">Thank You for Your Booking!</h3>
                        <p class="text-muted">Your booking has been confirmed. We've sent a confirmation email to <strong>{{ $booking->email ?? 'your email' }}</strong></p>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-hotel me-2"></i>Booking Details</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled">
                                        <li class="mb-2">
                                            <strong><i class="fas fa-calendar-check me-2"></i>Booking Date:</strong> 
                                            {{ $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('F d, Y') : 'N/A' }}
                                        </li>
                                        <li class="mb-2">
                                            <strong><i class="fas fa-user me-2"></i>Guest Name:</strong> 
                                            {{ $booking->first_name ?? '' }} {{ $booking->last_name ?? '' }}
                                        </li>
                                        <li class="mb-2">
                                            <strong><i class="fas fa-envelope me-2"></i>Email:</strong> 
                                            {{ $booking->email ?? 'N/A' }}
                                        </li>
                                        <li class="mb-2">
                                            <strong><i class="fas fa-phone me-2"></i>Phone:</strong> 
                                            {{ $booking->phone ?? 'N/A' }}
                                        </li>
                                        <li class="mb-2">
                                            <strong><i class="fas fa-flag me-2"></i>Nationality:</strong> 
                                            {{ $booking->nationality ?? 'N/A' }}
                                        </li>
                                        <li>
                                            <strong><i class="fas fa-info-circle me-2"></i>Status:</strong>
                                            @php
                                                $statusClass = [
                                                    'confirmed' => 'bg-success',
                                                    'pending' => 'bg-warning',
                                                    'cancelled' => 'bg-danger',
                                                    'completed' => 'bg-info'
                                                ][$booking->status ?? 'pending'] ?? 'bg-secondary';
                                            @endphp
                                            <span class="badge {{ $statusClass }}">
                                                {{ ucfirst($booking->status ?? 'pending') }}
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Stay Details</h5>
                                </div>
                                <div class="card-body">
                                    @if(isset($hotelBooking) && $hotelBooking)
                                        <ul class="list-unstyled">
                                            <li class="mb-2">
                                                <strong><i class="fas fa-hotel me-2"></i>Hotel:</strong> 
                                                {{ $hotel->name ?? 'N/A' }}
                                            </li>
                                            <li class="mb-2">
                                                <strong><i class="fas fa-map-marker-alt me-2"></i>Location:</strong> 
                                                {{ $hotel->address ?? 'N/A' }}
                                            </li>
                                            <li class="mb-2">
                                                <strong><i class="fas fa-door-open me-2"></i>Room Type:</strong> 
                                                {{ $roomType->name ?? 'N/A' }}
                                            </li>
                                            <li class="mb-2">
                                                <strong><i class="fas fa-calendar-day me-2"></i>Check-in:</strong> 
                                                {{ $hotelBooking->check_in_date ? \Carbon\Carbon::parse($hotelBooking->check_in_date)->format('F d, Y') : 'N/A' }}
                                            </li>
                                            <li class="mb-2">
                                                <strong><i class="fas fa-calendar-check me-2"></i>Check-out:</strong> 
                                                {{ $hotelBooking->check_out_date ? \Carbon\Carbon::parse($hotelBooking->check_out_date)->format('F d, Y') : 'N/A' }}
                                            </li>
                                            <li class="mb-2">
                                                <strong><i class="fas fa-moon me-2"></i>Nights:</strong> 
                                                {{ $nights ?? 'N/A' }}
                                            </li>
                                            <li class="mb-2">
                                                <strong><i class="fas fa-users me-2"></i>Guests:</strong> 
                                                {{ $hotelBooking->num_guests ?? '1' }} ({{ $booking->adults ?? '1' }} Adults, {{ $booking->children ?? '0' }} Children)
                                            </li>
                                            @if(!empty($hotelBooking->special_requests))
                                                <li class="mt-3 pt-2 border-top">
                                                    <strong><i class="fas fa-comment-alt me-2"></i>Special Requests:</strong>
                                                    <p class="mb-0 mt-1">{{ $hotelBooking->special_requests }}</p>
                                                </li>
                                            @endif
                                        </ul>
                                    @else
                                        <div class="text-center text-muted py-4">
                                            <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
                                            <p class="mb-0">No hotel booking details found.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-file-invoice-dollar me-2"></i>Pricing Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Description</th>
                                            <th class="text-end">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($roomType) && $roomType && isset($nights))
                                            <tr>
                                                <td>
                                                    {{ $roomType->name ?? 'Room' }} ({{ $nights }} night{{ $nights > 1 ? 's' : '' }} @ {{ number_format($roomType->price, 2) }} {{ config('app.currency', '$') }}/night)
                                                </td>
                                                <td class="text-end">{{ number_format($roomType->price * $nights, 2) }} {{ config('app.currency', '$') }}</td>
                                            </tr>
                                        @endif
                                        
                                        @if(isset($booking->tax_amount) && $booking->tax_amount > 0)
                                            <tr>
                                                <td>Taxes & Fees</td>
                                                <td class="text-end">{{ number_format($booking->tax_amount, 2) }} {{ config('app.currency', '$') }}</td>
                                            </tr>
                                        @endif
                                        
                                        @if(isset($booking->discount_amount) && $booking->discount_amount > 0)
                                            <tr class="table-success">
                                                <td>Discount</td>
                                                <td class="text-end">-{{ number_format($booking->discount_amount, 2) }} {{ config('app.currency', '$') }}</td>
                                            </tr>
                                        @endif
                                        
                                        <tr class="table-active fw-bold">
                                            <td>Total Amount</td>
                                            <td class="text-end">{{ number_format($booking->total_amount, 2) }} {{ config('app.currency', '$') }}</td>
                                        </tr>
                                        
                                        @if($booking->payment_status === 'pending' && $booking->payment_method === 'pay_at_hotel')
                                            <tr class="table-info">
                                                <td colspan="2" class="text-center">
                                                    <i class="fas fa-info-circle me-2"></i>Payment will be made at the hotel
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('home') }}" class="btn btn-outline-primary">
                            <i class="fas fa-home me-2"></i>Back to Home
                        </a>
                        <div>
                            <button onclick="window.print()" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-print me-2"></i>Print Voucher
                            </button>
                            <a href="{{ route('bookings.show', $booking->id) }}?download=pdf" class="btn btn-primary">
                                <i class="fas fa-download me-2"></i>Download PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .card, .card * {
            visibility: visible;
        }
        .card {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            border: none;
            box-shadow: none;
        }
        .no-print {
            display: none !important;
        }
        .card-header {
            background-color: #f8f9fa !important;
            color: #000 !important;
            border-bottom: 1px solid #dee2e6;
        }
    }
    
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: transform 0.2s;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    .card-header {
        font-weight: 600;
    }
    
    .badge {
        font-size: 0.8rem;
        padding: 0.4em 0.8em;
    }
</style>

@endsection
