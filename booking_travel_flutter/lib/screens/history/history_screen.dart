import 'package:flutter/material.dart';
import '../home_screen.dart'; // adjust path if needed

// Define a class to represent a single travel booking
class TravelBooking {
  final String hotelName;
  final String location;
  final DateTime checkInDate;
  final DateTime checkOutDate;
  final String bookingId; // Added booking ID
  final double price; // Added price

  TravelBooking({
    required this.hotelName,
    required this.location,
    required this.checkInDate,
    required this.checkOutDate,
    required this.bookingId,
    required this.price,
  });
}

class HistoryScreen extends StatefulWidget {
  @override
  _HistoryScreenState createState() => _HistoryScreenState();
}

class _HistoryScreenState extends State<HistoryScreen> {
  // Mock data for travel history.
  // In a real application, this data would come from a database or API.
  final List<TravelBooking> _travelHistory = [
    TravelBooking(
      hotelName: 'Grand Hyatt Bangkok',
      location: 'Bangkok, Thailand',
      checkInDate: DateTime(2023, 10, 15),
      checkOutDate: DateTime(2023, 10, 20),
      bookingId: 'BKK-GH-12345',
      price: 750.00,
    ),
    TravelBooking(
      hotelName: 'The Ritz-Carlton, Kyoto',
      location: 'Kyoto, Japan',
      checkInDate: DateTime(2024, 1, 22),
      checkOutDate: DateTime(2024, 1, 25),
      bookingId: 'KYO-RC-67890',
      price: 1200.00,
    ),
    TravelBooking(
      hotelName: 'Marina Bay Sands',
      location: 'Singapore',
      checkInDate: DateTime(2024, 5, 10),
      checkOutDate: DateTime(2024, 5, 13),
      bookingId: 'SIN-MBS-11223',
      price: 980.00,
    ),
    TravelBooking(
      hotelName: 'The Peninsula Paris',
      location: 'Paris, France',
      checkInDate: DateTime(2024, 7, 1),
      checkOutDate: DateTime(2024, 7, 7),
      bookingId: 'PAR-PEN-44556',
      price: 2500.00,
    ),
    TravelBooking(
      hotelName: 'Burj Al Arab Jumeirah',
      location: 'Dubai, UAE',
      checkInDate: DateTime(2024, 9, 5),
      checkOutDate: DateTime(2024, 9, 9),
      bookingId: 'DXB-BAJ-77889',
      price: 3500.00,
    ),
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Travel History'),
        leading: IconButton(
          icon: const Icon(Icons.arrow_back), // rollback icon
          onPressed: () {
            Navigator.pushReplacement(
              context,
              MaterialPageRoute(builder: (_) => HomeScreen()),
            );
          },
        ),
        backgroundColor: Colors.transparent, // Transparent app bar
        elevation: 0, // No shadow for app bar
        foregroundColor: Colors.black, // Changed to black for contrast
      ),
      extendBodyBehindAppBar: true, // Extends body behind app bar for gradient
      body: Container(
        decoration: const BoxDecoration(
          color: Color(0xFFFFF9FB), // Changed to the light pink/off-white color from the image
        ),
        child: Column( // Use a Column to place text above the list
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Padding(
              padding: EdgeInsets.fromLTRB(24.0, 100.0, 16.0, 16.0), // Adjust padding for app bar
              child: Text(
                'Travel Already',
                style: TextStyle(
                  fontSize: 32.0, // Even larger font size
                  fontWeight: FontWeight.bold,
                  color: Colors.black87, // Darker text for contrast on light background
                ),
              ),
            ),
            Expanded( // Expanded to make ListView take remaining space
              child: _travelHistory.isEmpty
                  ? const Center(
                      child: Text(
                        'Your travel history will appear here.',
                        style: TextStyle(color: Colors.black54),
                      ),
                    )
                  : ListView.builder(
                      padding: const EdgeInsets.symmetric(horizontal: 16.0), // Adjust padding
                      itemCount: _travelHistory.length,
                      itemBuilder: (context, index) {
                        final booking = _travelHistory[index];
                        return Card(
                          margin: const EdgeInsets.symmetric(vertical: 8.0), // Slightly reduced vertical margin
                          elevation: 6.0, // Moderate elevation
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(15.0), // Consistent rounded corners
                          ),
                          color: Colors.white, // Solid white card
                          child: Padding(
                            padding: const EdgeInsets.all(18.0), // Consistent padding
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  booking.hotelName,
                                  style: const TextStyle(
                                    fontSize: 20.0, // Consistent font size
                                    fontWeight: FontWeight.bold,
                                     color: Color.fromARGB(255, 64, 84, 164), // Keeping a purple accent for hotel name
                                  ),
                                ),
                                const SizedBox(height: 10.0), // Consistent spacing
                                _buildInfoRow(Icons.location_on, booking.location, Colors.grey[700]!),
                                const SizedBox(height: 6.0),
                                _buildInfoRow(Icons.calendar_today, '${_formatDate(booking.checkInDate)} - ${_formatDate(booking.checkOutDate)}', Colors.grey[700]!),
                                const SizedBox(height: 6.0),
                                _buildInfoRow(Icons.confirmation_number, 'Booking ID: ${booking.bookingId}', Colors.grey[700]!),
                                const Divider(height: 20, thickness: 1, color: Colors.grey), // Lighter divider
                                Row(
                                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                  children: [
                                    const Text(
                                      'Total Price:',
                                      style: TextStyle(
                                        fontSize: 16.0,
                                        color: Colors.black87,
                                      ),
                                    ),
                                    Text(
                                      '\$${booking.price.toStringAsFixed(2)}',
                                      style: const TextStyle(
                                        fontSize: 18.0,
                                        fontWeight: FontWeight.bold,
                                        color: Color.fromARGB(255, 64, 84, 164), // Keeping a lighter purple accent for price
                                      ),
                                    ),
                                  ],
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

  // Helper function to build consistent info rows
  Widget _buildInfoRow(IconData icon, String text, Color color) {
    return Row(
      children: [
        Icon(icon, size: 18.0, color: color), // Consistent icon size
        const SizedBox(width: 8.0), // Consistent spacing
        Expanded(
          child: Text(
            text,
            style: const TextStyle(
              fontSize: 15.0, // Consistent font size
              color: Colors.black87, // Darker text
            ),
          ),
        ),
      ],
    );
  }

  // Helper function to format dates
  String _formatDate(DateTime date) {
    return '${date.day}/${date.month}/${date.year}';
  }
}
