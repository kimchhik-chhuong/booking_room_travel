import 'dart:convert';
import 'package:http/http.dart' as http;
import '../models/hotel_model.dart';

class HotelService {
  static const String baseUrl = 'http://localhost:8000/api';

  Future<List<Hotel>> fetchHotels({
    int? provinceId,
    int? adventureId,
    String? searchQuery,
    double? minPrice,
    double? maxPrice,
  }) async {
    String url = '$baseUrl/hotelmetadata';
    List<String> params = [];

    if (provinceId != null) params.add('province_id=$provinceId');
    if (adventureId != null) params.add('adventure_id=$adventureId');
    if (searchQuery != null && searchQuery.isNotEmpty)
      params.add('q=$searchQuery');
    if (minPrice != null) params.add('min_price=$minPrice');
    if (maxPrice != null) params.add('max_price=$maxPrice');

    if (params.isNotEmpty) {
      url += '?${params.join('&')}';
    }

    final response = await http.get(Uri.parse(url));

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      return List<Hotel>.from(data['data'].map((x) => Hotel.fromJson(x)));
    } else {
      throw Exception('Failed to load hotels: ${response.statusCode}');
    }
  }

  Future<Hotel> fetchHotelDetails(int hotelId) async {
    final response =
        await http.get(Uri.parse('$baseUrl/hotelmetadata/$hotelId'));

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      return Hotel.fromJson(data['data']);
    } else {
      throw Exception('Failed to load hotel details: ${response.statusCode}');
    }
  }

  Future<List<RoomType>> fetchRoomTypes(int hotelId) async {
    final response =
        await http.get(Uri.parse('$baseUrl/roomtypes?hotel_id=$hotelId'));

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      return List<RoomType>.from(data['data'].map((x) => RoomType.fromJson(x)));
    } else {
      throw Exception('Failed to load room types: ${response.statusCode}');
    }
  }

  Future<RoomType> createRoomType(RoomType roomType) async {
    final response = await http.post(
      Uri.parse('$baseUrl/roomtypes'),
      headers: {'Content-Type': 'application/json'},
      body: json.encode(roomType.toJson()),
    );

    if (response.statusCode == 201) {
      final data = json.decode(response.body);
      return RoomType.fromJson(data['data']);
    } else {
      throw Exception('Failed to create room type: ${response.statusCode}');
    }
  }
}
