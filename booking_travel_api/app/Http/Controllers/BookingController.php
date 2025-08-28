<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\HotelBooking;
use App\Models\HotelMetadata;
use App\Models\RoomType;
use App\Models\Traveler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // Import the DB facade
use Barryvdh\DomPDF\Facade\Pdf;

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
        $perPage = $request->input('per_page', 10);
        
        // Base query with eager loading
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
                  })
                  ->orWhereHas('hotelBookings.hotel', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Apply sorting
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'amount_high':
                $query->orderBy('total_amount', 'desc');
                break;
            case 'amount_low':
                $query->orderBy('total_amount', 'asc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Get paginated results
        $bookings = $query->paginate($perPage)->withQueryString();
        
        // Get statistics for the dashboard
        $totalBookings = Booking::count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $totalRevenue = Booking::where('status', 'confirmed')->sum('total_amount');
        
        // Previous month comparison
        $previousMonthBookings = Booking::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
            
        $previousMonthRevenue = Booking::where('status', 'confirmed')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total_amount');
            
        // Monthly bookings for chart
        $monthlyBookings = Booking::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('created_at', date('Y'))
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
            'perPage' => (int)$perPage,
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
        \Illuminate\Support\Facades\Log::info('Booking Creation Request Data:', $request->all());
        \Illuminate\Support\Facades\Log::info('Auth User ID: ' . (auth()->check() ? auth()->id() : 'Not authenticated'));

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

        // Start database transaction
        DB::beginTransaction(); // Use the fully qualified namespace for the DB facade

        try {
            // Get the room type with price and availability
            $roomType = RoomType::findOrFail($validated['room_type_id']);
            \Illuminate\Support\Facades\Log::info('Room Type Found:', $roomType->toArray());
            
            // Check room availability
            if ($roomType->available_rooms <= 0) {
                \Illuminate\Support\Facades\Log::warning('No available rooms for room type: ' . $roomType->id);
                return back()->with('error', 'Sorry, the selected room type is no longer available.')->withInput();
            }

            // Calculate total price based on nights and room price
            $checkIn = new \Carbon\Carbon($validated['check_in']);
            $checkOut = new \Carbon\Carbon($validated['check_out']);
            $nights = $checkIn->diffInDays($checkOut);
            $totalPrice = $roomType->price * $nights;

            // Create the main booking record
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

            // Find or create traveler
            $traveler = Traveler::firstOrCreate(
                ['email' => $validated['email']],
                [
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'phone' => $validated['phone'],
                    'nationality' => $validated['nationality']
                ]
            );

            // Associate the booking with the traveler
            $booking->traveler_id = $traveler->id;
            $booking->save();

            // Create the hotel booking record
            $hotelBooking = new HotelBooking();
            $hotelBooking->booking_id = $booking->id;
            $hotelBooking->hotel_metadata_id = $validated['hotel_id'];
            $hotelBooking->room_type_id = $roomType->id;
            $hotelBooking->check_in_date = $checkIn;
            $hotelBooking->check_out_date = $checkOut;
            $hotelBooking->num_rooms = 1;
            $hotelBooking->num_guests = $validated['adults'] + $validated['children'];
            $hotelBooking->price_per_night = $roomType->price;
            $hotelBooking->total_hotel_price = $totalPrice;
            $hotelBooking->status = 'confirmed';
            $hotelBooking->guest_name = $validated['first_name'] . ' ' . $validated['last_name'];
            $hotelBooking->guest_email = $validated['email'];
            $hotelBooking->guest_phone = $validated['phone'];
            $hotelBooking->nationality = $validated['nationality'];
            $hotelBooking->special_requests = $validated['special_requests'] ?? null;
            $hotelBooking->save();

            // Decrement available rooms
            $roomType->decrement('available_rooms');

            // Commit the transaction
            DB::commit(); // Use the fully qualified namespace for the DB facade

            // Redirect to bookings index with success message
            return redirect()->route('bookings.index')
                ->with('success', 'Booking created successfully!');

        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack(); // Use the fully qualified namespace for the DB facade
            \Illuminate\Support\Facades\Log::error('Booking creation failed: ' . $e->getMessage());
            \Illuminate\Support\Facades\Log::error($e->getTraceAsString());
            
            return back()->with('error', 'Failed to create booking. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified booking.
     */
    public function show(Booking $booking)
    {
        try {
            // Eager load all necessary relationships
            $booking->load([
                'user',
                'hotelBookings',
                'hotelBookings.hotel',
                'hotelBookings.roomType',
                'payment'
            ]);
            
            // Get the first hotel booking (assuming one booking per reservation for now)
            $hotelBooking = $booking->hotelBookings->first();
            
            if (!$hotelBooking) {
                return redirect()->route('bookings.index')
                    ->with('error', 'No hotel booking found for this reservation.');
            }
            
            // Calculate number of nights
            $checkIn = new \DateTime($hotelBooking->check_in_date);
            $checkOut = new \DateTime($hotelBooking->check_out_date);
            $nights = $checkIn->diff($checkOut)->days;

            // Calculate total amount if not set
            if (!$booking->total_amount) {
                $booking->total_amount = $booking->hotelBookings->sum('total_hotel_price');
            }
            
            return view('bookings.show', [
                'booking' => $booking,
                'hotelBooking' => $hotelBooking,
                'hotel' => $hotelBooking->hotel,
                'roomType' => $hotelBooking->roomType,
                'nights' => $nights
            ]);
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error showing booking: ' . $e->getMessage());
            return redirect()->route('bookings.index')
                ->with('error', 'Error loading booking details. Please try again.');
        }
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
        
        $hotel = HotelMetadata::find($hotelBooking->hotel_metadata_id);
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
     * Update the specified booking in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        try {
            // Start database transaction
            \Illuminate\Support\Facades\DB::beginTransaction();
            
            // Update booking status
            $booking->update([
                'status' => $request->input('status'),
                'updated_at' => now(),
            ]);
            
            // Update hotel bookings if they exist
            if ($request->has('hotel_bookings') && $booking->hotelBookings->isNotEmpty()) {
                foreach ($request->input('hotel_bookings') as $index => $hotelBookingData) {
                    if (isset($booking->hotelBookings[$index])) {
                        $booking->hotelBookings[$index]->update([
                            'check_in_date' => $hotelBookingData['check_in_date'],
                            'check_out_date' => $hotelBookingData['check_out_date'],
                            'num_rooms' => $hotelBookingData['num_rooms'],
                            'num_guests' => $hotelBookingData['num_guests'],
                            'nationality' => $hotelBookingData['nationality'] ?? null,
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
            
            // Commit transaction
            \Illuminate\Support\Facades\DB::commit();
            
            // Log the update
            \Illuminate\Support\Facades\Log::info("Booking #{$booking->booking_reference} updated by user #" . auth()->id());
            
            return redirect()->route('bookings.show', $booking)
                ->with('success', 'Booking updated successfully!');
                
        } catch (\Exception $e) {
            // Rollback transaction on error
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Error updating booking: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Failed to update booking. Please try again.');
        }
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
        try {
            $booking = Booking::with('hotelBookings')
                ->where('user_id', Auth::id())
                ->findOrFail($id);

            // Check if booking can be cancelled
            if (!in_array($booking->status, ['pending', 'confirmed'])) {
                return back()->with('error', 'Only pending or confirmed bookings can be cancelled.');
            }

            // Check cancellation policy (24 hours before check-in)
            if ($booking->hotelBookings->isNotEmpty()) {
                $checkIn = new \DateTime($booking->hotelBookings->first()->check_in_date);
                $now = new \DateTime();
                $hoursUntilCheckIn = $now->diff($checkIn)->h + ($now->diff($checkIn)->days * 24);
                
                if ($hoursUntilCheckIn < 24) {
                    return back()->with('error', 'You can only cancel bookings at least 24 hours before check-in.');
                }
            }

            \Illuminate\Support\Facades\DB::beginTransaction();

            try {
                // Update booking status
                $booking->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                    'cancelled_by' => Auth::id()
                ]);

                // Update related hotel bookings
                if ($booking->hotelBookings->isNotEmpty()) {
                    $booking->hotelBookings()->update(['status' => 'cancelled']);
                }

                \Illuminate\Support\Facades\DB::commit();

                // Here you can add:
                // - Send cancellation email
                // - Process refund if needed
                // - Log the cancellation

                return redirect()
                    ->route('bookings.index')
                    ->with('success', 'Booking #' . $booking->id . ' has been cancelled successfully.');

            } catch (\Exception $e) {
                \Illuminate\Support\Facades\DB::rollBack();
                \Illuminate\Support\Facades\Log::error('Failed to cancel booking: ' . $e->getMessage());
                throw $e;
            }

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Booking cancellation error: ' . $e->getMessage());
            return back()->with('error', 'Failed to cancel booking. Please try again or contact support.');
        }
    }

    /**
     * Cancel the specified booking.
     */
    public function cancel($id)
    {
        try {
            $booking = Booking::with('hotelBookings')->findOrFail($id);
            
            // Check if user is authorized to cancel this booking
            if (auth()->id() !== $booking->user_id && !auth()->user()->hasRole('admin')) {
                return redirect()->back()->with('error', 'You are not authorized to cancel this booking.');
            }
            
            // Check if booking can be cancelled
            if ($booking->status === 'cancelled') {
                return redirect()->back()->with('error', 'This booking has already been cancelled.');
            }
            
            if ($booking->status === 'completed') {
                return redirect()->back()->with('error', 'Completed bookings cannot be cancelled.');
            }
            
            // Start database transaction
            \Illuminate\Support\Facades\DB::beginTransaction();
            
            // Update booking status
            $booking->update([
                'status' => 'cancelled',
                'cancelled_at' => now()
            ]);
            
            // Update hotel booking status
            foreach ($booking->hotelBookings as $hotelBooking) {
                $hotelBooking->update(['status' => 'cancelled']);
                
                // Increase room availability
                $roomType = \App\Models\RoomType::find($hotelBooking->room_type_id);
                if ($roomType) {
                    $roomType->increment('available_rooms', $hotelBooking->num_rooms);
                }
            }
            
            // If there's a payment, process refund if applicable
            if ($booking->payment && $booking->payment->status === 'completed') {
                // Here you would typically integrate with your payment gateway to process refund
                // For now, we'll just update the payment status
                $booking->payment->update([
                    'status' => 'refunded',
                    'refunded_at' => now()
                ]);
            }
            
            \Illuminate\Support\Facades\DB::commit();
            
            return redirect()->route('bookings.index')
                ->with('success', 'Booking has been cancelled successfully.');
                
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Error cancelling booking: ' . $e->getMessage());
            \Illuminate\Support\Facades\Log::error($e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Failed to cancel booking. Please try again.');
        }
    }

    /**
     * Process check-in for a booking.
     */
    public function checkIn(Booking $booking)
    {
        try {
            // Verify the booking belongs to the authenticated user
            if ($booking->user_id !== Auth::id()) {
                abort(403, 'Unauthorized action.');
            }

            // Check if check-in is allowed (for pending bookings)
            if ($booking->status !== 'pending') {
                return back()->with('error', 'Only pending bookings can be checked in.');
            }

            \Illuminate\Support\Facades\DB::beginTransaction();

            try {
                // Update booking status to confirmed
                $booking->update([
                    'status' => 'confirmed',  // Changed from 'checked_in' to 'confirmed'
                    'checked_in_at' => now(),
                    'checked_in_by' => Auth::id()
                ]);

                // Update related hotel bookings
                if ($booking->hotelBookings->isNotEmpty()) {
                    $booking->hotelBookings()->update(['status' => 'confirmed']);
                }

                \Illuminate\Support\Facades\DB::commit();

                return redirect()
                    ->route('bookings.index')
                    ->with('success', 'Successfully checked in for booking #' . $booking->id);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\DB::rollBack();
                \Illuminate\Support\Facades\Log::error('Failed to process check-in: ' . $e->getMessage());
                throw $e;
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Check-in error: ' . $e->getMessage());
            return back()->with('error', 'Failed to process check-in. Please try again or contact support.');
        }
    }

    /**
     * Show the form for editing the specified booking.
     */
    public function edit(Booking $booking)
    {
        try {
            // Eager load all necessary relationships
            $booking->load([
                'user',
                'hotelBookings',
                'hotelBookings.hotel',
                'hotelBookings.roomType',
                'payment'
            ]);
            
            // Get the first hotel booking (assuming one booking per reservation for now)
            $hotelBooking = $booking->hotelBookings->first();
            
            if (!$hotelBooking) {
                return redirect()->route('bookings.index')
                    ->with('error', 'No hotel booking found for this reservation.');
            }
            
            // Ensure dates are in the correct format for the form inputs
            if ($hotelBooking->check_in_date) {
                $hotelBooking->check_in_date = \Carbon\Carbon::parse($hotelBooking->check_in_date)->format('Y-m-d');
            }
            
            if ($hotelBooking->check_out_date) {
                $hotelBooking->check_out_date = \Carbon\Carbon::parse($hotelBooking->check_out_date)->format('Y-m-d');
            }
            
            // Get available hotels and room types for the form
            $hotels = \App\Models\HotelMetadata::all();
            $roomTypes = $hotelBooking->hotel ? 
                \App\Models\RoomType::where('hotel_metadata_id', $hotelBooking->hotel->id)->get() : 
                collect();
            
            return view('bookings.edit', [
                'booking' => $booking,
                'hotelBooking' => $hotelBooking,
                'hotels' => $hotels,
                'roomTypes' => $roomTypes
            ]);
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error loading booking edit form: ' . $e->getMessage());
            return redirect()->route('bookings.index')
                ->with('error', 'Error loading booking form. Please try again.');
        }
    }
}
