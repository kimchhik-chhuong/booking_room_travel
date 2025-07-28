import 'package:flutter/material.dart';
import 'page/trips_page.dart';
import 'page/hotels_page.dart';
import 'page/flights_page.dart';
import 'page/offers_page.dart';
import 'payment_screen.dart';
import 'search_screen.dart';
import 'profile_screen.dart';

class HomeScreen extends StatefulWidget {
  @override
  _HomeScreenState createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  int _currentIndex = 0;
  int _notificationCount = 3; // Added notification count

  final List<Widget> _pages = [
    HomePageContent(),
    PaymentScreen(),
    SearchScreen(),
    Center(child: Text('Historys Page')),
    ProfileScreen(),
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: _pages[_currentIndex],
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: _currentIndex,
        selectedItemColor: Colors.blue,
        unselectedItemColor: Colors.grey,
        onTap: (index) {
          setState(() {
            _currentIndex = index;
          });
        },
        type: BottomNavigationBarType.fixed,
        items: const [
          BottomNavigationBarItem(icon: Icon(Icons.home), label: 'Home'),
          BottomNavigationBarItem(icon: Icon(Icons.payment), label: 'Payment'),
          BottomNavigationBarItem(icon: Icon(Icons.search), label: 'Search'),
          BottomNavigationBarItem(
              icon: Icon(Icons.card_travel), label: 'Historys'),
          BottomNavigationBarItem(icon: Icon(Icons.person), label: 'Profile'),
        ],
      ),
    );
  }
}

class HomePageContent extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return ListView(
      padding: EdgeInsets.zero,
      children: [
        _buildHeader(context),
        const SizedBox(height: 10),
        _buildOptions(context),
        const SizedBox(height: 20),
        _buildSectionTitle('Popular Offer'),
        _buildPopularOffers(),
        const SizedBox(height: 20),
        _buildSectionTitle('Hotel Near You'),
        _buildHotelCard(
          context,
          hotelName: 'Pan Pacific Hotel',
          price: '\$1200/night',
          imageUrl: '../lib/assets/room2.jpg',
        ),
        _buildHotelCard(
          context,
          hotelName: 'Prestige Proga Inn',
          price: '\$100/night',
          imageUrl:
              'https://images.unsplash.com/photo-1505691723518-36a5ac3be353?w=800&q=80',
        ),
        const SizedBox(height: 20),
      ],
    );
  }

  Widget _buildSectionTitle(String title) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16),
      child: Text(
        title,
        style: const TextStyle(
          fontSize: 18,
          fontWeight: FontWeight.bold,
        ),
      ),
    );
  }

  Widget _buildHeader(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(16),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          const Flexible(
            child: Text(
              "Let's Explore The World!",
              style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold),
            ),
          ),
          Stack(
            children: [
              IconButton(
                icon: Icon(Icons.notifications),
                onPressed: () {
                  _showNotificationAlert(context);
                },
              ),
              Positioned(
                right: 8,
                top: 8,
                child: Container(
                  padding: EdgeInsets.all(2),
                  decoration: BoxDecoration(
                    color: Colors.red,
                    borderRadius: BorderRadius.circular(6),
                  ),
                  constraints: BoxConstraints(
                    minWidth: 14,
                    minHeight: 14,
                  ),
                  child: Text(
                    '3',
                    style: TextStyle(
                      color: Colors.white,
                      fontSize: 8,
                    ),
                    textAlign: TextAlign.center,
                  ),
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  void _showNotificationAlert(BuildContext context) {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: Text('Notifications'),
          content: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              ListTile(
                leading: CircleAvatar(
                  backgroundImage: NetworkImage(
                    'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=100&q=80',
                  ),
                ),
                title: Text('New message'),
                subtitle: Text('You have 3 new notifications'),
              ),
              Divider(),
              ListTile(
                leading: Icon(Icons.flight, color: Colors.blue),
                title: Text('Flight booking confirmed'),
                subtitle: Text('Your flight to Paris is confirmed'),
              ),
              ListTile(
                leading: Icon(Icons.hotel, color: Colors.green),
                title: Text('Hotel reservation'),
                subtitle: Text('Pan Pacific Hotel - 2 nights'),
              ),
            ],
          ),
          actions: [
            TextButton(
              child: Text('Close'),
              onPressed: () {
                Navigator.of(context).pop();
              },
            ),
          ],
        );
      },
    );
  }

  Widget _buildOptions(BuildContext context) {
    final options = ['Trips', 'Hotels', 'Flights', 'Offers'];
    final colors = [
      Colors.purple,
      Colors.pink,
      Colors.orange,
      Colors.blueAccent
    ];
    final icons = [
      Icons.airplanemode_active,
      Icons.hotel,
      Icons.flight,
      Icons.local_offer,
    ];
    final pages = [
      const TripScreen(),
      const HotelsPage(),
      const FlightsPage(),
      const OffersPage(),
    ];

    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: List.generate(options.length, (index) {
          return GestureDetector(
            onTap: () {
              Navigator.push(
                context,
                MaterialPageRoute(builder: (_) => pages[index]),
              );
            },
            child: Column(
              children: [
                CircleAvatar(
                  backgroundColor: colors[index],
                  radius: 30,
                  child: Icon(icons[index], color: Colors.white, size: 28),
                ),
                const SizedBox(height: 8),
                Text(
                  options[index],
                  style: const TextStyle(fontWeight: FontWeight.bold),
                ),
              ],
            ),
          );
        }),
      ),
    );
  }

  Widget _buildPopularOffers() {
    final offers = [
      'https://images.unsplash.com/photo-1560347876-aeef00ee58a1?w=800&q=80',
      '../lib/assets/room1.png',
      'https://images.unsplash.com/photo-1505691723518-36a5ac3be353?w=800&q=80',
      '../lib/assets/room2.jpg',
    ];

    return Container(
      height: 180,
      padding: const EdgeInsets.only(left: 16),
      child: ListView.separated(
        scrollDirection: Axis.horizontal,
        itemCount: offers.length,
        separatorBuilder: (context, index) => const SizedBox(width: 12),
        itemBuilder: (context, index) {
          final url = offers[index];
          return ClipRRect(
            borderRadius: BorderRadius.circular(12),
            child: url.startsWith('http')
                ? Image.network(
                    url,
                    width: 280,
                    height: 180,
                    fit: BoxFit.cover,
                    errorBuilder: (context, error, stackTrace) {
                      return Container(
                        width: 280,
                        height: 180,
                        color: Colors.grey[300],
                        child: const Icon(Icons.broken_image, size: 40),
                      );
                    },
                  )
                : Image.asset(
                    url,
                    width: 280,
                    height: 180,
                    fit: BoxFit.cover,
                  ),
          );
        },
      ),
    );
  }

  Widget _buildHotelCard(BuildContext context,
      {required String hotelName,
      required String price,
      required String imageUrl}) {
    final isNetwork = imageUrl.startsWith('http');
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      child: Card(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
        clipBehavior: Clip.antiAlias,
        elevation: 4,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            isNetwork
                ? Image.network(
                    imageUrl,
                    height: 180,
                    width: double.infinity,
                    fit: BoxFit.cover,
                    errorBuilder: (context, error, stackTrace) {
                      return Container(
                        height: 180,
                        color: Colors.grey[300],
                        child: const Icon(Icons.broken_image, size: 40),
                      );
                    },
                  )
                : Image.asset(
                    imageUrl,
                    height: 180,
                    width: double.infinity,
                    fit: BoxFit.cover,
                  ),
            Padding(
              padding: const EdgeInsets.all(12),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text(hotelName,
                      style:
                          TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                  Text(price, style: TextStyle(color: Colors.blue)),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}