import 'dart:convert';
import 'package:http/http.dart' as http;
import '../models/hotel_booking_model.dart';
import 'api_service.dart';

class BookingService {
  static const String baseUrl = 'http://localhost:8000/api';

  // Get all hotel bookings with optional filters
  static Future<List<HotelBooking>> fetchHotelBookings({
    int? userId,
    int? hotelId,
    String? status,
    DateTime? checkInFrom,
    DateTime? checkInTo,
  }) async {
    try {
      String endpoint = 'hotelbooking';
      List<String> queryParams = [];

      if (userId != null) queryParams.add('user_id=$userId');
      if (hotelId != null) queryParams.add('hotel_id=$hotelId');
      if (status != null) queryParams.add('status=$status');
      if (checkInFrom != null) {
        queryParams.add('check_in_from=${checkInFrom.toIso8601String().split('T')[0]}');
      }
      if (checkInTo != null) {
        queryParams.add('check_in_to=${checkInTo.toIso8601String().split('T')[0]}');
      }

      if (queryParams.isNotEmpty) {
        endpoint += '?${queryParams.join('&')}';
      }

      final response = await ApiService.get(endpoint);

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        final bookingsData = data['data'] as List;
        return bookingsData.map((json) => HotelBooking.fromJson(json)).toList();
      } else {
        throw Exception('Failed to load hotel bookings: ${response.statusCode}');
      }
    } catch (e) {
      throw Exception('Error fetching hotel bookings: $e');
    }
  }

  // Get hotel bookings for a specific user
  static Future<List<HotelBooking>> fetchUserBookings(int userId) async {
    try {
      final response = await ApiService.get('users/$userId/hotelbookings');

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        final bookingsData = data['data'] as List;
        return bookingsData.map((json) => HotelBooking.fromJson(json)).toList();
      } else {
        throw Exception('Failed to load user bookings: ${response.statusCode}');
      }
    } catch (e) {
      throw Exception('Error fetching user bookings: $e');
    }
  }

  // Get specific hotel booking details
  static Future<HotelBooking> fetchHotelBookingById(int bookingId) async {
    try {
      final response = await ApiService.get('hotelbooking/$bookingId');

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        return HotelBooking.fromJson(data['data']);
      } else {
        throw Exception('Failed to load hotel booking: ${response.statusCode}');
      }
    } catch (e) {
      throw Exception('Error fetching hotel booking: $e');
    }
  }

  // Create a new hotel booking
  static Future<HotelBooking> createHotelBooking({
    required int bookingId,
    required int hotelId,
    required DateTime checkInDate,
    required DateTime checkOutDate,
    required int roomTypeId,
    required int numRooms,
    required int numGuests,
    required double pricePerNight,
    required double totalHotelPrice,
    String status = 'pending',
  }) async {
    try {
      final data = {
        'booking_id': bookingId,
        'hotel_id': hotelId,
        'check_in_date': checkInDate.toIso8601String().split('T')[0],
        'check_out_date': checkOutDate.toIso8601String().split('T')[0],
        'room_type': roomTypeId,
        'num_rooms': numRooms,
        'num_guests': numGuests,
        'price_per_night': pricePerNight,
        'total_hotel_price': totalHotelPrice,
        'status': status,
      };

      final response = await ApiService.post('hotelbooking', data);

      if (response.statusCode == 201) {
        final responseData = json.decode(response.body);
        return HotelBooking.fromJson(responseData['data']);
      } else {
        final errorData = json.decode(response.body);
        throw Exception(errorData['message'] ?? 'Failed to create booking');
      }
    } catch (e) {
      throw Exception('Error creating hotel booking: $e');
    }
  }

  // Update an existing hotel booking
  static Future<HotelBooking> updateHotelBooking({
    required int bookingId,
    int? hotelId,
    DateTime? checkInDate,
    DateTime? checkOutDate,
    int? roomTypeId,
    int? numRooms,
    int? numGuests,
    double? pricePerNight,
    double? totalHotelPrice,
    String? status,
  }) async {
    try {
      final data = <String, dynamic>{};
      
      if (hotelId != null) data['hotel_id'] = hotelId;
      if (checkInDate != null) data['check_in_date'] = checkInDate.toIso8601String().split('T')[0];
      if (checkOutDate != null) data['check_out_date'] = checkOutDate.toIso8601String().split('T')[0];
      if (roomTypeId != null) data['room_type'] = roomTypeId;
      if (numRooms != null) data['num_rooms'] = numRooms;
      if (numGuests != null) data['num_guests'] = numGuests;
      if (pricePerNight != null) data['price_per_night'] = pricePerNight;
      if (totalHotelPrice != null) data['total_hotel_price'] = totalHotelPrice;
      if (status != null) data['status'] = status;

      final response = await http.put(
        Uri.parse('$baseUrl/hotelbooking/$bookingId'),
        headers: await ApiService.headers,
        body: json.encode(data),
      );

      if (response.statusCode == 200) {
        final responseData = json.decode(response.body);
        return HotelBooking.fromJson(responseData['data']);
      } else {
        final errorData = json.decode(response.body);
        throw Exception(errorData['message'] ?? 'Failed to update booking');
      }
    } catch (e) {
      throw Exception('Error updating hotel booking: $e');
    }
  }

  // Cancel a hotel booking
  static Future<HotelBooking> cancelHotelBooking(int bookingId) async {
    try {
      final response = await http.patch(
        Uri.parse('$baseUrl/hotelbooking/$bookingId/cancel'),
        headers: await ApiService.headers,
      );

      if (response.statusCode == 200) {
        final responseData = json.decode(response.body);
        return HotelBooking.fromJson(responseData['data']);
      } else {
        final errorData = json.decode(response.body);
        throw Exception(errorData['message'] ?? 'Failed to cancel booking');
      }
    } catch (e) {
      throw Exception('Error cancelling hotel booking: $e');
    }
  }

  // Delete a hotel booking
  static Future<bool> deleteHotelBooking(int bookingId) async {
    try {
      final response = await http.delete(
        Uri.parse('$baseUrl/hotelbooking/$bookingId'),
        headers: await ApiService.headers,
      );

      if (response.statusCode == 200) {
        return true;
      } else {
        throw Exception('Failed to delete booking: ${response.statusCode}');
      }
    } catch (e) {
      throw Exception('Error deleting hotel booking: $e');
    }
  }
}
