import 'dart:convert';
import 'package:http/http.dart' as http;
import '../models/room_type_model.dart';
import 'api_service.dart';
import 'auth_service.dart';

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

      print('Sending request to check availability with:');
      print('Room Type ID: $roomTypeId');
      print('Data: $data');

      final response = await ApiService.post('roomtypes/$roomTypeId/check-availability', data);

      if (response.statusCode == 200) {
        final responseData = json.decode(response.body);
        print('Availability response: $responseData');
        return responseData['data'] ?? responseData;
      } else if (response.statusCode == 422) {
        final errorData = json.decode(response.body);
        print('Validation error: $errorData');
        throw Exception(errorData['message'] ?? 'Invalid request data');
      } else {
        throw Exception('Failed to check availability: ${response.statusCode}');
      }
    } catch (e) {
      print('Error in checkAvailability: $e');
      rethrow;
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

  // Create a new room type for a hotel
  static Future<RoomType> createRoomType({
    required int hotelId,
    required String name,
    required String description,
    required double price,
    required int maxOccupancy,
    required int availableRooms,
    List<String>? amenities,
    String? imagePath,
  }) async {
    try {
      final url = '${ApiService.baseUrl}/hotels/$hotelId/roomtypes';
      
      // Create multipart request for file upload if image is provided
      var request = http.MultipartRequest('POST', Uri.parse(url));
      
      // Add text fields
      request.fields['name'] = name;
      request.fields['description'] = description;
      request.fields['price'] = price.toString();
      request.fields['max_occupancy'] = maxOccupancy.toString();
      request.fields['available_rooms'] = availableRooms.toString();
      
      if (amenities != null && amenities.isNotEmpty) {
        request.fields['amenities'] = jsonEncode(amenities);
      }
      
      // Add image file if provided
      if (imagePath != null && imagePath.isNotEmpty) {
        var file = await http.MultipartFile.fromPath('image', imagePath);
        request.files.add(file);
      }
      
      // Add authorization header if available
      try {
        final token = await AuthService.getToken();
        if (token != null && token.isNotEmpty) {
          request.headers['Authorization'] = 'Bearer $token';
        }
      } catch (e) {
        print('Warning: Could not get auth token: $e');
        // Continue without auth token if it's not available
      }
      
      // Send the request
      final streamedResponse = await request.send();
      final response = await http.Response.fromStream(streamedResponse);
      
      if (response.statusCode == 201) {
        final data = json.decode(response.body);
        return RoomType.fromJson(data['data']);
      } else {
        throw Exception('Failed to create room type: ${response.statusCode} - ${response.body}');
      }
    } catch (e) {
      throw Exception('Error creating room type: $e');
    }
  }
}
