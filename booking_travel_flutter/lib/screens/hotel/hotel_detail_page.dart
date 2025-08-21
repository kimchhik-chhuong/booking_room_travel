import 'dart:async';
import 'package:flutter/material.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:url_launcher/url_launcher_string.dart';
import 'package:intl/intl.dart';
import '../../models/hotel_model.dart';
import '../../models/room_type_model.dart';
import 'booking_confirmation_page.dart';

class HotelDetailPage extends StatefulWidget {
  final Hotel hotel;
  const HotelDetailPage({Key? key, required this.hotel}) : super(key: key);

  @override
  State<HotelDetailPage> createState() => _HotelDetailPageState();
}

class _HotelDetailPageState extends State<HotelDetailPage> {
  List<RoomType> roomTypes = [];
  bool isLoadingRooms = true;
  DateTime? checkInDate;
  DateTime? checkOutDate;
  int guests = 2;
  int rooms = 1;
  RoomType? selectedRoom;
  bool isCheckingAvailability = false;
  Map<String, dynamic>? availabilityData;
  final Completer<GoogleMapController> _mapController = Completer<GoogleMapController>();
  static const CameraPosition _kInitialPosition = CameraPosition(
    target: LatLng(11.5621, 104.8685), // Phnom Penh coordinates
    zoom: 12,
  );

  @override
  void initState() {
    super.initState();
    _loadRoomTypes();
  }

  Future<void> _loadRoomTypes() async {
    // TODO: Implement room types loading from API
    setState(() {
      isLoadingRooms = false;
    });
  }

  Future<void> _showBookingDialog(RoomType room) async {
    // TODO: Implement booking dialog
  }

  Future<void> _showRoomSelection() async {
    // TODO: Implement room selection dialog
  }

  Future<void> _launchMapsUrl() async {
    if (widget.hotel.latitude != null && widget.hotel.longitude != null) {
      final url = 'https://www.google.com/maps/search/?api=1&query=${widget.hotel.latitude},${widget.hotel.longitude}';
      if (await canLaunchUrlString(url)) {
        await launchUrlString(url);
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: CustomScrollView(
        slivers: [
          SliverAppBar(
            expandedHeight: 250.0,
            pinned: true,
            flexibleSpace: FlexibleSpaceBar(
              title: Text(widget.hotel.name),
              background: widget.hotel.images?.isNotEmpty == true
                  ? Image.network(
                      widget.hotel.images!.first,
                      fit: BoxFit.cover,
                    )
                  : null,
            ),
          ),
          SliverToBoxAdapter(
            child: Padding(
              padding: const EdgeInsets.all(16.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Hotel Info
                  Row(
                    children: [
                      if (widget.hotel.starRating != null)
                        Row(
                          children: List.generate(
                            5,
                            (index) => Icon(
                              Icons.star,
                              color: index < (widget.hotel.starRating ?? 0) ? Colors.amber : Colors.grey,
                            ),
                          ),
                        ),
                      const SizedBox(width: 8),
                      Text(
                        '${widget.hotel.starRating?.toStringAsFixed(1) ?? 'N/A'}/5',
                        style: Theme.of(context).textTheme.bodyLarge,
                      ),
                    ],
                  ),
                  const SizedBox(height: 8),
                  Text(
                    widget.hotel.name,
                    style: Theme.of(context).textTheme.headlineSmall,
                  ),
                  const SizedBox(height: 8),
                  if (widget.hotel.location != null || widget.hotel.address != null)
                    Row(
                      children: [
                        const Icon(Icons.location_on, size: 16, color: Colors.grey),
                        const SizedBox(width: 4),
                        Expanded(
                          child: Text(
                            widget.hotel.location ?? widget.hotel.address ?? '',
                            style: Theme.of(context).textTheme.bodyMedium?.copyWith(color: Colors.grey[600]),
                          ),
                        ),
                      ],
                    ),
                  const SizedBox(height: 16),
                  
                  // Description
                  if (widget.hotel.description?.isNotEmpty ?? false) ...[
                    Text(
                      'Description',
                      style: Theme.of(context).textTheme.titleMedium?.copyWith(fontWeight: FontWeight.bold),
                    ),
                    const SizedBox(height: 8),
                    Text(widget.hotel.description!),
                    const SizedBox(height: 16),
                  ],

                  // Map
                  if (widget.hotel.latitude != null && widget.hotel.longitude != null) ...[
                    Text(
                      'Location',
                      style: Theme.of(context).textTheme.titleMedium?.copyWith(fontWeight: FontWeight.bold),
                    ),
                    const SizedBox(height: 8),
                    SizedBox(
                      height: 200,
                      child: ClipRRect(
                        borderRadius: BorderRadius.circular(12),
                        child: GoogleMap(
                          initialCameraPosition: CameraPosition(
                            target: LatLng(widget.hotel.latitude!, widget.hotel.longitude!),
                            zoom: 15,
                          ),
                          markers: {
                            Marker(
                              markerId: const MarkerId('hotel_location'),
                              position: LatLng(widget.hotel.latitude!, widget.hotel.longitude!),
                              infoWindow: InfoWindow(title: widget.hotel.name),
                            ),
                          },
                          onMapCreated: (controller) {
                            _mapController.complete(controller);
                          },
                        ),
                      ),
                    ),
                    const SizedBox(height: 8),
                    ElevatedButton.icon(
                      onPressed: _launchMapsUrl,
                      icon: const Icon(Icons.directions),
                      label: const Text('Get Directions'),
                    ),
                    const SizedBox(height: 16),
                  ],

                  // Amenities
                  if (widget.hotel.amenities?.isNotEmpty ?? false) ...[
                    Text(
                      'Amenities',
                      style: Theme.of(context).textTheme.titleMedium?.copyWith(fontWeight: FontWeight.bold),
                    ),
                    const SizedBox(height: 8),
                    Wrap(
                      spacing: 8,
                      runSpacing: 8,
                      children: widget.hotel.amenities!
                          .map((amenity) => Chip(label: Text(amenity)))
                          .toList(),
                    ),
                    const SizedBox(height: 16),
                  ],

                  // Room Types
                  Text(
                    'Available Rooms',
                    style: Theme.of(context).textTheme.titleMedium?.copyWith(fontWeight: FontWeight.bold),
                  ),
                  const SizedBox(height: 8),
                  if (isLoadingRooms)
                    const Center(child: CircularProgressIndicator())
                  else if (roomTypes.isEmpty)
                    const Text('No rooms available')
                  else
                    ListView.builder(
                      shrinkWrap: true,
                      physics: const NeverScrollableScrollPhysics(),
                      itemCount: roomTypes.length,
                      itemBuilder: (context, index) {
                        final room = roomTypes[index];
                        return Card(
                          margin: const EdgeInsets.only(bottom: 16),
                          child: Padding(
                            padding: const EdgeInsets.all(12.0),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                if (room.imageUrl != null)
                                  ClipRRect(
                                    borderRadius: BorderRadius.circular(8),
                                    child: Image.network(
                                      room.imageUrl!,
                                      height: 150,
                                      width: double.infinity,
                                      fit: BoxFit.cover,
                                    ),
                                  ),
                                const SizedBox(height: 12),
                                Text(
                                  room.name,
                                  style: Theme.of(context).textTheme.titleMedium?.copyWith(
                                        fontWeight: FontWeight.bold,
                                      ),
                                ),
                                const SizedBox(height: 8),
                                if (room.description?.isNotEmpty ?? false) ...[
                                  Text(room.description!),
                                  const SizedBox(height: 8),
                                ],
                                Text(
                                  '\$${room.price?.toStringAsFixed(2) ?? 'N/A'} per night',
                                  style: Theme.of(context).textTheme.titleMedium?.copyWith(
                                        color: Theme.of(context).primaryColor,
                                        fontWeight: FontWeight.bold,
                                      ),
                                ),
                                const SizedBox(height: 8),
                                if (room.amenities?.isNotEmpty ?? false) ...[
                                  Text(
                                    'Room Amenities:',
                                    style: Theme.of(context).textTheme.bodySmall,
                                  ),
                                  Wrap(
                                    spacing: 8,
                                    runSpacing: 4,
                                    children: room.amenities!
                                        .map((a) => Chip(
                                              label: Text(a),
                                              padding: EdgeInsets.zero,
                                              labelStyle: Theme.of(context).textTheme.labelSmall,
                                            ))
                                        .toList(),
                                  ),
                                  const SizedBox(height: 8),
                                ],
                                SizedBox(
                                  width: double.infinity,
                                  child: ElevatedButton(
                                    onPressed: () => _showBookingDialog(room),
                                    child: const Text('Book Now'),
                                  ),
                                ),
                              ],
                            ),
                          ),
                        );
                      },
                    ),
                ],
              ),
            ),
          ),
        ],
      ),
      bottomNavigationBar: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(16.0),
          child: ElevatedButton(
            onPressed: roomTypes.isNotEmpty ? _showRoomSelection : null,
            child: const Text('Check Availability'),
          ),
        ),
      ),
    );
  }
}
