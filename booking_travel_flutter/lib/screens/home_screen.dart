import 'package:flutter/material.dart';
import 'search_screen.dart';

class HomeScreen extends StatefulWidget {
  @override
  _HomeScreenState createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  int _currentIndex = 0;

  final List<Widget> _pages = [
    HomePageContent(),
    SearchScreen(),
    Center(child: Text('Trips Page')),
    Center(child: Text('Profile Page')),
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
        items: [
          BottomNavigationBarItem(icon: Icon(Icons.home), label: 'Home'),
          BottomNavigationBarItem(icon: Icon(Icons.search), label: 'Search'),
          BottomNavigationBarItem(icon: Icon(Icons.card_travel), label: 'Trips'),
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
        _buildHeader(),
        SizedBox(height: 10),
        _buildOptions(),
        SizedBox(height: 20),
        _buildSectionTitle('Popular Offer'),
        _buildPopularOffers(),
        SizedBox(height: 20),
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
        SizedBox(height: 20),
      ],
    );
  }

  Widget _buildHeader() {
    return Padding(
      padding: EdgeInsets.all(16),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Flexible(
            child: Text(
              "Let's Explore The World!",
              style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold),
            ),
          ),
          CircleAvatar(
            backgroundImage: NetworkImage(
              'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=100&q=80',
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildOptions() {
    final options = ['Trips', 'Hotels', 'Flights', 'Offers'];
    final colors = [Colors.purple, Colors.pink, Colors.orange, Colors.blueAccent];
    final icons = [
      Icons.airplanemode_active,
      Icons.hotel,
      Icons.flight,
      Icons.local_offer,
    ];

    return Padding(
      padding: EdgeInsets.symmetric(horizontal: 16),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: List.generate(options.length, (index) {
          return Column(
            children: [
              CircleAvatar(
                backgroundColor: colors[index],
                radius: 30,
                child: Icon(icons[index], color: Colors.white, size: 28),
              ),
              SizedBox(height: 8),
              Text(
                options[index],
                style: TextStyle(fontWeight: FontWeight.bold),
              ),
            ],
          );
        }),
      ),
    );
  }

  Widget _buildSectionTitle(String title) {
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      child: Text(
        title,
        style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
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
      padding: EdgeInsets.only(left: 16),
      child: ListView.separated(
        scrollDirection: Axis.horizontal,
        itemCount: offers.length,
        separatorBuilder: (context, index) => SizedBox(width: 12),
        itemBuilder: (context, index) {
          return ClipRRect(
            borderRadius: BorderRadius.circular(12),
            child: Image.network(
              offers[index],
              width: 280,
              height: 180,
              fit: BoxFit.cover,
              errorBuilder: (context, error, stackTrace) {
                return Container(
                  width: 280,
                  height: 180,
                  color: Colors.grey[300],
                  child: Icon(Icons.broken_image, size: 40),
                );
              },
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
      padding: EdgeInsets.symmetric(horizontal: 16, vertical: 8),
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
                  child: Icon(Icons.broken_image, size: 40),
                );
              },
            ),
            Padding(
              padding: EdgeInsets.all(12),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text(hotelName,
                      style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
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
