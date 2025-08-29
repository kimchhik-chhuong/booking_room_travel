import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

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

  User? get user => _user;
  bool get isAuthenticated => _user != null;
  bool get isLoading => _isLoading;
  String? get error => _error;

  // For demo purposes - in a real app, this would make an API call
  Future<void> login(String email, String password) async {
    try {
      _isLoading = true;
      _error = null;
      notifyListeners();

      // Simulate API call
      await Future.delayed(const Duration(seconds: 1));
      
      _user = User(
        id: 'demo-user-id',
        name: 'Demo User',
        email: email,
      );
      
    } catch (e) {
      _error = e.toString();
      rethrow;
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  void logout() {
    _user = null;
    notifyListeners();
  }
}

// Helper extension to access the provider
extension AuthProviderExt on BuildContext {
  AuthProvider get authProvider => Provider.of<AuthProvider>(this, listen: false);
}
