@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Booking #{{ $booking->booking_reference ?? 'N/A' }}</h4>
                        <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back to Booking
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('bookings.update', $booking->id) }}" method="POST" id="bookingForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Guest Information -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Guest Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" 
                                               value="{{ old('first_name', $booking->first_name ?? '') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" 
                                               value="{{ old('last_name', $booking->last_name ?? '') }}" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="{{ old('email', $booking->email ?? '') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control" id="phone" name="phone" 
                                               value="{{ old('phone', $booking->phone ?? '') }}" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="nationality" class="form-label">Nationality <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nationality" name="nationality" 
                                           value="{{ old('nationality', $booking->nationality ?? '') }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- Booking Details -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Booking Details</h5>
                            </div>
                            <div class="card-body">
                                @php
                                    $hotelBooking = $booking->hotelBookings->first();
                                    $checkIn = $hotelBooking ? $hotelBooking->check_in_date : now();
                                    $checkOut = $hotelBooking ? $hotelBooking->check_out_date : now()->addDay();
                                    $adults = $hotelBooking ? $hotelBooking->num_guests : 2;
                                    $children = 0; // Default value, adjust as needed
                                @endphp

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="check_in" class="form-label">Check-in Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="check_in" name="check_in" 
                                               value="{{ old('check_in', $checkIn ? $checkIn->format('Y-m-d') : '') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="check_out" class="form-label">Check-out Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="check_out" name="check_out" 
                                               value="{{ old('check_out', $checkOut ? $checkOut->format('Y-m-d') : '') }}" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="adults" class="form-label">Adults <span class="text-danger">*</span></label>
                                        <select class="form-select" id="adults" name="adults" required>
                                            @for($i = 1; $i <= 10; $i++)
                                                <option value="{{ $i }}" {{ (old('adults', $adults) == $i) ? 'selected' : '' }}>
                                                    {{ $i }} {{ $i == 1 ? 'Adult' : 'Adults' }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="children" class="form-label">Children</label>
                                        <select class="form-select" id="children" name="children">
                                            @for($i = 0; $i <= 10; $i++)
                                                <option value="{{ $i }}" {{ (old('children', $children) == $i) ? 'selected' : '' }}>
                                                    {{ $i }} {{ $i == 1 ? 'Child' : 'Children' }}
                                                </option>
                                            @endfor
                                        </select>
                                        <small class="text-muted">Ages 2-12 years</small>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="special_requests" class="form-label">Special Requests</label>
                                    <textarea class="form-control" id="special_requests" name="special_requests" rows="3">{{ old('special_requests', $hotelBooking->special_requests ?? '') }}</textarea>
                                    <small class="text-muted">Any special requirements or requests</small>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden fields -->
                        <input type="hidden" name="hotel_id" value="{{ $hotelBooking->hotel_id ?? '' }}">
                        <input type="hidden" name="room_type_id" id="room_type_id" value="{{ $hotelBooking->room_type_id ?? '' }}">

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-outline-secondary me-md-2">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Date validation
        const checkInInput = document.getElementById('check_in');
        const checkOutInput = document.getElementById('check_out');
        
        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        checkInInput.min = today;
        
        // Update check-out min date when check-in date changes
        checkInInput.addEventListener('change', function() {
            checkOutInput.min = this.value;
            if (new Date(checkOutInput.value) < new Date(this.value)) {
                checkOutInput.value = this.value;
            }
        });
        
        // Form submission handler
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            if (new Date(checkOutInput.value) <= new Date(checkInInput.value)) {
                e.preventDefault();
                alert('Check-out date must be after check-in date');
                return false;
            }
            return true;
        });
    });
</script>
@endpush

<style>
    .card {
        border: none;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .card-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    .btn-outline-secondary {
        color: #6c757d;
        border-color: #6c757d;
    }
    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: white;
    }
</style>
@endsection
