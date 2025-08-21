import 'package:flutter/material.dart';

class RoomSelectionPage extends StatelessWidget {
  final Map<String, dynamic> roomType;
  final Map<String, dynamic> hotel;

  const RoomSelectionPage({
    Key? key,
    required this.roomType,
    required this.hotel,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Room Details'),
        backgroundColor: Colors.deepPurple,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Room Image
            if (roomType['image_url'] != null)
              ClipRRect(
                borderRadius: BorderRadius.circular(8),
                child: Image.network(
                  roomType['image_url'],
                  height: 200,
                  width: double.infinity,
                  fit: BoxFit.cover,
                ),
              ),

            const SizedBox(height: 16),

            // Room Details
            Text(
              roomType['name'],
              style: const TextStyle(
                fontSize: 24,
                fontWeight: FontWeight.bold,
              ),
            ),

            const SizedBox(height: 8),

            Text(
              '\$${roomType['price']}/night',
              style: const TextStyle(
                fontSize: 20,
                fontWeight: FontWeight.bold,
                color: Colors.green,
              ),
            ),

            const SizedBox(height: 16),

            // Room Features
            Card(
              child: Padding(
                padding: const EdgeInsets.all(16.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text(
                      'Room Features',
                      style: TextStyle(
                        fontSize: 18,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    const SizedBox(height: 8),
                    Text('Max Occupancy: ${roomType['max_occupancy']} guests'),
                    Text('Available Rooms: ${roomType['available_rooms']}'),
                    if (roomType['description'] != null)
                      Text('Description: ${roomType['description']}'),
                  ],
                ),
              ),
            ),

            const SizedBox(height: 16),

            // Amenities
            if (roomType['amenities'] != null) ...[
              const Text(
                'Amenities',
                style: TextStyle(
                  fontSize: 18,
                  fontWeight: FontWeight.bold,
                ),
              ),
              const SizedBox(height: 8),
              Wrap(
                spacing: 8,
                runSpacing: 4,
                children: List<String>.from(roomType['amenities'] ?? [])
                    .map((amenity) => Chip(
                          label: Text(amenity),
                          backgroundColor: Colors.deepPurple.withOpacity(0.1),
                        ))
                    .toList(),
              ),
            ],

            const SizedBox(height: 32),

            // Book Now Button
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: () {
                  // Return room selection to previous screen
                  Navigator.pop(context, roomType);
                },
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.deepPurple,
                  padding: const EdgeInsets.symmetric(vertical: 16),
                ),
                child: const Text(
                  'Book This Room',
                  style: TextStyle(fontSize: 18),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
