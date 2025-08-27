@extends('layouts.dashboard')

@section('title', 'Create New Booking')
@section('page-title', 'Create New Booking')
@section('page-subtitle', 'Add a new booking to the system')
@php
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
@endphp
@section('content')
<div class="min-h-screen">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    <div class="ml-72 p-8">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                @foreach ($errors->all() as $error)
                                    {{ $error }}<br>
                                @endforeach
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <form id="bookingForm" action="{{ route('bookings.store') }}" method="POST" onsubmit="return validateForm()">
                @csrf
                
                <!-- Hidden field for hotel_metadata_id -->
                <input type="hidden" name="hotel_metadata_id" id="hotel_metadata_id" value="{{ $selectedHotelId ?? '' }}">
                
                <!-- Guest Information Section -->
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Guest Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" name="first_name" id="first_name" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" name="last_name" id="last_name" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <input type="tel" name="phone" id="phone" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="md:col-span-2">
                            <label for="nationality" class="block text-sm font-medium text-gray-700">Nationality</label>
                            <input type="text" name="nationality" id="nationality" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Hotel Selection -->
                <div class="mb-6">
                    <label for="hotel_metadata_id_select" class="block text-sm font-medium text-gray-700 mb-2">Select Hotel</label>
                    <select id="hotel_metadata_id_select" name="hotel_metadata_id" required 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            onchange="updateRoomTypes(this.value)">
                        <option value="">-- Select a Hotel --</option>
                        @foreach($hotels as $hotel)
                            <option value="{{ $hotel->hotel_id }}" {{ ($selectedHotelId ?? '') == $hotel->hotel_id ? 'selected' : '' }}>
                                {{ $hotel->name }} - {{ $hotel->address }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Room Type Selection -->
                <div class="mb-6" id="roomTypeSection" style="display: {{ $selectedHotelId ? 'block' : 'none' }}">
                    <label for="room_type_id" class="block text-sm font-medium text-gray-700 mb-2">Select Room Type</label>
                    <select name="room_type_id" id="room_type_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">-- Select a Room Type --</option>
                        @if(isset($roomTypes) && $roomTypes->count() > 0)
                            @foreach($roomTypes as $roomType)
                                <option value="{{ $roomType->id }}" data-price="{{ $roomType->price }}">
                                    {{ $roomType->name }} - ${{ number_format($roomType->price, 2) }} per night
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Booking Details Section -->
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Booking Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="check_in" class="block text-sm font-medium text-gray-700">Check-in Date</label>
                            <input type="date" name="check_in" id="check_in_date" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                min="{{ date('Y-m-d') }}">
                        </div>
                        <div>
                            <label for="check_out" class="block text-sm font-medium text-gray-700">Check-out Date</label>
                            <input type="date" name="check_out" id="check_out_date" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                        </div>
                    </div>
                </div>

                <!-- Guest Details Section -->
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Guest Details</h3>
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Adults</label>
                                <select name="adults" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}" {{ $i == 2 ? 'selected' : '' }}>{{ $i }} {{ Str::plural('adult', $i) }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Children</label>
                                <select name="children" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    @for($i = 0; $i <= 5; $i++)
                                        <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? 'child' : 'children' }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div id="children-ages" class="hidden">
                            <!-- Children ages will be populated by JavaScript -->
                        </div>
                    </div>
                </div>

                <!-- Special Requests -->
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Special Requests</h3>
                    <div>
                        <label for="special_requests" class="block text-sm font-medium text-gray-700">Any special requests or additional information?</label>
                        <textarea name="special_requests" id="special_requests" rows="3"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                        <p class="mt-2 text-sm text-gray-500">We'll do our best to accommodate your requests.</p>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                            <select name="payment_method" id="payment_method" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="credit_card">Credit Card</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="paypal">PayPal</option>
                                <option value="pay_at_hotel">Pay at Hotel</option>
                            </select>
                        </div>
                        <div id="credit-card-fields" class="hidden">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="card_number" class="block text-sm font-medium text-gray-700">Card Number</label>
                                    <input type="text" name="card_number" id="card_number"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="1234 5678 9012 3456">
                                </div>
                                <div>
                                    <label for="card_expiry" class="block text-sm font-medium text-gray-700">Expiry Date</label>
                                    <input type="text" name="card_expiry" id="card_expiry"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="MM/YY">
                                </div>
                                <div>
                                    <label for="card_cvv" class="block text-sm font-medium text-gray-700">CVV</label>
                                    <input type="text" name="card_cvv" id="card_cvv"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="123">
                                </div>
                                <div>
                                    <label for="card_name" class="block text-sm font-medium text-gray-700">Name on Card</label>
                                    <input type="text" name="card_name" id="card_name"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="John Doe">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary and Submit -->
                <div class="p-6 bg-gray-50">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-500">Total for <span id="total-nights">0</span> nights</p>
                            <p class="text-2xl font-bold text-gray-900">$<span id="total-amount">0.00</span></p>
                            <p class="text-sm text-green-600">Free cancellation until 24 hours before check-in</p>
                            <p class="text-sm text-gray-500" id="price-per-night" style="display: none;">Price per night: $0.00</p>
                            <p class="text-sm text-gray-500" id="cancellation-policy" style="display: none;">Cancellation policy: Free cancellation until 24 hours before check-in</p>
                        </div>
                        <div class="space-x-3">
                            <a href="{{ route('bookings.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Confirm Booking
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function validateForm() {
        // Basic validation - check required fields
        const requiredFields = ['first_name', 'last_name', 'email', 'phone', 'nationality', 
                              'check_in_date', 'check_out_date', 'adults', 'room_type_id', 'payment_method'];
        
        for (const field of requiredFields) {
            const element = document.getElementById(field);
            if (element && !element.value.trim()) {
                alert(`Please fill in the ${field.replace('_', ' ')} field`);
                element.focus();
                return false;
            }
        }

        // Validate email format
        const email = document.getElementById('email').value;
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            alert('Please enter a valid email address');
            return false;
        }

        // Validate check-in date is before check-out date
        const checkIn = new Date(document.getElementById('check_in_date').value);
        const checkOut = new Date(document.getElementById('check_out_date').value);
        if (checkIn >= checkOut) {
            alert('Check-out date must be after check-in date');
            return false;
        }

        // Show loading state
        const submitBtn = document.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Processing...';
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
        }

        return true;
    }

    function updateRoomTypes(hotelId) {
        const roomTypeSection = document.getElementById('roomTypeSection');
        const roomTypeSelect = document.getElementById('room_type_id');
        const hotelMetadataIdInput = document.getElementById('hotel_metadata_id');
        
        // Update the hidden input
        hotelMetadataIdInput.value = hotelId;
        
        if (!hotelId) {
            roomTypeSection.style.display = 'none';
            return;
        }
        
        // Show loading state
        roomTypeSection.style.display = 'block';
        roomTypeSelect.innerHTML = '<option value="">Loading room types...</option>';
        
        // Fetch room types for the selected hotel
        fetch(`/api/hotels/${hotelId}/room-types`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    let options = '<option value="">-- Select a Room Type --</option>';
                    data.forEach(room => {
                        options += `<option value="${room.id}" data-price="${room.price}">
                            ${room.name} - $${parseFloat(room.price).toFixed(2)} per night
                        </option>`;
                    });
                    roomTypeSelect.innerHTML = options;
                } else {
                    roomTypeSelect.innerHTML = '<option value="">No rooms available for this hotel</option>';
                }
            })
            .catch(error => {
                console.error('Error fetching room types:', error);
                roomTypeSelect.innerHTML = '<option value="">Error loading room types</option>';
            });
    }
    
    // Initialize form validation and event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Update total amount when dates or room type changes
        const form = document.getElementById('bookingForm');
        const checkInInput = document.getElementById('check_in_date');
        const checkOutInput = document.getElementById('check_out_date');
        const roomTypeSelect = document.getElementById('room_type_id');
        
        function updateTotalAmount() {
            const checkIn = new Date(checkInInput.value);
            const checkOut = new Date(checkOutInput.value);
            const roomOption = roomTypeSelect.options[roomTypeSelect.selectedIndex];
            
            if (checkIn && checkOut && roomOption && roomOption.value) {
                const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
                const pricePerNight = parseFloat(roomOption.dataset.price);
                const total = nights * pricePerNight;
                
                document.getElementById('total-nights').textContent = nights;
                document.getElementById('total-amount').textContent = total.toFixed(2);
            }
        }
        
        // Add event listeners
        checkInInput?.addEventListener('change', updateTotalAmount);
        checkOutInput?.addEventListener('change', updateTotalAmount);
        roomTypeSelect?.addEventListener('change', updateTotalAmount);
        
        // Initialize total amount if we have all required values
        if (checkInInput?.value && checkOutInput?.value && roomTypeSelect?.value) {
            updateTotalAmount();
        }
    });
</script>
@endpush

@endsection