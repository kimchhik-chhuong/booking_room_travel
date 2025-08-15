import 'dart:convert';
import 'package:http/http.dart' as http;
import '../models/room_type_model.dart';
import 'api_service.dart';

class RoomService {
  static const String baseUrl = 'http://localhost:8000/api';

  // Get all room types with optional filters
  static Future<List<RoomType>> fetchRoomTypes({
    int? hotelId,
    double? minPrice,
    double? maxPrice,
    int? maxOccupancy,
    bool? availableOnly,
  }) async {
    try {
      String endpoint = 'roomtypes';
      List<String> queryParams = [];

      if (hotelId != null) queryParams.add('hotel_id=$hotelId');
      if (minPrice != null) queryParams.add('min_price=$minPrice');
      if (maxPrice != null) queryParams.add('max_price=$maxPrice');
      if (maxOccupancy != null) queryParams.add('max_occupancy=$maxOccupancy');
      if (availableOnly == true) queryParams.add('available_only=1');

      if (queryParams.isNotEmpty) {
        endpoint += '?${queryParams.join('&')}';
      }

      final response = await ApiService.get(endpoint);

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        final roomsData = data['data'] as List;
        return roomsData.map((json) => RoomType.fromJson(json)).toList();
      } else {
        throw Exception('Failed to load room types: ${response.statusCode}');
      }
    } catch (e) {
      throw Exception('Error fetching room types: $e');
    }
  }

  // Get room types for a specific hotel
  static Future<List<RoomType>> fetchRoomTypesByHotel(int hotelId) async {
    try {
      final response = await ApiService.get('hotels/$hotelId/roomtypes');

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        final roomsData = data['data'] as List;
        return roomsData.map((json) => RoomType.fromJson(json)).toList();
      } else {
        throw Exception('Failed to load hotel room types: ${response.statusCode}');
      }
    } catch (e) {
      throw Exception('Error fetching hotel room types: $e');
    }
  }

  // Get specific room type details
  static Future<RoomType> fetchRoomTypeById(int roomTypeId) async {
    try {
      final response = await ApiService.get('roomtypes/$roomTypeId');

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        return RoomType.fromJson(data['data']);
      } else {
        throw Exception('Failed to load room type: ${response.statusCode}');
      }
    } catch (e) {
      throw Exception('Error fetching room type: $e');
    }
  }

  // Check room availability for specific dates
  static Future<Map<String, dynamic>> checkAvailability({
    required int roomTypeId,
    required DateTime checkInDate,
    required DateTime checkOutDate,
    required int roomsNeeded,
  }) async {
    try {
      final data = {
        'check_in_date': checkInDate.toIso8601String().split('T')[0],
        'check_out_date': checkOutDate.toIso8601String().split('T')[0],
        'rooms_needed': roomsNeeded,
      };

      final response = await ApiService.post('roomtypes/$roomTypeId/check-availability', data);

      if (response.statusCode == 200) {
        final responseData = json.decode(response.body);
        return responseData['data'];
      } else {
        throw Exception('Failed to check availability: ${response.statusCode}');
      }
    } catch (e) {
      throw Exception('Error checking availability: $e');
    }
  }

  // Update room availability (for booking operations)
  static Future<bool> updateAvailability({
    required int roomTypeId,
    required int roomsBooked,
    required String operation, // 'book' or 'cancel'
  }) async {
    try {
      final data = {
        'rooms_booked': roomsBooked,
        'operation': operation,
      };

      final response = await http.patch(
        Uri.parse('$baseUrl/roomtypes/$roomTypeId/availability'),
        headers: await ApiService.headers,
        body: json.encode(data),
      );

      if (response.statusCode == 200) {
        return true;
      } else {
        throw Exception('Failed to update availability: ${response.statusCode}');
      }
    } catch (e) {
      throw Exception('Error updating availability: $e');
    }
  }
}
