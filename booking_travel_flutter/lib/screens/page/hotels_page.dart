import 'package:flutter/material.dart';

void main() {
  runApp(MaterialApp(
    home: HotelsPage(),
    theme: ThemeData(primarySwatch: Colors.blue),
  ));
}

class HotelsPage extends StatelessWidget {
  const HotelsPage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    final hotels = [
      {
        'name': 'Raffles Hotel Le Royal',
        'image':
            'https://images.unsplash.com/photo-1572715375839-98d3b6f16358?w=800&q=80',
        'location': 'Phnom Penh, Cambodia',
        'price': '\$120/night',
      },
      {
        'name': 'Anantara Angkor Resort',
        'image':
            'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&q=80',
        'location': 'Siem Reap, Cambodia',
        'price': '\$180/night',
      },
      {
        'name': 'Baitong Hotel & Resort',
        'image':
            'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=800&q=80',
        'location': 'Phnom Penh, Cambodia',
        'price': '\$75/night',
      },
    ];

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
        'imageUrl': '../lib/assets/room2.jpg',
        'rating': 4.5,
        'reviews': 20,
        'description': 'The ONOMO Hotels chain established...',
      },
      {
        'name': 'AR Hotel',
        'price': '\$200/Night',
        'imageUrl': '../lib/assets/room2.jpg',
        'rating': 4.5,
        'reviews': 20,
        'description': 'The ONOMO Hotels chain established...',
      },
      {
        'name': 'Al Rahman Hotel',
        'price': '\$200/Night',
        'imageUrl': '../lib/assets/room2.jpg',
        'rating': 4.5,
        'reviews': 20,
        'description': 'The ONOMO Hotels chain established...',
      },
      {
        'name': 'Oberoy Hotel',
        'price': '\$200/Night',
        'imageUrl': '../lib/assets/room2.jpg',
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

class BookingScreen extends StatelessWidget {
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
                imageUrl,
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
                        hotelName,
                        style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
                      ),
                      Text(
                        price,
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
                    address,
                    style: TextStyle(fontSize: 14, color: Colors.grey[600]),
                  ),
                  const SizedBox(height: 8),
                  Text(
                    description,
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
                  Center(
                    child: ElevatedButton(
                      onPressed: () {
                        ScaffoldMessenger.of(context).showSnackBar(
                          SnackBar(content: Text('Booking confirmed for $hotelName!')),
                        );
                        Navigator.pop(context);
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
}
