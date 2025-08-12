import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

class HotelListPage extends StatefulWidget {
  final int provinceId;
  final String provinceName;
  final String? adventureName;
  final int? adventureId;

  const HotelListPage({
    Key? key,
    required this.provinceId,
    required this.provinceName,
    this.adventureName,
    this.adventureId,
  }) : super(key: key);

  @override
  _HotelListPageState createState() => _HotelListPageState();
}

class _HotelListPageState extends State<HotelListPage> {
  late Future<List<Map<String, dynamic>>> _hotelsFuture;

  @override
  void initState() {
    super.initState();
    _hotelsFuture = fetchHotels();
  }

  Future<List<Map<String, dynamic>>> fetchHotels() async {
    String url =
        'http://localhost:8000/api/hotelmetadata?province_id=${widget.provinceId}';
    if (widget.adventureId != null) {
      url += '&adventure_id=${widget.adventureId}';
    }
    final response = await http.get(Uri.parse(url));

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      return List<Map<String, dynamic>>.from(data['data'] ?? []);
    } else {
      throw Exception('Failed to load hotels');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(widget.adventureName != null
            ? 'Hotels near ${widget.adventureName}'
            : 'Hotels in ${widget.provinceName}'),
        backgroundColor: Colors.orange,
      ),
      body: FutureBuilder<List<Map<String, dynamic>>>(
        future: _hotelsFuture,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          } else if (snapshot.hasError) {
            return Center(child: Text('Error: ${snapshot.error}'));
          } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
            return const Center(child: Text('No hotels available'));
          }

          final hotels = snapshot.data!;
          return ListView.builder(
            padding: const EdgeInsets.all(16),
            itemCount: hotels.length,
            itemBuilder: (context, index) {
              final hotel = hotels[index];
              return Card(
                margin: const EdgeInsets.only(bottom: 16),
                child: InkWell(
                  onTap: () {
                    // Handle hotel tap - show hotel details or booking
                    ScaffoldMessenger.of(context).showSnackBar(
                      SnackBar(content: Text('Selected: ${hotel['name']}')),
                    );
                  },
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      if (hotel['image'] != null)
                        ClipRRect(
                          borderRadius: const BorderRadius.vertical(
                              top: Radius.circular(8)),
                          child: Image.network(
                            hotel['image'],
                            height: 150,
                            width: double.infinity,
                            fit: BoxFit.cover,
                            errorBuilder: (context, error, stackTrace) =>
                                Container(
                              height: 150,
                              color: Colors.grey[300],
                              child: const Icon(Icons.hotel),
                            ),
                          ),
                        ),
                      Padding(
                        padding: const EdgeInsets.all(16),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              hotel['name'] ?? 'Hotel',
                              style: const TextStyle(
                                fontSize: 18,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                            const SizedBox(height: 8),
                            Text(
                              hotel['description'] ??
                                  'No description available',
                              maxLines: 2,
                              overflow: TextOverflow.ellipsis,
                            ),
                            const SizedBox(height: 8),
                            Row(
                              children: [
                                const Icon(Icons.star,
                                    color: Colors.amber, size: 16),
                                Text(' ${hotel['rating'] ?? 'N/A'}'),
                                const Spacer(),
                                Text(
                                  '\$${hotel['price_range'] ?? 'N/A'}/night',
                                  style: const TextStyle(
                                    fontWeight: FontWeight.bold,
                                    color: Colors.blue,
                                  ),
                                ),
                              ],
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
              );
            },
          );
        },
      ),
    );
  }
}
