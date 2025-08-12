import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

class HotelDetailPage extends StatefulWidget {
  final Map<String, dynamic> hotel;

  const HotelDetailPage({Key? key, required this.hotel}) : super(key: key);

  @override
  _HotelDetailPageState createState() => _HotelDetailPageState();
}

class _HotelDetailPageState extends State<HotelDetailPage> {
  late Future<List<Map<String, dynamic>>> _roomTypesFuture;

  @override
  void initState() {
    super.initState();
    _roomTypesFuture = fetchRoomTypes();
  }

  Future<List<Map<String, dynamic>>> fetchRoomTypes() async {
    final response = await http.get(
      Uri.parse(
          'http://localhost:8000/api/room-types?hotel_metadata_id=${widget.hotel['id']}'),
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      return List<Map<String, dynamic>>.from(data['data'] ?? []);
    } else {
      throw Exception('Failed to load room types');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(widget.hotel['name']),
        backgroundColor: Colors.deepPurple,
      ),
      body: SingleChildScrollView(
        child: Column(
          children: [
            // Hotel Image
            if (widget.hotel['image_url'] != null)
              Image.network(
                widget.hotel['image_url'],
                height: 200,
                width: double.infinity,
                fit: BoxFit.cover,
              ),

            // Hotel Details
            Padding(
              padding: const EdgeInsets.all(16.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    widget.hotel['name'],
                    style: const TextStyle(
                      fontSize: 24,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  const SizedBox(height: 8),
                  Text(
                    widget.hotel['description'] ?? 'No description available',
                    style: const TextStyle(fontSize: 16),
                  ),
                  const SizedBox(height: 16),
                  const Text(
                    'Available Room Types',
                    style: TextStyle(
                      fontSize: 20,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                ],
              ),
            ),

            // Room Types List
            FutureBuilder<List<Map<String, dynamic>>>(
              future: _roomTypesFuture,
              builder: (context, snapshot) {
                if (snapshot.connectionState == ConnectionState.waiting) {
                  return const Center(child: CircularProgressIndicator());
                } else if (snapshot.hasError) {
                  return Center(child: Text('Error: ${snapshot.error}'));
                } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
                  return const Center(child: Text('No room types available'));
                }

                final roomTypes = snapshot.data!;
                return ListView.builder(
                  shrinkWrap: true,
                  physics: const NeverScrollableScrollPhysics(),
                  itemCount: roomTypes.length,
                  itemBuilder: (context, index) {
                    final room = roomTypes[index];
                    return Card(
                      margin: const EdgeInsets.all(8),
                      child: ListTile(
                        leading: room['image_url'] != null
                            ? Image.network(
                                room['image_url'],
                                width: 50,
                                height: 50,
                                fit: BoxFit.cover,
                              )
                            : const Icon(Icons.hotel),
                        title: Text(room['name']),
                        subtitle: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text('\$${room['price']}/night'),
                            Text('Max occupancy: ${room['max_occupancy']}'),
                            Text('Available: ${room['available_rooms']}'),
                          ],
                        ),
                        trailing: ElevatedButton(
                          onPressed: () {
                            // Handle room selection
                            Navigator.push(
                              context,
                              MaterialPageRoute(
                                builder: (context) => RoomSelectionPage(
                                  roomType: room,
                                  hotel: widget.hotel,
                                ),
                              ),
                            );
                          },
                          child: const Text('Select'),
                        ),
                      ),
                    );
                  },
                );
              },
            ),
          ],
        ),
      ),
    );
  }
}
