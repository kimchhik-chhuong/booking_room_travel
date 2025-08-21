import 'package:flutter/material.dart';
import 'package:intl/intl.dart';

import '../history/history_screen.dart' show HistoryScreen;

// Define a global list to store booking history. This is a simple
// way to share state between different screens without a full state
// management solution.
List<Map<String, dynamic>> globalBookingHistory = [
  // Initial sample data
  {
    'hotelName': 'Taj Hotel',
    'date': DateTime.now().subtract(Duration(days: 2)),
    'total': '\$200',
    'status': 'Completed',
    'imageUrl': 'assets/room2.jpg',
    'showAlert': false,
  },
];

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Hotel Booking App',
      theme: ThemeData(primarySwatch: Colors.blue),
      home: const HotelsPage(),
      routes: {
        '/payment': (context) => PaymentPage(),
      },
    );
  }
}

class HotelsPage extends StatefulWidget {
  const HotelsPage({Key? key}) : super(key: key);

  @override
  _HotelsPageState createState() => _HotelsPageState();
}

class _HotelsPageState extends State<HotelsPage> {
  // Use a different map for local booking state to avoid conflicts with global list
  Map<String, bool> _isBooked = {};
  int _currentIndex = 0;

  final List<Widget> _pages = [
    const HotelListPage(),
    const Center(child: Text('Payment Page')),
    const Center(child: Text('Search Page')),
    HistoryScreen(),
    const Center(child: Text('Profile Page')),
    const Center(child: Text('Message Page')),
  ];

  void _onTabTapped(int index) {
    setState(() {
      _currentIndex = index;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: _pages[_currentIndex],
      bottomNavigationBar: BottomNavigationBar(
        type: BottomNavigationBarType.fixed,
        currentIndex: _currentIndex,
        selectedItemColor: Colors.blue,
        unselectedItemColor: Colors.grey,
        onTap: _onTabTapped,
        items: const [
          BottomNavigationBarItem(icon: Icon(Icons.home), label: 'Home'),
          BottomNavigationBarItem(icon: Icon(Icons.payment), label: 'Payment'),
          BottomNavigationBarItem(icon: Icon(Icons.search), label: 'Search'),
          BottomNavigationBarItem(icon: Icon(Icons.history), label: 'History'),
          BottomNavigationBarItem(icon: Icon(Icons.person), label: 'Profile'),
          BottomNavigationBarItem(icon: Icon(Icons.person), label: 'Message'),
        ],
      ),
    );
  }
}

class HotelListPage extends StatefulWidget {
  const HotelListPage({Key? key}) : super(key: key);

  @override
  _HotelListPageState createState() => _HotelListPageState();
}

class _HotelListPageState extends State<HotelListPage> {
  Map<String, bool> _isBooked = {};

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Hotels'),
        backgroundColor: Colors.blue,
      ),
      body: ListView(
        padding: EdgeInsets.zero,
        children: [
          _buildSectionTitle('Featured Hotels'),
          _buildHotelList(context),
        ],
      ),
    );
  }

  Widget _buildSectionTitle(String title) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      child: Text(
        title,
        style: const TextStyle(
          fontSize: 18,
          fontWeight: FontWeight.bold,
        ),
      ),
    );
  }

  Widget _buildHotelList(BuildContext context) {
    final hotels = [
      {
        'name': 'Taj Hotel',
        'price': '\$200/Night',
        'imageUrl': 'assets/room2.jpg',
        'rating': 4.5,
        'reviews': 20,
        'description': 'The ONOMO Hotels chain established...',
      },
      {
        'name': 'AR Hotel',
        'price': '\$200/Night',
        'imageUrl': 'assets/room2.jpg',
        'rating': 4.5,
        'reviews': 20,
        'description': 'The ONOMO Hotels chain established...',
      },
    ];

    return ListView.builder(
      padding: const EdgeInsets.all(8),
      shrinkWrap: true,
      physics: const NeverScrollableScrollPhysics(),
      itemCount: hotels.length,
      itemBuilder: (context, index) {
        final hotel = hotels[index];
        final hotelName = hotel['name'] as String;
        _isBooked.putIfAbsent(hotelName, () => false);
        return _buildHotelCard(
          context,
          hotelName: hotelName,
          price: hotel['price'] as String,
          imageUrl: hotel['imageUrl'] as String,
          rating: (hotel['rating'] as double?) ?? 0.0,
          reviews: (hotel['reviews'] as int?) ?? 0,
          description: hotel['description'] as String,
          isBooked: _isBooked[hotelName]!,
          onBookToggle: () async {
            if (!_isBooked[hotelName]!) {
              final result = await Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => BookingScreen(
                    hotelName: hotelName,
                    address: '12 Eze Adele Road Rumuomasi Lagos Nigeria',
                    price: hotel['price'] as String,
                    imageUrl: hotel['imageUrl'] as String,
                    description: hotel['description'] as String,
                  ),
                ),
              );

              // Check if the booking was successful and add to history
              if (result != null && result is Map<String, dynamic>) {
                setState(() {
                  _isBooked[hotelName] = true;
                  globalBookingHistory.add(result);
                });
                ScaffoldMessenger.of(context).showSnackBar(
                  SnackBar(content: Text('${hotelName} booked successfully!')),
                );
              }
            } else {
              // Unbook logic if needed
              setState(() {
                _isBooked[hotelName] = false;
                // Remove from globalBookingHistory if you want
                globalBookingHistory.removeWhere((item) => item['hotelName'] == hotelName);
              });
            }
          },
        );
      },
    );
  }

  Widget _buildHotelCard(BuildContext context,
      {required String hotelName,
      required String price,
      required String imageUrl,
      required double rating,
      required int reviews,
      required String description,
      required bool isBooked,
      required VoidCallback onBookToggle}) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Card(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
        clipBehavior: Clip.antiAlias,
        elevation: 4,
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            ClipRRect(
              borderRadius: BorderRadius.circular(12),
              child: Image.asset(
                imageUrl,
                height: 120,
                width: 120,
                fit: BoxFit.cover,
                errorBuilder: (context, error, stackTrace) {
                  return Container(
                    height: 120,
                    width: 120,
                    color: Colors.grey[300],
                    child: const Icon(Icons.broken_image, size: 40),
                  );
                },
              ),
            ),
            Expanded(
              child: Padding(
                padding: const EdgeInsets.all(12),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Text(
                          hotelName,
                          style: const TextStyle(
                              fontSize: 18, fontWeight: FontWeight.bold),
                        ),
                        const Icon(Icons.favorite_border, color: Colors.grey),
                      ],
                    ),
                    const SizedBox(height: 4),
                    Row(
                      children: [
                        Icon(Icons.star, size: 16, color: Colors.yellow[700]),
                        const SizedBox(width: 4),
                        Text(
                          '$rating Reviews ($reviews)',
                          style: TextStyle(fontSize: 14, color: Colors.grey[600]),
                        ),
                      ],
                    ),
                    const SizedBox(height: 4),
                    Text(
                      description,
                      style: TextStyle(fontSize: 12, color: Colors.grey[600]),
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                    ),
                    const SizedBox(height: 8),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Text(price, style: TextStyle(color: Colors.blue, fontSize: 16)),
                        IconButton(
                          icon: Icon(
                            isBooked ? Icons.undo : Icons.bookmark,
                            color: Colors.blue,
                          ),
                          onPressed: onBookToggle,
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class BookingScreen extends StatefulWidget {
  final String hotelName;
  final String address;
  final String price;
  final String imageUrl;
  final String description;

  const BookingScreen({
    Key? key,
    required this.hotelName,
    required this.address,
    required this.price,
    required this.imageUrl,
    required this.description,
  }) : super(key: key);

  @override
  _BookingScreenState createState() => _BookingScreenState();
}

class _BookingScreenState extends State<BookingScreen> {
  late TextEditingController _destinationController;
  late TextEditingController _hotelNameController;
  late TextEditingController _bedsController;
  late TextEditingController _peopleController;
  DateTime? _selectedDate;

  @override
  void initState() {
    super.initState();
    _destinationController = TextEditingController();
    _hotelNameController = TextEditingController(text: widget.hotelName);
    _bedsController = TextEditingController();
    _peopleController = TextEditingController();
    _selectedDate = null;
  }

  @override
  void dispose() {
    _destinationController.dispose();
    _hotelNameController.dispose();
    _bedsController.dispose();
    _peopleController.dispose();
    super.dispose();
  }

  Future<void> _selectDate(BuildContext context) async {
    final DateTime now = DateTime.now();
    final DateTime? picked = await showDatePicker(
      context: context,
      initialDate: _selectedDate ?? now,
      firstDate: now,
      lastDate: DateTime(now.year + 1),
    );
    if (picked != null && picked != _selectedDate) {
      setState(() {
        _selectedDate = picked;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Book Your Stay'),
        backgroundColor: Colors.blue,
      ),
      body: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            ClipRRect(
              borderRadius: const BorderRadius.vertical(bottom: Radius.circular(12)),
              child: Image.asset(
                widget.imageUrl,
                height: 200,
                width: double.infinity,
                fit: BoxFit.cover,
                errorBuilder: (context, error, stackTrace) {
                  return Container(
                    height: 200,
                    color: Colors.grey[300],
                    child: const Icon(Icons.broken_image, size: 40),
                  );
                },
              ),
            ),
            Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    widget.hotelName,
                    style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
                  ),
                  const SizedBox(height: 4),
                  Row(
                    children: [
                      Icon(Icons.star, size: 16, color: Colors.yellow[700]),
                      const SizedBox(width: 4),
                      Text(
                        '4.9 (1,092 Reviews)',
                        style: TextStyle(fontSize: 14, color: Colors.grey[600]),
                      ),
                    ],
                  ),
                  const SizedBox(height: 8),
                  Text(
                    widget.address,
                    style: TextStyle(fontSize: 14, color: Colors.grey[600]),
                  ),
                  const SizedBox(height: 8),
                  Text(
                    widget.description,
                    style: TextStyle(fontSize: 14, color: Colors.grey[600]),
                  ),
                  const SizedBox(height: 16),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      const Text(
                        'Amenities',
                        style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                      ),
                      TextButton(
                        onPressed: () {},
                        child: const Text('View All', style: TextStyle(color: Colors.blue)),
                      ),
                    ],
                  ),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceAround,
                    children: [
                      _buildAmenityIcon(Icons.local_cafe, 'Café'),
                      _buildAmenityIcon(Icons.restaurant, 'Restaurant'),
                      _buildAmenityIcon(Icons.local_dining, 'Garden'),
                      _buildAmenityIcon(Icons.golf_course, 'Golf Course'),
                      _buildAmenityIcon(Icons.wifi, 'Free WiFi'),
                    ],
                  ),
                  const SizedBox(height: 16),
                  // Form fields for booking details
                  const Text('Booking Details', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 16),
                  TextField(
                    controller: _hotelNameController,
                    decoration: const InputDecoration(
                      labelText: 'Hotel Name',
                      border: OutlineInputBorder(),
                    ),
                    readOnly: true,
                  ),
                  const SizedBox(height: 16),
                  GestureDetector(
                    onTap: () => _selectDate(context),
                    child: InputDecorator(
                      decoration: const InputDecoration(
                        labelText: 'Check-in Date',
                        border: OutlineInputBorder(),
                        suffixIcon: Icon(Icons.calendar_today),
                      ),
                      child: Text(
                        _selectedDate == null
                            ? 'Select Date'
                            : DateFormat('yyyy-MM-dd').format(_selectedDate!),
                      ),
                    ),
                  ),
                  const SizedBox(height: 16),
                  TextField(
                    controller: _bedsController,
                    decoration: const InputDecoration(
                      labelText: 'Number of Beds',
                      border: OutlineInputBorder(),
                    ),
                    keyboardType: TextInputType.number,
                  ),
                  const SizedBox(height: 16),
                  TextField(
                    controller: _peopleController,
                    decoration: const InputDecoration(
                      labelText: 'Number of People',
                      border: OutlineInputBorder(),
                      
                    ),
                    keyboardType: TextInputType.number,
                  ),
                  const SizedBox(height: 24),
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton(
                      onPressed: () async {
                        // Navigate to payment page and get result
                        final paymentSuccess = await Navigator.push(
                          context,
                          MaterialPageRoute(builder: (context) => PaymentPage()),
                        );
                        // If payment was successful, return the booking data
                        if (paymentSuccess == true) {
                          final newBooking = {
                            'hotelName': widget.hotelName,
                            'date': DateTime.now(),
                            'total': widget.price,
                            'status': 'Completed',
                            'imageUrl': widget.imageUrl,
                            'showAlert': true, // Set to true to show alert on history screen
                          };
                          Navigator.pop(context, newBooking);
                        }
                      },
                      style: ElevatedButton.styleFrom(
                        backgroundColor: Colors.blue,
                        padding: const EdgeInsets.symmetric(vertical: 16),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(8),
                        ),
                      ),
                      child: const Text('Confirm Booking'),
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildAmenityIcon(IconData icon, String label) {
    return Column(
      children: [
        Icon(icon, color: Colors.blue, size: 24),
        const SizedBox(height: 4),
        Text(label, style: TextStyle(fontSize: 12, color: Colors.grey[600])),
      ],
    );
  }
}

class PaymentPage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Payment'),
        backgroundColor: Colors.blue,
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Payment Details',
              style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 20),
            const TextField(
              decoration: InputDecoration(
                labelText: 'Card Number',
                border: OutlineInputBorder(),
                prefixIcon: Icon(Icons.credit_card),
              ),
            ),
            const SizedBox(height: 10),
            const TextField(
              decoration: InputDecoration(
                labelText: 'Expiry Date',
                border: OutlineInputBorder(),
                prefixIcon: Icon(Icons.calendar_today),
              ),
            ),
            const SizedBox(height: 10),
            const TextField(
              decoration: InputDecoration(
                labelText: 'CVV',
                border: OutlineInputBorder(),
                prefixIcon: Icon(Icons.lock),
              ),
            ),
            const SizedBox(height: 20),
            Center(
              child: ElevatedButton(
                onPressed: () {
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(content: Text('Payment Successful!')),
                  );
                  // Pass true back to indicate successful payment
                  Navigator.pop(context, true);
                },
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.blue,
                  padding: const EdgeInsets.symmetric(horizontal: 40, vertical: 15),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(8),
                  ),
                ),
                child: const Text('Pay Now'),
              ),
            ),
          ],
        ),
      ),
    );
  }
}