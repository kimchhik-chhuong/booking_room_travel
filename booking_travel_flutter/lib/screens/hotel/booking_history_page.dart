import 'package:flutter/material.dart';
import 'package:booking_travel/models/hotel_booking_model.dart';
import 'package:booking_travel/services/booking_service.dart';
import 'package:booking_travel/screens/hotel/booking_detail_page.dart';
import 'package:booking_travel/screens/hotel/cancellation_reason_page.dart';

class BookingHistoryPage extends StatefulWidget {
  const BookingHistoryPage({Key? key}) : super(key: key);

  @override
  State<BookingHistoryPage> createState() => _BookingHistoryPageState();
}

class _BookingHistoryPageState extends State<BookingHistoryPage>
    with SingleTickerProviderStateMixin {
  late TabController _tabController;
  List<HotelBooking> _allBookings = [];
  List<HotelBooking> _completedBookings = [];
  List<HotelBooking> _upcomingBookings = [];
  List<HotelBooking> _cancelledBookings = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 4, vsync: this);
    _fetchBookings();
  }

  @override
  void dispose() {
    _tabController.dispose();
    super.dispose();
  }

  Future<void> _fetchBookings() async {
    setState(() {
      _isLoading = true;
    });

    try {
      final bookings = await BookingService.getUserBookings();
      final now = DateTime.now();

      setState(() {
        _allBookings = bookings;
        
        _upcomingBookings = bookings.where((booking) {
          return booking.status == 'confirmed' && 
                 booking.checkOutDate.isAfter(now);
        }).toList();
        
        _completedBookings = bookings.where((booking) {
          return booking.status == 'confirmed' && 
                 booking.checkOutDate.isBefore(now);
        }).toList();
        
        _cancelledBookings = bookings.where((booking) {
          return booking.status == 'cancelled';
        }).toList();
        
        _isLoading = false;
      });
    } catch (e) {
      setState(() {
        _isLoading = false;
      });
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Error loading bookings: $e')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Booking History'),
        backgroundColor: Colors.blue,
        elevation: 0,
        bottom: TabBar(
          controller: _tabController,
          isScrollable: true,
          tabs: const [
            Tab(text: 'All'),
            Tab(text: 'Completed'),
            Tab(text: 'Upcoming'),
            Tab(text: 'Cancelled'),
          ],
        ),
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : TabBarView(
              controller: _tabController,
              children: [
                _buildBookingsList(_allBookings, 'all'),
                _buildBookingsList(_completedBookings, 'completed'),
                _buildBookingsList(_upcomingBookings, 'upcoming'),
                _buildBookingsList(_cancelledBookings, 'cancelled'),
              ],
            ),
      floatingActionButton: FloatingActionButton(
        onPressed: _fetchBookings,
        backgroundColor: Colors.blue,
        child: const Icon(Icons.refresh),
      ),
    );
  }

  Widget _buildBookingsList(List<HotelBooking> bookings, String type) {
    if (bookings.isEmpty) {
      return _buildEmptyState(type);
    }

    return RefreshIndicator(
      onRefresh: _fetchBookings,
      child: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: bookings.length,
        itemBuilder: (context, index) {
          final booking = bookings[index];
          return _buildBookingCard(booking, type);
        },
      ),
    );
  }

  Widget _buildEmptyState(String type) {
    String message;
    IconData icon;
    
    switch (type) {
      case 'upcoming':
        message = 'No upcoming bookings';
        icon = Icons.hotel_outlined;
        break;
      case 'completed':
        message = 'No completed bookings';
        icon = Icons.history;
        break;
      case 'cancelled':
        message = 'No cancelled bookings';
        icon = Icons.cancel_outlined;
        break;
      default:
        message = 'No bookings found';
        icon = Icons.inbox_outlined;
    }

    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(
            icon,
            size: 64,
            color: Colors.grey[400],
          ),
          const SizedBox(height: 16),
          Text(
            message,
            style: TextStyle(
              fontSize: 18,
              color: Colors.grey[600],
            ),
          ),
          const SizedBox(height: 8),
          Text(
            'Pull down to refresh',
            style: TextStyle(
              fontSize: 14,
              color: Colors.grey[500],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildBookingCard(HotelBooking booking, String type) {
    final Color statusColor = _getStatusColor(booking.status);
    final bool canCancel = type == 'upcoming' && booking.canCancel;
    final bool canCancelWithin24Hours = _canCancelWithin24Hours(booking);
    final bool showCancelButton = canCancel && canCancelWithin24Hours;

    return Card(
      margin: const EdgeInsets.only(bottom: 16),
      elevation: 4,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: InkWell(
        onTap: () {
          if (showCancelButton) {
            Navigator.push(
              context,
              MaterialPageRoute(
                builder: (context) => CancellationReasonPage(booking: booking),
              ),
            );
          } else {
            Navigator.push(
              context,
              MaterialPageRoute(
                builder: (context) => BookingDetailPage(booking: booking),
              ),
            );
          }
        },
        borderRadius: BorderRadius.circular(12),
        child: Padding(
          padding: const EdgeInsets.all(16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Hotel name as clickable text
              InkWell(
                onTap: () {
                  if (showCancelButton) {
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (context) => CancellationReasonPage(booking: booking),
                      ),
                    );
                  } else {
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (context) => BookingDetailPage(booking: booking),
                      ),
                    );
                  }
                },
                child: Text(
                  booking.hotelName ?? 'Hotel Name',
                  style: const TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                    color: Colors.blue,
                    decoration: TextDecoration.underline,
                  ),
                ),
              ),
              const SizedBox(height: 8),
              
              // Booking date and time
              Text(
                '${_formatDate(booking.createdAt)} at ${_formatTime(booking.createdAt)}',
                style: TextStyle(
                  fontSize: 14,
                  color: Colors.grey[600],
                ),
              ),
              const SizedBox(height: 8),
              
              // Price
              Text(
                '\$${booking.totalAmount.toStringAsFixed(2)}',
                style: const TextStyle(
                  fontSize: 20,
                  fontWeight: FontWeight.bold,
                  color: Colors.green,
                ),
              ),
              const SizedBox(height: 12),
              
              // Status and actions row
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                    decoration: BoxDecoration(
                      color: statusColor.withOpacity(0.2),
                      borderRadius: BorderRadius.circular(16),
                    ),
                    child: Text(
                      booking.status.toUpperCase(),
                      style: TextStyle(
                        fontSize: 12,
                        fontWeight: FontWeight.bold,
                        color: statusColor,
                      ),
                    ),
                  ),
                  
                  Row(
                    children: [
                      if (booking.status.toLowerCase() == 'completed' || 
                          booking.status.toLowerCase() == 'cancelled')
                        TextButton(
                          onPressed: () {
                            _viewReceipt(booking);
                          },
                          child: const Text(
                            'View Receipt',
                            style: TextStyle(color: Colors.blue),
                          ),
                        ),
                      if (booking.status.toLowerCase() == 'completed' || 
                          booking.status.toLowerCase() == 'confirmed')
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                          decoration: BoxDecoration(
                            color: Colors.green.withOpacity(0.2),
                            borderRadius: BorderRadius.circular(8),
                          ),
                          child: Text(
                            'Paid',
                            style: TextStyle(
                              fontSize: 12,
                              color: Colors.green[700],
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                        ),
                    ],
                  ),
                ],
              ),
              
              // Cancellation info for upcoming bookings
              if (showCancelButton) ...[
                const SizedBox(height: 12),
                Container(
                  padding: const EdgeInsets.all(8),
                  decoration: BoxDecoration(
                    color: Colors.blue[50],
                    borderRadius: BorderRadius.circular(8),
                  ),
                  child: Row(
                    children: [
                      Icon(Icons.info_outline, color: Colors.blue[700], size: 16),
                      const SizedBox(width: 8),
                      Expanded(
                        child: Text(
                          'Free cancellation available until ${_getCancellationDeadline(booking)}',
                          style: TextStyle(
                            fontSize: 12,
                            color: Colors.blue[700],
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
              ] else if (canCancel && !canCancelWithin24Hours) ...[
                const SizedBox(height: 12),
                Container(
                  padding: const EdgeInsets.all(8),
                  decoration: BoxDecoration(
                    color: Colors.grey[200],
                    borderRadius: BorderRadius.circular(8),
                  ),
                  child: Row(
                    children: [
                      Icon(Icons.info_outline, color: Colors.grey[600], size: 16),
                      const SizedBox(width: 8),
                      Expanded(
                        child: Text(
                          'Cancellation deadline passed (${_getCancellationDeadline(booking)})',
                          style: TextStyle(
                            fontSize: 12,
                            color: Colors.grey[600],
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
              ],
            ],
          ),
        ),
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

  bool _canCancelWithin24Hours(HotelBooking booking) {
    final now = DateTime.now();
    final cancellationDeadline = booking.createdAt.add(const Duration(hours: 24));
    return now.isBefore(cancellationDeadline);
  }

  String _getCancellationDeadline(HotelBooking booking) {
    final cancellationDeadline = booking.createdAt.add(const Duration(hours: 24));
    return '${cancellationDeadline.day}/${cancellationDeadline.month}/${cancellationDeadline.year} ${cancellationDeadline.hour}:${cancellationDeadline.minute.toString().padLeft(2, '0')}';
  }

  String _formatDate(DateTime date) {
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return '${months[date.month - 1]} ${date.day}, ${date.year}';
  }

  String _formatTime(DateTime date) {
    final hour = date.hour % 12;
    final period = date.hour < 12 ? 'AM' : 'PM';
    final minute = date.minute.toString().padLeft(2, '0');
    return '${hour == 0 ? 12 : hour}:$minute $period';
  }

  void _viewReceipt(HotelBooking booking) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Receipt'),
        content: Text('Receipt for booking #${booking.id}'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Close'),
          ),
        ],
      ),
    );
  }
}

class CancellationReasonPage extends StatefulWidget {
  final HotelBooking booking;

  const CancellationReasonPage({Key? key, required this.booking}) : super(key: key);

  @override
  State<CancellationReasonPage> createState() => _CancellationReasonPageState();
}

class _CancellationReasonPageState extends State<CancellationReasonPage> {
  String? selectedReason;
  final TextEditingController otherReasonController = TextEditingController();
  
  final List<Map<String, dynamic>> cancellationReasons = [
    {
      'title': 'Change of plans',
      'icon': Icons.event_busy,
    },
    {
      'title': 'Found better option',
      'icon': Icons.star_border,
    },
    {
      'title': 'Price too high',
      'icon': Icons.attach_money,
    },
    {
      'title': 'Travel restrictions',
      'icon': Icons.warning,
    },
    {
      'title': 'Personal emergency',
      'icon': Icons.emergency,
    },
    {
      'title': 'Other reason',
      'icon': Icons.more_horiz,
    },
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Cancel Booking'),
        backgroundColor: Colors.blue,
      ),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Cancel ${widget.booking.hotelName} Booking',
              style: const TextStyle(
                fontSize: 20,
                fontWeight: FontWeight.bold,
              ),
            ),
            const SizedBox(height: 8),
            Text(
              'Booking #${widget.booking.id}',
              style: TextStyle(
                fontSize: 16,
                color: Colors.grey[600],
              ),
            ),
            const SizedBox(height: 16),
            
            // Cancellation policy info
            Container(
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: Colors.blue[50],
                borderRadius: BorderRadius.circular(8),
              ),
              child: Row(
                children: [
                  Icon(Icons.info_outline, color: Colors.blue[700], size: 20),
                  const SizedBox(width: 8),
                  Expanded(
                    child: Text(
                      'You can cancel free of charge until ${_getCancellationDeadline(widget.booking)}',
                      style: TextStyle(
                        fontSize: 14,
                        color: Colors.blue[700],
                      ),
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 24),
            
            const Text(
              'Please select a reason for cancellation:',
              style: TextStyle(
                fontSize: 16,
                fontWeight: FontWeight.bold,
              ),
            ),
            const SizedBox(height: 16),
            
            // Circular reason selection
            Wrap(
              spacing: 12,
              runSpacing: 12,
              children: cancellationReasons.map((reason) {
                final bool isSelected = selectedReason == reason['title'];
                return InkWell(
                  onTap: () {
                    setState(() {
                      selectedReason = reason['title'];
                      if (reason['title'] != 'Other reason') {
                        otherReasonController.clear();
                      }
                    });
                  },
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
                    decoration: BoxDecoration(
                      color: isSelected ? Colors.blue[100] : Colors.grey[200],
                      borderRadius: BorderRadius.circular(20),
                      border: isSelected 
                          ? Border.all(color: Colors.blue, width: 2) 
                          : null,
                    ),
                    child: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Icon(
                          reason['icon'],
                          size: 18,
                          color: isSelected ? Colors.blue[800] : Colors.grey[700],
                        ),
                        const SizedBox(width: 6),
                        Text(
                          reason['title'],
                          style: TextStyle(
                            color: isSelected ? Colors.blue[800] : Colors.grey[700],
                            fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
                          ),
                        ),
                        if (isSelected) ...[
                          const SizedBox(width: 6),
                          Icon(
                            Icons.check_circle,
                            size: 18,
                            color: Colors.blue[800],
                          ),
                        ],
                      ],
                    ),
                  ),
                );
              }).toList(),
            ),
            
            // Other reason text field
            if (selectedReason == 'Other reason') ...[
              const SizedBox(height: 16),
              TextField(
                controller: otherReasonController,
                decoration: const InputDecoration(
                  labelText: 'Please specify your reason',
                  border: OutlineInputBorder(),
                ),
                maxLines: 3,
              ),
            ],
            
            const Spacer(),
            
            // Action buttons
            Row(
              children: [
                Expanded(
                  child: OutlinedButton(
                    onPressed: () => Navigator.pop(context),
                    style: OutlinedButton.styleFrom(
                      padding: const EdgeInsets.symmetric(vertical: 16),
                    ),
                    child: const Text('Go Back'),
                  ),
                ),
                const SizedBox(width: 16),
                Expanded(
                  child: ElevatedButton(
                    onPressed: selectedReason == null
                        ? null
                        : () => _cancelBooking(widget.booking, 
                            selectedReason == 'Other reason' 
                                ? otherReasonController.text 
                                : selectedReason!),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: Colors.red,
                      padding: const EdgeInsets.symmetric(vertical: 16),
                    ),
                    child: const Text(
                      'Cancel Booking',
                      style: TextStyle(color: Colors.white),
                    ),
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  String _getCancellationDeadline(HotelBooking booking) {
    final cancellationDeadline = booking.createdAt.add(const Duration(hours: 24));
    return '${cancellationDeadline.day}/${cancellationDeadline.month}/${cancellationDeadline.year} ${cancellationDeadline.hour}:${cancellationDeadline.minute.toString().padLeft(2, '0')}';
  }

  Future<void> _cancelBooking(HotelBooking booking, String reason) async {
    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (context) => const Center(child: CircularProgressIndicator()),
    );

    try {
      final result = await BookingService.cancelBooking(
        booking.id as String,
        reason: reason.isNotEmpty ? reason : null,
      );

      Navigator.of(context).pop();

      if (result['success']) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(result['message']),
            backgroundColor: Colors.green,
          ),
        );
        Navigator.of(context).pop();
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(result['message']),
            backgroundColor: Colors.red,
          ),
        );
      }
    } catch (e) {
      Navigator.of(context).pop();
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Error cancelling booking: $e'),
          backgroundColor: Colors.red,
        ),
      );
    }
  }
}