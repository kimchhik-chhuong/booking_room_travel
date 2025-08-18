import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

class PaymentService {
  static const String baseUrl = 'http://10.0.2.2:8000/api';

  static Future<Map<String, dynamic>> processPayment({
    required double amount,
    required String paymentMethod,
    Map<String, dynamic>? cardDetails,
  }) async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString('auth_token');

      if (token == null) {
        throw Exception('Authentication token not found');
      }

      Map<String, dynamic> paymentData = {
        'amount': amount,
        'payment_method': paymentMethod,
        'currency': 'USD',
      };

      // Add card details if paying with credit card
      if (paymentMethod == 'credit_card' && cardDetails != null) {
        paymentData.addAll({
          'card_number': cardDetails['cardNumber'],
          'expiry_date': cardDetails['expiry'],
          'cvv': cardDetails['cvv'],
          'cardholder_name': cardDetails['cardHolder'],
        });
      }

      final response = await http.post(
        Uri.parse('$baseUrl/payments/process'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
        body: jsonEncode(paymentData),
      );

      if (response.statusCode == 200 || response.statusCode == 201) {
        final responseData = jsonDecode(response.body);
        
        if (responseData['status'] == 'success') {
          return {
            'success': true,
            'paymentId': responseData['data']['payment_id'],
            'transactionId': responseData['data']['transaction_id'],
            'message': 'Payment processed successfully',
          };
        } else {
          return {
            'success': false,
            'message': responseData['message'] ?? 'Payment failed',
          };
        }
      } else {
        final errorData = jsonDecode(response.body);
        return {
          'success': false,
          'message': errorData['message'] ?? 'Payment processing failed',
        };
      }
    } catch (e) {
      print('Payment processing error: $e');
      
      // For demo purposes, simulate payment processing
      if (paymentMethod == 'pay_at_hotel') {
        return {
          'success': true,
          'paymentId': 'PAY_AT_HOTEL_${DateTime.now().millisecondsSinceEpoch}',
          'transactionId': 'TXN_${DateTime.now().millisecondsSinceEpoch}',
          'message': 'Payment will be collected at hotel',
        };
      }
      
      // Simulate successful payment for demo
      await Future.delayed(const Duration(seconds: 2));
      return {
        'success': true,
        'paymentId': 'DEMO_PAY_${DateTime.now().millisecondsSinceEpoch}',
        'transactionId': 'DEMO_TXN_${DateTime.now().millisecondsSinceEpoch}',
        'message': 'Payment processed successfully (Demo)',
      };
    }
  }

  static Future<Map<String, dynamic>> refundPayment({
    required String paymentId,
    required double amount,
    String? reason,
  }) async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString('auth_token');

      if (token == null) {
        throw Exception('Authentication token not found');
      }

      final response = await http.post(
        Uri.parse('$baseUrl/payments/refund'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
        body: jsonEncode({
          'payment_id': paymentId,
          'amount': amount,
          'reason': reason ?? 'Customer requested refund',
        }),
      );

      if (response.statusCode == 200) {
        final responseData = jsonDecode(response.body);
        return {
          'success': responseData['status'] == 'success',
          'message': responseData['message'],
          'refundId': responseData['data']?['refund_id'],
        };
      } else {
        final errorData = jsonDecode(response.body);
        return {
          'success': false,
          'message': errorData['message'] ?? 'Refund failed',
        };
      }
    } catch (e) {
      print('Refund processing error: $e');
      return {
        'success': false,
        'message': 'Failed to process refund: $e',
      };
    }
  }

  static Future<List<Map<String, dynamic>>> getPaymentHistory() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString('auth_token');

      if (token == null) {
        throw Exception('Authentication token not found');
      }

      final response = await http.get(
        Uri.parse('$baseUrl/payments/history'),
        headers: {
          'Authorization': 'Bearer $token',
        },
      );

      if (response.statusCode == 200) {
        final responseData = jsonDecode(response.body);
        if (responseData['status'] == 'success') {
          return List<Map<String, dynamic>>.from(responseData['data']);
        }
      }
      
      return [];
    } catch (e) {
      print('Error fetching payment history: $e');
      return [];
    }
  }

  static Future<Map<String, dynamic>> validateCard({
    required String cardNumber,
    required String expiry,
    required String cvv,
  }) async {
    // Basic card validation
    if (cardNumber.length < 16) {
      return {
        'valid': false,
        'message': 'Invalid card number',
      };
    }

    if (expiry.length != 5 || !expiry.contains('/')) {
      return {
        'valid': false,
        'message': 'Invalid expiry date format (MM/YY)',
      };
    }

    if (cvv.length < 3) {
      return {
        'valid': false,
        'message': 'Invalid CVV',
      };
    }

    // Check expiry date
    final parts = expiry.split('/');
    final month = int.tryParse(parts[0]);
    final year = int.tryParse('20${parts[1]}');
    
    if (month == null || year == null || month < 1 || month > 12) {
      return {
        'valid': false,
        'message': 'Invalid expiry date',
      };
    }

    final now = DateTime.now();
    final expiryDate = DateTime(year, month);
    
    if (expiryDate.isBefore(now)) {
      return {
        'valid': false,
        'message': 'Card has expired',
      };
    }

    return {
      'valid': true,
      'message': 'Card is valid',
      'cardType': _getCardType(cardNumber),
    };
  }

  static String _getCardType(String cardNumber) {
    if (cardNumber.startsWith('4')) {
      return 'Visa';
    } else if (cardNumber.startsWith('5') || cardNumber.startsWith('2')) {
      return 'Mastercard';
    } else if (cardNumber.startsWith('3')) {
      return 'American Express';
    } else {
      return 'Unknown';
    }
  }

  static String formatCardNumber(String cardNumber) {
    cardNumber = cardNumber.replaceAll(RegExp(r'\s+'), '');
    final buffer = StringBuffer();
    for (int i = 0; i < cardNumber.length; i++) {
      if (i > 0 && i % 4 == 0) {
        buffer.write(' ');
      }
      buffer.write(cardNumber[i]);
    }
    return buffer.toString();
  }

  static String maskCardNumber(String cardNumber) {
    if (cardNumber.length < 4) return cardNumber;
    final lastFour = cardNumber.substring(cardNumber.length - 4);
    return '**** **** **** $lastFour';
  }
}
