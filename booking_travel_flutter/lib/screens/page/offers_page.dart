import 'package:flutter/material.dart';

class AllDealsPage extends StatelessWidget {
  const AllDealsPage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    // Example list of deals (you can replace with API data later)
    final List<Map<String, String>> deals = [
      {
        'title': 'Summer Escape',
        'desc': 'Enjoy 30% off on beach resorts this summer!',
        'image': 'https://images.unsplash.com/photo-1505691938895-1758d7feb511?w=800&q=80'
      },
      {
        'title': 'Mountain Adventure',
        'desc': 'Get 20% discount on mountain cabins.',
        'image': 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800&q=80'
      },
      {
        'title': 'Luxury Hotels',
        'desc': 'Book 2 nights, get 1 free in luxury hotels.',
        'image': 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&q=80'
      },
      {
        'title': 'City Getaway',
        'desc': 'Flat 25% off on city tours and stays.',
        'image': 'https://images.unsplash.com/photo-1528909514045-2fa4ac7a08ba?w=800&q=80'
      },
    ];

    return Scaffold(
      appBar: AppBar(
        title: const Text('All Deals'),
        backgroundColor: Colors.orange,
      ),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: deals.length,
        itemBuilder: (context, index) {
          final deal = deals[index];
          return Card(
            margin: const EdgeInsets.only(bottom: 16),
            elevation: 4,
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(12),
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Deal image
                ClipRRect(
                  borderRadius: const BorderRadius.vertical(top: Radius.circular(12)),
                  child: Image.network(
                    deal['image']!,
                    height: 160,
                    width: double.infinity,
                    fit: BoxFit.cover,
                  ),
                ),
                // Deal details
                Padding(
                  padding: const EdgeInsets.all(12),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        deal['title']!,
                        style: const TextStyle(
                          fontSize: 18,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      const SizedBox(height: 6),
                      Text(
                        deal['desc']!,
                        style: const TextStyle(
                          fontSize: 14,
                          color: Colors.black54,
                        ),
                      ),
                      const SizedBox(height: 10),
                      Align(
                        alignment: Alignment.centerRight,
                        child: ElevatedButton(
                          onPressed: () {
                            ScaffoldMessenger.of(context).showSnackBar(
                              SnackBar(content: Text('${deal['title']} booked!')),
                            );
                          },
                          style: ElevatedButton.styleFrom(
                            backgroundColor: Colors.orange,
                            shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(6),
                            ),
                          ),
                          child: const Text('Book Now'),
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
