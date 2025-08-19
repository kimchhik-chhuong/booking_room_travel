import 'dart:convert';
import 'dart:io';
import 'package:http/http.dart' as http;
import 'package:http_parser/http_parser.dart';

class ApiService {
  static const String baseUrl = 'http://192.168.108.135:8000/api';

  static Future<Map<String, String>> get headers async {
    return {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
    };
  }

  static Future<http.Response> get(String endpoint) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/$endpoint'),
        headers: await headers,
      );
      return response;
    } catch (e) {
      throw Exception('Failed to fetch data: $e');
    }
  }

  static Future<http.Response> post(
      String endpoint, Map<String, dynamic> data) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/$endpoint'),
        headers: await headers,
        body: json.encode(data),
        encoding: Encoding.getByName('utf-8'),
      );
      return response;
    } catch (e) {
      throw Exception('Failed to post data: $e');
    }
  }

  // New method for multipart form data with image upload
  static Future<http.Response> postWithImage(
    String endpoint,
    Map<String, dynamic> data,
    File? imageFile,
  ) async {
    try {
      final request = http.MultipartRequest(
        'POST',
        Uri.parse('$baseUrl/$endpoint'),
      );

      // Add text fields
      data.forEach((key, value) {
        request.fields[key] = value.toString();
      });

      // Add image file if provided
      if (imageFile != null) {
        request.files.add(
          await http.MultipartFile.fromPath(
            'image',
            imageFile.path,
            contentType: MediaType('image', 'jpeg'),
          ),
        );
      }

      final streamedResponse = await request.send();
      final response = await http.Response.fromStream(streamedResponse);
      return response;
    } catch (e) {
      throw Exception('Failed to upload data: $e');
    }
  }

  // New method for updating with image
  static Future<http.Response> putWithImage(
    String endpoint,
    Map<String, dynamic> data,
    File? imageFile,
  ) async {
    try {
      final request = http.MultipartRequest(
        'POST', // Using POST with _method=PUT for Laravel
        Uri.parse('$baseUrl/$endpoint'),
      );

      request.fields['_method'] = 'PUT';

      // Add text fields
      data.forEach((key, value) {
        request.fields[key] = value.toString();
      });

      // Add image file if provided
      if (imageFile != null) {
        request.files.add(
          await http.MultipartFile.fromPath(
            'image',
            imageFile.path,
            contentType: MediaType('image', 'jpeg'),
          ),
        );
      }

      final streamedResponse = await request.send();
      final response = await http.Response.fromStream(streamedResponse);
      return response;
    } catch (e) {
      throw Exception('Failed to update data: $e');
    }
  }
}