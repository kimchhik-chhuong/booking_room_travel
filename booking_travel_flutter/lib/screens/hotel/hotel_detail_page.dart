import 'package:flutter/material.dart';
import 'package:booking_travel/models/hotel_model.dart';
import 'package:booking_travel/models/room_type_model.dart';
import 'package:booking_travel/services/room_service.dart';
import 'package:booking_travel/services/booking_service.dart';
import 'package:url_launcher/url_launcher.dart';

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

  @override
  void initState() {
    super.initState();
    _loadRoomTypes();
  }

  Future<void> _loadRoomTypes() async {
    try {
      final rooms = await RoomService.fetchRoomTypesByHotel(widget.hotel.id);
      setState(() {
        roomTypes = rooms;
        isLoadingRooms = false;
      });
    } catch (e) {
      setState(() {
        isLoadingRooms = false;
      });
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Error loading rooms: $e')),
      );
    }
  }

  Future<void> _checkAvailability() async {
    if (selectedRoom == null || checkInDate == null || checkOutDate == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please select room type and dates')),
      );
      return;
    }

    setState(() {
      isCheckingAvailability = true;
    });

    try {
      final availability = await RoomService.checkAvailability(
        roomTypeId: selectedRoom!.id,
        checkInDate: checkInDate!,
        checkOutDate: checkOutDate!,
        roomsNeeded: rooms,
      );

      setState(() {
        availabilityData = availability;
        isCheckingAvailability = false;
      });
    } catch (e) {
      setState(() {
        isCheckingAvailability = false;
      });
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Error checking availability: $e')),
      );
    }
  }

  Future<void> _selectDate(BuildContext context, bool isCheckIn) async {
    final DateTime? picked = await showDatePicker(
      context: context,
      initialDate: DateTime.now(),
      firstDate: DateTime.now(),
      lastDate: DateTime.now().add(const Duration(days: 365)),
    );
    
    if (picked != null) {
      setState(() {
        if (isCheckIn) {
          checkInDate = picked;
          if (checkOutDate != null && checkOutDate!.isBefore(picked)) {
            checkOutDate = null;
          }
        } else {
          if (checkInDate != null && picked.isAfter(checkInDate!)) {
            checkOutDate = picked;
          }
        }
        availabilityData = null; // Reset availability when dates change
      });
    }
  }

  void _proceedToBooking() {
    if (selectedRoom == null || checkInDate == null || checkOutDate == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please complete all booking details')),
      );
      return;
    }

    if (availabilityData == null || availabilityData!['available'] != true) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please check room availability first')),
      );
      return;
    }

    Navigator.push(
      context,
      MaterialPageRoute(
        builder: (context) => BookingConfirmationPage(
          hotel: widget.hotel,
          roomType: selectedRoom!,
          checkInDate: checkInDate!,
          checkOutDate: checkOutDate!,
          guests: guests,
          rooms: rooms,
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: CustomScrollView(
        slivers: [
          SliverAppBar(
            expandedHeight: 300.0,
            floating: false,
            pinned: true,
            flexibleSpace: FlexibleSpaceBar(
              title: Container(
                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                decoration: BoxDecoration(
                  color: Colors.black54,
                  borderRadius: BorderRadius.circular(8),
                ),
                child: Text(
                  widget.hotel.name,
                  style: const TextStyle(
                    color: Colors.white,
                    fontSize: 16,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
              background: Stack(
                fit: StackFit.expand,
                children: [
                  widget.hotel.image != null
                      ? Image.network(
                          widget.hotel.image!,
                          fit: BoxFit.cover,
                          errorBuilder: (context, error, stackTrace) {
                            return Container(
                              color: Colors.grey[300],
                              child: const Icon(Icons.hotel, size: 100),
                            );
                          },
                        )
                      : Container(
                          color: Colors.grey[300],
                          child: const Icon(Icons.hotel, size: 100),
                        ),
                  Container(
                    decoration: BoxDecoration(
                      gradient: LinearGradient(
                        begin: Alignment.topCenter,
                        end: Alignment.bottomCenter,
                        colors: [
                          Colors.transparent,
                          Colors.black.withOpacity(0.7),
                        ],
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ),
          SliverToBoxAdapter(
            child: Padding(
              padding: const EdgeInsets.all(16.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Hotel Info Section
                  _buildHotelInfoSection(),
                  const SizedBox(height: 24),
                  
                  // Location Map Section
                  _buildLocationMapSection(),
                  const SizedBox(height: 24),
                  
                  // Booking Section
                  _buildBookingSection(),
                  const SizedBox(height: 24),
                  
                  // Choose Room Section
                  _buildChooseRoomSection(),
                  const SizedBox(height: 24),
                  
                  // Availability Section
                  if (availabilityData != null) _buildAvailabilitySection(),
                  const SizedBox(height: 100), // Space for floating button
                ],
              ),
            ),
          ),
        ],
      ),
      floatingActionButton: selectedRoom != null &&
              checkInDate != null &&
              checkOutDate != null &&
              availabilityData != null &&
              availabilityData!['available'] == true
          ? FloatingActionButton.extended(
              onPressed: _proceedToBooking,
              backgroundColor: Colors.orange,
              icon: const Icon(Icons.book_online),
              label: const Text('Book Now'),
            )
          : null,
    );
  }

  Widget _buildHotelInfoSection() {
    return Card(
      elevation: 4,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Expanded(
                  child: Text(
                    widget.hotel.name,
                    style: const TextStyle(
                      fontSize: 24,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                ),
                if (widget.hotel.rating != null)
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                    decoration: BoxDecoration(
                      color: Colors.orange,
                      borderRadius: BorderRadius.circular(12),
                    ),
                    child: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        const Icon(Icons.star, color: Colors.white, size: 16),
                        const SizedBox(width: 4),
                        Text(
                          widget.hotel.rating!.toStringAsFixed(1),
                          style: const TextStyle(
                            color: Colors.white,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ],
                    ),
                  ),
              ],
            ),
            const SizedBox(height: 8),
            if (widget.hotel.address != null)
              Row(
                children: [
                  const Icon(Icons.location_on, color: Colors.grey, size: 16),
                  const SizedBox(width: 4),
                  Expanded(
                    child: Text(
                      widget.hotel.address!,
                      style: const TextStyle(color: Colors.grey),
                    ),
                  ),
                ],
              ),
            const SizedBox(height: 12),
            Text(
              widget.hotel.description,
              style: const TextStyle(fontSize: 16),
            ),
            const SizedBox(height: 16),
            _buildAmenities(),
          ],
        ),
      ),
    );
  }

  Widget _buildAmenities() {
    final amenities = [
      {'icon': Icons.wifi, 'label': 'Free WiFi'},
      {'icon': Icons.pool, 'label': 'Swimming Pool'},
      {'icon': Icons.restaurant, 'label': 'Restaurant'},
      {'icon': Icons.local_parking, 'label': 'Parking'},
      {'icon': Icons.fitness_center, 'label': 'Fitness Center'},
      {'icon': Icons.room_service, 'label': 'Room Service'},
    ];

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        const Text(
          'Amenities',
          style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
        ),
        const SizedBox(height: 8),
        Wrap(
          spacing: 16,
          runSpacing: 8,
          children: amenities.map((amenity) {
            return Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                Icon(amenity['icon'] as IconData, size: 20, color: Colors.orange),
                const SizedBox(width: 4),
                Text(amenity['label'] as String),
              ],
            );
          }).toList(),
        ),
      ],
    );
  }

  Widget _buildBookingSection() {
    return Card(
      elevation: 4,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Book Your Stay',
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 16),
            Row(
              children: [
                Expanded(
                  child: GestureDetector(
                    onTap: () => _selectDate(context, true),
                    child: Container(
                      padding: const EdgeInsets.all(12),
                      decoration: BoxDecoration(
                        border: Border.all(color: Colors.grey),
                        borderRadius: BorderRadius.circular(8),
                      ),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const Text('Check-in', style: TextStyle(fontSize: 12, color: Colors.grey)),
                          Text(
                            checkInDate != null
                                ? '${checkInDate!.day}/${checkInDate!.month}/${checkInDate!.year}'
                                : 'Select date',
                            style: const TextStyle(fontSize: 16),
                          ),
                        ],
                      ),
                    ),
                  ),
                ),
                const SizedBox(width: 16),
                Expanded(
                  child: GestureDetector(
                    onTap: () => _selectDate(context, false),
                    child: Container(
                      padding: const EdgeInsets.all(12),
                      decoration: BoxDecoration(
                        border: Border.all(color: Colors.grey),
                        borderRadius: BorderRadius.circular(8),
                      ),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const Text('Check-out', style: TextStyle(fontSize: 12, color: Colors.grey)),
                          Text(
                            checkOutDate != null
                                ? '${checkOutDate!.day}/${checkOutDate!.month}/${checkOutDate!.year}'
                                : 'Select date',
                            style: const TextStyle(fontSize: 16),
                          ),
                        ],
                      ),
                    ),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 16),
            Row(
              children: [
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text('Guests', style: TextStyle(fontSize: 12, color: Colors.grey)),
                      Row(
                        children: [
                          IconButton(
                            onPressed: guests > 1 ? () => setState(() => guests--) : null,
                            icon: const Icon(Icons.remove),
                          ),
                          Text('$guests', style: const TextStyle(fontSize: 16)),
                          IconButton(
                            onPressed: () => setState(() => guests++),
                            icon: const Icon(Icons.add),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text('Rooms', style: TextStyle(fontSize: 12, color: Colors.grey)),
                      Row(
                        children: [
                          IconButton(
                            onPressed: rooms > 1 ? () => setState(() => rooms--) : null,
                            icon: const Icon(Icons.remove),
                          ),
                          Text('$rooms', style: const TextStyle(fontSize: 16)),
                          IconButton(
                            onPressed: () => setState(() => rooms++),
                            icon: const Icon(Icons.add),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildLocationMapSection() {
    return Card(
      elevation: 4,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Location',
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 12),
            if (widget.hotel.address != null) ...[
              Row(
                children: [
                  const Icon(Icons.location_on, color: Colors.orange, size: 20),
                  const SizedBox(width: 8),
                  Expanded(
                    child: Text(
                      widget.hotel.address!,
                      style: const TextStyle(fontSize: 16),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 16),
            ],
            // Map placeholder with interactive elements
            Container(
              height: 200,
              width: double.infinity,
              decoration: BoxDecoration(
                borderRadius: BorderRadius.circular(8),
                border: Border.all(color: Colors.grey.shade300),
                color: Colors.grey.shade100,
              ),
              child: Stack(
                children: [
                  // Map placeholder
                  Center(
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Icon(
                          Icons.map,
                          size: 60,
                          color: Colors.grey.shade400,
                        ),
                        const SizedBox(height: 8),
                        Text(
                          'Interactive Map',
                          style: TextStyle(
                            fontSize: 16,
                            color: Colors.grey.shade600,
                            fontWeight: FontWeight.w500,
                          ),
                        ),
                        const SizedBox(height: 4),
                        Text(
                          'Tap to view location',
                          style: TextStyle(
                            fontSize: 12,
                            color: Colors.grey.shade500,
                          ),
                        ),
                      ],
                    ),
                  ),
                  // Overlay with hotel marker
                  Positioned(
                    top: 80,
                    left: MediaQuery.of(context).size.width * 0.4,
                    child: Container(
                      padding: const EdgeInsets.all(8),
                      decoration: BoxDecoration(
                        color: Colors.orange,
                        borderRadius: BorderRadius.circular(20),
                        boxShadow: [
                          BoxShadow(
                            color: Colors.black.withOpacity(0.2),
                            blurRadius: 4,
                            offset: const Offset(0, 2),
                          ),
                        ],
                      ),
                      child: const Icon(
                        Icons.location_on,
                        color: Colors.white,
                        size: 20,
                      ),
                    ),
                  ),
                  // Tap detector
                  Positioned.fill(
                    child: Material(
                      color: Colors.transparent,
                      child: InkWell(
                        borderRadius: BorderRadius.circular(8),
                        onTap: () => _openMap(),
                        child: Container(),
                      ),
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 12),
            Row(
              children: [
                Expanded(
                  child: OutlinedButton.icon(
                    onPressed: () => _openMap(),
                    icon: const Icon(Icons.directions, color: Colors.orange),
                    label: const Text(
                      'Get Directions',
                      style: TextStyle(color: Colors.orange),
                    ),
                    style: OutlinedButton.styleFrom(
                      side: const BorderSide(color: Colors.orange),
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(8),
                      ),
                    ),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: OutlinedButton.icon(
                    onPressed: () => _shareLocation(),
                    icon: const Icon(Icons.share, color: Colors.orange),
                    label: const Text(
                      'Share Location',
                      style: TextStyle(color: Colors.orange),
                    ),
                    style: OutlinedButton.styleFrom(
                      side: const BorderSide(color: Colors.orange),
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(8),
                      ),
                    ),
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  void _openMap() async {
    if (widget.hotel.address != null) {
      final query = Uri.encodeComponent(widget.hotel.address!);
      final googleMapsUrl = 'https://www.google.com/maps/search/?api=1&query=$query';
      final appleMapsUrl = 'https://maps.apple.com/?q=$query';
      
      try {
        if (await canLaunchUrl(Uri.parse(googleMapsUrl))) {
          await launchUrl(Uri.parse(googleMapsUrl), mode: LaunchMode.externalApplication);
        } else if (await canLaunchUrl(Uri.parse(appleMapsUrl))) {
          await launchUrl(Uri.parse(appleMapsUrl), mode: LaunchMode.externalApplication);
        }
      } catch (e) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Could not open maps application')),
        );
      }
    }
  }

  void _shareLocation() {
    if (widget.hotel.address != null) {
      // In a real app, you would use share_plus package
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Location: ${widget.hotel.address!}')),
      );
    }
  }

  Widget _buildChooseRoomSection() {
    return Card(
      elevation: 4,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Choose Room',
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 16),
            if (isLoadingRooms)
              const Center(child: CircularProgressIndicator())
            else if (roomTypes.isEmpty)
              const Center(child: Text('No rooms available'))
            else
              ...roomTypes.map((room) => _buildRoomCard(room)).toList(),
          ],
        ),
      ),
    );
  }

  Widget _buildRoomCard(RoomType room) {
    final isSelected = selectedRoom?.id == room.id;
    
    return GestureDetector(
      onTap: () {
        setState(() {
          selectedRoom = room;
          availabilityData = null; // Reset availability when room changes
        });
      },
      child: Container(
        margin: const EdgeInsets.only(bottom: 12),
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          border: Border.all(
            color: isSelected ? Colors.orange : Colors.grey.shade300,
            width: isSelected ? 2 : 1,
          ),
          borderRadius: BorderRadius.circular(8),
          color: isSelected ? Colors.orange.shade50 : Colors.white,
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Expanded(
                  child: Text(
                    room.name,
                    style: TextStyle(
                      fontSize: 16,
                      fontWeight: FontWeight.bold,
                      color: isSelected ? Colors.orange.shade800 : Colors.black,
                    ),
                  ),
                ),
                Text(
                  room.formattedPrice,
                  style: TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                    color: isSelected ? Colors.orange.shade800 : Colors.green,
                  ),
                ),
                const Text('/night', style: TextStyle(color: Colors.grey)),
              ],
            ),
            const SizedBox(height: 8),
            if (room.description != null)
              Text(
                room.description!,
                style: const TextStyle(color: Colors.grey),
              ),
            const SizedBox(height: 8),
            Row(
              children: [
                Icon(Icons.people, size: 16, color: Colors.grey),
                const SizedBox(width: 4),
                Text('Max ${room.maxOccupancy} guests'),
                const SizedBox(width: 16),
                Icon(Icons.hotel, size: 16, color: Colors.grey),
                const SizedBox(width: 4),
                Text('${room.availableRooms} available'),
              ],
            ),
            if (room.amenities != null && room.amenities!.isNotEmpty) ...[
              const SizedBox(height: 8),
              Wrap(
                spacing: 8,
                children: room.amenities!.take(3).map((amenity) {
                  return Chip(
                    label: Text(amenity, style: const TextStyle(fontSize: 12)),
                    backgroundColor: Colors.grey.shade200,
                  );
                }).toList(),
              ),
            ],
          ],
        ),
      ),
    );
  }

  Widget _buildAvailabilitySection() {
    final isAvailable = availabilityData!['available'] == true;
    
    return Card(
      elevation: 4,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Icon(
                  isAvailable ? Icons.check_circle : Icons.cancel,
                  color: isAvailable ? Colors.green : Colors.red,
                ),
                const SizedBox(width: 8),
                Text(
                  isAvailable ? 'Available' : 'Not Available',
                  style: TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                    color: isAvailable ? Colors.green : Colors.red,
                  ),
                ),
              ],
            ),
            const SizedBox(height: 8),
            if (isAvailable) ...[
              Text('${availabilityData!['available_rooms']} rooms available for your dates'),
              if (checkInDate != null && checkOutDate != null) ...[
                const SizedBox(height: 8),
                Text(
                  'Total: ${selectedRoom!.formattedPrice} × ${checkOutDate!.difference(checkInDate!).inDays} nights × $rooms rooms = ${(selectedRoom!.price * checkOutDate!.difference(checkInDate!).inDays * rooms).toStringAsFixed(2)}',
                  style: const TextStyle(fontWeight: FontWeight.bold),
                ),
              ],
            ] else
              Text('Only ${availabilityData!['available_rooms']} rooms available, but you requested $rooms rooms'),
            const SizedBox(height: 12),
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: isCheckingAvailability ? null : _checkAvailability,
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.orange,
                  padding: const EdgeInsets.symmetric(vertical: 12),
                ),
                child: isCheckingAvailability
                    ? const CircularProgressIndicator(color: Colors.white)
                    : const Text('Check Availability', style: TextStyle(color: Colors.white)),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

// Placeholder for BookingConfirmationPage
class BookingConfirmationPage extends StatelessWidget {
  final Hotel hotel;
  final RoomType roomType;
  final DateTime checkInDate;
  final DateTime checkOutDate;
  final int guests;
  final int rooms;

  const BookingConfirmationPage({
    Key? key,
    required this.hotel,
    required this.roomType,
    required this.checkInDate,
    required this.checkOutDate,
    required this.guests,
    required this.rooms,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    final nights = checkOutDate.difference(checkInDate).inDays;
    final totalPrice = roomType.price * nights * rooms;

    return Scaffold(
      appBar: AppBar(
        title: const Text('Booking Confirmation'),
        backgroundColor: Colors.orange,
      ),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Booking Summary',
              style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 16),
            Card(
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text('Hotel: ${hotel.name}', style: const TextStyle(fontSize: 16)),
                    Text('Room: ${roomType.name}', style: const TextStyle(fontSize: 16)),
                    Text('Check-in: ${checkInDate.day}/${checkInDate.month}/${checkInDate.year}'),
                    Text('Check-out: ${checkOutDate.day}/${checkOutDate.month}/${checkOutDate.year}'),
                    Text('Nights: $nights'),
                    Text('Rooms: $rooms'),
                    Text('Guests: $guests'),
                    const Divider(),
                    Text(
                      'Total: \$${totalPrice.toStringAsFixed(2)}',
                      style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                    ),
                  ],
                ),
              ),
            ),
            const Spacer(),
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: () {
                  // TODO: Implement actual booking logic
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(content: Text('Booking functionality coming soon!')),
                  );
                },
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.orange,
                  padding: const EdgeInsets.symmetric(vertical: 16),
                ),
                child: const Text(
                  'Confirm Booking',
                  style: TextStyle(fontSize: 18, color: Colors.white),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
