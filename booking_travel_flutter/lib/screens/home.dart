import 'package:flutter/material.dart';
import '../services/user_service.dart' as user_service;
import '../models/province.dart';
import 'adventures/adventures_page.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  _HomeScreenState createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  int _selectedIndex = 0;
  late Future<List<user_service.Province>> _provincesFuture;

  @override
  void initState() {
    super.initState();
    _provincesFuture = user_service.UserService.fetchProvinces();
  }

  // List of screens for each navigation item
  List<Widget> _widgetOptions(BuildContext context) => <Widget>[
        Center(
          child: FutureBuilder<List<user_service.Province>>(
            future: _provincesFuture,
            builder: (context, snapshot) {
              if (snapshot.connectionState == ConnectionState.waiting) {
                return const CircularProgressIndicator();
              } else if (snapshot.hasError) {
                return Text('Error loading provinces: ${snapshot.error}');
              } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
                return const Text('No provinces available.');
              } else {
                final provinces = snapshot.data!;
                return ListView.builder(
                  itemCount: provinces.length,
                  itemBuilder: (context, index) {
                    final province = provinces[index];
                    return ListTile(
                      title: Text(province.name),
                      trailing: const Icon(Icons.arrow_forward),
                      onTap: () {
                        Navigator.push(
                          context,
                          MaterialPageRoute(
                            builder: (context) => AdventuresPage(
                              provinceId: province.id,
                              provinceName: province.name,
                            ),
                          ),
                        );
                      },
                    );
                  },
                );
              }
            },
          ),
        ),
        const Center(child: Text('Payment Screen', style: TextStyle(fontSize: 24))),
        const Center(child: Text('Search Screen', style: TextStyle(fontSize: 24))),
        const Center(child: Text('Historys Screen', style: TextStyle(fontSize: 24))),
        const Center(child: Text('Profile Screen', style: TextStyle(fontSize: 24))),
      ];

  void _onItemTapped(int index) {
    setState(() {
      _selectedIndex = index;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Row(
          children: [
            Icon(Icons.flight_takeoff, color: Colors.white, size: 28),
            SizedBox(width: 8),
            Text('Booking Travel',
                style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold)),
          ],
        ),
        actions: [
          IconButton(
            icon: const Icon(Icons.logout, color: Colors.white),
            tooltip: 'Logout',
            onPressed: () async {
              await user_service.UserService.logoutUser();
              Navigator.pushReplacementNamed(context, '/login');
            },
          ),
        ],
        backgroundColor: const Color(0xFF00796B),
        elevation: 4,
      ),
      body: Container(
        decoration: const BoxDecoration(
          gradient: LinearGradient(
            colors: [Color(0xFFE0F2F1), Color(0xFFFFFFFF)],
            begin: Alignment.topCenter,
            end: Alignment.bottomCenter,
          ),
        ),
        child: _widgetOptions(context).elementAt(_selectedIndex),
      ),
      bottomNavigationBar: BottomNavigationBar(
        items: const <BottomNavigationBarItem>[
          BottomNavigationBarItem(
            icon: Icon(Icons.home),
            label: 'Home',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.payment),
            label: 'Payment',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.search),
            label: 'Search',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.flight_land),
            label: 'Historys',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.person),
            label: 'Profile',
          ),
        ],
        currentIndex: _selectedIndex,
        selectedItemColor: const Color(0xFF00796B),
        unselectedItemColor: Colors.grey,
        onTap: _onItemTapped,
        backgroundColor: Colors.white,
        elevation: 10,
      ),
    );
  }
}
