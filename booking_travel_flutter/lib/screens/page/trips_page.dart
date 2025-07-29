import 'package:flutter/material.dart';

class TripScreen extends StatelessWidget {
  const TripScreen({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    final trips = [
      {
        'title': 'Angkor Wat, Cambodia',
        'image': 'https://images.unsplash.com/photo-1549880186-7f3728d53e92',
        'duration': '3 days, 2 nights',
      },
      {
        'title': 'Phnom Penh City Tour',
        'image': 'https://images.unsplash.com/photo-1611931929836-16ef3f538f7d',
        'duration': '1 day tour',
      },
      {
        'title': 'Siem Reap Discovery',
        'image': 'https://images.unsplash.com/photo-1543248939-ff40856c3491',
        'duration': '2 days, 1 night',
      },
    ];

    return Scaffold(
      appBar: AppBar(
        title: const Text('Trips'),
        centerTitle: true,
      ),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: trips.length,
        itemBuilder: (context, index) {
          final trip = trips[index];
          return Card(
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            margin: const EdgeInsets.only(bottom: 16),
            elevation: 4,
            clipBehavior: Clip.antiAlias,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Image.network(
                  trip['image']!,
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
                        trip['title']!,
                        style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                      ),
                      const SizedBox(height: 4),
                      Text(
                        trip['duration']!,
                        style: TextStyle(color: Colors.grey[600]),
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
