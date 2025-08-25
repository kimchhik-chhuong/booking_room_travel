<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\HotelBooking;
use App\Models\HotelMetadata;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    /**
     * Display a listing of the bookings.
     */
    public function index(Request $request)
    {
        // Get filter and sort parameters from the request
        $status = $request->input('status');
        $search = $request->input('search');
        $sort = $request->input('sort', 'latest');
        
        // Base query
        $query = Booking::with(['user', 'hotelBookings', 'hotelBookings.hotel', 'hotelBookings.roomType']);
        
        // Apply filters
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('booking_reference', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        // Apply sorting
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at');
                break;
            case 'amount_asc':
                $query->orderBy('total_amount');
                break;
            case 'amount_desc':
                $query->orderBy('total_amount', 'desc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        // Get paginated results
        $bookings = $query->paginate(10);

        // Calculate statistics
        $totalBookings = Booking::count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $totalRevenue = Booking::where('status', '!=', 'cancelled')
            ->sum('total_amount');

        // Previous month statistics
        $previousMonthStart = now()->subMonth()->startOfMonth();
        $previousMonthEnd = now()->subMonth()->endOfMonth();
        
        $previousMonthBookings = Booking::whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
            ->count();
            
        $previousMonthRevenue = Booking::where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
            ->sum('total_amount');

        // Monthly booking data for the last 6 months for chart
        $monthlyBookings = Booking::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('count(*) as total'),
                DB::raw('SUM(CASE WHEN status = "confirmed" THEN 1 ELSE 0 END) as confirmed'),
                DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending'),
                DB::raw('SUM(CASE WHEN status != "cancelled" THEN total_amount ELSE 0 END) as revenue')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('bookings.index', [
            'bookings' => $bookings,
            'totalBookings' => $totalBookings,
            'confirmedBookings' => $confirmedBookings,
            'pendingBookings' => $pendingBookings,
            'totalRevenue' => $totalRevenue,
            'previousMonthBookings' => $previousMonthBookings,
            'previousMonthRevenue' => $previousMonthRevenue ?? 0,
            'monthlyBookings' => $monthlyBookings,
            'status' => $status,
            'search' => $search,
            'sort' => $sort,
        ]);
    }

    /**
     * Get the CSS class for the booking status
     */
    private function getStatusClass($status)
    {
        switch (strtolower($status)) {
            case 'confirmed':
                return 'bg-emerald-100 text-emerald-800';
            case 'pending':
                return 'bg-yellow-100 text-yellow-800';
            case 'cancelled':
                return 'bg-red-100 text-red-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function indexApi()
    {
        // Get all bookings with related user and payment
        $bookings = Booking::with(['user', 'payment', 'hotelBookings'])->get();
        return response()->json(['status' => 'success', 'data' => $bookings], 200);
    }

    /**
     * Show the form for creating a new booking.
     */
    public function create()
    {
        $hotels = \App\Models\HotelMetadata::select('hotel_id', 'name', 'address')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('bookings.create', [
            'hotels' => $hotels,
            'defaultCheckIn' => now()->format('Y-m-d'),
            'defaultCheckOut' => now()->addDays(1)->format('Y-m-d')
        ]);
    }

    /**
     * Store a newly created booking in storage.
     */
    public function store(Request $request)
    {
        // Log the incoming request data
        \Log::info('Booking Store Request Data:', $request->all());
        \Log::info('Auth User ID: ' . (auth()->check() ? auth()->id() : 'Not authenticated'));

        // Validate the request
        $validated = $request->validate([
            'hotel_id' => 'required|exists:hotel_metadata,hotel_id',
            'room_type_id' => 'required|exists:room_types,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'nationality' => 'required|string|max:100',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'adults' => 'required|integer|min:1',
            'children' => 'required|integer|min:0',
            'special_requests' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:credit_card,paypal,pay_at_hotel',
        ]);

        try {
            // Start a database transaction
            \DB::beginTransaction();

            // Get the room type with price and availability
            $roomType = RoomType::findOrFail($validated['room_type_id']);
            \Log::info('Room Type Found:', $roomType->toArray());
            
            // Check room availability
            if ($roomType->available_rooms <= 0) {
                \Log::warning('No available rooms for room type: ' . $roomType->id);
                return back()->with('error', 'Sorry, the selected room type is no longer available.')->withInput();
            }

            // Calculate total price based on nights and room price
            $checkIn = new \Carbon\Carbon($validated['check_in']);
            $checkOut = new \Carbon\Carbon($validated['check_out']);
            $nights = $checkIn->diffInDays($checkOut);
            $totalPrice = $roomType->price * $nights;

            // Create the main booking record (without package_id for hotel-only bookings)
            $booking = new Booking();
            $booking->user_id = auth()->id();
            $booking->booking_reference = Booking::generateBookingReference();
            $booking->booking_date = now();
            $booking->travel_date = $checkIn;
            $booking->participants = $validated['adults'] + $validated['children'];
            $booking->total_amount = $totalPrice;
            $booking->status = 'pending';
            $booking->payment_status = $validated['payment_method'] === 'pay_at_hotel' ? 'pending' : 'pending_payment';
            $booking->save();
            \Log::info('Booking created:', $booking->toArray());

            // Create the hotel booking record
            $hotelBooking = new HotelBooking();
            $hotelBooking->booking_id = $booking->id;
            $hotelBooking->hotel_id = $validated['hotel_id'];
            $hotelBooking->room_type_id = $roomType->id;
            $hotelBooking->check_in_date = $checkIn;
            $hotelBooking->check_out_date = $checkOut;
            $hotelBooking->num_rooms = 1; // Default to 1 room, can be updated if needed
            $hotelBooking->num_guests = $validated['adults'] + $validated['children'];
            $hotelBooking->price_per_night = $roomType->price;
            $hotelBooking->total_hotel_price = $totalPrice;
            $hotelBooking->status = 'pending';
            $hotelBooking->guest_name = $validated['first_name'] . ' ' . $validated['last_name'];
            $hotelBooking->guest_email = $validated['email'];
            $hotelBooking->guest_phone = $validated['phone'];
            $hotelBooking->special_requests = $validated['special_requests'] ?? null;
            $hotelBooking->save();
            \Log::info('Hotel Booking created:', $hotelBooking->toArray());

            // Decrement available rooms
            $roomType->decrement('available_rooms');
            \Log::info('Room availability updated. New available rooms: ' . ($roomType->available_rooms - 1));

            // Handle payment based on payment method
            if ($validated['payment_method'] === 'pay_at_hotel') {
                // For pay at hotel, mark as pending and show success
                $booking->update(['payment_status' => 'pending']);
                \DB::commit();
                \Log::info('Pay at hotel booking created successfully');
                
                return redirect()->route('bookings.show', $booking->id)
                    ->with('success', 'Your booking has been created successfully! Please present your booking reference at the hotel for payment.');
            } else {
                // For online payments, redirect to payment gateway
                \DB::commit();
                \Log::info('Booking created, redirecting to payment');
                
                return redirect()->route('payments.choose-method', ['booking' => $booking->id])
                    ->with('success', 'Booking created successfully! Please complete your payment.');
            }

        } catch (\Exception $e) {
            // Rollback the transaction on error
            \DB::rollBack();
            \Log::error('Booking creation failed: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return back()->with('error', 'Failed to create booking. Please try again. Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        // Load relationships
        $booking->load(['user', 'payment', 'hotelBookings']);
        return response()->json(['status' => 'success', 'data' => $booking], 200);
    }

    /**
     * Display the specified booking.
     */
    public function showBooking($id)
    {
        $booking = Booking::with(['user', 'hotelBookings'])->findOrFail($id);
        
        // Verify the booking belongs to the authenticated user
        if ($booking->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized access to this booking.');
        }
        
        $hotelBooking = $booking->hotelBookings->first();
        
        if (!$hotelBooking) {
            abort(404, 'Hotel booking details not found.');
        }
        
        $hotel = HotelMetadata::find($hotelBooking->hotel_id);
        $roomType = RoomType::find($hotelBooking->room_type_id);
        
        // Calculate number of nights
        $checkIn = new \DateTime($hotelBooking->check_in_date);
        $checkOut = new \DateTime($hotelBooking->check_out_date);
        $nights = $checkIn->diff($checkOut)->days;
        
        return view('bookings.show', [
            'booking' => $booking,
            'hotelBooking' => $hotelBooking,
            'hotel' => $hotel,
            'roomType' => $roomType,
            'nights' => $nights
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        // Validate request
        $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'booking_date' => 'sometimes|date',
        ]);

        // Update booking with validated data
        $booking->update($request->only(['user_id', 'booking_date']));

        return response()->json(['status' => 'success', 'data' => $booking], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();
        return response()->json(['status' => 'success', 'message' => 'Booking deleted'], 200);
    }

    /**
     * Display a listing of the user's bookings.
     */
    public function indexUserBookings()
    {
        $bookings = Booking::with(['hotelBooking.hotel', 'hotelBooking.roomType'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Display the specified booking.
     */
    public function showUserBooking($id)
    {
        $booking = Booking::with(['hotelBooking.hotel', 'hotelBooking.roomType'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('bookings.show', compact('booking'));
    }

    /**
     * Cancel the specified booking.
     */
    public function cancelUserBooking($id)
    {
        $booking = Booking::where('user_id', Auth::id())->findOrFail($id);
        
        // Check if cancellation is allowed (e.g., 24 hours before check-in)
        $checkIn = new \DateTime($booking->hotelBooking->check_in_date);
        $now = new \DateTime();
        $hoursUntilCheckIn = $now->diff($checkIn)->h + ($now->diff($checkIn)->days * 24);
        
        if ($hoursUntilCheckIn < 24) {
            return back()->with('error', 'You can only cancel bookings at least 24 hours before check-in.');
        }
        
        // Update booking status
        $booking->status = 'cancelled';
        $booking->save();
        
        // Update hotel booking status
        $booking->hotelBooking->status = 'cancelled';
        $booking->hotelBooking->save();
        
        return redirect()->route('bookings.show', $booking->id)
            ->with('success', 'Your booking has been cancelled successfully.');
    }
}
