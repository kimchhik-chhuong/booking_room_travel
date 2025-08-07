import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:http/http.dart' as http;
import 'dart:io' show Platform;

class HistoryScreen extends StatefulWidget {
  const HistoryScreen({Key? key}) : super(key: key);

  @override
  _HistoryScreenState createState() => _HistoryScreenState();
}

class _HistoryScreenState extends State<HistoryScreen> {
  List<dynamic> bookings = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    fetchBookings();
  }

  Future<void> fetchBookings() async {
    final String baseUrl = dotenv.env['API_URL'] ?? 'http://localhost:8000/api';
    final String apiUrl = Platform.isAndroid
        ? baseUrl.replaceFirst('localhost', '10.0.2.2')
        : baseUrl;
    final uri = Uri.parse('$apiUrl/booking-history');

    try {
      final response = await http.get(uri);
      if (response.statusCode == 200) {
        setState(() {
          bookings = List<dynamic>.from(jsonDecode(response.body));
          _isLoading = false;
        });
      } else {
        print('Failed to load booking history: ${response.statusCode}');
        setState(() {
          _isLoading = false;
        });
      }
    } catch (e) {
      print('Error fetching booking history: $e');
      setState(() {
        _isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Booking History')),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : bookings.isEmpty
          ? const Center(child: Text('No booking history found.'))
          : ListView.builder(
              itemCount: bookings.length,
              itemBuilder: (context, index) {
                final booking = bookings[index];
                return ListTile(
                  title: Text('Booking #${booking['id']}'),
                  subtitle: Text('Date: ${booking['date']}'),
                );
              },
            ),
    );
  }
}
