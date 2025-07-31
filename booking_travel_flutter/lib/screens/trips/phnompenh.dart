import 'package:flutter/material.dart';
import 'dart:convert';
import 'dart:async';
import 'package:url_launcher/url_launcher.dart'; // For opening maps and URLs

// --- 1. Enhanced Data Model ---
class Place {
  final String id;
  final String name;
  final String imageUrl;
  final double price;
  final int days;
  final String description;
  final double latitude;
  final double longitude;
  final List<String> nearbyHotels;

  Place({
    required this.id,
    required this.name,
    required this.imageUrl,
    required this.price,
    required this.days,
    required this.description,
    required this.latitude,
    required this.longitude,
    required this.nearbyHotels,
  });

  factory Place.fromJson(Map<String, dynamic> json) {
    return Place(
      id: json['id'] as String,
      name: json['name'] as String,
      imageUrl: json['imageUrl'] as String,
      price: (json['price'] as num).toDouble(),
      days: json['days'] as int,
      description: json['description'] as String,
      latitude: (json['latitude'] as num).toDouble(),
      longitude: (json['longitude'] as num).toDouble(),
      nearbyHotels: List<String>.from(json['nearbyHotels']),
    );
  }
}

// --- 2. Enhanced Booking Card Widget ---
class BookingCard extends StatelessWidget {
  final Place place;

  const BookingCard({Key? key, required this.place}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
      elevation: 4.0,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12.0)),
      child: Column(
        children: [
          // Image Section
          Stack(
            children: [
              ClipRRect(
                borderRadius: const BorderRadius.vertical(top: Radius.circular(12.0)),
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
              Positioned(
                bottom: 10,
                right: 10,
                child: Container(
                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                  decoration: BoxDecoration(
                    color: Colors.black.withOpacity(0.6),
                    borderRadius: BorderRadius.circular(8),
                  ),
                  child: Text(
                    '\$${place.price.toStringAsFixed(2)}',
                    style: const TextStyle(
                      color: Colors.white,
                      fontWeight: FontWeight.bold,
                      fontSize: 16,
                    ),
                  ),
                ),
              ),
            ],
          ),
          
          // Details Section
          Padding(
            padding: const EdgeInsets.all(12.0),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
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
                  children: [
                    const Icon(Icons.calendar_today, size: 16, color: Colors.grey),
                    const SizedBox(width: 4),
                    Text(
                      '${place.days} Day${place.days != 1 ? 's' : ''}',
                      style: TextStyle(
                        fontSize: 14.0,
                        color: Colors.grey[600],
                      ),
                    ),
                    const Spacer(),
                    const Icon(Icons.star, size: 16, color: Colors.amber),
                    const SizedBox(width: 4),
                    const Text(
                      '4.8',
                      style: TextStyle(
                        fontSize: 14.0,
                        color: Colors.grey,
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
                const SizedBox(height: 12.0),
                
                // Nearby Hotels Section
                ExpansionTile(
                  title: const Text('Nearby Hotels', style: TextStyle(fontSize: 14)),
                  children: [
                    Column(
                      children: place.nearbyHotels.map((hotel) => ListTile(
                        leading: const Icon(Icons.hotel, color: Colors.blue),
                        title: Text(hotel),
                        trailing: IconButton(
                          icon: const Icon(Icons.map, color: Colors.green),
                          onPressed: () => _launchMaps(hotel, place.latitude, place.longitude), // Pass hotel name
                        ),
                      )).toList(),
                    ),
                  ],
                ),
                
                // Action Buttons
                Row(
                  children: [
                    Expanded(
                      child: OutlinedButton.icon(
                        icon: const Icon(Icons.map),
                        label: const Text('View on Map'),
                        onPressed: () => _launchMaps(place.name, place.latitude, place.longitude), // Pass place name
                        style: OutlinedButton.styleFrom(
                          foregroundColor: Colors.deepPurple,
                          side: const BorderSide(color: Colors.deepPurple),
                          padding: const EdgeInsets.symmetric(vertical: 12),
                        ),
                      ),
                    ),
                    const SizedBox(width: 10),
                    Expanded(
                      child: ElevatedButton.icon(
                        icon: const Icon(Icons.book_online),
                        label: const Text('Book Now'),
                        onPressed: () => _showBookingDialog(context, place),
                        style: ElevatedButton.styleFrom(
                          foregroundColor: Colors.white, backgroundColor: Colors.deepPurple,
                          padding: const EdgeInsets.symmetric(vertical: 12),
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
  }

  void _launchMaps(String query, double lat, double lng) async {
    // Attempt to open with a specific query, falling back to coordinates if needed.
    // This will typically open a map app on mobile.
    final String queryUrl = Uri.encodeComponent(query);
    final String googleMapsUrl = 'https://www.google.com/maps/search/?api=1&query=$queryUrl';
    final String appleMapsUrl = 'http://maps.apple.com/?q=$queryUrl';
    final String geoUri = 'geo:$lat,$lng?q=$queryUrl';

    if (Theme.of(WidgetsBinding.instance.window.platformBrightness as BuildContext) == Brightness.light) {
        // Try launching with geo URI first for mobile app preference
        if (await canLaunchUrl(Uri.parse(geoUri))) {
          await launchUrl(Uri.parse(geoUri));
        } else if (await canLaunchUrl(Uri.parse(googleMapsUrl))) {
          await launchUrl(Uri.parse(googleMapsUrl));
        } else if (await canLaunchUrl(Uri.parse(appleMapsUrl))) {
          await launchUrl(Uri.parse(appleMapsUrl));
        } else {
          throw 'Could not launch map for $query';
        }
    } else {
      // Fallback for web or if geo URI fails, using lat/lng as a last resort
      final String fallbackUrl = 'https://www.google.com/maps/search/?api=1&query=$lat,$lng';
      if (await canLaunchUrl(Uri.parse(googleMapsUrl))) {
        await launchUrl(Uri.parse(googleMapsUrl));
      } else if (await canLaunchUrl(Uri.parse(fallbackUrl))) {
        await launchUrl(Uri.parse(fallbackUrl));
      } else {
        throw 'Could not launch map for $query';
      }
    }
  }


  void _showBookingDialog(BuildContext context, Place place) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: Text('Book ${place.name}'),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('\$${place.price.toStringAsFixed(2)} for ${place.days} day${place.days != 1 ? 's' : ''}'),
            const SizedBox(height: 20),
            const TextField(
              decoration: InputDecoration(
                labelText: 'Full Name',
                border: OutlineInputBorder(),
              ),
            ),
            const SizedBox(height: 10),
            const TextField(
              decoration: InputDecoration(
                labelText: 'Email',
                border: OutlineInputBorder(),
              ),
            ),
            const SizedBox(height: 10),
            const TextField(
              decoration: InputDecoration(
                labelText: 'Phone Number',
                border: OutlineInputBorder(),
              ),
            ),
            const SizedBox(height: 10),
            const TextField(
              decoration: InputDecoration(
                labelText: 'Date',
                suffixIcon: Icon(Icons.calendar_today),
                border: OutlineInputBorder(),
              ),
            ),
          ],
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Cancel'),
          ),
          ElevatedButton(
            onPressed: () {
              Navigator.pop(context);
              ScaffoldMessenger.of(context).showSnackBar(
                SnackBar(content: Text('Booking confirmed for ${place.name}!')),
              );
            },
            child: const Text('Confirm Booking'),
          ),
        ],
      ),
    );
  }
}

// --- 3. API Service (Updated with more data) ---
class ApiService {
  Future<List<Place>> fetchPlaces() async {
    await Future.delayed(const Duration(seconds: 1));

    final String dummyJson = '''
    [
      {
        "id": "1",
        "name": "Royal Palace & Silver Pagoda",
        "imageUrl": "https://upload.wikimedia.org/wikipedia/commons/e/ea/The_Throne_Hall_of_Royal_Palace_in_Phnom_Penh.jpg",
        "price": 25.00,
        "days": 1,
        "description": "Explore the historic Royal Palace and the stunning Silver Pagoda, iconic landmarks of Phnom Penh.",
        "latitude": 11.5624,
        "longitude": 104.9317,
        "nearbyHotels": [
          "Raffles Hotel Le Royal",
          "Plantation Urban Resort",
          "White Mansion Boutique Hotel"
        ]
      },
      {
        "id": "2",
        "name": "Tuol Sleng Genocide Museum",
        "imageUrl": "https://upload.wikimedia.org/wikipedia/commons/4/4b/S21_museum_phnom_penh.jpg",
        "price": 15.00,
        "days": 0,
        "description": "A sobering yet important visit to the former S-21 prison, now a museum commemorating the Khmer Rouge regime.",
        "latitude": 11.5495,
        "longitude": 104.9176,
        "nearbyHotels": [
          "The Kabiki",
          "The Pavilion",
          "Blue Lime"
        ]
      },
      {
        "id": "3",
        "name": "Choeung Ek Genocidal Centre (Killing Fields)",
        "imageUrl": "https://upload.wikimedia.org/wikipedia/commons/a/ae/Choeung_Ek_Mass_Grave.jpg",
        "price": 20.00,
        "days": 0,
        "description": "Visit the memorial site of the Killing Fields, a stark reminder of Cambodia's tragic past.",
        "latitude": 11.4846,
        "longitude": 104.9019,
        "nearbyHotels": [
          "Okay Boutique Hotel",
          "Dara Reang Sey Hotel",
          "La Rose Suites"
        ]
      },
      {
        "id": "4",
        "name": "Wat Phnom",
        "imageUrl": "https://upload.wikimedia.org/wikipedia/commons/e/e4/Wat_Phnom_01.jpg",
        "price": 5.00,
        "days": 0,
        "description": "The tallest religious structure in Phnom Penh, offering panoramic views and a serene atmosphere.",
        "latitude": 11.5765,
        "longitude": 104.9230,
        "nearbyHotels": [
          "Sofitel Phnom Penh Phokeethra",
          "Himawari Hotel Apartments",
          "Sun & Moon Urban Hotel"
        ]
      },
      {
        "id": "5",
        "name": "Mekong River Sunset Cruise",
        "imageUrl": "https://upload.wikimedia.org/wikipedia/commons/f/fa/Mekong_River_in_Phnom_Penh.jpg",
        "price": 30.00,
        "days": 0,
        "description": "Enjoy a relaxing evening cruise along the Mekong River, watching the city lights come alive.",
        "latitude": 11.5699,
        "longitude": 104.9335,
        "nearbyHotels": [
          "Amanjaya Pancam Hotel",
          "The Quay Hotel",
          "Aquarius Hotel & Urban Resort"
        ]
      },
      {
        "id": "6",
        "name": "Phnom Penh Cooking Class",
        "imageUrl": "https://upload.wikimedia.org/wikipedia/commons/4/47/Khmer_cuisine.jpg",
        "price": 45.00,
        "days": 1,
        "description": "Learn to prepare authentic Cambodian dishes with a hands-on cooking experience.",
        "latitude": 11.5588,
        "longitude": 104.9172,
        "nearbyHotels": [
          "FCC Angkor Boutique Hotel",
          "The 252 Hotel",
          "The Square Boutique Hotel"
        ]
      }
    ]
    ''';

    try {
      final List<dynamic> jsonList = json.decode(dummyJson);
      return jsonList.map((json) => Place.fromJson(json)).toList();
    } catch (e) {
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
    _placesFuture = _apiService.fetchPlaces();
  }

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
        actions: [
          IconButton(
            icon: const Icon(Icons.map),
            onPressed: () {
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => const MapViewPage(),
                ),
              );
            },
          ),
        ],
      ),
      body: RefreshIndicator(
        onRefresh: _refreshPlaces,
        child: CustomScrollView(
          slivers: [
            SliverToBoxAdapter(
              child: Padding(
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
            ),
            SliverToBoxAdapter(
              child: Padding(
                padding: const EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
                child: Row(
                  children: [
                    const Text(
                      'Popular Experiences',
                      style: TextStyle(
                        fontSize: 20.0,
                        fontWeight: FontWeight.bold,
                        color: Colors.blueGrey,
                      ),
                    ),
                    const Spacer(),
                    TextButton(
                      onPressed: () {
                        Navigator.push(
                          context,
                          MaterialPageRoute(
                            builder: (context) => const MapViewPage(),
                          ),
                        );
                      },
                      child: const Row(
                        children: [
                          Text('View on Map'),
                          SizedBox(width: 4),
                          Icon(Icons.arrow_forward, size: 16),
                        ],
                      ),
                    ),
                  ],
                ),
              ),
            ),
            FutureBuilder<List<Place>>(
              future: _placesFuture,
              builder: (context, snapshot) {
                if (snapshot.connectionState == ConnectionState.waiting) {
                  return const SliverFillRemaining(
                    child: Center(child: CircularProgressIndicator()),
                  );
                } else if (snapshot.hasError) {
                  return SliverToBoxAdapter(
                    child: Center(
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
                    ),
                  );
                } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
                  return const SliverToBoxAdapter(
                    child: Center(
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
                    ),
                  );
                } else {
                  return SliverList(
                    delegate: SliverChildBuilderDelegate(
                      (context, index) => BookingCard(place: snapshot.data![index]),
                      childCount: snapshot.data!.length,
                    ),
                  );
                }
              },
            ),
          ],
        ),
      ),
    );
  }
}

// --- 5. Map View Page ---
class MapViewPage extends StatelessWidget {
  const MapViewPage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Phnom Penh Map'),
        backgroundColor: Colors.deepPurple,
        foregroundColor: Colors.white,
      ),
      body: Stack(
        children: [
          // This would be replaced with a real map widget like Google Maps in a real app
          Center(
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                const Icon(Icons.map, size: 100, color: Colors.blue),
                const SizedBox(height: 20),
                const Text(
                  'Interactive Map View',
                  style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
                ),
                const SizedBox(height: 10),
                Text(
                  'In a real app, this would show Google Maps with markers for attractions and hotels',
                  textAlign: TextAlign.center,
                  style: TextStyle(color: Colors.grey[600]),
                ),
                const SizedBox(height: 20),
                ElevatedButton(
                  onPressed: () {
                    // Open Google Maps with Phnom Penh location search
                    _launchMaps('Phnom Penh', 11.5564, 104.9282); // Pass a general query for Phnom Penh
                  },
                  child: const Text('Open in Google Maps'),
                ),
              ],
            ),
          ),
          Positioned(
            bottom: 20,
            left: 20,
            right: 20,
            child: Card(
              elevation: 8,
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(12),
              ),
              child: Padding(
                padding: const EdgeInsets.all(16.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text(
                      'Top Hotels in Phnom Penh',
                      style: TextStyle(
                        fontWeight: FontWeight.bold,
                        fontSize: 18,
                      ),
                    ),
                    const SizedBox(height: 10),
                    _buildHotelItem('Raffles Hotel Le Royal', '5-star luxury'),
                    _buildHotelItem('Plantation Urban Resort', 'Boutique hotel with pool'),
                    _buildHotelItem('Sofitel Phnom Penh', 'Riverside luxury'),
                    const SizedBox(height: 10),
                    SizedBox(
                      width: double.infinity,
                      child: ElevatedButton(
                        onPressed: () {
                          // In a real app, this would open a hotel booking page
                          ScaffoldMessenger.of(context).showSnackBar(
                            const SnackBar(content: Text('Opening hotel booking...')),
                          );
                        },
                        child: const Text('Book a Hotel'),
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildHotelItem(String name, String description) {
    return ListTile(
      contentPadding: EdgeInsets.zero,
      leading: const Icon(Icons.hotel, color: Colors.blue),
      title: Text(name),
      subtitle: Text(description),
      trailing: IconButton(
        icon: const Icon(Icons.directions, color: Colors.green),
        onPressed: () {
          // In a real app, this would open directions to the hotel using its name or precise coordinates
          _launchMaps(name, 0.0, 0.0); // Pass name, coordinates would be from a real data source
        },
      ),
    );
  }

  void _launchMaps(String query, double lat, double lng) async {
    // Attempt to open with a specific query, falling back to coordinates if needed.
    // This will typically open a map app on mobile.
    final String queryUrl = Uri.encodeComponent(query);
    final String googleMapsUrl = 'https://www.google.com/maps/search/?api=1&query=$queryUrl';
    final String appleMapsUrl = 'http://maps.apple.com/?q=$queryUrl';
    final String geoUri = 'geo:$lat,$lng?q=$queryUrl'; // Preferred for mobile apps

    if (await canLaunchUrl(Uri.parse(geoUri))) {
      await launchUrl(Uri.parse(geoUri));
    } else if (await canLaunchUrl(Uri.parse(googleMapsUrl))) {
      await launchUrl(Uri.parse(googleMapsUrl));
    } else if (await canLaunchUrl(Uri.parse(appleMapsUrl))) {
      await launchUrl(Uri.parse(appleMapsUrl));
    } else {
      throw 'Could not launch map for $query';
    }
  }
}

// --- 6. Main App ---
void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Phnom Penh Adventures',
      theme: ThemeData(
        primarySwatch: Colors.deepPurple,
        appBarTheme: const AppBarTheme(
          elevation: 0,
          centerTitle: true,
        ),
      ),
      home: const PhnompenhPage(),
    );
  }
}
