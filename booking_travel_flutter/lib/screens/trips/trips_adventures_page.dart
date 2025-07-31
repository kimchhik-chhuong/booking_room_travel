import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

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

  factory Place.fromJson(Map<String, dynamic> json) {
    double parsePrice(dynamic price) {
      if (price is String) {
        return double.tryParse(price) ?? 0.0;
      } else if (price is num) {
        return price.toDouble();
      } else {
        return 0.0;
      }
    }

    return Place(
      id: json['id'].toString(),
      name: json['hotel_name'] ?? json['name'] ?? '',
      imageUrl: json['image_url'] ?? '',
      price: parsePrice(json['price']),
      days: json['days'] ?? 0,
      description: json['description'] ?? '',
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
                child: place.imageUrl.isNotEmpty
                    ? Image.network(
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
                      )
                    : Container(
                        height: 180.0,
                        width: double.infinity,
                        color: Colors.grey[300],
                        child: const Center(
                          child: Icon(Icons.image_not_supported, size: 50, color: Colors.grey),
                        ),
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
                        '\$${place.price.toStringAsFixed(2)}',
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
                      ScaffoldMessenger.of(context).showSnackBar(
                        SnackBar(content: Text('Booking ${place.name}...')),
                      );
                    },
                    icon: const Icon(Icons.book_online),
                    label: const Text('Book Now'),
                    style: ElevatedButton.styleFrom(
                      foregroundColor: Colors.white,
                      backgroundColor: Colors.deepPurpleAccent,
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

// --- 3. API Service ---
class ApiService {
  final String baseUrl = 'http://localhost:8000/api'; // Change to your Laravel API base URL

  Future<List<Place>> fetchHotelsByProvinceId(String provinceId) async {
    final response = await http.get(Uri.parse('$baseUrl/provinces/$provinceId/hotels'));

    if (response.statusCode == 200) {
      final List<dynamic> jsonList = json.decode(response.body)['data'];
      return jsonList.map((json) => Place.fromJson(json)).toList();
    } else {
      throw Exception('Failed to load hotels');
    }
  }
}

// --- 4. Generic TripsAdventuresPage Widget ---
class TripsAdventuresPage extends StatefulWidget {
  final String provinceId;
  final String provinceName;

  const TripsAdventuresPage({Key? key, required this.provinceId, required this.provinceName}) : super(key: key);

  @override
  State<TripsAdventuresPage> createState() => _TripsAdventuresPageState();
}

class _TripsAdventuresPageState extends State<TripsAdventuresPage> {
  late Future<List<Place>> _placesFuture;
  final ApiService _apiService = ApiService();

  @override
  void initState() {
    super.initState();
    _placesFuture = _apiService.fetchHotelsByProvinceId(widget.provinceId);
  }

  Future<void> _refreshPlaces() async {
    setState(() {
      _placesFuture = _apiService.fetchHotelsByProvinceId(widget.provinceId);
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('${widget.provinceName} Adventures'),
        backgroundColor: Colors.deepPurple,
        foregroundColor: Colors.white,
        centerTitle: true,
        elevation: 0,
      ),
      body: RefreshIndicator(
        onRefresh: _refreshPlaces,
        child: FutureBuilder<List<Place>>(
          future: _placesFuture,
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
                        onPressed: _refreshPlaces,
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
