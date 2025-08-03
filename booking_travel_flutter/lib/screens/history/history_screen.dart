import 'dart:io';
import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:flutter/material.dart';

// Assuming Booking model is defined in the same file or a correct path.
// If it's in a different file, adjust the import.
// import 'package:travel_app/models/booking.dart';

class HistoryScreen extends StatefulWidget {
  const HistoryScreen({super.key});

  @override
  State<HistoryScreen> createState() => _HistoryScreenState();
}

class _HistoryScreenState extends State<HistoryScreen> {
  List<Booking> _bookings = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    fetchBookings();
  }

  Future<void> fetchBookings() async {
    // Determine the correct base URL based on the platform.
    final String baseUrl = Platform.isAndroid ? 'http://10.0.2.2:8000' : 'http://127.0.0.1:8000';
    final uri = Uri.parse('$baseUrl/api/booking-history');

    // Replace this with your actual method for getting the Sanctum token.
    // For example, from a state management solution or shared preferences.
    final token = 'YOUR_SANCTUM_TOKEN_HERE';

    if (token.isEmpty || token == 'YOUR_SANCTUM_TOKEN_HERE') {
      setState(() {
        _isLoading = false;
      });
      print('Authentication token is missing. Cannot fetch booking history.');
      return;
    }

    try {
      final response = await http.get(
        uri,
        headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        },
      );

      if (response.statusCode == 200) {
        final Map<String, dynamic> responseData = jsonDecode(response.body);
        final List<dynamic> bookingList = responseData['data'];

        setState(() {
          _bookings = bookingList.map((json) => Booking.fromJson(json)).toList();
          _isLoading = false;
        });
      } else {
        print('Failed to load bookings. Status code: ${response.statusCode}');
        print('Response body: ${response.body}');
        setState(() {
          _isLoading = false;
        });
      }
    } catch (e) {
      print('Error fetching bookings: $e');
      setState(() {
        _isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Travel History'),
        backgroundColor: Colors.blueAccent,
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Your Booking History',
              style: TextStyle(
                fontSize: 24.0,
                fontWeight: FontWeight.bold,
                color: Colors.black87,
              ),
            ),
            const SizedBox(height: 16.0),
            Expanded(
              child: _isLoading
                  ? const Center(child: CircularProgressIndicator())
                  : _bookings.isEmpty
                      ? const Center(child: Text('No travel history found.'))
                      : ListView.builder(
                          itemCount: _bookings.length,
                          itemBuilder: (context, index) {
                            final booking = _bookings[index];
                            return BookingCard(booking: booking);
                          },
                        ),
            ),
          ],
        ),
      ),
    );
  }
}

// Assuming BookingCard and Booking models are defined elsewhere or in the same file.
// Here is a sample BookingCard widget for completeness, matching the image.
class BookingCard extends StatelessWidget {
  final Booking booking;

  const BookingCard({
    Key? key,
    required this.booking,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Card(
      elevation: 4,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(15)),
      margin: const EdgeInsets.symmetric(vertical: 8),
      child: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              booking.hotelName,
              style: const TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
                color: Colors.blue,
              ),
            ),
            const SizedBox(height: 8),
            _buildIconText(Icons.location_on, booking.location),
            _buildIconText(
                Icons.calendar_today,
                '${booking.startDate} - ${booking.endDate}'),
            _buildIconText(
                Icons.confirmation_number, 'Booking ID: ${booking.bookingId}'),
            const Divider(),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                const Text(
                  'Total Price:',
                  style: TextStyle(fontSize: 16),
                ),
                Text(
                  '\$${booking.totalPrice.toStringAsFixed(2)}',
                  style: const TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.bold,
                    color: Colors.blue,
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildIconText(IconData icon, String text) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4.0),
      child: Row(
        children: [
          Icon(icon, size: 16, color: Colors.grey),
          const SizedBox(width: 8),
          Expanded(child: Text(text, style: const TextStyle(fontSize: 14))),
        ],
      ),
    );
  }
}

class Booking {
  final String hotelName;
  final String location;
  final String startDate;
  final String endDate;
  final String bookingId;
  final double totalPrice;

  Booking({
    required this.hotelName,
    required this.location,
    required this.startDate,
    required this.endDate,
    required this.bookingId,
    required this.totalPrice,
  });

  factory Booking.fromJson(Map<String, dynamic> json) {
    return Booking(
      hotelName: json['hotel_name'] ?? 'N/A',
      location: json['location'] ?? 'N/A',
      // Check for both 'travel_date' and 'booking_date' as possible start dates
      startDate: json['travel_date'] ?? json['booking_date'] ?? '',
      // Add an end date if it exists, or leave it as an empty string
      endDate: json['end_date'] ?? '',
      bookingId: json['booking_id'] ?? 'N/A',
      totalPrice: (json['total_amount'] as num?)?.toDouble() ?? 0.0,
    );
  }
}
