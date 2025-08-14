import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:booking_travel/models/booking.dart';

class BookingService {
  Future<List<Booking>> getUserBookings() async {
    try {
      // Replace with your actual API endpoint
      final response = await http.get(Uri.parse('https://your-api.com/bookings'));
      
      if (response.statusCode == 200) {
        final List<dynamic> data = json.decode(response.body);
        return data.map((json) => Booking.fromJson(json)).toList();
      } else {
        throw Exception('Failed to load bookings');
      }
    } catch (e) {
      throw Exception('Error fetching bookings: $e');
    }
  }

  Future<void> cancelBooking(String id) async {
    try {
      final response = await http.post(
        Uri.parse('https://your-api.com/cancel'),
        body: json.encode({'id': id}),
      );
      
      if (response.statusCode != 200) {
        throw Exception('Failed to cancel booking');
      }
    } catch (e) {
      throw Exception('Error cancelling booking: $e');
    }
  }
}