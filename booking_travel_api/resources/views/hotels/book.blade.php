@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Book {{ $hotel->name }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('hotels.book.store', $hotel->id) }}" method="POST" id="bookingForm">
                        @csrf
                        
                        <!-- Hotel Info -->
                        <div class="hotel-info mb-4">
                            <h5>Hotel Information</h5>
                            <p class="mb-1"><i class="fas fa-map-marker-alt"></i> {{ $hotel->address }}</p>
                            @if($hotel->contact_phone)
                                <p class="mb-1"><i class="fas fa-phone"></i> {{ $hotel->contact_phone }}</p>
                            @endif
                            @if($hotel->star_rating)
                                <div class="text-warning">
                                    @for($i = 0; $i < $hotel->star_rating; $i++)
                                        <i class="fas fa-star"></i>
                                    @endfor
                                </div>
                            @endif
                        </div>

                        <!-- Check-in/Check-out Dates -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="check_in">Check-in Date</label>
                                    <input type="date" class="form-control @error('check_in_date') is-invalid @enderror" 
                                           id="check_in" name="check_in_date" 
                                           value="{{ old('check_in_date') }}" required>
                                    @error('check_in_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="check_out">Check-out Date</label>
                                    <input type="date" class="form-control @error('check_out_date') is-invalid @enderror" 
                                           id="check_out" name="check_out_date" 
                                           value="{{ old('check_out_date') }}" required>
                                    @error('check_out_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Guests -->
                        <div class="form-group mb-4">
                            <label for="guests">Number of Guests</label>
                            <select class="form-control @error('num_guests') is-invalid @enderror" 
                                    id="guests" name="num_guests" required>
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}" {{ old('num_guests') == $i ? 'selected' : '' }}>
                                        {{ $i }} {{ $i === 1 ? 'Guest' : 'Guests' }}
                                    </option>
                                @endfor
                            </select>
                            @error('num_guests')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Room Type -->
                        <div class="form-group mb-4">
                            <label>Room Type</label>
                            @foreach($roomTypes as $roomType)
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <div class="form-check">
                                            <input class="form-check-input room-type-radio" 
                                                   type="radio" 
                                                   name="room_type_id" 
                                                   id="room_type_{{ $roomType->id }}" 
                                                   value="{{ $roomType->id }}"
                                                   data-price="{{ $roomType->price }}"
                                                   {{ old('room_type_id') == $roomType->id ? 'checked' : ($loop->first ? 'checked' : '') }}>
                                            <label class="form-check-label w-100" for="room_type_{{ $roomType->id }}">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <h6 class="mb-1">{{ $roomType->name }}</h6>
                                                        <p class="text-muted small mb-1">Max Occupancy: {{ $roomType->max_occupancy }} guests</p>
                                                        @if($roomType->amenities)
                                                            <p class="text-muted small mb-0">
                                                                @foreach(explode(',', $roomType->amenities) as $amenity)
                                                                    <span class="badge bg-light text-dark">{{ trim($amenity) }}</span>
                                                                @endforeach
                                                            </p>
                                                        @endif
                                                    </div>
                                                    <div class="text-end">
                                                        <div class="h5 mb-0">${{ number_format($roomType->price, 2) }}</div>
                                                        <small class="text-muted">per night</small>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            @error('room_type_id')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Number of Rooms -->
                        <div class="form-group mb-4">
                            <label for="num_rooms">Number of Rooms</label>
                            <select class="form-control @error('num_rooms') is-invalid @enderror" 
                                    id="num_rooms" name="num_rooms" required>
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ old('num_rooms') == $i ? 'selected' : '' }}>
                                        {{ $i }} {{ $i === 1 ? 'Room' : 'Rooms' }}
                                    </option>
                                @endfor
                            </select>
                            @error('num_rooms')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Price Summary -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h5>Price Summary</h5>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Room Price (x<span id="nights">1</span> nights)</span>
                                    <span id="roomTotal">$0.00</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Number of Rooms</span>
                                    <span id="numRooms">1</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Total Amount</span>
                                    <span id="totalAmount">$0.00</span>
                                </div>
                            </div>
                        </div>

                        <!-- Special Requests -->
                        <div class="form-group mb-4">
                            <label for="special_requests">Special Requests (Optional)</label>
                            <textarea class="form-control" id="special_requests" name="special_requests" 
                                      rows="3" placeholder="Any special requirements or requests?">{{ old('special_requests') }}</textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-credit-card me-2"></i> Book Now
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-check-input:checked + .form-check-label {
        background-color: #f8f9fa;
    }
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15);
    }
    .form-check-input:checked + .form-check-label .card {
        border-color: #0d6efd;
        background-color: #f8f9fa;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkInInput = document.getElementById('check_in');
        const checkOutInput = document.getElementById('check_out');
        const guestsSelect = document.getElementById('guests');
        const numRoomsSelect = document.getElementById('num_rooms');
        const roomTypeRadios = document.querySelectorAll('.room-type-radio');
        
        // Set minimum date to tomorrow
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);
        
        checkInInput.min = tomorrow.toISOString().split('T')[0];
        
        // Set checkout date to 1 day after check-in
        checkInInput.addEventListener('change', function() {
            if (this.value) {
                const checkInDate = new Date(this.value);
                const nextDay = new Date(checkInDate);
                nextDay.setDate(checkInDate.getDate() + 1);
                checkOutInput.min = nextDay.toISOString().split('T')[0];
                
                // If check-out is before new min date, update it
                if (checkOutInput.value && new Date(checkOutInput.value) < nextDay) {
                    checkOutInput.value = nextDay.toISOString().split('T')[0];
                }
                
                calculateTotal();
            }
        });
        
        checkOutInput.addEventListener('change', calculateTotal);
        numRoomsSelect.addEventListener('change', calculateTotal);
        
        // Add event listeners to all room type radio buttons
        roomTypeRadios.forEach(radio => {
            radio.addEventListener('change', calculateTotal);
        });
        
        // Calculate total price
        function calculateTotal() {
            const checkIn = new Date(checkInInput.value);
            const checkOut = new Date(checkOutInput.value);
            
            if (checkIn && checkOut && checkOut > checkIn) {
                const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
                document.getElementById('nights').textContent = nights;
                
                const selectedRoomType = document.querySelector('.room-type-radio:checked');
                if (selectedRoomType) {
                    const pricePerNight = parseFloat(selectedRoomType.dataset.price);
                    const numRooms = parseInt(numRoomsSelect.value);
                    const total = pricePerNight * nights * numRooms;
                    
                    document.getElementById('roomTotal').textContent = `$${(pricePerNight * nights).toFixed(2)}`;
                    document.getElementById('numRooms').textContent = numRooms;
                    document.getElementById('totalAmount').textContent = `$${total.toFixed(2)}`;
                }
            } else {
                document.getElementById('nights').textContent = '0';
                document.getElementById('roomTotal').textContent = '$0.00';
                document.getElementById('totalAmount').textContent = '$0.00';
            }
        }
        
        // Initial calculation
        calculateTotal();
    });
</script>
@endpush
