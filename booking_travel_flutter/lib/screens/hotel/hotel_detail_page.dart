import 'package:flutter/material.dart';
import 'package:booking_travel/models/hotel_model.dart';
import 'package:booking_travel/models/room_type_model.dart';
import 'package:booking_travel/services/room_service.dart';
import 'package:booking_travel/services/booking_service.dart';
import 'package:booking_travel/services/payment_service.dart';
import 'package:booking_travel/screens/hotel/booking_confirmation_page.dart';
import 'package:booking_travel/screens/hotel/booking_success_page.dart';
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
    if (checkInDate == null || checkOutDate == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please select both check-in and check-out dates')),
      );
      return;
    }

    if (checkOutDate!.isBefore(checkInDate!.add(const Duration(days: 1)))) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Check-out date must be after check-in date')),
      );
      return;
    }

    setState(() {
      isCheckingAvailability = true;
    });

    try {
      // Log the dates being sent
      print('Checking availability with:');
      print('Check-in: ${checkInDate!.toIso8601String().split('T')[0]}');
      print('Check-out: ${checkOutDate!.toIso8601String().split('T')[0]}');
      print('Rooms: $rooms');

      final result = await RoomService.checkAvailability(
        roomTypeId: selectedRoom!.id,
        checkInDate: checkInDate!,
        checkOutDate: checkOutDate!,
        roomsNeeded: rooms,
      );

      setState(() {
        availabilityData = result;
      });

      if (result['available'] == true) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Room is available!')),
        );
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Not enough rooms available. Only ${result['available_rooms']} left.')),
        );
      }
    } catch (e) {
      print('Error checking availability: $e');
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Error: ${e.toString().replaceAll('Exception: ', '')}')),
      );
    } finally {
      setState(() {
        isCheckingAvailability = false;
      });
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

    // Calculate total price
    final nights = checkOutDate!.difference(checkInDate!).inDays;
    final totalPrice = selectedRoom!.price * nights * rooms;

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
          totalPrice: totalPrice,
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
                  _buildHotelInfo(),
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
              checkOutDate != null
          ? FloatingActionButton.extended(
              onPressed: () {
                if (availabilityData == null) {
                  _checkAvailability().then((_) {
                    if (availabilityData != null && availabilityData!['available'] == true) {
                      _proceedToBooking();
                    } else {
                      ScaffoldMessenger.of(context).showSnackBar(
                        const SnackBar(
                            content: Text('Please check room availability first')),
                      );
                    }
                  });
                } else if (availabilityData!['available'] == true) {
                  _proceedToBooking();
                } else {
                  _checkAvailability();
                }
              },
              backgroundColor: Colors.orange,
              icon: const Icon(Icons.book_online),
              label: const Text('Book Now'),
            )
          : null,
    );
  }

  Widget _buildHotelInfo() {
    return Padding(
      padding: const EdgeInsets.all(16.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            widget.hotel.name,
            style: const TextStyle(
              fontSize: 24.0,
              fontWeight: FontWeight.bold,
              color: Colors.black87,
            ),
          ),
          const SizedBox(height: 8.0),
          if (widget.hotel.rating != null) _buildRating(widget.hotel.rating!),
          const SizedBox(height: 16.0),
          if (widget.hotel.address != null) ...[
            _buildInfoRow(Icons.location_on, widget.hotel.address!),
            const SizedBox(height: 8.0),
          ],
          if (widget.hotel.phone != null) ...[
            _buildInfoRow(Icons.phone, widget.hotel.phone!),
            const SizedBox(height: 8.0),
          ],
          if (widget.hotel.website != null) ...[
            _buildInfoRow(Icons.public, widget.hotel.website!),
            const SizedBox(height: 8.0),
          ],
          if (widget.hotel.amenities != null && widget.hotel.amenities!.isNotEmpty) ...[
            const SizedBox(height: 8.0),
            const Text(
              'Amenities',
              style: TextStyle(
                fontSize: 18.0,
                fontWeight: FontWeight.w600,
                color: Colors.black87,
              ),
            ),
            const SizedBox(height: 8.0),
            _buildAmenitiesGrid(widget.hotel.amenities!),
          ],
          const SizedBox(height: 16.0),
          const Text(
            'Description',
            style: TextStyle(
              fontSize: 18.0,
              fontWeight: FontWeight.w600,
              color: Colors.black87,
            ),
          ),
          const SizedBox(height: 8.0),
          Text(
            widget.hotel.description,
            style: const TextStyle(
              fontSize: 16.0,
              height: 1.5,
              color: Colors.black87,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildInfoRow(IconData icon, String text) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Icon(icon, size: 20.0, color: Colors.grey[600]),
        const SizedBox(width: 8.0),
        Expanded(
          child: Text(
            text,
            style: TextStyle(
              fontSize: 15.0,
              color: Colors.grey[800],
            ),
          ),
        ),
      ],
    );
  }

  Widget _buildRating(double rating) {
    return Row(
      children: [
        const Icon(Icons.star, color: Colors.amber, size: 20.0),
        const SizedBox(width: 4.0),
        Text(
          rating.toStringAsFixed(1),
          style: const TextStyle(
            fontSize: 16.0,
            fontWeight: FontWeight.w600,
            color: Colors.black87,
          ),
        ),
      ],
    );
  }

  Widget _buildAmenitiesGrid(List<String> amenities) {
    // Define icons for common amenities
    final Map<String, IconData> amenityIcons = {
      'wifi': Icons.wifi,
      'pool': Icons.pool,
      'restaurant': Icons.restaurant,
      'parking': Icons.local_parking,
      'gym': Icons.fitness_center,
      'spa': Icons.spa,
      'ac': Icons.ac_unit,
      'tv': Icons.tv,
      'bar': Icons.local_bar,
      'breakfast': Icons.free_breakfast,
      'pets': Icons.pets,
      'elevator': Icons.elevator,
      'laundry': Icons.local_laundry_service,
      'concierge': Icons.room_service,
      'meeting': Icons.business_center,
    };

    // Create a list of widgets for the grid
    final amenityWidgets = amenities.map((amenity) {
      final icon = amenityIcons.entries
          .firstWhere(
            (entry) => amenity.toLowerCase().contains(entry.key),
            orElse: () => const MapEntry('', Icons.check_circle_outline),
          )
          .value;

      return Container(
        padding: const EdgeInsets.symmetric(horizontal: 12.0, vertical: 8.0),
        decoration: BoxDecoration(
          color: Colors.grey[100],
          borderRadius: BorderRadius.circular(8.0),
          border: Border.all(color: Colors.grey[300]!),
        ),
        child: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(icon, size: 16.0, color: Colors.blue[700]),
            const SizedBox(width: 6.0),
            Text(
              amenity,
              style: const TextStyle(fontSize: 14.0, color: Colors.black87),
            ),
          ],
        ),
      );
    }).toList();

    return Wrap(
      spacing: 8.0,
      runSpacing: 8.0,
      children: amenityWidgets,
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
              const Icon(Icons.people, size: 16, color: Colors.grey),
              const SizedBox(width: 4),
              Text('Max ${room.maxOccupancy} guests'),
              const SizedBox(width: 16),
              const Icon(Icons.hotel, size: 16, color: Colors.grey),
              const SizedBox(width: 4),
              Text('${room.availableRooms} available'),
            ],
          ),
          if (room.amenities != null && room.amenities!.isNotEmpty) ...[
            const SizedBox(height: 8),
            Wrap(
              spacing: 8,
              runSpacing: 4,
              children: room.amenities!.map((amenity) {
                return Chip(
                  label: Text(
                    amenity,
                    style: const TextStyle(fontSize: 12),
                    overflow: TextOverflow.ellipsis,
                  ),
                  backgroundColor: Colors.grey.shade200,
                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 0),
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

class BookingConfirmationPage extends StatefulWidget {
  final Hotel hotel;
  final RoomType roomType;
  final DateTime checkInDate;
  final DateTime checkOutDate;
  final int guests;
  final int rooms;
  final double totalPrice;

  const BookingConfirmationPage({
    Key? key,
    required this.hotel,
    required this.roomType,
    required this.checkInDate,
    required this.checkOutDate,
    required this.guests,
    required this.rooms,
    required this.totalPrice,
  }) : super(key: key);

  @override
  State<BookingConfirmationPage> createState() => _BookingConfirmationPageState();
}

class _BookingConfirmationPageState extends State<BookingConfirmationPage> {
  final _formKey = GlobalKey<FormState>();
  final _cardNumberController = TextEditingController();
  final _expiryController = TextEditingController();
  final _cvvController = TextEditingController();
  final _cardNameController = TextEditingController();
  bool _isLoading = false;
  String _selectedPaymentMethod = 'credit_card';

  @override
  void dispose() {
    _cardNumberController.dispose();
    _expiryController.dispose();
    _cvvController.dispose();
    _cardNameController.dispose();
    super.dispose();
  }

  Future<void> _confirmBooking() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() => _isLoading = true);

    try {
      // Process payment first
      final paymentResult = await PaymentService.processPayment(
        amount: widget.totalPrice,
        paymentMethod: _selectedPaymentMethod,
        cardDetails: _selectedPaymentMethod == 'credit_card'
            ? {
                'cardNumber': _cardNumberController.text,
                'expiry': _expiryController.text,
                'cvv': _cvvController.text,
                'cardHolder': _cardNameController.text,
              }
            : null,
      );

      if (!mounted) return;

      if (paymentResult['success'] == true) {
        // Create booking
        final bookingResult = await BookingService.createBookingWithPayment(
          hotelId: widget.hotel.id,
          roomTypeId: widget.roomType.id,
          checkInDate: widget.checkInDate,
          checkOutDate: widget.checkOutDate,
          numberOfGuests: widget.guests,
          numberOfRooms: widget.rooms,
          totalAmount: widget.totalPrice,
          paymentMethod: _selectedPaymentMethod,
          guestInfo: {
            'name': _cardNameController.text,
            'email': 'user@example.com', // Replace with actual user email
            'phone': '+1234567890', // Replace with actual user phone
          },
          cardDetails: _selectedPaymentMethod == 'credit_card'
              ? {
                  'cardNumber': _cardNumberController.text,
                  'expiry': _expiryController.text,
                  'cvv': _cvvController.text,
                  'cardHolder': _cardNameController.text,
                }
              : null,
        );

        if (!mounted) return;

        if (bookingResult['success'] == true) {
          Navigator.pushReplacement(
            context,
            MaterialPageRoute(
              builder: (context) => BookingSuccessPage(
                bookingId: bookingResult['bookingId'],
                hotel: widget.hotel,
                roomType: widget.roomType,
                checkInDate: widget.checkInDate,
                checkOutDate: widget.checkOutDate,
                guests: widget.guests,
                rooms: widget.rooms,
                totalAmount: widget.totalPrice,
              ),
            ),
          );
        } else {
          throw Exception(bookingResult['message'] ?? 'Failed to create booking');
        }
      } else {
        throw Exception(paymentResult['message'] ?? 'Payment failed');
      }
    } catch (e) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Error: ${e.toString()}')),
      );
    } finally {
      if (mounted) {
        setState(() => _isLoading = false);
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final nights = widget.checkOutDate.difference(widget.checkInDate).inDays;
    final pricePerNight = widget.roomType.price;

    return Scaffold(
      appBar: AppBar(
        title: const Text('Confirm Booking'),
        backgroundColor: Colors.orange,
      ),
      body: Form(
        key: _formKey,
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Booking Summary
              _buildBookingSummary(nights, pricePerNight),
              const SizedBox(height: 24),
              
              // Payment Method
              _buildPaymentMethodSection(),
              const SizedBox(height: 24),
              
              // Payment Details (only show for credit card)
              if (_selectedPaymentMethod == 'credit_card')
                _buildPaymentDetails(),
              
              const SizedBox(height: 24),
              
              // Total Price
              _buildTotalPrice(),
              const SizedBox(height: 32),
              
              // Confirm Button
              SizedBox(
                width: double.infinity,
                child: ElevatedButton(
                  onPressed: _isLoading ? null : _confirmBooking,
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.orange,
                    padding: const EdgeInsets.symmetric(vertical: 16),
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(8),
                    ),
                  ),
                  child: _isLoading
                      ? const CircularProgressIndicator(color: Colors.white)
                      : const Text(
                          'Confirm Booking',
                          style: TextStyle(fontSize: 16, color: Colors.white),
                        ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildBookingSummary(int nights, double pricePerNight) {
    return Card(
      elevation: 2,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Booking Summary',
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 16),
            _buildSummaryRow('Hotel', widget.hotel.name),
            _buildSummaryRow('Room Type', widget.roomType.name),
            _buildSummaryRow('Check-in',
                '${widget.checkInDate.day}/${widget.checkInDate.month}/${widget.checkInDate.year}'),
            _buildSummaryRow('Check-out',
                '${widget.checkOutDate.day}/${widget.checkOutDate.month}/${widget.checkOutDate.year}'),
            _buildSummaryRow('Nights', '$nights'),
            _buildSummaryRow('Guests', '${widget.guests}'),
            _buildSummaryRow('Rooms', '${widget.rooms}'),
            const Divider(thickness: 1, height: 32),
            _buildSummaryRow('Price per night', '\$${pricePerNight.toStringAsFixed(2)}'),
            _buildSummaryRow('Total', '\$${widget.totalPrice.toStringAsFixed(2)}', isTotal: true),
          ],
        ),
      ),
    );
  }

  Widget _buildPaymentMethodSection() {
    return Card(
      elevation: 2,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Payment Method',
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 12),
            RadioListTile<String>(
              title: const Text('Credit/Debit Card'),
              value: 'credit_card',
              groupValue: _selectedPaymentMethod,
              onChanged: (value) {
                setState(() {
                  _selectedPaymentMethod = value!;
                });
              },
            ),
            RadioListTile<String>(
              title: const Text('Pay at Hotel'),
              value: 'pay_at_hotel',
              groupValue: _selectedPaymentMethod,
              onChanged: (value) {
                setState(() {
                  _selectedPaymentMethod = value!;
                });
              },
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildPaymentDetails() {
    return Card(
      elevation: 2,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Card Details',
              style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 12),
            TextFormField(
              controller: _cardNameController,
              decoration: const InputDecoration(
                labelText: 'Cardholder Name',
                border: OutlineInputBorder(),
                prefixIcon: Icon(Icons.person_outline),
              ),
              validator: (value) {
                if (value == null || value.isEmpty) {
                  return 'Please enter cardholder name';
                }
                return null;
              },
            ),
            const SizedBox(height: 12),
            TextFormField(
              controller: _cardNumberController,
              decoration: const InputDecoration(
                labelText: 'Card Number',
                border: OutlineInputBorder(),
                prefixIcon: Icon(Icons.credit_card),
                hintText: '1234 5678 9012 3456',
              ),
              keyboardType: TextInputType.number,
              validator: (value) {
                if (value == null || value.isEmpty) {
                  return 'Please enter card number';
                }
                // Simple validation - in production, use a proper card validation library
                if (value.replaceAll(' ', '').length < 16) {
                  return 'Please enter a valid card number';
                }
                return null;
              },
            ),
            const SizedBox(height: 12),
            Row(
              children: [
                Expanded(
                  child: TextFormField(
                    controller: _expiryController,
                    decoration: const InputDecoration(
                      labelText: 'MM/YY',
                      border: OutlineInputBorder(),
                      hintText: 'MM/YY',
                    ),
                    validator: (value) {
                      if (value == null || value.isEmpty) {
                        return 'Required';
                      }
                      // Simple validation
                      if (!RegExp(r'^\d{2}/\d{2}$').hasMatch(value)) {
                        return 'Invalid format';
                      }
                      return null;
                    },
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: TextFormField(
                    controller: _cvvController,
                    decoration: const InputDecoration(
                      labelText: 'CVV',
                      border: OutlineInputBorder(),
                      hintText: '123',
                    ),
                    keyboardType: TextInputType.number,
                    obscureText: true,
                    validator: (value) {
                      if (value == null || value.isEmpty) {
                        return 'Required';
                      }
                      if (value.length < 3) {
                        return 'Invalid CVV';
                      }
                      return null;
                    },
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildTotalPrice() {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.grey[100],
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: Colors.grey[300]!), 
      ),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          const Text(
            'Total Amount:',
            style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
          ),
          Text(
            '\$${widget.totalPrice.toStringAsFixed(2)}',
            style: const TextStyle(
              fontSize: 24,
              fontWeight: FontWeight.bold,
              color: Colors.orange,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildSummaryRow(String label, String value, {bool isTotal = false}) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(
            label,
            style: TextStyle(
              color: isTotal ? Colors.black : Colors.grey[700],
              fontWeight: isTotal ? FontWeight.bold : FontWeight.normal,
            ),
          ),
          Text(
            value,
            style: TextStyle(
              fontSize: isTotal ? 18 : 14,
              fontWeight: isTotal ? FontWeight.bold : FontWeight.normal,
              color: isTotal ? Colors.orange : null,
            ),
          ),
        ],
      ),
    );
  }
}

class BookingSuccessPage extends StatelessWidget {
  final String bookingId;
  final Hotel hotel;
  final RoomType roomType;
  final DateTime checkInDate;
  final DateTime checkOutDate;
  final int guests;
  final int rooms;
  final double totalAmount;

  const BookingSuccessPage({
    Key? key,
    required this.bookingId,
    required this.hotel,
    required this.roomType,
    required this.checkInDate,
    required this.checkOutDate,
    required this.guests,
    required this.rooms,
    required this.totalAmount,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    final nights = checkOutDate.difference(checkInDate).inDays;

    return Scaffold(
      appBar: AppBar(
        title: const Text('Booking Success'),
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
                      'Total: \$${totalAmount.toStringAsFixed(2)}',
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
                  'View Booking Details',
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
