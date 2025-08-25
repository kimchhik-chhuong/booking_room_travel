<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title }}</title>
    <style>
        @page { margin: 20px; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }
        .logo {
            max-width: 200px;
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 5px;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        .info-item {
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
            color: #7f8c8d;
            margin-bottom: 3px;
        }
        .info-value {
            color: #2c3e50;
        }
        .hotel-image {
            width: 100%;
            max-height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            text-transform: capitalize;
        }
        .status-confirmed { background-color: #d4edda; color: #155724; }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-cancelled { background-color: #f8d7da; color: #721c24; }
        .status-completed { background-color: #cce5ff; color: #004085; }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
            text-align: center;
            font-size: 12px;
            color: #7f8c8d;
        }
        .qr-code {
            width: 100px;
            height: 100px;
            margin: 20px auto;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e9ecef;
            border-radius: 8px;
        }
        .barcode {
            font-family: 'Libre Barcode 128', cursive;
            font-size: 40px;
            text-align: center;
            margin: 20px 0;
            letter-spacing: 3px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h2>Booking Confirmation</h2>
        <p>Thank you for choosing our service!</p>
        <div class="barcode">*{{ $booking->booking_reference }}*</div>
    </div>

    <!-- Booking Status -->
    <div class="section">
        <div style="text-align: center; margin-bottom: 20px;">
            @php
                $statusClass = [
                    'confirmed' => 'status-confirmed',
                    'pending' => 'status-pending',
                    'cancelled' => 'status-cancelled',
                    'completed' => 'status-completed'
                ][$booking->status ?? 'pending'] ?? 'status-pending';
            @endphp
            <span class="status-badge {{ $statusClass }}">
                {{ ucfirst($booking->status ?? 'pending') }}
            </span>
            <p>Booking Reference: <strong>{{ $booking->booking_reference }}</strong></p>
            <p>Booking Date: {{ \Carbon\Carbon::parse($booking->booking_date)->format('F d, Y') }}</p>
        </div>
    </div>

    <div style="display: flex; margin-bottom: 30px;">
        <!-- Guest Information -->
        <div style="flex: 1; padding-right: 15px;">
            <div class="section">
                <h3 class="section-title">Guest Information</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Name</div>
                        <div class="info-value">{{ $booking->first_name }} {{ $booking->last_name }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $booking->email }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Phone</div>
                        <div class="info-value">{{ $booking->phone ?? 'N/A' }}</div>
                    </div>
                    @if(!empty($booking->nationality))
                    <div class="info-item">
                        <div class="info-label">Nationality</div>
                        <div class="info-value">{{ $booking->nationality }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- QR Code Placeholder -->
        <div style="width: 150px; text-align: center;">
            <div class="qr-code">
                <!-- In a real application, generate a QR code with the booking reference -->
                <div style="text-align: center; font-size: 12px;">
                    Scan for<br>Check-in
                </div>
            </div>
        </div>
    </div>

    <!-- Hotel Information -->
    <div class="section">
        <h3 class="section-title">Stay Details</h3>
        
        @if(isset($hotel->images) && !empty($hotel->images))
            @php
                $images = is_string($hotel->images) ? json_decode($hotel->images, true) : $hotel->images;
                $firstImage = is_array($images) ? (is_array($images[0] ?? null) ? ($images[0]['url'] ?? '') : $images[0]) : '';
            @endphp
            @if($firstImage)
                <img src="{{ $firstImage }}" alt="{{ $hotel->name }}" class="hotel-image">
            @endif
        @endif

        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Hotel</div>
                <div class="info-value">{{ $hotel->name ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Room Type</div>
                <div class="info-value">{{ $roomType->name ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Check-in</div>
                <div class="info-value">
                    {{ \Carbon\Carbon::parse($hotelBooking->check_in_date)->format('F d, Y') }}
                    @if(isset($hotel->check_in_time))
                        <br><small>After {{ $hotel->check_in_time }}</small>
                    @endif
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Check-out</div>
                <div class="info-value">
                    {{ \Carbon\Carbon::parse($hotelBooking->check_out_date)->format('F d, Y') }}
                    @if(isset($hotel->check_out_time))
                        <br><small>Before {{ $hotel->check_out_time }}</small>
                    @endif
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Nights</div>
                <div class="info-value">{{ $nights }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Guests</div>
                <div class="info-value">
                    {{ $hotelBooking->num_guests ?? '1' }}
                    @if(isset($booking->adults) || isset($booking->children))
                        ({{ $booking->adults ?? '1' }} Adults, {{ $booking->children ?? '0' }} Children)
                    @endif
                </div>
            </div>
            @if(!empty($hotel->address))
            <div class="info-item" style="grid-column: 1 / -1;">
                <div class="info-label">Address</div>
                <div class="info-value">{{ $hotel->address }}</div>
            </div>
            @endif
            @if(!empty($hotelBooking->special_requests))
            <div class="info-item" style="grid-column: 1 / -1;">
                <div class="info-label">Special Requests</div>
                <div class="info-value">{{ $hotelBooking->special_requests }}</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Pricing Summary -->
    <div class="section">
        <h3 class="section-title">Pricing Summary</h3>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <thead>
                <tr style="background-color: #f8f9fa; border-bottom: 1px solid #dee2e6;">
                    <th style="text-align: left; padding: 10px;">Description</th>
                    <th style="text-align: right; padding: 10px;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($roomType) && $roomType && isset($nights))
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 10px;">
                        {{ $roomType->name ?? 'Room' }}
                        <div style="font-size: 12px; color: #6c757d;">
                            {{ $nights }} night{{ $nights > 1 ? 's' : '' }} @ {{ number_format($roomType->price, 2) }} {{ config('app.currency', '$') }}/night
                        </div>
                    </td>
                    <td style="text-align: right; padding: 10px;">
                        {{ number_format($roomType->price * $nights, 2) }} {{ config('app.currency', '$') }}
                    </td>
                </tr>
                @endif
                
                @if(isset($booking->tax_amount) && $booking->tax_amount > 0)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 10px;">Taxes & Fees</td>
                    <td style="text-align: right; padding: 10px;">
                        {{ number_format($booking->tax_amount, 2) }} {{ config('app.currency', '$') }}
                    </td>
                </tr>
                @endif
                
                @if(isset($booking->discount_amount) && $booking->discount_amount > 0)
                <tr style="background-color: #f8fff8; border-bottom: 1px solid #eee;">
                    <td style="padding: 10px;">Discount</td>
                    <td style="text-align: right; padding: 10px; color: #28a745;">
                        -{{ number_format($booking->discount_amount, 2) }} {{ config('app.currency', '$') }}
                    </td>
                </tr>
                @endif
                
                <tr style="background-color: #f8f9fa; font-weight: bold; font-size: 1.1em;">
                    <td style="padding: 15px 10px;">Total Amount</td>
                    <td style="text-align: right; padding: 15px 10px;">
                        {{ number_format($booking->total_amount, 2) }} {{ config('app.currency', '$') }}
                    </td>
                </tr>
                
                @if($booking->payment_status === 'pending' && $booking->payment_method === 'pay_at_hotel')
                <tr>
                    <td colspan="2" style="text-align: center; padding: 15px 10px; background-color: #e7f5ff; color: #0056b3;">
                        <i class="fas fa-info-circle"></i> Payment will be made at the hotel
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Policies -->
    @if(isset($hotel) && (!empty($hotel->cancellation_policy) || !empty($hotel->check_in_policy)))
    <div class="section">
        <h3 class="section-title">Hotel Policies</h3>
        @if(!empty($hotel->cancellation_policy))
            <div style="margin-bottom: 15px;">
                <div style="font-weight: bold; margin-bottom: 5px;">Cancellation Policy:</div>
                <div>{{ $hotel->cancellation_policy }}</div>
            </div>
        @endif
        
        @if(!empty($hotel->check_in_policy))
            <div>
                <div style="font-weight: bold; margin-bottom: 5px;">Check-in/Check-out Policy:</div>
                <div>{{ $hotel->check_in_policy }}</div>
            </div>
        @endif
    </div>
    @endif

    <!-- Contact Information -->
    <div class="section">
        <h3 class="section-title">Need Help?</h3>
        <p>If you have any questions about your booking, please contact our customer support team:</p>
        <div style="display: flex; gap: 30px; margin-top: 15px;">
            <div><i class="fas fa-phone-alt"></i> +855 23 999 999</div>
            <div><i class="fas fa-envelope"></i> support@bookingtravel.com</div>
            <div><i class="fas fa-clock"></i> 24/7 Support</div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Thank you for choosing our service. We look forward to serving you!</p>
        <p>Booking Reference: {{ $booking->booking_reference }} | Printed on {{ now()->format('F d, Y h:i A') }}</p>
    </div>
</body>
</html>
