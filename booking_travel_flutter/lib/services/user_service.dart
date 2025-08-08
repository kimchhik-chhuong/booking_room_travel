import 'dart:convert';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

class UserService {
  static const String _currentUserKey = 'current_user';
  static const String _accessTokenKey = 'access_token';

  static final String baseUrl =
      dotenv.env['API_URL'] ?? 'http://localhost:8000/api';

  /// Register
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

  /// Login
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

      print("Login status: ${response.statusCode}");
      print("Login body: ${response.body}");

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        final prefs = await SharedPreferences.getInstance();
        await prefs.setString(_accessTokenKey, data['access_token']);
        await prefs.setString(_currentUserKey, jsonEncode(data['user']));
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

  /// Get Current User
  static Future<Map<String, dynamic>?> getCurrentUser() async {
    final prefs = await SharedPreferences.getInstance();
    final userJson = prefs.getString(_currentUserKey);
    if (userJson != null) {
      return jsonDecode(userJson);
    }
    return null;
  }

  /// Get Access Token
  static Future<String?> getAccessToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString(_accessTokenKey);
  }

  /// Logout
  static Future<void> logoutUser() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove(_currentUserKey);
    await prefs.remove(_accessTokenKey);
  }

  /// Check Login Status
  static Future<bool> isLoggedIn() async {
    final user = await getCurrentUser();
    return user != null;
  }

  /// Placeholder: Update profile
  static Future<void> updateUserProfile({
    required String name,
    required String email,
    String? profileImageUrl,
    int? followingCount,
  }) async {
    print("Update profile (not implemented yet)");
  }

  /// Placeholder: Save new user data
  static Future<void> updateUser(Map<String, dynamic>? currentUser) async {
    final prefs = await SharedPreferences.getInstance();
    if (currentUser != null) {
      await prefs.setString(_currentUserKey, jsonEncode(currentUser));
    }
  }
}
