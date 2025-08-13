import 'package:http/http.dart' as http;
import 'dart:convert';
import '../models/hotel_model.dart';

class HotelService {
  static const String baseUrl = 'http://localhost:8000/api';

  static Future<List<Hotel>> fetchHotelsByProvince(int provinceId) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/hotelmetadata?province_id=$provinceId'),
      );

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        final hotelsData = data['data'] as List;
        return hotelsData.map((json) => Hotel.fromJson(json)).toList();
      } else {
        throw Exception('Failed to load hotels: ${response.statusCode}');
      }
    } catch (e) {
      throw Exception('Error fetching hotels: $e');
    }
  }

  static Future<List<Hotel>> fetchHotelsByAdventure(int adventureId) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/hotelmetadata?adventure_id=$adventureId'),
      );

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        final hotelsData = data['data'] as List;
        return hotelsData.map((json) => Hotel.fromJson(json)).toList();
      } else {
        throw Exception('Failed to load hotels: ${response.statusCode}');
      }
    } catch (e) {
      throw Exception('Error fetching hotels: $e');
    }
  }

  static Future<Hotel> fetchHotelById(int hotelId) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/hotelmetadata/$hotelId'),
      );

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        return Hotel.fromJson(data['data']);
      } else {
        throw Exception('Failed to load hotel: ${response.statusCode}');
      }
    } catch (e) {
      throw Exception('Error fetching hotel: $e');
    }
  }
}
