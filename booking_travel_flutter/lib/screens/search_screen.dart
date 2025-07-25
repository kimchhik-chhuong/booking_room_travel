import 'package:flutter/material.dart';

class SearchScreen extends StatefulWidget {
  @override
  _SearchScreenState createState() => _SearchScreenState();
}

class _SearchScreenState extends State<SearchScreen> {
  final List<String> allHotels = [
    'The Royal Hotel',
    'Mountain View Resort',
    'Cozy Stay Inn',
    'Sunrise Villa',
    'City Center Hotel',
  ];

  List<String> filteredHotels = [];

  @override
  void initState() {
    super.initState();
    filteredHotels = allHotels; // initially show all
  }

  void _filterHotels(String query) {
    final results = allHotels.where((hotel) =>
        hotel.toLowerCase().contains(query.toLowerCase())).toList();
    setState(() {
      filteredHotels = results;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Search Hotels'),
        backgroundColor: Colors.blue,
      ),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            TextField(
              onChanged: _filterHotels,
              decoration: InputDecoration(
                hintText: 'Search hotel name...',
                prefixIcon: Icon(Icons.search),
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(10),
                ),
              ),
            ),
            SizedBox(height: 20),
            Expanded(
              child: filteredHotels.isEmpty
                  ? Center(child: Text('No hotels found'))
                  : ListView.builder(
                      itemCount: filteredHotels.length,
                      itemBuilder: (context, index) {
                        final hotel = filteredHotels[index];
                        return Card(
                          margin: EdgeInsets.symmetric(vertical: 8),
                          shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(10)),
                          child: ListTile(
                            leading: Icon(Icons.hotel, color: Colors.blue),
                            title: Text(hotel),
                            trailing: Icon(Icons.arrow_forward_ios),
                            onTap: () {
                              Navigator.pushNamed(context, '/room_detail');
                            },
                          ),
                        );
                      },
                    ),
            ),
          ],
        ),
      ),
    );
  }
}
