@extends('layouts.app')

@section('content')
<div class="container py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Header -->
            <div class="bg-blue-600 text-white px-6 py-4">
                <h2 class="text-2xl font-bold">Complete Your Booking</h2>
                <p class="text-blue-100">Booking Reference: {{ $booking->booking_reference }}</p>
            </div>
            
            <!-- Progress Steps -->
            <div class="px-6 py-4 border-b">
                <div class="flex justify-between items-center">
                    <div class="flex-1 text-center">
                        <div class="w-10 h-10 mx-auto rounded-full bg-green-500 text-white flex items-center justify-center">
                            <i class="fas fa-check"></i>
                        </div>
                        <p class="mt-2 text-sm text-gray-600">Booking Details</p>
                    </div>
                    <div class="flex-1">
                        <div class="h-1 bg-green-500"></div>
                    </div>
                    <div class="flex-1 text-center">
                        <div class="w-10 h-10 mx-auto rounded-full bg-blue-500 text-white flex items-center justify-center font-bold">2</div>
                        <p class="mt-2 text-sm font-medium text-gray-800">Payment</p>
                    </div>
                    <div class="flex-1">
                        <div class="h-1 bg-gray-200"></div>
                    </div>
                    <div class="flex-1 text-center">
                        <div class="w-10 h-10 mx-auto rounded-full bg-gray-200 text-gray-600 flex items-center justify-center">3</div>
                        <p class="mt-2 text-sm text-gray-600">Confirmation</p>
                    </div>
                </div>
            </div>
            
            <!-- Payment Methods -->
            <div class="p-6">
                <h3 class="text-xl font-semibold mb-6">Select Payment Method</h3>
                
                <form action="{{ route('payments.process', $booking->id) }}" method="POST" id="paymentForm">
                    @csrf
                    
                    <!-- Payment Method Selection -->
                    <div class="space-y-4 mb-8">
                        <!-- Credit/Debit Card -->
                        <div class="border rounded-lg overflow-hidden">
                            <input type="radio" name="payment_method" id="card_payment" value="card" class="hidden peer" checked>
                            <label for="card_payment" class="block p-4 cursor-pointer hover:bg-gray-50 peer-checked:bg-blue-50 peer-checked:border-blue-500 peer-checked:border-l-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-6 h-6 rounded-full border-2 border-blue-500 flex items-center justify-center mr-3">
                                            <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                                        </div>
                                    </div>
                                    <div class="ml-2">
                                        <h4 class="font-medium text-gray-900">Credit/Debit Card</h4>
                                        <div class="flex space-x-2 mt-1">
                                            <img src="{{ asset('images/visa.png') }}" alt="Visa" class="h-6">
                                            <img src="{{ asset('images/mastercard.png') }}" alt="Mastercard" class="h-6">
                                            <img src="{{ asset('images/amex.png') }}" alt="American Express" class="h-6">
                                        </div>
                                    </div>
                                </div>
                            </label>
                            
                            <!-- Card Details (shown when credit card is selected) -->
                            <div id="cardDetails" class="p-4 pt-0 border-t border-gray-200">
                                <div class="space-y-4">
                                    <div>
                                        <label for="card_number" class="block text-sm font-medium text-gray-700 mb-1">Card Number</label>
                                        <div class="relative">
                                            <input type="text" id="card_number" name="card_details[card_number]" 
                                                   class="input-field w-full pl-10" 
                                                   placeholder="1234 5678 9012 3456"
                                                   maxlength="19"
                                                   oninput="formatCardNumber(this)">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="far fa-credit-card text-gray-400"></i>
                                            </div>
                                        </div>
                                        <div id="card-type" class="mt-1 text-sm text-gray-500"></div>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="expiry_date" class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
                                            <input type="text" id="expiry_date" name="card_details[expiry_date]" 
                                                   class="input-field w-full" 
                                                   placeholder="MM/YY"
                                                   maxlength="5"
                                                   oninput="formatExpiryDate(this)">
                                        </div>
                                        <div>
                                            <label for="cvv" class="block text-sm font-medium text-gray-700 mb-1">CVV</label>
                                            <div class="relative">
                                                <input type="password" id="cvv" name="card_details[cvv]" 
                                                       class="input-field w-full pr-10" 
                                                       placeholder="123"
                                                       maxlength="4">
                                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                                    <i class="far fa-question-circle text-gray-400 hover:text-gray-500 cursor-help" 
                                                       title="3 or 4 digit code on the back of your card"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label for="card_holder" class="block text-sm font-medium text-gray-700 mb-1">Cardholder Name</label>
                                        <input type="text" id="card_holder" name="card_details[card_holder]" 
                                               class="input-field w-full" 
                                               placeholder="Name on card">
                                    </div>
                                    
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-lock text-green-500 mr-2"></i>
                                        <span>Your payment is secured with SSL encryption</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pay at Hotel -->
                        <div class="border rounded-lg overflow-hidden">
                            <input type="radio" name="payment_method" id="pay_at_hotel" value="hotel" class="hidden peer">
                            <label for="pay_at_hotel" class="block p-4 cursor-pointer hover:bg-gray-50 peer-checked:bg-blue-50 peer-checked:border-blue-500 peer-checked:border-l-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-6 h-6 rounded-full border-2 border-gray-300 flex items-center justify-center mr-3 peer-checked:border-blue-500">
                                            <div class="w-3 h-3 rounded-full bg-white peer-checked:bg-blue-500"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Pay at Hotel</h4>
                                        <p class="text-sm text-gray-500">Pay when you arrive at the hotel</p>
                                    </div>
                                </div>
                            </label>
                            
                            <!-- Pay at Hotel Details -->
                            <div id="payAtHotelDetails" class="p-4 pt-0 border-t border-gray-200 hidden">
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <h5 class="font-medium text-blue-800 mb-2">How it works:</h5>
                                    <ul class="list-disc list-inside text-sm text-blue-700 space-y-1">
                                        <li>Your booking will be confirmed immediately</li>
                                        <li>No payment required now</li>
                                        <li>Pay directly at the hotel reception when you check in</li>
                                        <li>Pay in USD or KHR (Cambodian Riel)</li>
                                    </ul>
                                    <div class="mt-3 p-3 bg-white rounded border border-blue-100">
                                        <p class="text-sm text-gray-600">
                                            <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                                            Some hotels may require a credit card guarantee at the time of booking.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Order Summary -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h4 class="font-medium text-lg mb-4">Order Summary</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal</span>
                                <span>${{ number_format($booking->total_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Taxes & Fees</span>
                                <span>Included</span>
                            </div>
                            <div class="border-t border-gray-200 my-2"></div>
                            <div class="flex justify-between font-semibold text-lg">
                                <span>Total</span>
                                <span class="text-blue-600">${{ number_format($booking->total_amount, 2) }}</span>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-200">
                                Complete Booking
                            </button>
                            <p class="text-xs text-gray-500 mt-2 text-center">
                                By completing this booking, you agree to our 
                                <a href="#" class="text-blue-600 hover:underline">Terms of Service</a> and 
                                <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a>.
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .input-field {
        @apply w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500;
    }
    
    input[type="radio"]:checked + label {
        @apply bg-blue-50 border-l-4 border-blue-500;
    }
    
    /* Hide the default radio button */
    input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }
</style>
@endpush

@push('scripts')
<script>
    // Toggle payment method details
    document.addEventListener('DOMContentLoaded', function() {
        const cardPayment = document.getElementById('card_payment');
        const payAtHotel = document.getElementById('pay_at_hotel');
        const cardDetails = document.getElementById('cardDetails');
        const payAtHotelDetails = document.getElementById('payAtHotelDetails');
        
        function togglePaymentDetails() {
            if (cardPayment.checked) {
                cardDetails.classList.remove('hidden');
                payAtHotelDetails.classList.add('hidden');
            } else {
                cardDetails.classList.add('hidden');
                payAtHotelDetails.classList.remove('hidden');
            }
        }
        
        cardPayment.addEventListener('change', togglePaymentDetails);
        payAtHotel.addEventListener('change', togglePaymentDetails);
        
        // Initialize
        togglePaymentDetails();
    });
    
    // Format card number with spaces (e.g., 4242 4242 4242 4242)
    function formatCardNumber(input) {
        // Remove all non-digits
        let value = input.value.replace(/\D/g, '');
        
        // Add space after every 4 digits
        value = value.replace(/(\d{4})(?=\d)/g, '$1 ');
        
        // Update the input value
        input.value = value;
        
        // Detect card type
        detectCardType(value);
    }
    
    // Format expiry date (MM/YY)
    function formatExpiryDate(input) {
        let value = input.value.replace(/\D/g, '');
        
        if (value.length > 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        
        input.value = value;
    }
    
    // Detect card type and update UI
    function detectCardType(cardNumber) {
        const cardType = document.getElementById('card-type');
        const firstDigit = cardNumber.charAt(0);
        let type = '';
        
        // Simple card type detection
        if (/^4/.test(cardNumber)) {
            type = 'Visa';
        } else if (/^5[1-5]/.test(cardNumber)) {
            type = 'Mastercard';
        } else if (/^3[47]/.test(cardNumber)) {
            type = 'American Express';
        } else if (/^6(?:011|5)/.test(cardNumber)) {
            type = 'Discover';
        } else if (/^3(?:0[0-5]|[68][0-9])/.test(cardNumber)) {
            type = 'Diners Club';
        } else if (/^(?:2131|1800|35\d{3})/.test(cardNumber)) {
            type = 'JCB';
        }
        
        cardType.textContent = type ? `Card Type: ${type}` : '';
    }
    
    // Form validation before submission
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
        
        if (paymentMethod === 'card') {
            const cardNumber = document.getElementById('card_number').value.replace(/\s/g, '');
            const expiryDate = document.getElementById('expiry_date').value;
            const cvv = document.getElementById('cvv').value;
            const cardHolder = document.getElementById('card_holder').value;
            
            if (!cardNumber || cardNumber.length < 16) {
                e.preventDefault();
                alert('Please enter a valid card number');
                return false;
            }
            
            if (!expiryDate || !/\d{2}\/\d{2}/.test(expiryDate)) {
                e.preventDefault();
                alert('Please enter a valid expiry date (MM/YY)');
                return false;
            }
            
            if (!cvv || (cvv.length !== 3 && cvv.length !== 4)) {
                e.preventDefault();
                alert('Please enter a valid CVV');
                return false;
            }
            
            if (!cardHolder) {
                e.preventDefault();
                alert('Please enter the cardholder name');
                return false;
            }
        }
        
        // Show loading state
        const submitButton = this.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
        }
        
        return true;
    });
</script>
@endpush
@endsection
