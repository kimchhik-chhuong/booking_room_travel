import 'package:flutter/material.dart';

class OffersPage extends StatelessWidget {
  const OffersPage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    // Updated hero image (adjust if needed)
    final String heroImg =
        'https://images.unsplash.com/photo-1560347876-aeef00ee58a1?w=800&q=80';

    // Card images (use your 4 URLs; add a fifth if you have one)
    final List<String> cardImgs = [
      'https://images.unsplash.com/photo-1505691723518-36a5ac3be353?w=800&q=80',
      'https://images.unsplash.com/photo-1513694203232-719a280e022f?w=800&q=80',
      'assets/room2.jpg',
      'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&q=80',
      // Optional fifth image goes here
    ];

    return Scaffold(
      appBar: AppBar(
        title: const Text('Offers'),
        backgroundColor: Colors.orange,
      ),
      body: SingleChildScrollView(
        child: Column(
          children: [
            // Hero/banner section
            Stack(
              children: [
                Image.network(
                  heroImg,
                  width: double.infinity,
                  height: 200,
                  fit: BoxFit.cover,
                ),
                Container(
                  height: 200,
                  decoration: BoxDecoration(
                    gradient: LinearGradient(
                      colors: [Colors.black.withOpacity(0.5), Colors.transparent],
                      begin: Alignment.bottomCenter,
                      end: Alignment.topCenter,
                    ),
                  ),
                ),
                Positioned(
                  bottom: 16,
                  left: 16,
                  child: const Text(
                    'Special Offers',
                    style: TextStyle(
                      color: Colors.white,
                      fontSize: 24,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 16),

            // Offers section
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: Column(
                children: List.generate(5, (index) {
                  final String img = index < cardImgs.length
                      ? cardImgs[index]
                      : 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=800&q=80';

                  return Card(
                    margin: const EdgeInsets.only(bottom: 16),
                    elevation: 4,
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(12),
                    ),
                    child: Column(
                      children: [
                        ClipRRect(
                          borderRadius: const BorderRadius.vertical(top: Radius.circular(12)),
                          child: Image.network(
                            img,
                            height: 150,
                            width: double.infinity,
                            fit: BoxFit.cover,
                          ),
                        ),
                        Padding(
                          padding: const EdgeInsets.all(12),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                'Offer ${index + 1}',
                                style: const TextStyle(
                                  fontWeight: FontWeight.bold,
                                  fontSize: 16,
                                ),
                              ),
                              const SizedBox(height: 4),
                              const Text(
                                'Get amazing discounts on our services. Limited time offer!',
                                style: TextStyle(fontSize: 14, color: Colors.black54),
                              ),
                              const SizedBox(height: 8),
                              ElevatedButton(
                                onPressed: () {},
                                style: ElevatedButton.styleFrom(
                                  backgroundColor: Colors.orange,
                                  shape: RoundedRectangleBorder(
                                    borderRadius: BorderRadius.circular(6),
                                  ),
                                ),
                                child: const Text('Book Now'),
                              ),
                            ],
                          ),
                        ),
                      ],
                    ),
                  );
                }),
              ),
            ),
          ],
        ),
      ),
    );
  }
}