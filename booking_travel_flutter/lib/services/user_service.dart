import 'dart:convert';
import 'dart:io';
import 'dart:typed_data';

import 'package:flutter/foundation.dart' show kIsWeb;
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'package:http_parser/http_parser.dart';
import 'package:path/path.dart' as path;

class UserService {
  static const String _currentUserKey = 'current_user';
  static const String _accessTokenKey = 'access_token';

  static final String baseUrl =
      dotenv.env['API_URL'] ?? 'http://localhost:8000/api';

  static late SharedPreferences _prefs;

  // --- Initialize SharedPreferences ---
  static Future<void> init() async {
    _prefs = await SharedPreferences.getInstance();
  }

  // --- Register User ---
  static Future<bool> registerUser({
    required String name,
    required String email,
    required String password,
    String role = 'user',
  }) async {
    try {
      final response = await http.post(
        Uri.parse("$baseUrl/register"),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: jsonEncode({
          'name': name,
          'email': email,
          'password': password,
          'password_confirmation': password,
          'role': role,
        }),
      );

      if (response.statusCode == 201) {
        return true;
      } else {
        final errorData = jsonDecode(response.body);
        print('Register Error: ${errorData['message']}');
        return false;
      }
    } catch (e) {
      print('Register Exception: $e');
      return false;
    }
  }

  // --- Login User ---
  static Future<Map<String, dynamic>?> loginUser({
    required String email,
    required String password,
  }) async {
    try {
      final response = await http.post(
        Uri.parse("$baseUrl/login"),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: jsonEncode({'email': email, 'password': password}),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        await _prefs.setString(_accessTokenKey, data['access_token']);
        await _prefs.setString(_currentUserKey, jsonEncode(data['user']));
        return data['user'];
      } else {
        final error = jsonDecode(response.body);
        print("Login error: ${error['message']}");
        return null;
      }
    } catch (e) {
      print("Login exception: $e");
      return null;
    }
  }

  // --- Get current user ---
  static Future<Map<String, dynamic>?> getCurrentUser() async {
    final userJson = _prefs.getString(_currentUserKey);
    if (userJson != null) {
      return jsonDecode(userJson);
    }
    return null;
  }

  static Future<String?> getAccessToken() async {
    return _prefs.getString(_accessTokenKey);
  }

  static Future<void> logoutUser() async {
    await _prefs.remove(_currentUserKey);
    await _prefs.remove(_accessTokenKey);
  }

  static Future<bool> isLoggedIn() async {
    final user = await getCurrentUser();
    return user != null;
  }

  // --- Update user profile (supports File for mobile & Uint8List for web) ---
  static Future<void> updateUser(
    Map<String, dynamic>? currentUser, {
    File? imageFile,
    Uint8List? imageBytes,
    String? imageFileName,
  }) async {
    if (currentUser == null) return;

    final token = await getAccessToken();
    if (token == null) throw Exception("No access token found");

    var uri = Uri.parse("$baseUrl/profile/update"); // update your backend route
    var request = http.MultipartRequest('POST', uri);

    // Add user fields
    currentUser.forEach((key, value) {
      request.fields[key] = value.toString();
    });

    // Add image
    if (imageFile != null && !kIsWeb) {
      // Mobile: File
      var multipartFile = await http.MultipartFile.fromPath(
        'profile_image',
        imageFile.path,
      );
      request.files.add(multipartFile);
    } else if (imageBytes != null && kIsWeb && imageFileName != null) {
      // Web: Uint8List
      var multipartFile = http.MultipartFile.fromBytes(
        'profile_image',
        imageBytes,
        filename: imageFileName,
        contentType: MediaType('image', path.extension(imageFileName).replaceAll('.', '')),
      );
      request.files.add(multipartFile);
    }

    // Add headers
    request.headers['Authorization'] = 'Bearer $token';
    request.headers['Accept'] = 'application/json';

    // Send request
    var response = await request.send();
    if (response.statusCode == 200) {
      var respStr = await response.stream.bytesToString();
      var data = jsonDecode(respStr);
      await _prefs.setString(_currentUserKey, jsonEncode(data['user']));
    } else {
      var respStr = await response.stream.bytesToString();
      print('Update profile failed: $respStr');
      throw Exception('Failed to update profile');
    }
  }
}
