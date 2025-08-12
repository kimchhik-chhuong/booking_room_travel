import 'package:flutter/material.dart';
import 'package:booking_travel_flutter/models/hotel_model.dart';
import 'package:booking_travel_flutter/services/hotel_service.dart';
import 'package:booking_travel_flutter/screens/hotel/hotel_detail_page.dart';

class HotelListPageUpdated extends StatefulWidget {
  final int provinceId;
  final String provinceName;
  final String? adventureName;
  final int? adventureId;

  const HotelListPageUpdated({
    Key? key,
    required this.provinceId,
    required this.provinceName,
    this.adventureName,
    this.adventureId,
  }) : super(key: key);

  @override
  _HotelListPageUpdatedState createState() => _HotelListPageUpdatedState();
}

class _HotelListPageUpdatedState extends State<HotelListPageUpdated> {
  late Future<List<Hotel>> _hotelsFuture;
  final HotelService _hotelService = HotelService();
  String _searchQuery = '';
  double? _minPrice;
  double? _maxPrice;

  @override
  void initState() {
    super.initState();
    _loadHotels();
  }

  void _loadHotels() {
    setState(() {
      _hotelsFuture = _hotelService.fetchHotels(
        provinceId: widget.provinceId,
        adventureId: widget.adventureId,
        searchQuery: _searchQuery.isNotEmpty ? _searchQuery : null,
        minPrice: _minPrice,
        maxPrice: _maxPrice,
      );
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(widget.adventureName != null
            ? 'Hotels near ${widget.adventureName}'
            : 'Hotels in ${widget.provinceName}'),
        backgroundColor: Colors.orange,
        actions: [
          IconButton(
            icon: const Icon(Icons.filter_list),
            onPressed: _showFilterDialog,
          ),
        ],
      ),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(16),
            child: TextField(
              decoration: InputDecoration(
                hintText: 'Search hotels...',
                prefixIcon: const Icon(Icons.search),
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(8),
                ),
              ),
              onChanged: (value) {
                setState(() {
                  _searchQuery = value;
                  _loadHotels();
                });
              },
            ),
          ),
          Expanded(
            child: FutureBuilder<List<Hotel>>(
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
                return RefreshIndicator(
                  onRefresh: () async => _loadHotels(),
                  child: ListView.builder(
                    padding: const EdgeInsets.all(16),
                    itemCount: hotels.length,
                    itemBuilder: (context, index) {
                      final hotel = hotels[index];
                      return _buildHotelCard(hotel);
                    },
                  ),
                );
              },
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildHotelCard(Hotel hotel) {
    final minPrice = hotel.roomTypes.isNotEmpty
        ? hotel.roomTypes
            .map((room) => room.price)
            .reduce((a, b) => a < b ? a : b)
        : 0.0;

    return Card(
      margin: const EdgeInsets.only(bottom: 16),
      elevation: 4,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(12),
      ),
      child: InkWell(
        borderRadius: BorderRadius.circular(12),
        onTap: () {
          Navigator.push(
            context,
            MaterialPageRoute(
              builder: (context) => HotelDetailPage(hotel: hotel),
            ),
          );
        },
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            if (hotel.imageUrl != null)
              ClipRRect(
                borderRadius: const BorderRadius.vertical(
                  top: Radius.circular(12),
                ),
                child: Image.network(
                  hotel.imageUrl!,
                  height: 180,
                  width: double.infinity,
                  fit: BoxFit.cover,
                  errorBuilder: (context, error, stackTrace) => Container(
                    height: 180,
                    color: Colors.grey[300],
                    child: const Icon(Icons.hotel, size: 50),
                  ),
                ),
              ),
            Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    hotel.name,
                    style: const TextStyle(
                      fontSize: 20,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  const SizedBox(height: 8),
                  if (hotel.description != null)
                    Text(
                      hotel.description!,
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                      style: TextStyle(color: Colors.grey[600]),
                    ),
                  const SizedBox(height: 12),
                  Row(
                    children: [
                      const Icon(Icons.star, color: Colors.amber, size: 20),
                      const SizedBox(width: 4),
                      Text(
                        '4.5',
                        style: const TextStyle(fontWeight: FontWeight.bold),
                      ),
                      const Spacer(),
                      if (hotel.roomTypes.isNotEmpty)
                        Text(
                          '\$${minPrice.toStringAsFixed(0)}+ /night',
                          style: const TextStyle(
                            fontWeight: FontWeight.bold,
                            color: Colors.blue,
                            fontSize: 16,
                          ),
                        ),
                    ],
                  ),
                  const SizedBox(height: 8),
                  if (hotel.roomTypes.isNotEmpty)
                    Text(
                      '${hotel.roomTypes.length} room types available',
                      style: TextStyle(color: Colors.grey[600], fontSize: 12),
                    ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  void _showFilterDialog() {
    showDialog(
      context: context,
      builder: (context) {
        double tempMinPrice = _minPrice ?? 0;
        double tempMaxPrice = _maxPrice ?? 1000;

        return AlertDialog(
          title: const Text('Filter Hotels'),
          content: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              TextField(
                decoration: const InputDecoration(
                  labelText: 'Min Price',
                  prefixText: '\$',
                ),
                keyboardType: TextInputType.number,
                onChanged: (value) {
                  tempMinPrice = double.tryParse(value) ?? 0;
                },
              ),
              TextField(
                decoration: const InputDecoration(
                  labelText: 'Max Price',
                  prefixText: '\$',
                ),
                keyboardType: TextInputType.number,
                onChanged: (value) {
                  tempMaxPrice = double.tryParse(value) ?? 1000;
                },
              ),
            ],
          ),
          actions: [
            TextButton(
              onPressed: () {
                Navigator.pop(context);
              },
              child: const Text('Cancel'),
            ),
            TextButton(
              onPressed: () {
                setState(() {
                  _minPrice = tempMinPrice;
                  _maxPrice = tempMaxPrice;
                  _loadHotels();
                });
                Navigator.pop(context);
              },
              child: const Text('Apply'),
            ),
          ],
        );
      },
    );
  }
}
