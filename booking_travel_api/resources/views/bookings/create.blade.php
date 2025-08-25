@extends('layouts.dashboard')

@section('title', 'Create New Booking')
@section('page-title', 'Create New Booking')
@section('page-subtitle', 'Add a new booking to the system')

@section('content')
<div class="min-h-screen">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    <div class="ml-72 p-8">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <form action="{{ route('bookings.store') }}" method="POST">
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
                            <option value="{{ $hotel->hotel_id }}">{{ $hotel->name }} - {{ $hotel->address }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Room Type Selection (will be populated by JavaScript) -->
                <div class="mb-6" id="roomTypeSection" style="display: none;">
                    <label for="room_type_id" class="block text-sm font-medium text-gray-700 mb-2">Select Room Type</label>
                    <div id="roomTypeOptions">
                        <!-- Room types will be loaded here -->
                    </div>
                    <div id="noRoomsMessage" class="text-red-500 text-sm mt-2" style="display: none;">
                        No available rooms for the selected hotel.
                    </div>
                </div>

                <!-- Booking Details Section -->
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Booking Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="check_in" class="block text-sm font-medium text-gray-700">Check-in Date</label>
                            <input type="date" name="check_in" id="check_in" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="check_out" class="block text-sm font-medium text-gray-700">Check-out Date</label>
                            <input type="date" name="check_out" id="check_out" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
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
                            <p class="text-sm text-gray-500">Total for <span id="night-count">0</span> nights</p>
                            <p class="text-2xl font-bold text-gray-900">$<span id="total-amount">0.00</span></p>
                            <p class="text-sm text-green-600">Free cancellation until 24 hours before check-in</p>
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
    document.addEventListener('DOMContentLoaded', function() {
        const hotelSelect = document.getElementById('hotel_id');
        const roomTypeSection = document.getElementById('roomTypeSection');
        const roomTypeOptions = document.getElementById('roomTypeOptions');
        const noRoomsMessage = document.getElementById('noRoomsMessage');
        const checkInInput = document.getElementById('check_in');
        const checkOutInput = document.getElementById('check_out');
        const nightCount = document.getElementById('night-count');
        const totalAmount = document.getElementById('total-amount');
        let selectedRoomPrice = 0;

        // Format currency
        const formatCurrency = (amount) => {
            return parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        };

        // Calculate nights between two dates
        const calculateNights = (checkIn, checkOut) => {
            if (!checkIn || !checkOut) return 0;
            const oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
            const checkInDate = new Date(checkIn);
            const checkOutDate = new Date(checkOut);
            return Math.round(Math.abs((checkOutDate - checkInDate) / oneDay));
        };

        // Update the booking summary
        const updateBookingSummary = () => {
            const nights = calculateNights(checkInInput.value, checkOutInput.value);
            const total = nights * selectedRoomPrice;
            
            nightCount.textContent = nights;
            totalAmount.textContent = formatCurrency(total);
        };

        // Handle room type selection
        const handleRoomTypeSelection = (roomPrice) => {
            selectedRoomPrice = parseFloat(roomPrice) || 0;
            updateBookingSummary();
        };

        // Initialize date inputs
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);
        
        // Format dates as YYYY-MM-DD
        const formatDate = (date) => date.toISOString().split('T')[0];
        
        // Set default date values
        if (!checkInInput.value) checkInInput.value = formatDate(today);
        if (!checkOutInput.value) checkOutInput.value = formatDate(tomorrow);
        
        // Set minimum dates
        checkInInput.min = formatDate(today);
        checkOutInput.min = formatDate(tomorrow);

        // Add event listeners for date changes
        [checkInInput, checkOutInput].forEach(input => {
            input.addEventListener('change', function() {
                // Basic date validation
                if (checkInInput.value && checkOutInput.value) {
                    const checkIn = new Date(checkInInput.value);
                    const checkOut = new Date(checkOutInput.value);
                    
                    if (checkOut <= checkIn) {
                        // If check-out is before or same as check-in, set it to next day
                        const nextDay = new Date(checkIn);
                        nextDay.setDate(checkIn.getDate() + 1);
                        checkOutInput.value = formatDate(nextDay);
                    }
                    
                    // Update room availability when dates change
                    if (hotelSelect.value) {
                        hotelSelect.dispatchEvent(new Event('change'));
                    }
                    
                    // Update booking summary
                    updateBookingSummary();
                }
            });
        });

        if (hotelSelect) {
            hotelSelect.addEventListener('change', function() {
                const hotelId = this.value;
                
                if (!hotelId) {
                    roomTypeSection.style.display = 'none';
                    return;
                }

                // Show loading state
                roomTypeOptions.innerHTML = `
                    <div class="flex items-center justify-center p-4">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                        <span class="ml-2 text-gray-600">Loading room types...</span>
                    </div>`;
                roomTypeSection.style.display = 'block';
                noRoomsMessage.style.display = 'none';

                // Fetch available rooms for the selected hotel
                fetch(`/hotels/${hotelId}/available-rooms`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.status === 'success' && data.data && data.data.length > 0) {
                            roomTypeOptions.innerHTML = '';
                            
                            data.data.forEach(room => {
                                const roomCard = document.createElement('div');
                                roomCard.className = 'room-type-option mb-4 p-4 border rounded-lg cursor-pointer hover:border-blue-300';
                                roomCard.dataset.price = room.price || 0;
                                roomCard.innerHTML = `
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-medium text-gray-900">${room.name}</h4>
                                            <p class="text-sm text-gray-500">${room.description || 'Comfortable room with all amenities'}</p>
                                            <div class="mt-2">
                                                <span class="text-sm font-medium text-gray-900">
                                                    $${parseFloat(room.price || 0).toFixed(2)}
                                                </span>
                                                <span class="text-xs text-gray-500"> / night</span>
                                            </div>
                                            <div class="mt-1 text-xs text-green-600">
                                                ${room.available_rooms || 0} ${(room.available_rooms === 1 ? 'room' : 'rooms')} available
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0 ml-4">
                                            <input type="radio" name="room_type_id" value="${room.id}" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        </div>
                                    </div>
                                `;
                                
                                roomCard.addEventListener('click', function() {
                                    // Update radio button
                                    const radio = this.querySelector('input[type="radio"]');
                                    radio.checked = true;
                                    // Handle selection with the room price
                                    handleRoomTypeSelection(this.dataset.price);
                                });
                                
                                roomTypeOptions.appendChild(roomCard);
                            });
                            
                            // Select first room by default if none selected
                            const firstRoom = roomTypeOptions.querySelector('.room-type-option');
                            if (firstRoom) {
                                firstRoom.click();
                            }
                            
                            roomTypeSection.style.display = 'block';
                            noRoomsMessage.style.display = 'none';
                        } else {
                            roomTypeOptions.innerHTML = '';
                            noRoomsMessage.style.display = 'block';
                            noRoomsMessage.textContent = data.message || 'No available rooms for the selected dates.';
                            
                            // Reset booking summary
                            selectedRoomPrice = 0;
                            updateBookingSummary();
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching rooms:', error);
                        roomTypeOptions.innerHTML = `
                            <div class="bg-red-50 border-l-4 border-red-400 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-red-700">Error loading room types. Please try again later.</p>
                                    </div>
                                </div>
                            </div>`;
                        
                        // Reset booking summary
                        selectedRoomPrice = 0;
                        updateBookingSummary();
                    });
            });
        }

        // Initialize booking summary
        updateBookingSummary();
    });
</script>
@endpush

@endsection