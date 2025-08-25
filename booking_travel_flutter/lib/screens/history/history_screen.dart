import 'package:booking_travel/screens/page/hotels_page.dart';
import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import '../../main.dart'; 

class HistoryScreen extends StatefulWidget {
  @override
  _HistoryScreenState createState() => _HistoryScreenState();
}

class _HistoryScreenState extends State<HistoryScreen> {
  String _selectedStatus = 'All';         

  @override                 
  void initState() {
    super.initState();
    // Show alerts for new completed bookings when screen first loads
    WidgetsBinding.instance.addPostFrameCallback((_) {
      _showInitialAlerts();
    });
  }

  void _showInitialAlerts() {
    for (var booking in globalBookingHistory) {
      if (booking['status'] == 'Completed' && booking['showAlert'] == true) {
        _showPaymentAlert(context, booking);
        // Mark alert as shown to prevent showing it again
        setState(() {
          booking['showAlert'] = false;
        });
      }
    }
  }

  List<Map<String, dynamic>> get _filteredHistory {
    if (_selectedStatus == 'All') {
      return globalBookingHistory;
    }
    return globalBookingHistory.where((booking) {
      return booking['status'] == _selectedStatus;
    }).toList();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Booking History'),
        centerTitle: true,
        backgroundColor: Colors.blue,
      ),
      body: Column(
        children: [
          // Status Filter Chips
          Container(
            height: 60,
            child: ListView(
              scrollDirection: Axis.horizontal,
              padding: const EdgeInsets.symmetric(horizontal: 16),
              children: [
                _buildFilterChip('All'),
                const SizedBox(width: 8),
                _buildFilterChip('Completed'),
                const SizedBox(width: 8),
                _buildFilterChip('Upcoming'),
                const SizedBox(width: 8),
                _buildFilterChip('Cancelled'),
              ],
            ),
          ),
          const Divider(height: 1),
          Expanded(
            child: ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: _filteredHistory.length,
              itemBuilder: (context, index) {
                final booking = _filteredHistory[index];
                return _buildHistoryCard(booking, context);
              },
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildFilterChip(String status) {
    return FilterChip(
      label: Text(status),
      selected: _selectedStatus == status,
      onSelected: (bool value) {
        if (value) {
          setState(() {
            _selectedStatus = status;
          });
        }
      },
      selectedColor: Colors.blue.withOpacity(0.1),
      checkmarkColor: Colors.blue,
      labelStyle: TextStyle(
        color: _selectedStatus == status ? Colors.blue : Colors.black,
        fontWeight: _selectedStatus == status ? FontWeight.bold : FontWeight.normal,
      ),
    );
  }

  Widget _buildHistoryCard(Map<String, dynamic> booking, BuildContext context) {
    final dateFormat = DateFormat('MMM dd, yyyy');
    final timeFormat = DateFormat('hh:mm a');

    return Card(
      margin: const EdgeInsets.only(bottom: 16),
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(12),
      ),
      elevation: 2,
      child: Padding(
        padding: const EdgeInsets.all(12),
        child: Column(
          children: [
            Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                ClipRRect(
                  borderRadius: BorderRadius.circular(8),
                  child: Image.asset(
                    booking['imageUrl'],
                    width: 80,
                    height: 80,
                    fit: BoxFit.cover,
                    errorBuilder: (context, error, stackTrace) {
                      return Container(
                        width: 80,
                        height: 80,
                        color: Colors.grey[200],
                        child: const Icon(Icons.broken_image),
                      );
                    },
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        booking['hotelName'],
                        style: const TextStyle(
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      const SizedBox(height: 4),
                      Text(
                        '${dateFormat.format(booking['date'])} at ${timeFormat.format(booking['date'])}',
                        style: TextStyle(
                          fontSize: 12,
                          color: Colors.grey[600],
                        ),
                      ),
                      const SizedBox(height: 8),
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Text(
                            booking['total'],
                            style: const TextStyle(
                              fontSize: 16,
                              fontWeight: FontWeight.bold,
                              color: Colors.blue,
                            ),
                          ),
                          Container(
                            padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                            decoration: BoxDecoration(
                              color: _getStatusColor(booking['status'], isBackground: true),
                              borderRadius: BorderRadius.circular(12),
                            ),
                            child: Text(
                              booking['status'],
                              style: TextStyle(
                                color: _getStatusColor(booking['status']),
                                fontSize: 12,
                              ),
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
              ],
            ),
            const SizedBox(height: 12),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                OutlinedButton(
                  onPressed: () => _showReceiptDialog(context, booking),
                  style: OutlinedButton.styleFrom(
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(8),
                    ),
                  ),
                  child: const Text('View Receipt'),
                ),
                ElevatedButton(
                  onPressed: () => _handleStatusButtonPress(context, booking),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: _getButtonColor(booking['status']),
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(8),
                    ),
                  ),
                  child: Text(
                    _getButtonText(booking['status']),
                    style: const TextStyle(color: Colors.white),
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Color _getStatusColor(String status, {bool isBackground = false}) {
    switch (status) {
      case 'Completed':
        return isBackground ? Colors.green[50]! : Colors.green;
      case 'Cancelled':
        return isBackground ? Colors.red[50]! : Colors.red;
      case 'Upcoming':
        return isBackground ? Colors.orange[50]! : Colors.orange;
      default:
        return isBackground ? Colors.grey[200]! : Colors.grey;
    }
  }

  Color _getButtonColor(String status) {
    switch (status) {
      case 'Completed':
        return Colors.blue;
      case 'Cancelled':
        return Colors.grey;
      case 'Upcoming':
        return Colors.orange;
      default:
        return Colors.grey;
    }
  }

  String _getButtonText(String status) {
    switch (status) {
      case 'Completed':
        return 'Paid';
      case 'Cancelled':
        return 'Cancelled';
      case 'Upcoming':
        return 'Upcoming';
      default:
        return status;
    }
  }

  void _handleStatusButtonPress(BuildContext context, Map<String, dynamic> booking) {
    // ... (logic remains the same)
  }

  void _showPaymentAlert(BuildContext context, Map<String, dynamic> booking) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Payment Confirmed'),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('Your payment for ${booking['hotelName']} has been completed.'),
            const SizedBox(height: 8),
            Text('Amount: ${booking['total']}'),
            const SizedBox(height: 8),
            Text('Date: ${DateFormat('MMM dd, yyyy').format(booking['date'])}'),
          ],
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('OK'),
          ),
        ],
      ),
    );
  }

  void _showReceiptDialog(BuildContext context, Map<String, dynamic> booking) {
    showDialog(
      context: context,
      builder: (context) => Dialog(
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(16),
        ),
        child: Padding(
          padding: const EdgeInsets.all(16),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              const Icon(Icons.check_circle, color: Colors.green, size: 60),
              const SizedBox(height: 16),
              const Text(
                'Payment Receipt',
                style: TextStyle(
                  fontSize: 20,
                  fontWeight: FontWeight.bold,
                ),
              ),
              const SizedBox(height: 16),
              _buildReceiptItem('Hotel', booking['hotelName']),
              _buildReceiptItem('Date', DateFormat('MMM dd, yyyy').format(booking['date'])),
              _buildReceiptItem('Time', DateFormat('hh:mm a').format(booking['date'])),
              _buildReceiptItem('Status', booking['status']),
              _buildReceiptItem('Amount', booking['total']),
              const SizedBox(height: 24),
              ElevatedButton(
                onPressed: () => Navigator.pop(context),
                child: const Text('Close'),
                style: ElevatedButton.styleFrom(
                  minimumSize: const Size(double.infinity, 50),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(8),
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildReceiptItem(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(
            label,
            style: TextStyle(
              fontWeight: FontWeight.bold,
              color: Colors.grey[600],
            ),
          ),
          Text(
            value,
            style: const TextStyle(
              fontSize: 16,
            ),
          ),
        ],
      ),
    );
  }
}