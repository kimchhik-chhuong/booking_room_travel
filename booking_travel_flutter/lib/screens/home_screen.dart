import 'package:flutter/material.dart';
import 'page/trips_page.dart';
import 'page/hotels_page.dart' hide TripScreen;
import 'page/flights_page.dart';
import 'page/offers_page.dart';
import 'payment_screen.dart';
import 'search_screen.dart';
import 'profile_screen.dart';
import 'history/history_screen.dart';

class HomeScreen extends StatefulWidget {
  @override
  _HomeScreenState createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  int _currentIndex = 0;
  int _notificationCount = 5; // Updated count
  final List<Widget> _pages = [
    HomePageContent(),
    PaymentScreen(),
    SearchScreen(),
    HistoryScreen(),
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
        _buildSectionTitle('Popular Offers'),
        _buildPopularOffers(),
        const SizedBox(height: 20),
        _buildSectionTitle('Hotels Near You'),
        _buildHotelCard(
          context,
          hotelName: 'Grand Hyatt Resort',
          price: '\$1300/night',
          imageUrl: '../lib/assets/room2.jpg',
        ),
        _buildHotelCard(
          context,
          hotelName: 'Luxury Haven Inn',
          price: '\$150/night',
          imageUrl: '../lib/assets/room1.png',
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
          colors: [Colors.blue.shade400, Colors.blue.shade600],
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
                      color: Colors.white.withOpacity(0.2),
                      borderRadius: BorderRadius.circular(12),
                    ),
                    child: IconButton(
                      icon: Icon(Icons.notifications, color: Colors.white),
                      onPressed: () {
                        Navigator.push(
                          context,
                          MaterialPageRoute(
                            builder: (context) => NotificationDetailScreen(),
                          ),
                        );
                      },
                    ),
                  ),
                  Positioned(
                    right: 6,
                    top: 6,
                    child: Container(
                      padding: EdgeInsets.all(4),
                      decoration: BoxDecoration(
                        color: Colors.red,
                        borderRadius: BorderRadius.circular(10),
                        boxShadow: [
                          BoxShadow(
                            color: Colors.black26,
                            blurRadius: 4,
                            offset: Offset(0, 2),
                          ),
                        ],
                      ),
                      constraints: BoxConstraints(
                        minWidth: 18,
                        minHeight: 18,
                      ),
                      child: Text(
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
      TripsPage(),
      HotelsPage(),
      FlightsPage(),
      OffersPage(),
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
        'url': 'https://images.unsplash.com/photo-1560347876-aeef00ee58a1?w=800&q=80',
        'name': 'Offer 1',
        'price': '\$150/night',
        'rating': 3.5,
      },
      {
        'url': 'https://images.unsplash.com/photo-1505691723518-36a5ac3be353?w=800&q=80',
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
                      style: TextStyle(
                          fontSize: 16, fontWeight: FontWeight.bold)),
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
// Enhanced Notification Detail Screen
// =======================
class NotificationDetailScreen extends StatefulWidget {
  @override
  _NotificationDetailScreenState createState() => _NotificationDetailScreenState();
}

class _NotificationDetailScreenState extends State<NotificationDetailScreen> {
  List<NotificationItem> notifications = [
    NotificationItem(
      id: '1',
      icon: Icons.mark_email_unread,
      color: Colors.orange,
      title: 'New Message Received',
      subtitle: 'You have 3 unread messages from your travel agent.',
      dateTime: DateTime.now().subtract(Duration(minutes: 5)),
      isRead: false,
      priority: NotificationPriority.high,
      category: NotificationCategory.message,
    ),
    NotificationItem(
      id: '2',
      icon: Icons.flight_takeoff,
      color: Colors.blue,
      title: 'Flight Confirmed',
      subtitle: 'Your booking to Paris has been confirmed. Flight AF123.',
      dateTime: DateTime.now().subtract(Duration(hours: 2)),
      isRead: false,
      priority: NotificationPriority.high,
      category: NotificationCategory.booking,
    ),
    NotificationItem(
      id: '3',
      icon: Icons.hotel,
      color: Colors.green,
      title: 'Hotel Reserved',
      subtitle: 'Pan Pacific Hotel - 2 nights stay confirmed.',
      dateTime: DateTime.now().subtract(Duration(days: 1)),
      isRead: true,
      priority: NotificationPriority.medium,
      category: NotificationCategory.booking,
    ),
    NotificationItem(
      id: '4',
      icon: Icons.local_offer,
      color: Colors.purple,
      title: 'Special Offer Available',
      subtitle: '50% off on weekend getaways. Limited time offer!',
      dateTime: DateTime.now().subtract(Duration(days: 2)),
      isRead: false,
      priority: NotificationPriority.low,
      category: NotificationCategory.promotion,
    ),
    NotificationItem(
      id: '5',
      icon: Icons.warning,
      color: Colors.red,
      title: 'Weather Alert',
      subtitle: 'Heavy rain expected at your destination. Pack accordingly.',
      dateTime: DateTime.now().subtract(Duration(hours: 6)),
      isRead: false,
      priority: NotificationPriority.high,
      category: NotificationCategory.alert,
    ),
  ];

  String selectedFilter = 'All';
  final List<String> filterOptions = ['All', 'Unread', 'Bookings', 'Messages', 'Offers'];

  List<NotificationItem> get filteredNotifications {
    switch (selectedFilter) {
      case 'Unread':
        return notifications.where((n) => !n.isRead).toList();
      case 'Bookings':
        return notifications.where((n) => n.category == NotificationCategory.booking).toList();
      case 'Messages':
        return notifications.where((n) => n.category == NotificationCategory.message).toList();
      case 'Offers':
        return notifications.where((n) => n.category == NotificationCategory.promotion).toList();
      default:
        return notifications;
    }
  }

  void _markAsRead(String id) {
    setState(() {
      final index = notifications.indexWhere((n) => n.id == id);
      if (index != -1) {
        notifications[index] = notifications[index].copyWith(isRead: true);
      }
    });
  }

  void _markAllAsRead() {
    setState(() {
      notifications = notifications.map((n) => n.copyWith(isRead: true)).toList();
    });
  }

  void _deleteNotification(String id) {
    setState(() {
      notifications.removeWhere((n) => n.id == id);
    });
  }

  String _formatDateTime(DateTime dt) {
    final now = DateTime.now();
    final difference = now.difference(dt);

    if (difference.inMinutes < 60) {
      return '${difference.inMinutes}m ago';
    } else if (difference.inHours < 24) {
      return '${difference.inHours}h ago';
    } else if (difference.inDays < 7) {
      return '${difference.inDays}d ago';
    } else {
      return '${dt.day}/${dt.month}/${dt.year}';
    }
  }

  @override
  Widget build(BuildContext context) {
    final unreadCount = notifications.where((n) => !n.isRead).length;

    return Scaffold(
      appBar: AppBar(
        title: Text('Notifications ($unreadCount)'),
        backgroundColor: Colors.blue,
        elevation: 0,
        actions: [
          if (unreadCount > 0)
            TextButton(
              onPressed: _markAllAsRead,
              child: Text(
                'Mark all read',
                style: TextStyle(color: Colors.white),
              ),
            ),
          PopupMenuButton<String>(
            icon: Icon(Icons.more_vert, color: Colors.white),
            onSelected: (value) {
              if (value == 'clear_all') {
                setState(() {
                  notifications.clear();
                });
              }
            },
            itemBuilder: (context) => [
              PopupMenuItem(
                value: 'clear_all',
                child: Row(
                  children: [
                    Icon(Icons.clear_all, color: Colors.red),
                    SizedBox(width: 8),
                    Text('Clear All'),
                  ],
                ),
              ),
            ],
          ),
        ],
      ),
      body: Column(
        children: [
          // Filter chips
          Container(
            height: 60,
            padding: EdgeInsets.symmetric(vertical: 8),
            child: ListView.builder(
              scrollDirection: Axis.horizontal,
              padding: EdgeInsets.symmetric(horizontal: 16),
              itemCount: filterOptions.length,
              itemBuilder: (context, index) {
                final option = filterOptions[index];
                final isSelected = selectedFilter == option;
                return Padding(
                  padding: EdgeInsets.only(right: 8),
                  child: FilterChip(
                    label: Text(option),
                    selected: isSelected,
                    onSelected: (selected) {
                      setState(() {
                        selectedFilter = option;
                      });
                    },
                    selectedColor: Colors.blue.withOpacity(0.2),
                    checkmarkColor: Colors.blue,
                  ),
                );
              },
            ),
          ),
          Divider(height: 1),
          // Notifications list
          Expanded(
            child: filteredNotifications.isEmpty
                ? Center(
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Icon(
                          Icons.notifications_none,
                          size: 64,
                          color: Colors.grey,
                        ),
                        SizedBox(height: 16),
                        Text(
                          'No notifications',
                          style: TextStyle(
                            fontSize: 18,
                            color: Colors.grey,
                          ),
                        ),
                      ],
                    ),
                  )
                : ListView.separated(
                    padding: const EdgeInsets.all(8),
                    itemCount: filteredNotifications.length,
                    separatorBuilder: (context, index) => Divider(height: 1),
                    itemBuilder: (context, index) {
                      final item = filteredNotifications[index];
                      return Dismissible(
                        key: Key(item.id),
                        direction: DismissDirection.endToStart,
                        background: Container(
                          alignment: Alignment.centerRight,
                          padding: EdgeInsets.only(right: 20),
                          color: Colors.red,
                          child: Icon(
                            Icons.delete,
                            color: Colors.white,
                          ),
                        ),
                        onDismissed: (direction) {
                          _deleteNotification(item.id);
                          ScaffoldMessenger.of(context).showSnackBar(
                            SnackBar(
                              content: Text('Notification deleted'),
                              action: SnackBarAction(
                                label: 'Undo',
                                onPressed: () {
                                  setState(() {
                                    notifications.add(item);
                                  });
                                },
                              ),
                            ),
                          );
                        },
                        child: Container(
                          margin: EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                          decoration: BoxDecoration(
                            color: item.isRead ? Colors.white : Colors.blue.withOpacity(0.05),
                            borderRadius: BorderRadius.circular(12),
                            border: Border.all(
                              color: item.isRead ? Colors.grey.withOpacity(0.2) : Colors.blue.withOpacity(0.3),
                            ),
                          ),
                          child: ListTile(
                            contentPadding: EdgeInsets.all(12),
                            leading: Stack(
                              children: [
                                CircleAvatar(
                                  backgroundColor: item.color.withOpacity(0.2),
                                  radius: 24,
                                  child: Icon(item.icon, color: item.color, size: 24),
                                ),
                                if (item.priority == NotificationPriority.high)
                                  Positioned(
                                    right: 0,
                                    top: 0,
                                    child: Container(
                                      width: 12,
                                      height: 12,
                                      decoration: BoxDecoration(
                                        color: Colors.red,
                                        shape: BoxShape.circle,
                                        border: Border.all(color: Colors.white, width: 2),
                                      ),
                                    ),
                                  ),
                              ],
                            ),
                            title: Row(
                              children: [
                                Expanded(
                                  child: Text(
                                    item.title,
                                    style: TextStyle(
                                      fontWeight: item.isRead ? FontWeight.normal : FontWeight.bold,
                                      fontSize: 16,
                                    ),
                                  ),
                                ),
                                if (!item.isRead)
                                  Container(
                                    width: 8,
                                    height: 8,
                                    decoration: BoxDecoration(
                                      color: Colors.blue,
                                      shape: BoxShape.circle,
                                    ),
                                  ),
                              ],
                            ),
                            subtitle: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                SizedBox(height: 4),
                                Text(
                                  item.subtitle,
                                  style: TextStyle(
                                    color: Colors.grey[600],
                                    fontSize: 14,
                                  ),
                                ),
                                SizedBox(height: 8),
                                Row(
                                  children: [
                                    Icon(
                                      Icons.access_time,
                                      size: 12,
                                      color: Colors.grey[500],
                                    ),
                                    SizedBox(width: 4),
                                    Text(
                                      _formatDateTime(item.dateTime),
                                      style: TextStyle(
                                        fontSize: 12,
                                        color: Colors.grey[500],
                                      ),
                                    ),
                                    Spacer(),
                                    _buildPriorityChip(item.priority),
                                  ],
                                ),
                              ],
                            ),
                            isThreeLine: true,
                            onTap: () {
                              if (!item.isRead) {
                                _markAsRead(item.id);
                              }
                              Navigator.push(
                                context,
                                MaterialPageRoute(
                                  builder: (context) {
                                    switch (item.category) {
                                      case NotificationCategory.message:
                                        return NotificationDetailMessageScreen(notification: item);
                                      case NotificationCategory.booking:
                                        if (item.title.contains('Flight')) {
                                          return NotificationDetailFlightScreen(notification: item);
                                        } else {
                                          return NotificationDetailHotelScreen(notification: item);
                                        }
                                      case NotificationCategory.promotion:
                                        return NotificationDetailOfferScreen(notification: item);
                                      case NotificationCategory.alert:
                                        return NotificationDetailAlertScreen(notification: item);
                                      default:
                                        return NotificationDetailGenericScreen(item: item);
                                    }
                                  },
                                ),
                              );
                            },
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

  Widget _buildPriorityChip(NotificationPriority priority) {
    Color color;
    String text;
    
    switch (priority) {
      case NotificationPriority.high:
        color = Colors.red;
        text = 'High';
        break;
      case NotificationPriority.medium:
        color = Colors.orange;
        text = 'Medium';
        break;
      case NotificationPriority.low:
        color = Colors.green;
        text = 'Low';
        break;
    }

    return Container(
      padding: EdgeInsets.symmetric(horizontal: 6, vertical: 2),
      decoration: BoxDecoration(
        color: color.withOpacity(0.1),
        borderRadius: BorderRadius.circular(8),
        border: Border.all(color: color.withOpacity(0.3)),
      ),
      child: Text(
        text,
        style: TextStyle(
          color: color,
          fontSize: 10,
          fontWeight: FontWeight.bold,
        ),
      ),
    );
  }
}

// Enhanced NotificationItem class
class NotificationItem {
  final String id;
  final IconData icon;
  final Color color;
  final String title;
  final String subtitle;
  final DateTime dateTime;
  final bool isRead;
  final NotificationPriority priority;
  final NotificationCategory category;

  NotificationItem({
    required this.id,
    required this.icon,
    required this.color,
    required this.title,
    required this.subtitle,
    required this.dateTime,
    this.isRead = false,
    this.priority = NotificationPriority.medium,
    this.category = NotificationCategory.general,
  });

  NotificationItem copyWith({
    String? id,
    IconData? icon,
    Color? color,
    String? title,
    String? subtitle,
    DateTime? dateTime,
    bool? isRead,
    NotificationPriority? priority,
    NotificationCategory? category,
  }) {
    return NotificationItem(
      id: id ?? this.id,
      icon: icon ?? this.icon,
      color: color ?? this.color,
      title: title ?? this.title,
      subtitle: subtitle ?? this.subtitle,
      dateTime: dateTime ?? this.dateTime,
      isRead: isRead ?? this.isRead,
      priority: priority ?? this.priority,
      category: category ?? this.category,
    );
  }
}

enum NotificationPriority { high, medium, low }
enum NotificationCategory { message, booking, promotion, alert, general }

// ======= Enhanced Notification Detail Pages =======
class NotificationDetailMessageScreen extends StatelessWidget {
  final NotificationItem notification;

  const NotificationDetailMessageScreen({Key? key, required this.notification}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Message Details'),
        backgroundColor: Colors.orange,
      ),
      body: Padding(
        padding: EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Card(
              child: Padding(
                padding: EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        Icon(Icons.message, color: Colors.orange),
                        SizedBox(width: 8),
                        Text(
                          'Travel Agent Message',
                          style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                        ),
                      ],
                    ),
                    SizedBox(height: 16),
                    Text(
                      'Hello! I hope you\'re excited about your upcoming trip to Paris. I wanted to reach out with some important updates and recommendations for your journey.',
                      style: TextStyle(fontSize: 16),
                    ),
                    SizedBox(height: 12),
                    Text(
                      '• Your flight departure gate has been changed to Gate B12\n'
                      '• Weather forecast shows sunny skies for your entire stay\n'
                      '• I\'ve arranged a complimentary airport pickup service\n'
                      '• Don\'t forget to try the local cuisine recommendations I sent earlier',
                      style: TextStyle(fontSize: 14),
                    ),
                    SizedBox(height: 16),
                    Row(
                      children: [
                        ElevatedButton.icon(
                          onPressed: () {},
                          icon: Icon(Icons.reply),
                          label: Text('Reply'),
                          style: ElevatedButton.styleFrom(backgroundColor: Colors.orange),
                        ),
                        SizedBox(width: 12),
                        OutlinedButton.icon(
                          onPressed: () {},
                          icon: Icon(Icons.call),
                          label: Text('Call Agent'),
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

class NotificationDetailFlightScreen extends StatelessWidget {
  final NotificationItem notification;

  const NotificationDetailFlightScreen({Key? key, required this.notification}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Flight Confirmation'),
        backgroundColor: Colors.blue,
      ),
      body: Padding(
        padding: EdgeInsets.all(16),
        child: Column(
          children: [
            Card(
              child: Padding(
                padding: EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        Icon(Icons.flight_takeoff, color: Colors.blue),
                        SizedBox(width: 8),
                        Text(
                          'Flight Confirmed',
                          style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                        ),
                      ],
                    ),
                    SizedBox(height: 16),
                    _buildFlightDetail('Flight Number', 'AF123'),
                    _buildFlightDetail('Route', 'New York (JFK) → Paris (CDG)'),
                    _buildFlightDetail('Departure', 'July 30, 2025 - 10:00 AM'),
                    _buildFlightDetail('Arrival', 'July 31, 2025 - 12:30 PM'),
                    _buildFlightDetail('Seat', '12A (Window)'),
                    _buildFlightDetail('Gate', 'B12 (Updated)'),
                    SizedBox(height: 16),
                    Row(
                      children: [
                        ElevatedButton.icon(
                          onPressed: () {},
                          icon: Icon(Icons.qr_code),
                          label: Text('Boarding Pass'),
                          style: ElevatedButton.styleFrom(backgroundColor: Colors.blue),
                        ),
                        SizedBox(width: 12),
                        OutlinedButton.icon(
                          onPressed: () {},
                          icon: Icon(Icons.calendar_today),
                          label: Text('Add to Calendar'),
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

  Widget _buildFlightDetail(String label, String value) {
    return Padding(
      padding: EdgeInsets.symmetric(vertical: 4),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 100,
            child: Text(
              '$label:',
              style: TextStyle(fontWeight: FontWeight.w500, color: Colors.grey[600]),
            ),
          ),
          Expanded(
            child: Text(
              value,
              style: TextStyle(fontSize: 16),
            ),
          ),
        ],
      ),
    );
  }
}

class NotificationDetailHotelScreen extends StatelessWidget {
  final NotificationItem notification;

  const NotificationDetailHotelScreen({Key? key, required this.notification}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Hotel Reservation'),
        backgroundColor: Colors.green,
      ),
      body: Padding(
        padding: EdgeInsets.all(16),
        child: Column(
          children: [
            Card(
              child: Padding(
                padding: EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        Icon(Icons.hotel, color: Colors.green),
                        SizedBox(width: 8),
                        Text(
                          'Hotel Reservation Confirmed',
                          style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                        ),
                      ],
                    ),
                    SizedBox(height: 16),
                    _buildHotelDetail('Hotel', 'Pan Pacific Hotel'),
                    _buildHotelDetail('Location', 'Paris, France'),
                    _buildHotelDetail('Check-in', 'August 5, 2025 - 3:00 PM'),
                    _buildHotelDetail('Check-out', 'August 7, 2025 - 11:00 AM'),
                    _buildHotelDetail('Duration', '2 nights'),
                    _buildHotelDetail('Room Type', 'Deluxe King Room'),
                    _buildHotelDetail('Guests', '2 Adults'),
                    SizedBox(height: 16),
                    Row(
                      children: [
                        ElevatedButton.icon(
                          onPressed: () {},
                          icon: Icon(Icons.directions),
                          label: Text('Get Directions'),
                          style: ElevatedButton.styleFrom(backgroundColor: Colors.green),
                        ),
                        SizedBox(width: 12),
                        OutlinedButton.icon(
                          onPressed: () {},
                          icon: Icon(Icons.phone),
                          label: Text('Call Hotel'),
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

  Widget _buildHotelDetail(String label, String value) {
    return Padding(
      padding: EdgeInsets.symmetric(vertical: 4),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 100,
            child: Text(
              '$label:',
              style: TextStyle(fontWeight: FontWeight.w500, color: Colors.grey[600]),
            ),
          ),
          Expanded(
            child: Text(
              value,
              style: TextStyle(fontSize: 16),
            ),
          ),
        ],
      ),
    );
  }
}

class NotificationDetailOfferScreen extends StatelessWidget {
  final NotificationItem notification;

  const NotificationDetailOfferScreen({Key? key, required this.notification}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Special Offer'),
        backgroundColor: Colors.purple,
      ),
      body: Padding(
        padding: EdgeInsets.all(16),
        child: Column(
          children: [
            Card(
              child: Padding(
                padding: EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        Icon(Icons.local_offer, color: Colors.purple),
                        SizedBox(width: 8),
                        Text(
                          '50% OFF Weekend Getaways',
                          style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                        ),
                      ],
                    ),
                    SizedBox(height: 16),
                    Container(
                      padding: EdgeInsets.all(12),
                      decoration: BoxDecoration(
                        color: Colors.purple.withOpacity(0.1),
                        borderRadius: BorderRadius.circular(8),
                      ),
                      child: Text(
                        'Limited Time Offer - Valid until August 15, 2025',
                        style: TextStyle(
                          color: Colors.purple,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                    SizedBox(height: 16),
                    Text(
                      'Escape for the weekend with our exclusive 50% discount on selected destinations. Perfect for a quick getaway!',
                      style: TextStyle(fontSize: 16),
                    ),
                    SizedBox(height: 12),
                    Text(
                      '• Valid for bookings made before August 10, 2025\n'
                      '• Travel dates: Any weekend in August 2025\n'
                      '• Minimum 2-night stay required\n'
                      '• Cannot be combined with other offers',
                      style: TextStyle(fontSize: 14),
                    ),
                    SizedBox(height: 16),
                    SizedBox(
                      width: double.infinity,
                      child: ElevatedButton.icon(
                        onPressed: () {},
                        icon: Icon(Icons.shopping_cart),
                        label: Text('Book Now'),
                        style: ElevatedButton.styleFrom(
                          backgroundColor: Colors.purple,
                          padding: EdgeInsets.symmetric(vertical: 12),
                        ),
                      ),
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

class NotificationDetailAlertScreen extends StatelessWidget {
  final NotificationItem notification;

  const NotificationDetailAlertScreen({Key? key, required this.notification}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Weather Alert'),
        backgroundColor: Colors.red,
      ),
      body: Padding(
        padding: EdgeInsets.all(16),
        child: Column(
          children: [
            Card(
              child: Padding(
                padding: EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        Icon(Icons.warning, color: Colors.red),
                        SizedBox(width: 8),
                        Text(
                          'Weather Alert',
                          style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                        ),
                      ],
                    ),
                    SizedBox(height: 16),
                    Container(
                      padding: EdgeInsets.all(12),
                      decoration: BoxDecoration(
                        color: Colors.red.withOpacity(0.1),
                        borderRadius: BorderRadius.circular(8),
                      ),
                      child: Row(
                        children: [
                          Icon(Icons.umbrella, color: Colors.red),
                          SizedBox(width: 8),
                          Text(
                            'Heavy Rain Expected',
                            style: TextStyle(
                              color: Colors.red,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                        ],
                      ),
                    ),
                    SizedBox(height: 16),
                    Text(
                      'Heavy rainfall is expected at your destination (Paris) during your stay. Please pack accordingly and plan for indoor activities.',
                      style: TextStyle(fontSize: 16),
                    ),
                    SizedBox(height: 12),
                    Text(
                      'Recommendations:\n'
                      '• Pack waterproof clothing and umbrella\n'
                      '• Consider indoor attractions and museums\n'
                      '• Check for any travel disruptions\n'
                      '• Keep emergency contacts handy',
                      style: TextStyle(fontSize: 14),
                    ),
                    SizedBox(height: 16),
                    Row(
                      children: [
                        ElevatedButton.icon(
                          onPressed: () {},
                          icon: Icon(Icons.cloud),
                          label: Text('View Forecast'),
                          style: ElevatedButton.styleFrom(backgroundColor: Colors.red),
                        ),
                        SizedBox(width: 12),
                        OutlinedButton.icon(
                          onPressed: () {},
                          icon: Icon(Icons.map),
                          label: Text('Indoor Activities'),
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
