import 'package:flutter/material.dart';
import '../services/user_service.dart';
import '../trips/trips_adventures_page.dart'; // Reuse Place and BookingCard from trips_adventures_page.dart

class HotelsByAdventurePage extends StatefulWidget {
  final String adventureId;
  final String adventureName;

  const HotelsByAdventurePage({Key? key, required this.adventureId, required this.adventureName}) : super(key: key);

  @override
  State<HotelsByAdventurePage> createState() => _HotelsByAdventurePageState();
}

class _HotelsByAdventurePageState extends State<HotelsByAdventurePage> {
  late Future<List<Place>> _hotelsFuture;

  @override
  void initState() {
    super.initState();
    _hotelsFuture = UserService.fetchHotelsByAdventure(widget.adventureId);
  }

  Future<void> _refreshHotels() async {
    setState(() {
      _hotelsFuture = UserService.fetchHotelsByAdventure(widget.adventureId);
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('${widget.adventureName} Hotels'),
        backgroundColor: Colors.deepPurple,
        foregroundColor: Colors.white,
        centerTitle: true,
      ),
      body: RefreshIndicator(
        onRefresh: _refreshHotels,
        child: FutureBuilder<List<Place>>(
          future: _hotelsFuture,
          builder: (context, snapshot) {
            if (snapshot.connectionState == ConnectionState.waiting) {
              return const Center(child: CircularProgressIndicator());
            } else if (snapshot.hasError) {
              return Center(
                child: Padding(
                  padding: const EdgeInsets.all(16.0),
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      const Icon(Icons.error_outline, color: Colors.red, size: 40),
                      const SizedBox(height: 10),
                      Text(
                        'Failed to load hotels: ${snapshot.error}',
                        textAlign: TextAlign.center,
                        style: const TextStyle(color: Colors.red, fontSize: 16),
                      ),
                      const SizedBox(height: 10),
                      ElevatedButton.icon(
                        onPressed: _refreshHotels,
                        icon: const Icon(Icons.refresh),
                        label: const Text('Try Again'),
                      ),
                    ],
                  ),
                ),
              );
            } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
              return const Center(
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Icon(Icons.sentiment_dissatisfied, color: Colors.blueGrey, size: 40),
                    SizedBox(height: 10),
                    Text(
                      'No hotels available at the moment.',
                      style: TextStyle(fontSize: 16),
                    ),
                  ],
                ),
              );
            } else {
              return ListView.builder(
                itemCount: snapshot.data!.length,
                itemBuilder: (context, index) {
                  return BookingCard(place: snapshot.data![index]);
                },
              );
            }
          },
        ),
      ),
    );
  }
}
