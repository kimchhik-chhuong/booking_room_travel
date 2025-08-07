import 'package:flutter/material.dart';

void main() {
  runApp(MaterialApp(
    home: HotelsPage(),
    theme: ThemeData(primarySwatch: Colors.blue),
    routes: {
      '/payment': (context) => PaymentPage(),
    },
  ));
}

class HotelsPage extends StatelessWidget {
  const HotelsPage({Key? key}) : super(key: key);

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
      {
        'name': 'Al Rahman Hotel',
        'price': '\$200/Night',
        'imageUrl': 'assets/room2.jpg',
        'rating': 4.5,
        'reviews': 20,
        'description': 'The ONOMO Hotels chain established...',
      },
      {
        'name': 'Oberoy Hotel',
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
      physics: NeverScrollableScrollPhysics(),
      itemCount: hotels.length,
      itemBuilder: (context, index) {
        final hotel = hotels[index];
        return _buildHotelCard(
          context,
          hotelName: hotel['name'] as String,
          price: hotel['price'] as String,
          imageUrl: hotel['imageUrl'] as String,
          rating: (hotel['rating'] as double?) ?? 0.0,
          reviews: (hotel['reviews'] as int?) ?? 0,
          description: hotel['description'] as String,
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
      required String description}) {
    final isNetwork = imageUrl.startsWith('http');
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
              child: isNetwork
                  ? Image.network(
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
                    )
                  : Image.asset(
                      imageUrl,
                      height: 120,
                      width: 120,
                      fit: BoxFit.cover,
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
                        ElevatedButton(
                          onPressed: () {
                            Navigator.push(
                              context,
                              MaterialPageRoute(
                                builder: (context) => BookingScreen(
                                  hotelName: hotelName,
                                  address: '12 Eze Adele Road Rumuomasi Lagos Nigeria',
                                  price: price,
                                  imageUrl: imageUrl,
                                  description: description,
                                ),
                              ),
                            );
                          },
                          style: ElevatedButton.styleFrom(
                            backgroundColor: Colors.blue,
                            shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(8),
                            ),
                          ),
                          child: const Text('Book now'),
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
    _selectedDate = null; // Initialize with null, will be set via date picker
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
      body: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Header Image
            ClipRRect(
              borderRadius: BorderRadius.vertical(bottom: Radius.circular(12)),
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
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text(
                        widget.hotelName,
                        style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
                      ),
                      Text(
                        widget.price,
                        style: TextStyle(fontSize: 18, color: Colors.green),
                      ),
                    ],
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
                      Text(
                        'Amenities',
                        style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                      ),
                      TextButton(
                        onPressed: () {},
                        child: Text('View All', style: TextStyle(color: Colors.blue)),
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
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text(
                        'Gallery Photos',
                        style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                      ),
                      TextButton(
                        onPressed: () {},
                        child: Text('See All', style: TextStyle(color: Colors.blue)),
                      ),
                    ],
                  ),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      _buildGalleryImage('assets/room1.jpg'),
                      _buildGalleryImage('assets/room2.jpg'),
                      _buildGalleryImage('assets/room3.jpg'),
                    ],
                  ),
                  const SizedBox(height: 20),
                  // New Booking Form Section
                  _buildBookingForm(context),
                  const SizedBox(height: 20),
                  Center(
                    child: ElevatedButton(
                      onPressed: () {
                        Navigator.pushNamed(context, '/payment');
                      },
                      style: ElevatedButton.styleFrom(
                        backgroundColor: Colors.blue,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(8),
                        ),
                        padding: EdgeInsets.symmetric(horizontal: 40, vertical: 15),
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

  Widget _buildGalleryImage(String imageUrl) {
    return Padding(
      padding: const EdgeInsets.only(right: 8),
      child: ClipRRect(
        borderRadius: BorderRadius.circular(8),
        child: Image.asset(
          imageUrl,
          height: 80,
          width: 80,
          fit: BoxFit.cover,
          errorBuilder: (context, error, stackTrace) {
            return Container(
              height: 80,
              width: 80,
              color: Colors.grey[300],
              child: const Icon(Icons.broken_image, size: 40),
            );
          },
        ),
      ),
    );
  }

  Widget _buildBookingForm(BuildContext context) {
    return Card(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      elevation: 4,
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Destination',
              style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 8),
            TextField(
              controller: _destinationController,
              decoration: InputDecoration(
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(8),
                ),
                hintText: 'Enter destination',
                prefixIcon: Icon(Icons.location_on),
              ),
            ),
            const SizedBox(height: 16),
            Text(
              'Hotel Name',
              style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 8),
            TextField(
              controller: _hotelNameController,
              decoration: InputDecoration(
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(8),
                ),
                hintText: 'Enter hotel name',
                prefixIcon: Icon(Icons.hotel),
              ),
            ),
            const SizedBox(height: 16),
            Text(
              'Details',
              style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 8),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Expanded(
                  child: TextField(
                    controller: TextEditingController(
                      text: _selectedDate != null
                          ? '${_selectedDate!.day}/${_selectedDate!.month}/${_selectedDate!.year}'
                          : '',
                    ),
                    onTap: () => _selectDate(context),
                    readOnly: true,
                    decoration: InputDecoration(
                      border: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(8),
                      ),
                      hintText: 'Select date',
                      prefixIcon: Icon(Icons.calendar_today),
                      labelText: 'Date',
                    ),
                  ),
                ),
                const SizedBox(width: 8),
                Expanded(
                  child: TextField(
                    controller: _bedsController,
                    decoration: InputDecoration(
                      border: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(8),
                      ),
                      hintText: '2',
                      prefixIcon: Icon(Icons.bed),
                      labelText: 'Beds',
                    ),
                  ),
                ),
                const SizedBox(width: 8),
                Expanded(
                  child: TextField(
                    controller: _peopleController,
                    decoration: InputDecoration(
                      border: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(8),
                      ),
                      hintText: '4',
                      prefixIcon: Icon(Icons.people),
                      labelText: 'People',
                    ),
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}

class PaymentPage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Payment'),
        backgroundColor: Colors.blue,
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Payment Details',
              style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
            ),
            SizedBox(height: 20),
            TextField(
              decoration: InputDecoration(
                labelText: 'Card Number',
                border: OutlineInputBorder(),
                prefixIcon: Icon(Icons.credit_card),
              ),
            ),
            SizedBox(height: 10),
            TextField(
              decoration: InputDecoration(
                labelText: 'Expiry Date',
                border: OutlineInputBorder(),
                prefixIcon: Icon(Icons.calendar_today),
              ),
            ),
            SizedBox(height: 10),
            TextField(
              decoration: InputDecoration(
                labelText: 'CVV',
                border: OutlineInputBorder(),
                prefixIcon: Icon(Icons.lock),
              ),
            ),
            SizedBox(height: 20),
            Center(
              child: ElevatedButton(
                onPressed: () {
                  ScaffoldMessenger.of(context).showSnackBar(
                    SnackBar(content: Text('Payment Successful!')),
                  );
                  Navigator.pop(context); // Return to previous screen
                },
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.blue,
                  padding: EdgeInsets.symmetric(horizontal: 40, vertical: 15),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(8),
                  ),
                ),
                child: Text('Pay Now'),
              ),
            ),
          ],
        ),
      ),
    );
  }
}