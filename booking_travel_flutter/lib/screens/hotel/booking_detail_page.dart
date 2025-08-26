import 'package:flutter/material.dart';
import 'package:booking_travel/models/hotel_booking_model.dart';
import 'package:booking_travel/services/booking_service.dart';
import 'package:url_launcher/url_launcher.dart';

class BookingDetailPage extends StatefulWidget {
  final HotelBooking booking;

  const BookingDetailPage({
    Key? key,
    required this.booking,
  }) : super(key: key);

  @override
  State<BookingDetailPage> createState() => _BookingDetailPageState();
}

class _BookingDetailPageState extends State<BookingDetailPage> {
  late HotelBooking _booking;
  bool _isLoading = false;

  @override
  void initState() {
    super.initState();
    _booking = widget.booking;
  }

  int get nights => _booking.checkOutDate.difference(_booking.checkInDate).inDays;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Booking #${_booking.id}'),
        backgroundColor: _getStatusColor(_booking.status),
        elevation: 0,
        actions: [
          IconButton(
            icon: const Icon(Icons.share),
            onPressed: _shareBooking,
          ),
        ],
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : SingleChildScrollView(
              child: Column(
                children: [
                  _buildStatusHeader(),
                  _buildBookingInfo(),
                  _buildHotelInfo(),
                  _buildGuestInfo(),
                  _buildPaymentInfo(),
                  _buildActionButtons(),
                  const SizedBox(height: 32),
                ],
              ),
            ),
    );
  }

  Widget _buildStatusHeader() {
    final Color statusColor = _getStatusColor(_booking.status);
    
    return Container(
      width: double.infinity,
      color: statusColor,
      padding: const EdgeInsets.all(24),
      child: Column(
        children: [
          Icon(
            _getStatusIcon(_booking.status),
            size: 48,
            color: Colors.white,
          ),
          const SizedBox(height: 12),
          Text(
            _booking.status.toUpperCase(),
            style: const TextStyle(
              fontSize: 24,
              fontWeight: FontWeight.bold,
              color: Colors.white,
            ),
          ),
          const SizedBox(height: 8),
          Text(
            _getStatusMessage(_booking.status),
            style: const TextStyle(
              fontSize: 16,
              color: Colors.white70,
            ),
            textAlign: TextAlign.center,
          ),
        ],
      ),
    );
  }

  Widget _buildBookingInfo() {
    return Card(
      margin: const EdgeInsets.all(16),
      elevation: 4,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Booking Information',
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 16),
            _buildInfoRow('Booking ID', '#${_booking.id}'),
            _buildInfoRow('Check-in Date', '${_booking.checkInDate.day}/${_booking.checkInDate.month}/${_booking.checkInDate.year}'),
            _buildInfoRow('Check-out Date', '${_booking.checkOutDate.day}/${_booking.checkOutDate.month}/${_booking.checkOutDate.year}'),
            _buildInfoRow('Duration', '$nights night${nights > 1 ? 's' : ''}'),
            _buildInfoRow('Number of Rooms', '${_booking.numberOfRooms}'),
            _buildInfoRow('Number of Guests', '${_booking.numberOfGuests}'),
            _buildInfoRow('Room Type', _booking.roomTypeName ?? 'N/A'),
            if (_booking.specialRequests != null && _booking.specialRequests!.isNotEmpty)
              _buildInfoRow('Special Requests', _booking.specialRequests!),
            const Divider(thickness: 1),
            _buildInfoRow(
              'Total Amount',
              '\$${_booking.totalAmount.toStringAsFixed(2)}',
              isHighlighted: true,
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildHotelInfo() {
    return Card(
      margin: const EdgeInsets.symmetric(horizontal: 16),
      elevation: 4,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Hotel Information',
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 16),
            Row(
              children: [
                Container(
                  width: 80,
                  height: 80,
                  decoration: BoxDecoration(
                    borderRadius: BorderRadius.circular(8),
                    color: Colors.grey[300],
                  ),
                  child: const Icon(Icons.hotel, size: 40),
                ),
                const SizedBox(width: 16),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        _booking.hotelName ?? 'Hotel Name',
                        style: const TextStyle(
                          fontSize: 18,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      const SizedBox(height: 8),
                      if (_booking.hotelAddress != null)
                        Row(
                          children: [
                            const Icon(Icons.location_on, size: 16, color: Colors.grey),
                            const SizedBox(width: 4),
                            Expanded(
                              child: Text(
                                _booking.hotelAddress!,
                                style: TextStyle(
                                  fontSize: 14,
                                  color: Colors.grey[600],
                                ),
                              ),
                            ),
                          ],
                        ),
                      const SizedBox(height: 8),
                      if (_booking.hotelPhone != null)
                        Row(
                          children: [
                            const Icon(Icons.phone, size: 16, color: Colors.green),
                            const SizedBox(width: 4),
                            Text(
                              _booking.hotelPhone!,
                              style: const TextStyle(fontSize: 14),
                            ),
                          ],
                        ),
                    ],
                  ),
                ),
              ],
            ),
            const SizedBox(height: 16),
            Row(
              children: [
                Expanded(
                  child: ElevatedButton.icon(
                    onPressed: _booking.hotelPhone != null ? () => _callHotel(_booking.hotelPhone!) : null,
                    style: ElevatedButton.styleFrom(
                      backgroundColor: Colors.green,
                    ),
                    icon: const Icon(Icons.phone, color: Colors.white),
                    label: const Text('Call Hotel', style: TextStyle(color: Colors.white)),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: OutlinedButton.icon(
                    onPressed: _booking.hotelAddress != null ? () => _getDirections(_booking.hotelAddress!) : null,
                    icon: const Icon(Icons.directions, color: Colors.blue),
                    label: const Text('Directions', style: TextStyle(color: Colors.blue)),
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildGuestInfo() {
    return Card(
      margin: const EdgeInsets.all(16),
      elevation: 4,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Guest Information',
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 16),
            _buildInfoRow('Guest Name', _booking.guestName ?? 'N/A'),
            _buildInfoRow('Email', _booking.guestEmail ?? 'N/A'),
            _buildInfoRow('Phone', _booking.guestPhone ?? 'N/A'),
          ],
        ),
      ),
    );
  }

  Widget _buildPaymentInfo() {
    return Card(
      margin: const EdgeInsets.symmetric(horizontal: 16),
      elevation: 4,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Payment Information',
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 16),
            _buildInfoRow('Payment Method', _booking.paymentMethod ?? 'N/A'),
            if (_booking.paymentId != null)
              _buildInfoRow('Payment ID', _booking.paymentId!),
            if (_booking.transactionId != null)
              _buildInfoRow('Transaction ID', _booking.transactionId!),
            _buildInfoRow('Booking Date', '${_booking.createdAt?.day}/${_booking.createdAt?.month}/${_booking.createdAt?.year}' ?? 'N/A'),
          ],
        ),
      ),
    );
  }

  Widget _buildActionButtons() {
    final bool canCancel = _booking.status == 'confirmed' && _booking.canCancel;
    final bool canModify = _booking.status == 'confirmed' && _booking.checkInDate.isAfter(DateTime.now().add(const Duration(days: 1)));

    return Padding(
      padding: const EdgeInsets.all(16),
      child: Column(
        children: [
          if (canModify) ...[
            SizedBox(
              width: double.infinity,
              height: 50,
              child: ElevatedButton.icon(
                onPressed: _showModifyDialog,
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.orange,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(12),
                  ),
                ),
                icon: const Icon(Icons.edit, color: Colors.white),
                label: const Text(
                  'Modify Booking',
                  style: TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.bold,
                    color: Colors.white,
                  ),
                ),
              ),
            ),
            const SizedBox(height: 12),
          ],
          if (canCancel) ...[
            SizedBox(
              width: double.infinity,
              height: 50,
              child: OutlinedButton.icon(
                onPressed: _showCancelDialog,
                style: OutlinedButton.styleFrom(
                  side: const BorderSide(color: Colors.red),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(12),
                  ),
                ),
                icon: const Icon(Icons.cancel, color: Colors.red),
                label: const Text(
                  'Cancel Booking',
                  style: TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.bold,
                    color: Colors.red,
                  ),
                ),
              ),
            ),
            const SizedBox(height: 12),
          ],
          SizedBox(
            width: double.infinity,
            height: 50,
            child: ElevatedButton.icon(
              onPressed: _downloadReceipt,
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.blue,
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(12),
                ),
              ),
              icon: const Icon(Icons.download, color: Colors.white),
              label: const Text(
                'Download Receipt',
                style: TextStyle(
                  fontSize: 16,
                  fontWeight: FontWeight.bold,
                  color: Colors.white,
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildInfoRow(String label, String value, {bool isHighlighted = false}) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 120,
            child: Text(
              label,
              style: TextStyle(
                fontSize: isHighlighted ? 16 : 14,
                color: isHighlighted ? Colors.black : Colors.grey[600],
                fontWeight: isHighlighted ? FontWeight.bold : FontWeight.normal,
              ),
            ),
          ),
          Expanded(
            child: Text(
              value,
              style: TextStyle(
                fontSize: isHighlighted ? 18 : 14,
                fontWeight: FontWeight.bold,
                color: isHighlighted ? Colors.green : Colors.black,
              ),
            ),
          ),
        ],
      ),
    );
  }

  Color _getStatusColor(String status) {
    switch (status.toLowerCase()) {
      case 'confirmed':
        return Colors.green;
      case 'pending':
        return Colors.orange;
      case 'cancelled':
        return Colors.red;
      case 'completed':
        return Colors.blue;
      default:
        return Colors.grey;
    }
  }

  IconData _getStatusIcon(String status) {
    switch (status.toLowerCase()) {
      case 'confirmed':
        return Icons.check_circle;
      case 'pending':
        return Icons.hourglass_empty;
      case 'cancelled':
        return Icons.cancel;
      case 'completed':
        return Icons.done_all;
      default:
        return Icons.info;
    }
  }

  String _getStatusMessage(String status) {
    switch (status.toLowerCase()) {
      case 'confirmed':
        return 'Your booking is confirmed and ready!';
      case 'pending':
        return 'Your booking is being processed.';
      case 'cancelled':
        return 'This booking has been cancelled.';
      case 'completed':
        return 'Thank you for staying with us!';
      default:
        return 'Booking status information.';
    }
  }

  void _callHotel(String phoneNumber) async {
    final Uri phoneUri = Uri(scheme: 'tel', path: phoneNumber);
    if (await canLaunchUrl(phoneUri)) {
      await launchUrl(phoneUri);
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Could not launch phone dialer')),
      );
    }
  }

  void _getDirections(String address) async {
    final Uri mapsUri = Uri.parse('https://www.google.com/maps/search/?api=1&query=${Uri.encodeComponent(address)}');
    if (await canLaunchUrl(mapsUri)) {
      await launchUrl(mapsUri);
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Could not open maps')),
      );
    }
  }

  void _shareBooking() {
    final String shareText = '''
Booking Confirmation #${_booking.id}

Hotel: ${_booking.hotelName ?? 'N/A'}
Check-in: ${_booking.checkInDate.day}/${_booking.checkInDate.month}/${_booking.checkInDate.year}
Check-out: ${_booking.checkOutDate.day}/${_booking.checkOutDate.month}/${_booking.checkOutDate.year}
Guests: ${_booking.numberOfGuests}
Total: \$${_booking.totalAmount.toStringAsFixed(2)}

Status: ${_booking.status.toUpperCase()}
''';

    // Note: In a real app, you would use the share_plus package
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text('Booking details copied: $shareText'),
        duration: const Duration(seconds: 3),
      ),
    );
  }

  void _downloadReceipt() {
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(
        content: Text('Receipt download feature coming soon!'),
        duration: Duration(seconds: 2),
      ),
    );
  }

  void _showModifyDialog() {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: const Text('Modify Booking'),
          content: const Text('What would you like to modify?'),
          actions: [
            TextButton(
              onPressed: () => Navigator.of(context).pop(),
              child: const Text('Cancel'),
            ),
            ElevatedButton(
              onPressed: () {
                Navigator.of(context).pop();
                ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(content: Text('Booking modification feature coming soon!')),
                );
              },
              child: const Text('Continue'),
            ),
          ],
        );
      },
    );
  }

  void _showCancelDialog() {
    final TextEditingController reasonController = TextEditingController();
    
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: const Text('Cancel Booking'),
          content: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text('Are you sure you want to cancel booking #${_booking.id}?'),
              const SizedBox(height: 16),
              TextField(
                controller: reasonController,
                decoration: const InputDecoration(
                  labelText: 'Reason for cancellation (optional)',
                  border: OutlineInputBorder(),
                ),
                maxLines: 3,
              ),
            ],
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.of(context).pop(),
              child: const Text('Keep Booking'),
            ),
            ElevatedButton(
              onPressed: () => _cancelBooking(reasonController.text),
              style: ElevatedButton.styleFrom(backgroundColor: Colors.red),
              child: const Text('Cancel Booking', style: TextStyle(color: Colors.white)),
            ),
          ],
        );
      },
    );
  }

  Future<void> _cancelBooking(String reason) async {
    Navigator.of(context).pop(); // Close dialog
    
    setState(() {
      _isLoading = true;
    });

    try {
      final result = await BookingService.cancelBooking(
        _booking.id,
        reason: reason.isNotEmpty ? reason : null,
      );         

      setState(() {
        _isLoading = false;
      });

      if (result['success']) {
        setState(() {
          _booking = _booking.copyWith(status: 'cancelled');
        });
        
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(result['message']),
            backgroundColor: Colors.green,
          ),
        );
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(result['message']),
            backgroundColor: Colors.red,
          ),
        );
      }
    } catch (e) {
      setState(() {
        _isLoading = false;
      });
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Error cancelling booking: $e'),
          backgroundColor: Colors.red,
        ),
      );
    }
  }
}
