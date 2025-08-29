import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

class User {
  final String id;
  final String name;
  final String email;

  User({
    required this.id,
    required this.name,
    required this.email,
  });
}

class AuthProvider with ChangeNotifier {
  User? _user;
  bool _isLoading = false;
  String? _error;
  bool _isInitialized = false;
  final SharedPreferences _prefs;

  AuthProvider(this._prefs) {
    _loadUserFromPrefs();
  }

  User? get user => _user;
  bool get isAuthenticated => _user != null;
  bool get isLoading => _isLoading;
  String? get error => _error;
  bool get isInitialized => _isInitialized;

  Future<void> _loadUserFromPrefs() async {
    try {
      _isLoading = true;
      final token = _prefs.getString('auth_token');
      
      if (token != null) {
        // If you have an endpoint to get user data, call it here
        // For now, we'll just create a basic user from stored data
        _user = User(
          id: _prefs.getString('user_id') ?? '',
          name: _prefs.getString('user_name') ?? 'User',
          email: _prefs.getString('user_email') ?? '',
        );
      }
    } catch (e) {
      print('Error loading user from prefs: $e');
    } finally {
      _isInitialized = true;
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<void> initialize() async {
    try {
      _isLoading = true;
      _error = null;
      
      final token = _prefs.getString('auth_token');
      if (token != null) {
        // If you have a user info endpoint, call it here
        // For now, we'll just load from prefs
        _user = User(
          id: _prefs.getString('user_id') ?? '',
          name: _prefs.getString('user_name') ?? 'User',
          email: _prefs.getString('user_email') ?? '',
        );
      }
    } catch (e) {
      _error = 'Failed to initialize authentication';
      print('AuthProvider initialization error: $e');
    } finally {
      _isInitialized = true;
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<bool> login(String email, String password) async {
    try {
      _isLoading = true;
      _error = null;
      notifyListeners();

      final response = await http.post(
        Uri.parse('http://localhost:8000/api/login'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: json.encode({
          'email': email,
          'password': password,
        }),
      );

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        
        // Save token and user data to SharedPreferences
        await _prefs.setString('auth_token', data['access_token']);
        await _prefs.setString('user_id', data['user']['id'].toString());
        await _prefs.setString('user_name', data['user']['name']);
        await _prefs.setString('user_email', data['user']['email']);
        
        // Update the current user
        _user = User(
          id: data['user']['id'].toString(),
          name: data['user']['name'],
          email: data['user']['email'],
        );
        
        _isLoading = false;
        notifyListeners();
        return true;
      } else {
        final errorData = json.decode(response.body);
        _error = errorData['message'] ?? 'Login failed';
        _isLoading = false;
        notifyListeners();
        return false;
      }
    } catch (e) {
      _error = 'An error occurred during login';
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }

  Future<void> logout() async {
    await _prefs.remove('auth_token');
    await _prefs.remove('user_id');
    await _prefs.remove('user_name');
    await _prefs.remove('user_email');
    _user = null;
    _isInitialized = true;
    notifyListeners();
  }
}

// Helper extension to access the provider
extension AuthProviderExt on BuildContext {
  AuthProvider get authProvider => Provider.of<AuthProvider>(this, listen: false);
}
