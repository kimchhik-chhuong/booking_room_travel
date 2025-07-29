import 'package:flutter/material.dart';
import 'package:http/http.dart' as http; // Import the http package
import 'dart:convert'; // For JSON decoding
import 'dart:async'; // For simulating a delay

// --- 1. Data Model for a Booking Place ---
class Place {
  final String id;
  final String name;
  final String imageUrl;
  final double price;
  final int days;
  final String description;

  Place({
    required this.id,
    required this.name,
    required this.imageUrl,
    required this.price,
    required this.days,
    required this.description,
  });

  // Factory constructor to create a Place from a JSON map
  factory Place.fromJson(Map<String, dynamic> json) {
    return Place(
      id: json['id'] as String,
      name: json['name'] as String,
      imageUrl: json['imageUrl'] as String,
      price: (json['price'] as num).toDouble(), // Handle int or double
      days: json['days'] as int,
      description: json['description'] as String,
    );
  }
}

// --- 2. Booking Card Widget ---
class BookingCard extends StatelessWidget {
  final Place place;

  const BookingCard({Key? key, required this.place}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
      elevation: 4.0,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12.0)),
      child: InkWell(
        onTap: () {
          // TODO: Implement navigation to a detail page or booking form
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text('Tapped on ${place.name}')),
          );
        },
        child: Padding(
          padding: const EdgeInsets.all(12.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              ClipRRect(
                borderRadius: BorderRadius.circular(8.0),
                child: Image.network(
                  place.imageUrl,
                  height: 180.0,
                  width: double.infinity,
                  fit: BoxFit.cover,
                  errorBuilder: (context, error, stackTrace) {
                    return Container(
                      height: 180.0,
                      width: double.infinity,
                      color: Colors.grey[300],
                      child: const Center(
                        child: Icon(Icons.broken_image, size: 50, color: Colors.grey),
                      ),
                    );
                  },
                  loadingBuilder: (context, child, loadingProgress) {
                    if (loadingProgress == null) return child;
                    return Container(
                      height: 180.0,
                      width: double.infinity,
                      color: Colors.grey[200],
                      child: Center(
                        child: CircularProgressIndicator(
                          value: loadingProgress.expectedTotalBytes != null
                              ? loadingProgress.cumulativeBytesLoaded /
                                  loadingProgress.expectedTotalBytes!
                              : null,
                        ),
                      ),
                    );
                  },
                ),
              ),
              const SizedBox(height: 12.0),
              Text(
                place.name,
                style: const TextStyle(
                  fontSize: 18.0,
                  fontWeight: FontWeight.bold,
                  color: Colors.deepPurple,
                ),
              ),
              const SizedBox(height: 8.0),
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        '\$${place.price.toStringAsFixed(2)}', // Format price
                        style: TextStyle(
                          fontSize: 16.0,
                          fontWeight: FontWeight.bold,
                          color: Colors.green[700],
                        ),
                      ),
                      Text(
                        '${place.days} Days',
                        style: TextStyle(
                          fontSize: 14.0,
                          color: Colors.grey[600],
                        ),
                      ),
                    ],
                  ),
                  ElevatedButton.icon(
                    onPressed: () {
                      // TODO: Implement booking action
                      ScaffoldMessenger.of(context).showSnackBar(
                        SnackBar(content: Text('Booking ${place.name}...')),
                      );
                    },
                    icon: const Icon(Icons.book_online),
                    label: const Text('Book Now'),
                    style: ElevatedButton.styleFrom(
                      foregroundColor: Colors.white, backgroundColor: Colors.deepPurpleAccent,
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(8.0),
                      ),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 8.0),
              Text(
                place.description,
                maxLines: 2,
                overflow: TextOverflow.ellipsis,
                style: TextStyle(
                  fontSize: 13.0,
                  color: Colors.grey[700],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

// --- 3. API Service (Simulated) ---
class ApiService {
  // Simulates fetching data from an API
  // In a real app, you would use http.get or http.post here
  Future<List<Place>> fetchPlaces() async {
    // Simulate network delay
    await Future.delayed(const Duration(seconds: 2));

    // Dummy JSON data representing what you might get from an API
    final String dummyJson = '''
    [
      {
        "id": "1",
        "name": "Royal Palace & Silver Pagoda",
        "imageUrl": "https://upload.wikimedia.org/wikipedia/commons/e/ea/The_Throne_Hall_of_Royal_Palace_in_Phnom_Penh.jpg",
        "price": 25.00,
        "days": 1,
        "description": "Explore the historic Royal Palace and the stunning Silver Pagoda, iconic landmarks of Phnom Penh."
      },
      {
        "id": "2",
        "name": "Tuol Sleng Genocide Museum",
        "imageUrl": "https://upload.wikimedia.org/wikipedia/commons/4/4b/S21_museum_phnom_penh.jpg",
        "price": 15.00,
        "days": 0,
        "description": "A sobering yet important visit to the former S-21 prison, now a museum commemorating the Khmer Rouge regime."
      },
      {
        "id": "3",
        "name": "Choeung Ek Genocidal Centre (Killing Fields)",
        "imageUrl": "https://upload.wikimedia.org/wikipedia/commons/a/ae/Choeung_Ek_Mass_Grave.jpg",
        "price": 20.00,
        "days": 0,
        "description": "Visit the memorial site of the Killing Fields, a stark reminder of Cambodia's tragic past."
      },
      {
        "id": "4",
        "name": "Wat Phnom",
        "imageUrl": "https://upload.wikimedia.org/wikipedia/commons/e/e4/Wat_Phnom_01.jpg",
        "price": 5.00,
        "days": 0,
        "description": "The tallest religious structure in Phnom Penh, offering panoramic views and a serene atmosphere."
      },
      {
        "id": "5",
        "name": "Mekong River Sunset Cruise",
        "imageUrl": "https://upload.wikimedia.org/wikipedia/commons/f/fa/Mekong_River_in_Phnom_Penh.jpg",
        "price": 30.00,
        "days": 0,
        "description": "Enjoy a relaxing evening cruise along the Mekong River, watching the city lights come alive."
      },
      {
        "id": "6",
        "name": "Phnom Penh Cooking Class",
        "imageUrl": "https://upload.wikimedia.org/wikipedia/commons/4/47/Khmer_cuisine.jpg",
        "price": 45.00,
        "days": 1,
        "description": "Learn to prepare authentic Cambodian dishes with a hands-on cooking experience."
      }
    ]
    ''';

    try {
      // In a real scenario, this would be:
      // final response = await http.get(Uri.parse('YOUR_API_ENDPOINT_HERE'));
      // if (response.statusCode == 200) {
      //   final List<dynamic> jsonList = json.decode(response.body);
      //   return jsonList.map((json) => Place.fromJson(json)).toList();
      // } else {
      //   throw Exception('Failed to load places: ${response.statusCode}');
      // }

      final List<dynamic> jsonList = json.decode(dummyJson);
      return jsonList.map((json) => Place.fromJson(json)).toList();
    } catch (e) {
      // Catch any errors during the process
      throw Exception('Failed to load places: $e');
    }
  }
}

// --- 4. Main PhnompenhPage Widget ---
class PhnompenhPage extends StatefulWidget {
  const PhnompenhPage({Key? key}) : super(key: key);

  @override
  State<PhnompenhPage> createState() => _PhnompenhPageState();
}

class _PhnompenhPageState extends State<PhnompenhPage> {
  late Future<List<Place>> _placesFuture;
  final ApiService _apiService = ApiService();

  @override
  void initState() {
    super.initState();
    _placesFuture = _apiService.fetchPlaces(); // Initiate the API call
  }

  // Function to refresh data
  Future<void> _refreshPlaces() async {
    setState(() {
      _placesFuture = _apiService.fetchPlaces();
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Phnom Penh Adventures'),
        backgroundColor: Colors.deepPurple,
        foregroundColor: Colors.white,
        centerTitle: true,
        elevation: 0,
      ),
      body: RefreshIndicator( // Allows pull-to-refresh functionality
        onRefresh: _refreshPlaces,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Welcome section
            Padding(
              padding: const EdgeInsets.all(16.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    'Welcome to Phnom Penh!',
                    style: TextStyle(
                      fontSize: 28.0,
                      fontWeight: FontWeight.bold,
                      color: Colors.deepPurple,
                    ),
                  ),
                  const SizedBox(height: 8.0),
                  Text(
                    'Discover and book exciting experiences and tours in the heart of Cambodia.',
                    style: TextStyle(
                      fontSize: 16.0,
                      color: Colors.grey[700],
                    ),
                  ),
                ],
              ),
            ),
            const Padding(
              padding: EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
              child: Text(
                'Popular Experiences:',
                style: TextStyle(
                  fontSize: 20.0,
                  fontWeight: FontWeight.bold,
                  color: Colors.blueGrey,
                ),
              ),
            ),
            // Places booking section using FutureBuilder
            Expanded(
              child: FutureBuilder<List<Place>>(
                future: _placesFuture,
                builder: (context, snapshot) {
                  if (snapshot.connectionState == ConnectionState.waiting) {
                    // Show a loading indicator while data is being fetched
                    return const Center(child: CircularProgressIndicator());
                  } else if (snapshot.hasError) {
                    // Show an error message if something went wrong
                    return Center(
                      child: Padding(
                        padding: const EdgeInsets.all(16.0),
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            const Icon(Icons.error_outline, color: Colors.red, size: 40),
                            const SizedBox(height: 10),
                            Text(
                              'Failed to load experiences: ${snapshot.error}',
                              textAlign: TextAlign.center,
                              style: const TextStyle(color: Colors.red, fontSize: 16),
                            ),
                            const SizedBox(height: 10),
                            ElevatedButton.icon(
                              onPressed: _refreshPlaces,
                              icon: const Icon(Icons.refresh),
                              label: const Text('Try Again'),
                            ),
                          ],
                        ),
                      ),
                    );
                  } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
                    // Show a message if no data is available
                    return const Center(
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Icon(Icons.sentiment_dissatisfied, color: Colors.blueGrey, size: 40),
                          SizedBox(height: 10),
                          Text(
                            'No booking places available at the moment.',
                            style: TextStyle(fontSize: 16),
                          ),
                        ],
                      ),
                    );
                  } else {
                    // Display the list of places using a ListView
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
          ],
        ),
      ),
    );
  }
}
