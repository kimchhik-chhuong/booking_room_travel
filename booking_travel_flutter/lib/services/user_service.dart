import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';

class UserService {
  static const String _usersKey = 'registered_users';
  static const String _currentUserKey = 'current_user';

  // Register a new user
  static Future<bool> registerUser({
    required String name,
    required String email,
    required String password,
  }) async {
    try {
      final prefs = await SharedPreferences.getInstance();
      
      // Get existing users
      List<Map<String, dynamic>> users = await getRegisteredUsers();
      
      // Check if user already exists
      bool userExists = users.any((user) => user['email'] == email);
      if (userExists) {
        return false; // User already exists
      }
      
      // Add new user
      Map<String, dynamic> newUser = {
        'name': name,
        'email': email,
        'password': password, // In real app, hash this password
        'registeredAt': DateTime.now().toIso8601String(),
      };
      
      users.add(newUser);
      
      // Save users
      String usersJson = jsonEncode(users);
      await prefs.setString(_usersKey, usersJson);
      
      return true;
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
      List<Map<String, dynamic>> users = await getRegisteredUsers();
      
      // Find user with matching email and password
      for (Map<String, dynamic> user in users) {
        if (user['email'] == email && user['password'] == password) {
          // Save current user
          final prefs = await SharedPreferences.getInstance();
          await prefs.setString(_currentUserKey, jsonEncode(user));
          return user;
        }
      }
      
      return null; // Login failed
    } catch (e) {
      print('Login error: $e');
      return null;
    }
  }

  // Get all registered users
  static Future<List<Map<String, dynamic>>> getRegisteredUsers() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      String? usersJson = prefs.getString(_usersKey);
      
      if (usersJson != null) {
        List<dynamic> usersList = jsonDecode(usersJson);
        return usersList.cast<Map<String, dynamic>>();
      }
      
      return [];
    } catch (e) {
      print('Get users error: $e');
      return [];
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
    } catch (e) {
      print('Logout error: $e');
    }
  }

  // Check if user is logged in
  static Future<bool> isLoggedIn() async {
    Map<String, dynamic>? user = await getCurrentUser();
    return user != null;
  }
}