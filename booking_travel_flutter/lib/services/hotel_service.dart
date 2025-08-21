import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:booking_travel/services/api_service.dart';
import '../models/hotel_model.dart';

class HotelService {
  static const String baseUrl = 'http://192.168.108.135:8000/api';

  static Future<List<Hotel>> fetchHotelsByProvince(int provinceId) async {
    try {
      final response = await ApiService.get('hotelmetadata?province_id=$provinceId');
      
      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        if (data is Map && data.containsKey('data')) {
          final hotelsData = data['data'] as List;
          return hotelsData.map((json) => Hotel.fromJson(json)).toList();
        } else {
          // Handle case where response doesn't have 'data' key
          return [];
        }
      } else {
        throw Exception('Failed to load hotels: ${response.statusCode}');
      }
    } catch (e) {
      print('Error in fetchHotelsByProvince: $e');
      throw Exception('Error fetching hotels: $e');
    }
  }

  static Future<List<Hotel>> fetchHotelsByAdventure(int adventureId) async {
    try {
      final response = await ApiService.get('hotelmetadata?adventure_id=$adventureId');

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        
        // Check if the response has the expected paginated structure
        if (data is Map && data.containsKey('data') && data['data'] is Map) {
          final responseData = data['data'];
          
          // Extract the hotels list from the paginated response
          if (responseData.containsKey('data') && responseData['data'] is List) {
            final hotelsData = responseData['data'] as List;
            return hotelsData.map((json) => Hotel.fromJson(json)).toList();
          }
        }
        
        // Fallback to handle other response formats
        if (data is List) {
          return data.map((json) => Hotel.fromJson(json)).toList();
        } else if (data is Map && data.containsKey('data') && data['data'] is List) {
          return (data['data'] as List).map((json) => Hotel.fromJson(json)).toList();
        }
        
        print('Unexpected response format: $data');
        return [];
      } else {
        throw Exception('Failed to load hotels: ${response.statusCode}');
      }
    } catch (e) {
      print('Error in fetchHotelsByAdventure: $e');
      throw Exception('Error fetching hotels: $e');
    }
  }

  static Future<Hotel> fetchHotelById(int hotelId) async {
    try {
      final response = await ApiService.get('hotelmetadata/$hotelId');

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        if (data is Map && data.containsKey('data')) {
          return Hotel.fromJson(data['data']);
        } else {
          throw Exception('Invalid response format');
        }
      } else {
        throw Exception('Failed to load hotel: ${response.statusCode}');
      }
    } catch (e) {
      print('Error in fetchHotelById: $e');
      throw Exception('Error fetching hotel: $e');
    }
  }
}
