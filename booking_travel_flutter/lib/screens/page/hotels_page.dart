import 'package:flutter/material.dart';

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
        centerTitle: true,
      ),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: hotels.length,
        itemBuilder: (context, index) {
          final hotel = hotels[index];
          return Card(
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(12),
            ),
            margin: const EdgeInsets.only(bottom: 16),
            elevation: 4,
            clipBehavior: Clip.antiAlias,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Image.network(
                  hotel['image']!,
                  height: 180,
                  width: double.infinity,
                  fit: BoxFit.cover,
                  errorBuilder: (context, error, stackTrace) => Container(
                    height: 180,
                    color: Colors.grey[300],
                    child: const Icon(Icons.broken_image, size: 40),
                  ),
                ),
                Padding(
                  padding: const EdgeInsets.all(12),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        hotel['name']!,
                        style: const TextStyle(
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      const SizedBox(height: 4),
                      Text(
                        hotel['location']!,
                        style: TextStyle(color: Colors.grey[700]),
                      ),
                      const SizedBox(height: 4),
                      Text(
                        hotel['price']!,
                        style: const TextStyle(
                          color: Colors.blue,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ],
                  ),
                ),
              ],
            ),
          );
        },
      ),
    );
  }
}
