import 'package:flutter/material.dart';
import 'page/trips_page.dart';
import 'page/car_rental.dart';
import 'page/offers_page.dart';
import 'payment_screen.dart';
// import 'search_screen.dart';
import 'profile_screen.dart';
import 'history/history_screen.dart';
import 'message_screen.dart';
import 'page/hotels_page.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({Key? key}) : super(key: key);

  @override
  _HomeScreenState createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  int _currentIndex = 0;
  final List<Widget> _pages = [
    const HomePageContent(),
    PaymentScreen(),
    // SearchScreen(),
    HistoryScreen(),
    ProfileScreen(),
    MessageScreen(),
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
          // BottomNavigationBarItem(icon: Icon(Icons.search), label: 'Search'),
          BottomNavigationBarItem(
              icon: Icon(Icons.card_travel), label: 'Historys'),
          BottomNavigationBarItem(icon: Icon(Icons.person), label: 'Profile'),
          BottomNavigationBarItem(icon: Icon(Icons.message), label: 'Message'),
        ],
      ),
    );
  }
}

class HomePageContent extends StatelessWidget {
  const HomePageContent({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return ListView(
      padding: EdgeInsets.zero,
      children: [
        _buildHeader(context),
        const SizedBox(height: 10),
        _buildOptions(context),
        const SizedBox(height: 20),
        _buildSectionTitle('Popular Offers'),
        _buildPopularOffers(),
        const SizedBox(height: 20),
        _buildSectionTitle('Hotels Near You'),
        _buildHotelCard(
          context,
          hotelName: 'Grand Hyatt Resort',
          price: '\$1300/night',
          imageUrl: 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=800&q=80',
        ),
        _buildHotelCard(
          context,
          hotelName: 'Luxury Haven Inn',
          price: '\$150/night',
          imageUrl: 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&q=80',
        ),
        const SizedBox(height: 20),
      ],
    );
  }

  Widget _buildHeader(BuildContext context) {
    return Container(
      decoration: BoxDecoration(
        gradient: LinearGradient(
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
          colors: [
            Color.fromRGBO(102, 153, 255, 1.0),
            Color.fromRGBO(153, 204, 255, 1.0)
          ],
        ),
      ),
      child: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(16),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Flexible(
                child: Text(
                  "Let's Explore The World!",
                  style: TextStyle(
                    fontSize: 22,
                    fontWeight: FontWeight.bold,
                    color: Colors.white,
                  ),
                ),
              ),
              Stack(
                children: [
                  Container(
                    decoration: BoxDecoration(
                      color: Color.fromRGBO(255, 255, 255, 0.2),
                      borderRadius: BorderRadius.circular(12),
                    ),
                    child: IconButton(
                      icon:
                          const Icon(Icons.notifications, color: Colors.white),
                      onPressed: () {
                        Navigator.push(
                          context,
                          MaterialPageRoute(
                            builder: (context) => BookingApp(),
                          ),
                        );
                      },
                    ),
                  ),
                  Positioned(
                    right: 6,
                    top: 6,
                    child: Container(
                      padding: const EdgeInsets.all(4),
                      decoration: BoxDecoration(
                        color: Colors.red,
                        borderRadius: BorderRadius.circular(10),
                        boxShadow: const [
                          BoxShadow(
                            color: Colors.black26,
                            blurRadius: 4,
                            offset: Offset(0, 2),
                          ),
                        ],
                      ),
                      constraints: const BoxConstraints(
                        minWidth: 18,
                        minHeight: 18,
                      ),
                      child: const Text(
                        '5',
                        style: TextStyle(
                          color: Colors.white,
                          fontSize: 10,
                          fontWeight: FontWeight.bold,
                        ),
                        textAlign: TextAlign.center,
                      ),
                    ),
                  ),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildOptions(BuildContext context) {
  final options = ['Trips', 'Hotels', 'CarRental', 'Offers'];
  final colors = [
    Colors.purple,
    Colors.pink,
    Colors.orange,
    Colors.blueAccent
  ];
  final icons = [
    Icons.airplanemode_active,
    Icons.hotel,
    Icons.directions_car, // updated icon for CarRental
    Icons.local_offer,
  ];
  final pages = [
    const TripsPage(),
    const HotelsPage(),
    CarRentalApp(), // updated navigation target
    const AllDealsPage(),
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

  Widget _buildPopularOffers() {
    final offers = [
      {
        'url':
            'https://images.unsplash.com/photo-1560347876-aeef00ee58a1?w=800&q=80',
        'name': 'Offer 1',
        'price': '\$150/night',
        'rating': 3.5,
      },
      {
        'url':
            'https://images.unsplash.com/photo-1505691723518-36a5ac3be353?w=800&q=80',
        'name': 'Offer 2',
        'price': '\$200/night',
        'rating': 4.0,
      },
    ];

    return Container(
      height: 220,
      padding: const EdgeInsets.only(left: 16),
      child: ListView.separated(
        scrollDirection: Axis.horizontal,
        itemCount: offers.length,
        separatorBuilder: (context, index) => const SizedBox(width: 12),
        itemBuilder: (context, index) {
          final offer = offers[index];
          final url = offer['url'] as String;
          final name = offer['name'] as String;
          final price = offer['price'] as String;
          final rating = offer['rating'] as double;

          return ClipRRect(
            borderRadius: BorderRadius.circular(12),
            child: Stack(
              children: [
                url.startsWith('http')
                    ? Image.network(
                        url,
                        width: 280,
                        height: 220,
                        fit: BoxFit.cover,
                        errorBuilder: (context, error, stackTrace) {
                          return Container(
                            width: 280,
                            height: 220,
                            color: Colors.grey[300],
                            child: const Icon(Icons.broken_image, size: 40),
                          );
                        },
                      )
                    : Image.asset(
                        url,
                        width: 280,
                        height: 220,
                        fit: BoxFit.cover,
                      ),
                Positioned(
                  bottom: 12,
                  left: 12,
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        name,
                        style: TextStyle(
                          fontSize: 16,
                          color: Colors.white,
                          shadows: [
                            Shadow(
                              color: Colors.black.withOpacity(0.5),
                              offset: Offset(2, 2),
                              blurRadius: 4,
                            ),
                          ],
                        ),
                      ),
                      Row(
                        children: [
                          Text(
                            price,
                            style: TextStyle(
                              fontSize: 16,
                              color: Colors.white,
                              shadows: [
                                Shadow(
                                  color: Colors.black.withOpacity(0.5),
                                  offset: Offset(2, 2),
                                  blurRadius: 4,
                                ),
                              ],
                            ),
                          ),
                          const SizedBox(width: 8),
                          Row(
                            children: List.generate(
                              5,
                              (index) => Icon(
                                index < (rating * 1).floor()
                                    ? Icons.star
                                    : Icons.star_border,
                                size: 16,
                                color: Colors.amber,
                              ),
                            ),
                          ),
                        ],
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

  Widget _buildHotelCard(BuildContext context,
      {required String hotelName,
      required String price,
      required String imageUrl}) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      child: Card(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
        clipBehavior: Clip.antiAlias,
        elevation: 4,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Image.network(
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

void main() {
  runApp(const BookingApp());
}

// ==================== MAIN APP ====================
class BookingApp extends StatelessWidget {
  const BookingApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Travel Notifications',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        primarySwatch: Colors.blue,
        useMaterial3: true,
      ),
      home: const NotificationListScreen(),
    );
  }
}

// ==================== MODEL ====================
class NotificationItem {
  final String id;
  final String title;
  final String subtitle;
  final String category; // message, flight, hotel, offer, alert
  final String imageUrl;
  bool isUnread;

  NotificationItem({
    required this.id,
    required this.title,
    required this.subtitle,
    required this.category,
    required this.imageUrl,
    this.isUnread = true,
  });
}

// ==================== SAMPLE DATA WITH FIXED TRAVEL IMAGES ====================
final List<NotificationItem> notificationsData = [
  NotificationItem(
    id: '1',
    title: 'New Message from Agent',
    subtitle: 'Check your upcoming trip details to Paris.',
    category: 'message',
    imageUrl:
        'https://images.unsplash.com/photo-1554415707-6e8cfc93fe23?fit=crop&w=800&q=80',
  ),
  NotificationItem(
    id: '2',
    title: 'Flight Confirmed',
    subtitle: 'Flight AF123 from NYC to Paris is confirmed.',
    category: 'flight',
    imageUrl:
        'https://images.unsplash.com/photo-1529070538774-1843cb3265df?fit=crop&w=800&q=80',
  ),
  NotificationItem(
    id: '3',
    title: 'Hotel Reserved',
    subtitle: 'Pan Pacific Hotel booked for 2 nights.',
    category: 'hotel',
    imageUrl:
        'https://images.unsplash.com/photo-1501117716987-c8e3f67a3e12?fit=crop&w=800&q=80',
  ),
  NotificationItem(
    id: '4',
    title: '50% OFF Weekend Getaway',
    subtitle: 'Limited time offer for August 2025.',
    category: 'offer',
    imageUrl:
        'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?fit=crop&w=800&q=80',
  ),
  NotificationItem(
    id: '5',
    title: 'Weather Alert',
    subtitle: 'Heavy rain expected during your trip.',
    category: 'alert',
    imageUrl:
        'https://images.unsplash.com/photo-1501594907352-04cda38ebc29?fit=crop&w=800&q=80',
  ),
];

// ==================== NOTIFICATION LIST SCREEN ====================
class NotificationListScreen extends StatefulWidget {
  const NotificationListScreen({super.key});

  @override
  State<NotificationListScreen> createState() => _NotificationListScreenState();
}

class _NotificationListScreenState extends State<NotificationListScreen> {
  String selectedFilter = 'All';
  final List<String> filterOptions = [
    'All',
    'Unread',
    'Messages',
    'Flights',
    'Hotels',
    'Offers',
    'Alerts',
  ];

  List<NotificationItem> get filteredNotifications {
    switch (selectedFilter) {
      case 'Unread':
        return notificationsData.where((n) => n.isUnread).toList();
      case 'Messages':
        return notificationsData.where((n) => n.category == 'message').toList();
      case 'Flights':
        return notificationsData.where((n) => n.category == 'flight').toList();
      case 'Hotels':
        return notificationsData.where((n) => n.category == 'hotel').toList();
      case 'Offers':
        return notificationsData.where((n) => n.category == 'offer').toList();
      case 'Alerts':
        return notificationsData.where((n) => n.category == 'alert').toList();
      default:
        return notificationsData;
    }
  }

  List<Color> categoryGradient(String category) {
    switch (category) {
      case 'message':
        return [Colors.orange, Colors.deepOrangeAccent];
      case 'flight':
        return [Colors.blue, Colors.lightBlueAccent];
      case 'hotel':
        return [Colors.green, Colors.lightGreenAccent];
      case 'offer':
        return [Colors.purple, Colors.deepPurpleAccent];
      case 'alert':
        return [Colors.red, Colors.deepOrange];
      default:
        return [Colors.grey, Colors.blueGrey];
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Travel Notifications'),
        elevation: 0,
      ),
      body: Column(
        children: [
          // ===== Filter Chips =====
          SizedBox(
            height: 60,
            child: ListView.builder(
              scrollDirection: Axis.horizontal,
              padding: const EdgeInsets.symmetric(horizontal: 16),
              itemCount: filterOptions.length,
              itemBuilder: (context, index) {
                final option = filterOptions[index];
                final isSelected = selectedFilter == option;
                return Padding(
                  padding: const EdgeInsets.only(right: 8),
                  child: ChoiceChip(
                    label: Text(option),
                    selected: isSelected,
                    onSelected: (_) {
                      setState(() {
                        selectedFilter = option;
                      });
                    },
                    selectedColor: Colors.blue.withOpacity(0.2),
                    backgroundColor: Colors.grey.shade200,
                    labelStyle: TextStyle(
                        color: isSelected ? Colors.blue : Colors.black),
                  ),
                );
              },
            ),
          ),
          const Divider(height: 1),
          // ===== Notification Cards =====
          Expanded(
            child: ListView.builder(
              padding: const EdgeInsets.all(12),
              itemCount: filteredNotifications.length,
              itemBuilder: (context, index) {
                final item = filteredNotifications[index];

                return GestureDetector(
                  onTap: () {
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (_) => NotificationDetailScreen(
                          notification: item,
                          gradientColors: categoryGradient(item.category),
                        ),
                      ),
                    );
                  },
                  child: Container(
                    margin: const EdgeInsets.symmetric(vertical: 8),
                    decoration: BoxDecoration(
                      borderRadius: BorderRadius.circular(20),
                      boxShadow: const [
                        BoxShadow(
                            color: Colors.black26,
                            blurRadius: 6,
                            offset: Offset(0, 4))
                      ],
                    ),
                    child: Stack(
                      children: [
                        ClipRRect(
                          borderRadius: BorderRadius.circular(20),
                          child: Image.network(
                            item.imageUrl,
                            height: 180,
                            width: double.infinity,
                            fit: BoxFit.cover,
                            errorBuilder: (context, error, stackTrace) {
                              return Container(
                                height: 180,
                                color: Colors.grey.shade300,
                                child: const Icon(Icons.image, size: 60),
                              );
                            },
                          ),
                        ),
                        Container(
                          height: 180,
                          decoration: BoxDecoration(
                            borderRadius: BorderRadius.circular(20),
                            gradient: LinearGradient(
                              colors: [
                                Colors.black.withOpacity(0.5),
                                Colors.transparent
                              ],
                              begin: Alignment.bottomCenter,
                              end: Alignment.topCenter,
                            ),
                          ),
                        ),
                        Positioned(
                          bottom: 16,
                          left: 16,
                          right: 16,
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                item.title,
                                style: const TextStyle(
                                    color: Colors.white,
                                    fontSize: 20,
                                    fontWeight: FontWeight.bold),
                              ),
                              const SizedBox(height: 4),
                              Text(
                                item.subtitle,
                                style: const TextStyle(
                                    color: Colors.white70, fontSize: 14),
                              ),
                            ],
                          ),
                        ),
                        if (item.isUnread)
                          Positioned(
                            top: 12,
                            right: 12,
                            child: Container(
                              width: 12,
                              height: 12,
                              decoration: const BoxDecoration(
                                color: Colors.blue,
                                shape: BoxShape.circle,
                              ),
                            ),
                          )
                      ],
                    ),
                  ),
                );
              },
            ),
          ),
        ],
      ),
    );
  }
}

// ==================== NOTIFICATION DETAIL SCREEN ====================
class NotificationDetailScreen extends StatelessWidget {
  final NotificationItem notification;
  final List<Color> gradientColors;

  const NotificationDetailScreen(
      {super.key, required this.notification, required this.gradientColors});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(notification.title),
        backgroundColor: gradientColors.first,
      ),
      body: Container(
        decoration: BoxDecoration(
          gradient: LinearGradient(
            colors: gradientColors,
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
          ),
        ),
        child: ListView(
          padding: const EdgeInsets.all(16),
          children: [
            ClipRRect(
              borderRadius: BorderRadius.circular(20),
              child: Image.network(
                notification.imageUrl,
                height: 250,
                width: double.infinity,
                fit: BoxFit.cover,
                errorBuilder: (context, error, stackTrace) {
                  return Container(
                    height: 250,
                    color: Colors.grey.shade300,
                    child: const Icon(Icons.image, size: 60),
                  );
                },
              ),
            ),
            const SizedBox(height: 20),
            Card(
              shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(20)),
              elevation: 6,
              child: Padding(
                padding: const EdgeInsets.all(20),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      notification.title,
                      style: const TextStyle(
                          fontSize: 24, fontWeight: FontWeight.bold),
                    ),
                    const SizedBox(height: 12),
                    Text(
                      notification.subtitle,
                      style: const TextStyle(fontSize: 16),
                    ),
                    const SizedBox(height: 20),
                    ElevatedButton.icon(
                      onPressed: () {},
                      icon: Icon(
                        notification.category == 'message'
                            ? Icons.reply
                            : notification.category == 'flight'
                                ? Icons.flight
                                : notification.category == 'hotel'
                                    ? Icons.hotel
                                    : notification.category == 'offer'
                                        ? Icons.local_offer
                                        : Icons.warning,
                      ),
                      label: Text(notification.category == 'message'
                          ? 'Reply to Agent'
                          : notification.category == 'flight'
                              ? 'View Boarding Pass'
                              : notification.category == 'hotel'
                                  ? 'View Booking'
                                  : notification.category == 'offer'
                                      ? 'Book Now'
                                      : 'View Alert'),
                      style: ElevatedButton.styleFrom(
                        padding: const EdgeInsets.symmetric(vertical: 14),
                        shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(12)),
                        backgroundColor: gradientColors.last,
                      ),
                    )
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
