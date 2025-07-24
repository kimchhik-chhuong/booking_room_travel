import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

class UserService {
  static const String _currentUserKey = 'current_user';
  static const String _accessTokenKey = 'access_token';

  static const String baseUrl = 'http://localhost:8000/api'; // Change to your API base URL

  // Register a new user
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
        // Registration successful
        return true;
      } else if (response.statusCode == 409) {
        // User already exists
        print('User already exists. Please login instead.');
        return false;
      } else {
        // Try to parse validation errors from response body
        try {
          final Map<String, dynamic> errorData = jsonDecode(response.body);
          if (errorData.containsKey('errors')) {
            final errors = errorData['errors'] as Map<String, dynamic>;
            final errorMessages = errors.values
                .map((e) => (e as List).join(', '))
                .join('\n');
            print('Registration validation errors: $errorMessages');
          } else if (errorData.containsKey('message')) {
            print('Registration error message: ${errorData['message']}');
          } else {
            print('Registration failed: ${response.body}');
          }
        } catch (e) {
          print('Error parsing registration error response: $e');
          print('Raw response: ${response.body}');
        }
        return false;
      }
    } catch (e) {
      print('Registration error: $e');
      return false;
    }
  }

  // Login user
  static Future<Map<String, dynamic>?> loginUser({
    required String email,
    required String password,
  }) async {
    try {
      final response = await http.post(
        Uri.parse("$baseUrl/login"),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({
          'email': email,
          'password': password,
        }),
      );

      print('Login response status: ${response.statusCode}');
      print('Login response body: ${response.body}');

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        final user = data['user'];
        final token = data['access_token'];

        final prefs = await SharedPreferences.getInstance();
        await prefs.setString(_currentUserKey, jsonEncode(user));
        await prefs.setString(_accessTokenKey, token);

        return user;
      } else {
        print('Login failed: ${response.body}');
        return null;
      }
    } catch (e) {
      print('Login error: $e');
      return null;
    }
  }

  // Get current logged in user
  static Future<Map<String, dynamic>?> getCurrentUser() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      String? userJson = prefs.getString(_currentUserKey);

      if (userJson != null) {
        return jsonDecode(userJson);
      }

      return null;
    } catch (e) {
      print('Get current user error: $e');
      return null;
    }
  }

  // Logout user
  static Future<void> logoutUser() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      await prefs.remove(_currentUserKey);
      await prefs.remove(_accessTokenKey);
    } catch (e) {
      print('Logout error: $e');
    }
  }

  // Check if user is logged in
  static Future<bool> isLoggedIn() async {
    Map<String, dynamic>? user = await getCurrentUser();
    return user != null;
  }

  static Future updateUserProfile({required name, required email, required profileImageUrl, required followingCount}) async {}

  static Future<void> updateUser(Map<String, dynamic>? currentUser) async {}
}
