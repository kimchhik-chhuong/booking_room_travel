import 'package:flutter/material.dart';
import 'package:booking_travel/models/hotel_model.dart';
import 'package:booking_travel/services/hotel_service.dart';
import 'package:booking_travel/screens/hotel/hotel_detail_page.dart';

class HotelListPage extends StatefulWidget {
  final int provinceId;
  final String provinceName;
  final String? adventureName;
  final int? adventureId;

  const HotelListPage({
    super.key,
    required this.provinceId,
    required this.provinceName,
    this.adventureName,
    this.adventureId,
  });

  @override
  State<HotelListPage> createState() => _HotelListPageState();
}

class _HotelListPageState extends State<HotelListPage> {
  late Future<List<Hotel>> _hotelsFuture;

  @override
  void initState() {
    super.initState();
    print('HotelListPage initState:');
    print('  - provinceId: ${widget.provinceId}');
    print('  - provinceName: ${widget.provinceName}');
    print('  - adventureName: ${widget.adventureName}');
    print('  - adventureId: ${widget.adventureId}');
    _hotelsFuture = _fetchHotels();
  }

  Future<List<Hotel>> _fetchHotels() async {
    print('_fetchHotels called');
    try {
      if (widget.adventureId != null) {
        print('Fetching hotels by adventure ID: ${widget.adventureId}');
        final hotels = await HotelService.fetchHotelsByAdventure(widget.adventureId!);
        print('Fetched ${hotels.length} hotels by adventure');
        return hotels;
      } else {
        print('Fetching hotels by province ID: ${widget.provinceId}');
        final hotels = await HotelService.fetchHotelsByProvince(widget.provinceId);
        print('Fetched ${hotels.length} hotels by province');
        return hotels;
      }
    } catch (e) {
      print('Error fetching hotels: $e');
      throw Exception('Failed to load hotels: $e');
    }
  }

  void _navigateToHotelDetail(Hotel hotel) {
    Navigator.push(
      context,
      MaterialPageRoute(
        builder: (context) => HotelDetailPage(
          hotel: hotel,
        ),
      ),
    );
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
      body: FutureBuilder<List<Hotel>>(
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
              return Container(
                margin: const EdgeInsets.only(bottom: 20),
                decoration: BoxDecoration(
                  borderRadius: BorderRadius.circular(16),
                  boxShadow: [
                    BoxShadow(
                      color: Colors.black.withOpacity(0.1),
                      blurRadius: 10,
                      offset: const Offset(0, 4),
                    ),
                  ],
                ),
                child: Card(
                  elevation: 0,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(16),
                  ),
                  child: InkWell(
                    onTap: () => _navigateToHotelDetail(hotel),
                    borderRadius: BorderRadius.circular(16),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        // Hotel Image with Overlay
                        Stack(
                          children: [
                            ClipRRect(
                              borderRadius: const BorderRadius.vertical(
                                top: Radius.circular(16),
                              ),
                              child: hotel.firstImage != null
                                  ? Image.network(
                                      hotel.firstImage!,
                                      height: 200,
                                      width: double.infinity,
                                      fit: BoxFit.cover,
                                      loadingBuilder: (context, child, loadingProgress) {
                                        if (loadingProgress == null) return child;
                                        return Container(
                                          height: 200,
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
                                      errorBuilder: (context, error, stackTrace) =>
                                          _buildImagePlaceholder(),
                                    )
                                  : _buildImagePlaceholder(),
                            ),
                            // Rating Badge
                            if (hotel.starRating != null)
                              Positioned(
                                top: 12,
                                right: 12,
                                child: Row(
                                  children: [
                                    for (int i = 0; i < 5; i++)
                                      Icon(
                                        Icons.star,
                                        size: 16,
                                        color: i < (hotel.starRating ?? 0)
                                            ? Colors.amber
                                            : Colors.grey[300],
                                      ),
                                    const SizedBox(width: 4),
                                    Text(
                                      hotel.starRating!.toStringAsFixed(1),
                                      style: const TextStyle(fontSize: 12),
                                    ),
                                  ],
                                ),
                              ),
                          ],
                        ),
                        
                        // Hotel Information
                        Padding(
                          padding: const EdgeInsets.all(16),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              // Hotel Name
                              Text(
                                hotel.name,
                                style: const TextStyle(
                                  fontSize: 20,
                                  fontWeight: FontWeight.bold,
                                  color: Colors.black87,
                                ),
                              ),
                              const SizedBox(height: 8),
                              
                              // Description
                              if (hotel.description?.isNotEmpty == true)
                                Text(
                                  hotel.description!,
                                  maxLines: 2,
                                  overflow: TextOverflow.ellipsis,
                                ),
                              const SizedBox(height: 12),
                              
                              // Contact Information Row
                              Row(
                                children: [
                                  // Phone
                                  if (hotel.contactPhone != null && hotel.contactPhone!.isNotEmpty)
                                    Expanded(
                                      child: Row(
                                        children: [
                                          Icon(
                                            Icons.phone,
                                            size: 16,
                                            color: Colors.orange[600],
                                          ),
                                          const SizedBox(width: 4),
                                          Flexible(
                                            child: Text(
                                              hotel.contactPhone!,
                                              style: TextStyle(
                                                fontSize: 12,
                                                color: Colors.grey[700],
                                              ),
                                              overflow: TextOverflow.ellipsis,
                                            ),
                                          ),
                                        ],
                                      ),
                                    ),
                                  
                                  // Website indicator
                                  if (hotel.contactPhone != null && hotel.contactPhone!.isNotEmpty)
                                    const SizedBox(width: 16),
                                  Icon(
                                    Icons.language,
                                    size: 16,
                                    color: Colors.orange[600],
                                  ),
                                  const SizedBox(width: 4),
                                  Text(
                                    'Website Available',
                                    style: TextStyle(
                                      fontSize: 12,
                                      color: Colors.grey[700],
                                    ),
                                  ),
                                ],
                              ),
                              const SizedBox(height: 12),
                              
                              // Price and Action Row
                              Row(
                                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                children: [
                                  // Price - Show "Contact for Price" since we don't have room type data in list view
                                  Container(
                                    padding: const EdgeInsets.symmetric(
                                      horizontal: 12,
                                      vertical: 6,
                                    ),
                                    decoration: BoxDecoration(
                                      color: Colors.grey.shade100,
                                      borderRadius: BorderRadius.circular(20),
                                    ),
                                    child: Text(
                                      'View Rooms & Prices',
                                      style: TextStyle(
                                        color: Colors.grey[600],
                                        fontSize: 12,
                                      ),
                                    ),
                                  ),
                                  
                                  // View Details Button
                                  Container(
                                    padding: const EdgeInsets.symmetric(
                                      horizontal: 16,
                                      vertical: 8,
                                    ),
                                    decoration: BoxDecoration(
                                      gradient: LinearGradient(
                                        colors: [
                                          Colors.orange.shade400,
                                          Colors.orange.shade600,
                                        ],
                                      ),
                                      borderRadius: BorderRadius.circular(20),
                                    ),
                                    child: const Text(
                                      'View Details',
                                      style: TextStyle(
                                        color: Colors.white,
                                        fontWeight: FontWeight.w600,
                                        fontSize: 12,
                                      ),
                                    ),
                                  ),
                                ],
                              ),
                              Text(
                                '${hotel.starRating?.toStringAsFixed(1) ?? 'N/A'}/5',
                                style: const TextStyle(fontSize: 12, color: Colors.amber),
                              ),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
              );
            },
          );
        },
      ),
    );
  }

  Widget _buildImagePlaceholder() {
    return Container(
      height: 200,
      decoration: BoxDecoration(
        gradient: LinearGradient(
          colors: [
            Colors.orange.shade300,
            Colors.orange.shade500,
          ],
        ),
      ),
      child: const Center(
        child: Icon(
          Icons.hotel,
          size: 60,
          color: Colors.white,
        ),
      ),
    );
  }
}
