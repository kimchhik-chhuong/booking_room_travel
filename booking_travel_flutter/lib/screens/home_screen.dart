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
  int _notificationCount = 3;

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
          BottomNavigationBarItem(icon: Icon(Icons.card_travel), label: 'Historys'),
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
                  // Navigate to notification detail screen
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (context) => NotificationDetailScreen(),
                    ),
                  );
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

// =======================
// Notification Detail Screen with tappable notifications
// =======================

class NotificationDetailScreen extends StatelessWidget {
  final List<NotificationItem> notifications = [
    NotificationItem(
      icon: Icons.mark_email_unread,
      color: Colors.orange,
      title: 'New Message Received',
      subtitle: 'You have 3 unread messages.',
      dateTime: DateTime.now().subtract(Duration(minutes: 5)),
    ),
    NotificationItem(
      icon: Icons.flight_takeoff,
      color: Colors.blue,
      title: 'Flight Confirmed',
      subtitle: 'Your booking to Paris has been confirmed.',
      dateTime: DateTime.now().subtract(Duration(hours: 2)),
    ),
    NotificationItem(
      icon: Icons.hotel,
      color: Colors.green,
      title: 'Hotel Reserved',
      subtitle: 'Pan Pacific Hotel - 2 nights stay.',
      dateTime: DateTime.now().subtract(Duration(days: 1)),
    ),
  ];

  String _formatDateTime(DateTime dt) {
    return '${dt.year}-${dt.month.toString().padLeft(2, '0')}-${dt.day.toString().padLeft(2, '0')} '
           '${dt.hour.toString().padLeft(2, '0')}:${dt.minute.toString().padLeft(2, '0')}';
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Notifications'),
        backgroundColor: Colors.blue,
      ),
      body: ListView.separated(
        padding: const EdgeInsets.all(12),
        itemCount: notifications.length,
        separatorBuilder: (context, index) => Divider(color: Colors.grey[300]),
        itemBuilder: (context, index) {
          final item = notifications[index];
          return ListTile(
            leading: CircleAvatar(
              backgroundColor: item.color.withOpacity(0.2),
              child: Icon(item.icon, color: item.color),
            ),
            title: Text(
              item.title,
              style: const TextStyle(fontWeight: FontWeight.bold),
            ),
            subtitle: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(item.subtitle),
                const SizedBox(height: 4),
                Text(
                  _formatDateTime(item.dateTime),
                  style: TextStyle(fontSize: 12, color: Colors.grey[600]),
                ),
              ],
            ),
            isThreeLine: true,
            onTap: () {
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) {
                    switch (item.title) {
                      case 'New Message Received':
                        return NotificationDetailMessageScreen();
                      case 'Flight Confirmed':
                        return NotificationDetailFlightScreen();
                      case 'Hotel Reserved':
                        return NotificationDetailHotelScreen();
                      default:
                        return NotificationDetailGenericScreen(item: item);
                    }
                  },
                ),
              );
            },
          );
        },
      ),
    );
  }
}

class NotificationItem {
  final IconData icon;
  final Color color;
  final String title;
  final String subtitle;
  final DateTime dateTime;

  NotificationItem({
    required this.icon,
    required this.color,
    required this.title,
    required this.subtitle,
    required this.dateTime,
  });
}

// ======= Notification Detail Pages =======

class NotificationDetailMessageScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Message Details')),
      body: Padding(
        padding: EdgeInsets.all(16),
        child: Text(
          'You have 3 unread messages from your contacts. Please check your inbox to read them.',
          style: TextStyle(fontSize: 16),
        ),
      ),
    );
  }
}

class NotificationDetailFlightScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Flight Confirmation')),
      body: Padding(
        padding: EdgeInsets.all(16),
        child: Text(
          'Your flight to Paris is confirmed.\nFlight number: AF123\nDeparture: 10:00 AM, July 30, 2025.',
          style: TextStyle(fontSize: 16),
        ),
      ),
    );
  }
}

class NotificationDetailHotelScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Hotel Reservation')),
      body: Padding(
        padding: EdgeInsets.all(16),
        child: Text(
          'Your reservation at Pan Pacific Hotel is confirmed for 2 nights.\nCheck-in date: Aug 5, 2025.\nEnjoy your stay!',
          style: TextStyle(fontSize: 16),
        ),
      ),
    );
  }
}

class NotificationDetailGenericScreen extends StatelessWidget {
  final NotificationItem item;

  const NotificationDetailGenericScreen({Key? key, required this.item}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text(item.title)),
      body: Padding(
        padding: EdgeInsets.all(16),
        child: Text(
          item.subtitle,
          style: TextStyle(fontSize: 16),
        ),
      ),
    );
  }
}
