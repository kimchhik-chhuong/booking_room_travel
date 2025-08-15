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
              return Card(
                margin: const EdgeInsets.only(bottom: 16),
                child: InkWell(
                  onTap: () => _navigateToHotelDetail(hotel),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      if (hotel.image != null)
                        ClipRRect(
                          borderRadius: const BorderRadius.vertical(
                              top: Radius.circular(8)),
                          child: Image.network(
                            hotel.image!,
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
                              hotel.name,
                              style: const TextStyle(
                                fontSize: 18,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                            const SizedBox(height: 8),
                            Text(
                              hotel.description,
                              maxLines: 2,
                              overflow: TextOverflow.ellipsis,
                            ),
                            const SizedBox(height: 8),
                            Row(
                              children: [
                                const Icon(Icons.star,
                                    color: Colors.amber, size: 16),
                                Text(' ${hotel.rating ?? 'N/A'}'),
                                const Spacer(),
                                Text(
                                  '\$${hotel.priceRange ?? 'N/A'}/night',
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
