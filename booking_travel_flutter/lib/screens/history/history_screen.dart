import 'dart:io';
import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

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

  Future<String?> _getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('token'); // Make sure the token key matches your login storage
  }

  Future<void> fetchBookings() async {
    final String baseUrl = Platform.isAndroid ? 'http://10.0.2.2:8000' : 'http://127.0.0.1:8000';
    final uri = Uri.parse('$baseUrl/api/booking-history');

    final token = await _getToken();

    if (token == null || token.isEmpty) {
      setState(() {
        _isLoading = false;
      });
      print('Token not found');
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
        final List<dynamic> bookingList = responseData['data'] ?? [];

        setState(() {
          _bookings = bookingList.map((json) => Booking.fromJson(json)).toList();
          _isLoading = false;
        });
      } else {
        print('Error: ${response.statusCode}');
        print('Body: ${response.body}');
        setState(() => _isLoading = false);
      }
    } catch (e) {
      print('Fetch error: $e');
      setState(() => _isLoading = false);
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
                fontSize: 24,
                fontWeight: FontWeight.bold,
                color: Colors.black87,
              ),
            ),
            const SizedBox(height: 16),
            Expanded(
              child: RefreshIndicator(
                onRefresh: fetchBookings,
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
            ),
          ],
        ),
      ),
    );
  }
}

class BookingCard extends StatelessWidget {
  final Booking booking;

  const BookingCard({super.key, required this.booking});

  @override
  Widget build(BuildContext context) {
    return Card(
      elevation: 4,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
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
                color: Colors.blueAccent,
              ),
            ),
            const SizedBox(height: 8),
            _buildInfoRow(Icons.location_on, booking.location),
            _buildInfoRow(Icons.calendar_month, '${booking.startDate} → ${booking.endDate}'),
            _buildInfoRow(Icons.confirmation_number, 'Booking ID: ${booking.bookingId}'),
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
                    color: Colors.blueAccent,
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildInfoRow(IconData icon, String text) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        children: [
          Icon(icon, size: 18, color: Colors.grey),
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
      hotelName: json['hotel_name'] ?? 'Unknown Hotel',
      location: json['location'] ?? 'Unknown Location',
      startDate: json['start_date'] ?? '',
      endDate: json['end_date'] ?? '',
      bookingId: json['booking_id'] ?? '',
      totalPrice: (json['total_price'] ?? 0).toDouble(),
    );
  }
}
