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
            <form action="{{ $hotel ? route('hotels.bookings.store', $hotel->hotel_id) : route('bookings.store') }}" method="POST" id="bookingForm" onsubmit="return validateForm()">
                @csrf
                
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
                    <label for="hotel_id" class="block text-sm font-medium text-gray-700 mb-2">Select Hotel</label>
                    <select id="hotel_id" name="hotel_id" required 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">-- Select a Hotel --</option>
                        @foreach($hotels as $hotel)
                            <option value="{{ $hotel->hotel_id }}" data-hotel-id="{{ $hotel->hotel_id }}">
                                {{ $hotel->name }} - {{ $hotel->address }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Room Type Selection -->
                <div class="mb-6" id="roomTypeSection" style="display: none;">
                    <label for="room_type_id" class="block text-sm font-medium text-gray-700 mb-2">Select Room Type</label>
                    <div id="roomTypeOptions">
                        <!-- Room types will be loaded here by JavaScript -->
                    </div>
                    <div id="noRoomsMessage" class="text-red-500 text-sm mt-2" style="display: none;">
                        No available rooms for the selected hotel.
                    </div>
                </div>

                <!-- Booking Details Section -->
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Booking Dates</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="check_in" class="block text-sm font-medium text-gray-700">Check-in Date</label>
                            <input type="date" name="check_in" id="check_in_date" required
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   min="{{ now()->format('Y-m-d') }}">
                        </div>
                        <div>
                            <label for="check_out" class="block text-sm font-medium text-gray-700">Check-out Date</label>
                            <input type="date" name="check_out" id="check_out_date" required
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   min="{{ now()->addDay()->format('Y-m-d') }}">
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

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('bookingForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                console.log('Form submitted');
                if (!validateForm()) {
                    e.preventDefault();
                    return false;
                }
                return true;
            });
        }

        const hotelSelect = document.getElementById('hotel_id');
        const roomTypeSection = document.getElementById('roomTypeSection');
        const roomTypeOptions = document.getElementById('roomTypeOptions');
        const noRoomsMessage = document.getElementById('noRoomsMessage');
        const checkInInput = document.getElementById('check_in_date');
        const checkOutInput = document.getElementById('check_out_date');
        const nightCount = document.getElementById('total-nights');
        const totalAmount = document.getElementById('total-amount');

        // Set minimum check-in date to today
        const today = new Date().toISOString().split('T')[0];
        checkInInput.min = today;

        // Update check-out date minimum when check-in date changes
        checkInInput.addEventListener('change', function() {
            if (this.value) {
                const nextDay = new Date(this.value);
                nextDay.setDate(nextDay.getDate() + 1);
                checkOutInput.min = nextDay.toISOString().split('T')[0];
                
                if (!checkOutInput.value || new Date(checkOutInput.value) <= new Date(this.value)) {
                    checkOutInput.value = nextDay.toISOString().split('T')[0];
                }
                
                updateBookingSummary();
            }
        });

        // Update booking summary when check-out date changes
        checkOutInput.addEventListener('change', function() {
            if (checkInInput.value && this.value) {
                updateBookingSummary();
            }
        });

        // Function to update booking summary
        function updateBookingSummary() {
            if (checkInInput.value && checkOutInput.value) {
                const start = new Date(checkInInput.value);
                const end = new Date(checkOutInput.value);
                const nights = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
                nightCount.textContent = nights;
                
                // Update total amount if a room is selected
                const selectedRoom = document.querySelector('input[name="room_type_id"]:checked');
                if (selectedRoom) {
                    const pricePerNight = parseFloat(selectedRoom.dataset.price);
                    const total = (pricePerNight * nights).toFixed(2);
                    totalAmount.textContent = total;
                }
            }
        }

        // Handle hotel selection change
        if (hotelSelect) {
            hotelSelect.addEventListener('change', function() {
                const hotelId = this.value;
                
                if (!hotelId) {
                    roomTypeSection.style.display = 'none';
                    return;
                }
                
                // Show loading state
                roomTypeOptions.innerHTML = '<div class="p-4 text-center"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto"></div><p class="mt-2 text-sm text-gray-600">Loading rooms...</p></div>';
                roomTypeSection.style.display = 'block';
                noRoomsMessage.style.display = 'none';
                
                // Fetch available rooms for the selected hotel
                fetch(`/api/hotels/${hotelId}/available-rooms`)
                    .then(async response => {
                        const data = await response.json();
                        
                        if (!response.ok) {
                            // Handle HTTP errors (4xx, 5xx)
                            const error = (data && data.message) || `HTTP error! status: ${response.status}`;
                            throw new Error(error);
                        }
                        
                        // Check if the response is an array (success) or has an error
                        if (Array.isArray(data)) {
                            return data; // Success case - return the rooms array
                        } else if (data && data.status === 'error') {
                            // Handle application-level errors
                            throw new Error(data.message || 'Failed to load rooms');
                        } else {
                            throw new Error('Invalid response format from server');
                        }
                    })
                    .then(rooms => {
                        // Clear previous options and hide error message
                        roomTypeOptions.innerHTML = '';
                        noRoomsMessage.style.display = 'none';

                        if (rooms.length === 0) {
                            noRoomsMessage.style.display = 'block';
                            return;
                        }
                        
                        // Add room type options
                        rooms.forEach(room => {
                            const roomDiv = document.createElement('div');
                            roomDiv.className = 'flex items-center p-4 border rounded-lg mb-3 hover:bg-gray-50';
                            
                            // Format amenities for display
                            const amenities = Array.isArray(room.amenities) 
                                ? room.amenities.join(', ')
                                : (room.amenities || 'No amenities');
                            
                            roomDiv.innerHTML = `
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">${room.name || 'Unnamed Room'}</h4>
                                    <p class="text-sm text-gray-500">${room.description || 'No description available'}</p>
                                    <div class="mt-2">
                                        <span class="text-sm text-gray-600">Max occupancy: ${room.max_occupancy || 2} guests</span>
                                        <span class="mx-2 text-gray-400">•</span>
                                        <span class="text-sm text-gray-600">Available: ${room.available_rooms || 0} room(s)</span>
                                    </div>
                                    <div class="mt-1">
                                        <span class="text-sm text-gray-600">Amenities: ${amenities}</span>
                                    </div>
                                </div>
                                <div class="ml-4 text-right">
                                    <div class="text-lg font-bold text-blue-600">$${parseFloat(room.price || 0).toFixed(2)}</div>
                                    <div class="text-sm text-gray-500">per night</div>
                                    <div class="mt-2 flex items-center justify-end">
                                        <input type="radio" 
                                               name="room_type_id" 
                                               value="${room.id}" 
                                               data-price="${room.price || 0}" 
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500"
                                               onchange="window.updateTotalAmount(${room.price || 0}, ${room.id})">
                                        <label class="ml-1 text-sm text-gray-700">Select</label>
                                    </div>
                                </div>
                            `;
                            roomTypeOptions.appendChild(roomDiv);
                        });
                        
                        roomTypeSection.style.display = 'block';
                    })
                    .catch(error => {
                        console.error('Error fetching rooms:', error);
                        roomTypeOptions.innerHTML = `
                            <div class="bg-red-50 border-l-4 border-red-400 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-red-700">${error.message || 'Error loading room types. Please try again later.'}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
            });
        }

        // Make the function available globally
        window.updateTotalAmount = function(pricePerNight, roomId) {
            const checkInDate = document.getElementById('check_in_date')?.value;
            const checkOutDate = document.getElementById('check_out_date')?.value;
            
            // If dates are not selected yet, show base price only
            if (!checkInDate || !checkOutDate) {
                document.getElementById('total-amount').textContent = parseFloat(pricePerNight || 0).toFixed(2);
                document.getElementById('total-nights').textContent = '1';
                document.getElementById('price-per-night').style.display = 'block';
                document.getElementById('price-per-night').textContent = `Price per night: $${parseFloat(pricePerNight || 0).toFixed(2)}`;
                document.getElementById('cancellation-policy').style.display = 'block';
                return;
            }
            
            // Calculate number of nights
            const oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
            const startDate = new Date(checkInDate);
            const endDate = new Date(checkOutDate);
            const nights = Math.round(Math.abs((endDate - startDate) / oneDay)) || 1;
            
            // Calculate total amount
            const totalAmount = (pricePerNight * nights).toFixed(2);
            
            // Update the UI
            document.getElementById('total-amount').textContent = totalAmount;
            document.getElementById('total-nights').textContent = nights;
            document.getElementById('price-per-night').style.display = 'block';
            document.getElementById('price-per-night').textContent = `Price per night: $${parseFloat(pricePerNight || 0).toFixed(2)}`;
            document.getElementById('cancellation-policy').style.display = 'block';
            
            // Store the selected room ID in a hidden input if needed
            if (roomId) {
                let roomTypeInput = document.getElementById('room_type_id');
                if (!roomTypeInput) {
                    roomTypeInput = document.createElement('input');
                    roomTypeInput.type = 'hidden';
                    roomTypeInput.name = 'room_type_id';
                    roomTypeInput.id = 'room_type_id';
                    document.querySelector('form').appendChild(roomTypeInput);
                }
                roomTypeInput.value = roomId;
            }
        };

        // Add event listeners for date changes
        document.addEventListener('change', function(e) {
            if (e.target.matches('#check_in_date, #check_out_date')) {
                const selectedRoom = document.querySelector('input[name="room_type_id"]:checked');
                if (selectedRoom) {
                    window.updateTotalAmount(
                        parseFloat(selectedRoom.dataset.price || 0),
                        selectedRoom.value
                    );
                }
            }
        });

        // Handle children age fields
        const childrenSelect = document.querySelector('select[name="children"]');
        const childrenAgesContainer = document.getElementById('children-ages');
        
        if (childrenSelect && childrenAgesContainer) {
            childrenSelect.addEventListener('change', function() {
                const numChildren = parseInt(this.value);
                
                if (numChildren > 0) {
                    childrenAgesContainer.innerHTML = '';
                    childrenAgesContainer.classList.remove('hidden');
                    
                    for (let i = 1; i <= numChildren; i++) {
                        const div = document.createElement('div');
                        div.className = 'mt-2';
                        div.innerHTML = `
                            <label class="block text-sm font-medium text-gray-700">Child ${i} Age</label>
                            <select name="children_ages[]" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="0">Under 1</option>
                                ${Array.from({length: 17}, (_, i) => 
                                    `<option value="${i + 1}">${i + 1} years</option>`
                                ).join('')}
                            </select>
                        `;
                        childrenAgesContainer.appendChild(div);
                    }
                } else {
                    childrenAgesContainer.innerHTML = '';
                    childrenAgesContainer.classList.add('hidden');
                }
            });
        }

        // Handle payment method change
        const paymentMethod = document.getElementById('payment_method');
        const creditCardFields = document.getElementById('credit-card-fields');
        
        if (paymentMethod && creditCardFields) {
            paymentMethod.addEventListener('change', function() {
                if (this.value === 'credit_card') {
                    creditCardFields.classList.remove('hidden');
                } else {
                    creditCardFields.classList.add('hidden');
                }
            });
            
            // Trigger change event to set initial state
            paymentMethod.dispatchEvent(new Event('change'));
        }
    });
</script>
@endpush

@endsection