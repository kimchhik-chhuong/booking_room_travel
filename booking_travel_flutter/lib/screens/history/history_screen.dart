import 'package:flutter/material.dart';
import 'dart:convert';
import 'package:http/http.dart' as http;

class HistoryScreenState extends State<HistoryScreen> {
  List<dynamic> bookings = [];

  @override
  void initState() {
    super.initState();
    fetchBookings();
  }

  Future<void> fetchBookings() async {
    final response = await http.get(
      Uri.parse(
          'http://http://127.0.0.1:8000/api/booking-history'), // Adjust port if needed
      headers: {'Authorization': 'Bearer YOUR_SANCTUM_TOKEN'},
    );

    if (response.statusCode == 200) {
      setState(() {
        bookings = jsonDecode(response.body)['data'];
      });
    } else {
      print('Failed to load bookings: ${response.body}');
    }
  }

  Widget build(BuildContext context) {
    return Scaffold(
      // appBar: const Text('Travel History'),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Travel Already',
              style: TextStyle(fontSize: 24.0, fontWeight: FontWeight.bold),
            ),
            SizedBox(height: 16.0),
            Expanded(
              child: bookings.isEmpty
                  ? Center(child: CircularProgressIndicator())
                  : ListView.builder(
                      itemCount: bookings.length,
                      itemBuilder: (context, index) {
                        final booking = bookings[index];
                        final userName =
                            booking['user']?['name'] ?? 'Unknown User';
                        return Card(
                          margin: EdgeInsets.symmetric(vertical: 8.0),
                          elevation: 4.0,
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(10.0),
                          ),
                          color: Color.fromARGB(255, 100, 100, 100),
                          child: Padding(
                            padding: const EdgeInsets.all(16.0),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  'Booking Ref: ${booking['booking_reference']}',
                                  style: TextStyle(
                                    fontSize: 20.0,
                                    fontWeight: FontWeight.bold,
                                    color: Colors.white,
                                  ),
                                ),
                                Text(
                                  'User: $userName',
                                  style: TextStyle(color: Colors.white70),
                                ),
                                Text(
                                  'Date: ${booking['booking_date']} - ${booking['travel_date']}',
                                  style: TextStyle(color: Colors.white70),
                                ),
                                Text(
                                  'Participants: ${booking['participants']}',
                                  style: TextStyle(color: Colors.white70),
                                ),
                                Text(
                                  'Total: ${booking['total_amount']} ${booking['currency']}',
                                  style: TextStyle(
                                    fontSize: 18.0,
                                    fontWeight: FontWeight.bold,
                                    color: Colors.blue,
                                  ),
                                ),
                              ],
                            ),
                          ),
                        );
                      },
                    ),
            ),
          ],
        ),
      ),
    );
  }
}

class HistoryScreen extends StatefulWidget {
  @override
  HistoryScreenState createState() => HistoryScreenState();
}
