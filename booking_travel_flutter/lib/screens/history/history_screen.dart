import 'package:flutter/material.dart';
import 'package:intl/intl.dart';

class HistoryScreen extends StatelessWidget {
  // Sample booking history data
  final List<Map<String, dynamic>> bookingHistory = [
    {
      'hotelName': 'Taj Hotel',
      'date': DateTime.now().subtract(Duration(days: 2)),
      'total': '\$200',
      'status': 'Completed',
      'imageUrl': 'assets/room1.jpg',
    },
    {
      'hotelName': 'AR Hotel',
      'date': DateTime.now().subtract(Duration(days: 5)),
      'total': '\$180',
      'status': 'Completed',
      'imageUrl': 'assets/room2.jpg',
    },
    {
      'hotelName': 'Al Rahman Hotel',
      'date': DateTime.now().subtract(Duration(days: 10)),
      'total': '\$220',
      'status': 'Cancelled',
      'imageUrl': 'assets/room3.jpg',
    },
    {
      'hotelName': 'Upcoming Hotel',
      'date': DateTime.now().add(Duration(days: 3)),
      'total': '\$250',
      'status': 'Upcoming',
      'imageUrl': 'assets/room4.jpg',
    },
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Booking History'),
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
              padding: EdgeInsets.symmetric(horizontal: 16),
              children: [
                FilterChip(
                  label: Text('All'),
                  selected: true,
                  onSelected: (bool value) {},
                ),
                SizedBox(width: 8),
                FilterChip(
                  label: Text('Completed'),
                  selected: false,
                  onSelected: (bool value) {},
                ),
                SizedBox(width: 8),
                FilterChip(
                  label: Text('Upcoming'),
                  selected: false,
                  onSelected: (bool value) {},
                ),
                SizedBox(width: 8),
                FilterChip(
                  label: Text('Cancelled'),
                  selected: false,
                  onSelected: (bool value) {},
                ),
              ],
            ),
          ),
          Divider(height: 1),
          Expanded(
            child: ListView.builder(
              padding: EdgeInsets.all(16),
              itemCount: bookingHistory.length,
              itemBuilder: (context, index) {
                final booking = bookingHistory[index];
                return _buildHistoryCard(booking, context);
              },
            ),
          ),
        ],
      ),
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: 3, // History tab selected
        items: [
          BottomNavigationBarItem(icon: Icon(Icons.home), label: 'Home'),
          BottomNavigationBarItem(icon: Icon(Icons.payment), label: 'Payment'),
          BottomNavigationBarItem(icon: Icon(Icons.search), label: 'Search'),
          BottomNavigationBarItem(icon: Icon(Icons.history), label: 'History'),
          BottomNavigationBarItem(icon: Icon(Icons.person), label: 'Profile'),
        ],
      ),
    );
  }

  Widget _buildHistoryCard(Map<String, dynamic> booking, BuildContext context) {
    final dateFormat = DateFormat('MMM dd, yyyy');
    final timeFormat = DateFormat('hh:mm a');

    return Card(
      margin: EdgeInsets.only(bottom: 16),
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(12),
      ),
      elevation: 2,
      child: Padding(
        padding: EdgeInsets.all(12),
        child: Column(
          children: [
            Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Hotel Image
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
                        child: Icon(Icons.broken_image),
                      );
                    },
                  ),
                ),
                SizedBox(width: 12),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      // Hotel Name
                      Text(
                        booking['hotelName'],
                        style: TextStyle(
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      SizedBox(height: 4),
                      
                      // Booking Date and Time
                      Text(
                        '${dateFormat.format(booking['date'])} at ${timeFormat.format(booking['date'])}',
                        style: TextStyle(
                          fontSize: 12,
                          color: Colors.grey[600],
                        ),
                      ),
                      SizedBox(height: 8),
                      
                      // Price and Status
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Text(
                            booking['total'],
                            style: TextStyle(
                              fontSize: 16,
                              fontWeight: FontWeight.bold,
                              color: Colors.blue,
                            ),
                          ),
                          Container(
                            padding: EdgeInsets.symmetric(horizontal: 8, vertical: 4),
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
            SizedBox(height: 12),
            
            // Action Buttons
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                // View Receipt Button
                OutlinedButton(
                  onPressed: () => _showReceiptDialog(context, booking),
                  style: OutlinedButton.styleFrom(
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(8),
                    ),
                  ),
                  child: Text('View Receipt'),
                ),
                
                // Status Button
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
                    style: TextStyle(color: Colors.white),
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  // Helper methods for cleaner code
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
    switch (booking['status']) {
      case 'Completed':
        _showPaymentAlert(context, booking);
        break;
      case 'Cancelled':
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('This booking was cancelled'),
            backgroundColor: Colors.red,
          ),
        );
        break;
      case 'Upcoming':
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('This booking is upcoming'),
            backgroundColor: Colors.orange,
          ),
        );
        break;
      default:
        break;
    }
  }

  void _showPaymentAlert(BuildContext context, Map<String, dynamic> booking) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: Text('Payment Confirmed'),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('Your payment for ${booking['hotelName']} has been completed.'),
            SizedBox(height: 8),
            Text('Amount: ${booking['total']}'),
            SizedBox(height: 8),
            Text('Date: ${DateFormat('MMM dd, yyyy').format(booking['date'])}'),
          ],
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: Text('OK'),
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
          padding: EdgeInsets.all(16),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              Icon(Icons.check_circle, color: Colors.green, size: 60),
              SizedBox(height: 16),
              Text(
                'Payment Receipt',
                style: TextStyle(
                  fontSize: 20,
                  fontWeight: FontWeight.bold,
                ),
              ),
              SizedBox(height: 16),
              _buildReceiptItem('Hotel', booking['hotelName']),
              _buildReceiptItem('Date', DateFormat('MMM dd, yyyy').format(booking['date'])),
              _buildReceiptItem('Time', DateFormat('hh:mm a').format(booking['date'])),
              _buildReceiptItem('Status', booking['status']),
              _buildReceiptItem('Amount', booking['total']),
              SizedBox(height: 24),
              ElevatedButton(
                onPressed: () => Navigator.pop(context),
                child: Text('Close'),
                style: ElevatedButton.styleFrom(
                  minimumSize: Size(double.infinity, 50),
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
      padding: EdgeInsets.symmetric(vertical: 8),
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
            style: TextStyle(
              fontSize: 16,
            ),
          ),
        ],
      ),
    );
  }
}