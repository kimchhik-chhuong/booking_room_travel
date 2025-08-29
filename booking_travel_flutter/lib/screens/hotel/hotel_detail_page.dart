import 'package:flutter/material.dart';
import 'package:booking_travel/models/hotel_model.dart';
import 'package:booking_travel/models/room_type_model.dart';
import 'package:booking_travel/screens/hotel/widgets/hotel_booking_form.dart';
import 'package:url_launcher/url_launcher.dart';
import 'package:intl/intl.dart';

class HotelDetailPage extends StatefulWidget {
  final Hotel hotel;

  const HotelDetailPage({Key? key, required this.hotel}) : super(key: key);

  @override
  State<HotelDetailPage> createState() => _HotelDetailPageState();
}

class _HotelDetailPageState extends State<HotelDetailPage> {
  int _currentImageIndex = 0;
  final PageController _pageController = PageController();
  final ScrollController _scrollController = ScrollController();
  bool showBookingForm = true;

  @override
  void initState() {
    super.initState();
    // Pre-load images if needed
    WidgetsBinding.instance.addPostFrameCallback((_) {
      // Any initialization after first frame
    });
  }

  @override
  void dispose() {
    _pageController.dispose();
    _scrollController.dispose();
    super.dispose();
  }

  Future<void> _onBookNow(RoomType roomType) async {
    try {
      await HotelBookingForm.show(
        context,
        hotel: widget.hotel,
        roomType: roomType,
        onBookNow: () {
          // Refresh any necessary data after booking
          if (mounted) {
            setState(() {});
            ScaffoldMessenger.of(context).showSnackBar(
              const SnackBar(content: Text('Booking successful!')),
            );
          }
        },
      );
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Error: ${e.toString()}')),
        );
      }
    }
  }

  void _toggleBookingForm() {
    setState(() {
      showBookingForm = !showBookingForm;
    });
  }

  List<Widget> _buildRoomList() {
    // Check if there are any room types available
    if (widget.hotel.roomTypes == null || widget.hotel.roomTypes!.isEmpty) {
      return [
        const Padding(
          padding: EdgeInsets.all(16.0),
          child: Center(
            child: Text('No rooms available for booking at this time.'),
          ),
        ),
      ];
    }

    // Create a list of widgets for each room type
    return widget.hotel.roomTypes!.map((roomType) => Card(
      margin: const EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
      child: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Room name and price
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text(
                  roomType.name,
                  style: const TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                Text(
                  '\$${roomType.price.toStringAsFixed(2)}/night',
                  style: const TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.bold,
                    color: Colors.orange,
                  ),
                ),
              ],
            ),
            
            // Room description
            if (roomType.description != null && roomType.description!.isNotEmpty)
              Padding(
                padding: const EdgeInsets.symmetric(vertical: 8.0),
                child: Text(
                  roomType.description!,
                  style: TextStyle(
                    color: Colors.grey[600],
                    fontSize: 14,
                  ),
                ),
              ),
            
            // Room amenities
            if (roomType.amenities != null && roomType.amenities!.isNotEmpty)
              Wrap(
                spacing: 8,
                runSpacing: 4,
                children: roomType.amenities!.take(3).map((amenity) => Chip(
                  label: Text(amenity),
                  backgroundColor: Colors.grey[100],
                  padding: EdgeInsets.zero,
                  materialTapTargetSize: MaterialTapTargetSize.shrinkWrap,
                )).toList(),
              ),
            
            // Book Now button
            const SizedBox(height: 16),
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: () => _onBookNow(roomType),
                style: ElevatedButton.styleFrom(
                  padding: const EdgeInsets.symmetric(vertical: 16),
                  backgroundColor: Colors.orange,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(8.0),
                  ),
                ),
                child: const Text(
                  'Book Now',
                  style: TextStyle(fontSize: 16, color: Colors.white),
                ),
              ),
            ),
          ],
        ),
      ),
    )).toList();
  }

  Widget _buildAppBarContent() {
    final images = widget.hotel.images ?? [];
    final hasMultipleImages = images.length > 1;

    return Stack(
      fit: StackFit.expand,
      children: [
        // Main image
        if (images.isNotEmpty)
          PageView.builder(
            controller: _pageController,
            itemCount: images.length,
            onPageChanged: (index) {
              setState(() {
                _currentImageIndex = index;
              });
            },
            itemBuilder: (context, index) {
              return Image.network(
                _getFullImageUrl(images[index]),
                fit: BoxFit.cover,
                loadingBuilder: (context, child, loadingProgress) {
                  if (loadingProgress == null) return child;
                  return Center(
                    child: CircularProgressIndicator(
                      value: loadingProgress.expectedTotalBytes != null
                          ? loadingProgress.cumulativeBytesLoaded /
                              loadingProgress.expectedTotalBytes!
                          : null,
                    ),
                  );
                },
                errorBuilder: (context, error, stackTrace) => Container(
                  color: Colors.grey[300],
                  child: const Icon(Icons.hotel, size: 60, color: Colors.grey),
                ),
              );
            },
          )
        else
          Container(
            color: Colors.grey[300],
            child: const Icon(Icons.hotel, size: 60, color: Colors.grey),
          ),

        // Gradient overlay
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

        // Page indicator
        if (hasMultipleImages)
          Positioned(
            bottom: 16,
            left: 0,
            right: 0,
            child: Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: List.generate(
                images.length,
                (index) => Container(
                  width: 8,
                  height: 8,
                  margin: const EdgeInsets.symmetric(horizontal: 4),
                  decoration: BoxDecoration(
                    shape: BoxShape.circle,
                    color: _currentImageIndex == index
                        ? Colors.white
                        : Colors.white.withOpacity(0.5),
                  ),
                ),
              ),
            ),
          ),

        // Back button
        Positioned(
          top: MediaQuery.of(context).padding.top + 8,
          left: 8,
          child: Material(
            color: Colors.black26,
            borderRadius: BorderRadius.circular(20),
            child: IconButton(
              icon: const Icon(Icons.arrow_back, color: Colors.white),
              onPressed: () => Navigator.of(context).pop(),
            ),
          ),
        ),
      ],
    );
  }

  String _getFullImageUrl(String imageUrl) {
    // Handle relative URLs by checking if it starts with http
    return imageUrl.startsWith('http')
        ? imageUrl
        : 'https://your-api-base-url${imageUrl.startsWith('/') ? '' : '/'}$imageUrl';
  }

  Future<void> _shareHotel() async {
    // TODO: Implement share functionality
    final url = 'https://your-app-url.com/hotels/${widget.hotel.id}';
    await launchUrl(Uri.parse('whatsapp://send?text=Check out this hotel: $url'));
  }

  Future<void> _showOnMap() async {
    if (widget.hotel.latitude != null && widget.hotel.longitude != null) {
      final url =
          'https://www.google.com/maps/search/?api=1&query=${widget.hotel.latitude},${widget.hotel.longitude}';
      if (await canLaunchUrl(Uri.parse(url))) {
        await launchUrl(Uri.parse(url));
      } else {
        // Fallback to web URL if the maps app can't be opened
        await launchUrl(Uri.parse('https://maps.google.com?q=${widget.hotel.latitude},${widget.hotel.longitude}'));
      }
    } else if (widget.hotel.address != null) {
      final url =
          'https://www.google.com/maps/search/?api=1&query=${Uri.encodeComponent(widget.hotel.address!)}';
      if (await canLaunchUrl(Uri.parse(url))) {
        await launchUrl(Uri.parse(url));
      } else {
        // Fallback to web URL if the maps app can't be opened
        await launchUrl(Uri.parse('https://maps.google.com?q=${Uri.encodeComponent(widget.hotel.address!)}'));
      }
    } else {
      // Show error if no location data is available
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Location information not available')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: CustomScrollView(
        controller: _scrollController,
        slivers: [
          // App bar with image
          SliverAppBar(
            expandedHeight: 300,
            pinned: true,
            flexibleSpace: _buildAppBarContent(),
            actions: [
              IconButton(
                icon: const Icon(Icons.share, color: Colors.white),
                onPressed: _shareHotel,
              ),
            ],
          ),
          
          // Hotel details
          SliverToBoxAdapter(
            child: Padding(
              padding: const EdgeInsets.all(16.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Hotel name and rating
                  Row(
                    children: [
                      Expanded(
                        child: Text(
                          widget.hotel.name,
                          style: Theme.of(context).textTheme.headlineSmall?.copyWith(
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ),
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                        decoration: BoxDecoration(
                          color: Colors.orange,
                          borderRadius: BorderRadius.circular(4),
                        ),
                        child: Row(
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            const Icon(Icons.star, color: Colors.white, size: 16),
                            const SizedBox(width: 4),
                            Text(
                              widget.hotel.rating?.toStringAsFixed(1) ?? 'N/A',
                              style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                  
                  // Location
                  const SizedBox(height: 8),
                  Row(
                    children: [
                      const Icon(Icons.location_on, size: 16, color: Colors.grey),
                      const SizedBox(width: 4),
                      Expanded(
                        child: Text(
                          widget.hotel.address ?? 'No address provided',
                          style: TextStyle(color: Colors.grey[600]),
                        ),
                      ),
                    ],
                  ),
                  
                  // Description
                  const SizedBox(height: 16),
                  Text(
                    widget.hotel.description ?? 'No description available',
                    style: TextStyle(fontSize: 14, color: Colors.grey[800]),
                  ),
                  
                  // Divider
                  const Padding(
                    padding: EdgeInsets.symmetric(vertical: 16.0),
                    child: Divider(),
                  ),
                  
                  // Available Rooms section
                  const Text(
                    'Available Rooms',
                    style: TextStyle(
                      fontSize: 18,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  const SizedBox(height: 8),
                ],
              ),
            ),
          ),
          
          // Room list
          SliverList(
            delegate: SliverChildListDelegate(_buildRoomList()),
          ),
          
          // Add some bottom padding
          const SliverToBoxAdapter(
            child: SizedBox(height: 24),
          ),
        ],
      ),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: _toggleBookingForm,
        backgroundColor: Colors.orange,
        label: const Text('Book Now', style: TextStyle(color: Colors.white)),
        icon: const Icon(Icons.calendar_today, color: Colors.white),
      ),
    );
  }
}
