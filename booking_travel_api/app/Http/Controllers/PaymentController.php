<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments.
     */
    public function index()
    {
        $payments = Payment::with('booking')->paginate(10);
        return response()->json(['status' => 'success', 'data' => $payments], 200);
    }

    /**
     * Store a newly created payment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,completed,failed'
        ]);

        $payment = Payment::create($validated);

        return response()->json(['status' => 'success', 'data' => $payment], 201);
    }

    /**
     * Display the specified payment.
     */
    public function show(Payment $payment)
    {
        $payment->load('booking');
        return response()->json(['status' => 'success', 'data' => $payment], 200);
    }

    /**
     * Update the specified payment.
     */
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'amount' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:pending,completed,failed'
        ]);

        $payment->update($validated);

        return response()->json(['status' => 'success', 'data' => $payment], 200);
    }

    /**
     * Remove the specified payment.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();
        return response()->json(['status' => 'success', 'message' => 'Payment deleted successfully'], 200);
    }

    /**
     * Show the payment selection page
     */
    public function showPaymentForm($bookingId)
    {
        $booking = Booking::with(['hotelBookings', 'hotelBookings.hotel'])
            ->where('id', $bookingId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('payments.choose-method', [
            'booking' => $booking,
            'totalAmount' => $booking->total_amount,
        ]);
    }

    /**
     * Process the selected payment method
     */
    public function processPayment(Request $request, $bookingId)
    {
        $request->validate([
            'payment_method' => 'required|in:card,hotel',
            'card_details' => 'required_if:payment_method,card|array',
            'card_details.card_number' => 'required_if:payment_method,card|string|size:16',
            'card_details.expiry_date' => 'required_if:payment_method,card|string|size:5',
            'card_details.cvv' => 'required_if:payment_method,card|string|size:3',
            'card_details.card_holder' => 'required_if:payment_method,card|string|max:255',
        ]);

        $booking = Booking::where('id', $bookingId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($request->payment_method === 'card') {
            return $this->processCardPayment($booking, $request->card_details);
        }

        return $this->processPayAtHotel($booking);
    }

    /**
     * Process credit card payment
     */
    private function processCardPayment($booking, $cardDetails)
    {
        try {
            // In a real application, you would integrate with a payment gateway here
            // This is a simplified example
            
            // Validate card (in a real app, this would be done by the payment gateway)
            $this->validateCard($cardDetails);
            
            // Process payment (in a real app, this would call the payment gateway API)
            $transactionId = 'PAY-' . strtoupper(Str::random(12));
            
            // Create payment record
            $payment = new Payment([
                'booking_id' => $booking->id,
                'amount' => $booking->total_amount,
                'currency' => 'USD', // Default currency for Cambodia
                'payment_method' => 'credit_card',
                'transaction_id' => $transactionId,
                'status' => 'completed',
                'payment_details' => [
                    'last4' => substr($cardDetails['card_number'], -4),
                    'card_type' => $this->detectCardType($cardDetails['card_number']),
                    'payment_gateway' => 'acleda', // Example: ACLEDA Bank is a major bank in Cambodia
                ],
            ]);
            
            $payment->save();
            
            // Update booking status
            $booking->update([
                'payment_status' => 'paid',
                'status' => 'confirmed'
            ]);
            
            return redirect()->route('bookings.show', $booking->id)
                ->with('success', 'Payment processed successfully!');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Process pay at hotel option
     */
    private function processPayAtHotel($booking)
    {
        try {
            // Create payment record for pay at hotel
            $payment = new Payment([
                'booking_id' => $booking->id,
                'amount' => $booking->total_amount,
                'currency' => 'USD',
                'payment_method' => 'pay_at_hotel',
                'status' => 'pending',
                'payment_details' => [
                    'instructions' => 'Please pay at the hotel reception upon check-in',
                    'due_date' => $booking->travel_date,
                ],
            ]);
            
            $payment->save();
            
            // Update booking status
            $booking->update([
                'payment_status' => 'pending',
                'status' => 'confirmed'
            ]);
            
            return redirect()->route('bookings.show', $booking->id)
                ->with('success', 'Booking confirmed! Please pay at the hotel upon check-in.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to process booking: ' . $e->getMessage());
        }
    }
    
    /**
     * Simple card validation (in a real app, this would be done by the payment gateway)
     */
    private function validateCard($cardDetails)
    {
        // Basic Luhn algorithm check
        $cardNumber = str_replace(' ', '', $cardDetails['card_number']);
        $sum = 0;
        $numDigits = strlen($cardNumber);
        $parity = $numDigits % 2;
        
        for ($i = 0; $i < $numDigits; $i++) {
            $digit = $cardNumber[$i];
            if ($i % 2 == $parity) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            $sum += $digit;
        }
        
        if ($sum % 10 !== 0) {
            throw new \Exception('Invalid card number');
        }
        
        // Check expiry date (format: MM/YY)
        $expiry = explode('/', $cardDetails['expiry_date']);
        if (count($expiry) !== 2 || !checkdate($expiry[0], 1, '20' . $expiry[1])) {
            throw new \Exception('Invalid expiry date');
        }
        
        // Check if card is not expired
        $expiryMonth = (int)$expiry[0];
        $expiryYear = (int)('20' . $expiry[1]);
        $currentYear = (int)date('Y');
        $currentMonth = (int)date('n');
        
        if ($expiryYear < $currentYear || ($expiryYear === $currentYear && $expiryMonth < $currentMonth)) {
            throw new \Exception('Card has expired');
        }
    }
    
    /**
     * Detect card type based on number (simplified)
     */
    private function detectCardType($cardNumber)
    {
        $firstDigit = substr($cardNumber, 0, 1);
        
        switch ($firstDigit) {
            case '4':
                return 'visa';
            case '5':
                return 'mastercard';
            case '3':
                return 'amex';
            case '6':
                return 'discover';
            default:
                return 'unknown';
        }
    }
}
