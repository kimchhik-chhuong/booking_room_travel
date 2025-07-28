import 'package:flutter/material.dart';

class SearchScreen extends StatefulWidget {
  @override
  _SearchScreenState createState() => _SearchScreenState();
}

class _SearchScreenState extends State<SearchScreen> {
  final List<String> allHotels = [
    'The Royal Hotel',
    'Mountain View Resort',
    'Cozy Stay Inn',
    'Sunrise Villa',
    'City Center Hotel',
  ];

  List<String> filteredHotels = [];
  final TextEditingController _controller = TextEditingController();

  @override
  void initState() {
    super.initState();
    filteredHotels = allHotels; // initially show all
  }

  void _filterHotels(String query) {
    final results = allHotels.where((hotel) =>
        hotel.toLowerCase().contains(query.toLowerCase())).toList();
    setState(() {
      filteredHotels = results;
    });
  }

  void _performSearch() {
    _filterHotels(_controller.text); // Trigger filter on button press
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Search Hotels'),
        backgroundColor: Colors.blue,
      ),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            Row(
              children: [
                Expanded(
                  child: TextField(
                    controller: _controller,
                    onChanged: _filterHotels, // Real-time filtering
                    decoration: InputDecoration(
                      hintText: 'Search hotel name...',
                      prefixIcon: Icon(Icons.search),
                      border: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(10),
                      ),
                    ),
                  ),
                ),
                SizedBox(width: 10),
                ElevatedButton(
                  onPressed: _performSearch,
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.blue,
                    foregroundColor: Colors.white,
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(10),
                    ),
                  ),
                  child: Text('Search'),
                ),
              ],
            ),
            SizedBox(height: 20),
            Expanded(
              child: filteredHotels.isEmpty
                  ? Center(child: Text('No hotels found'))
                  : ListView.builder(
                      itemCount: filteredHotels.length,
                      itemBuilder: (context, index) {
                        final hotel = filteredHotels[index];
                        return Card(
                          margin: EdgeInsets.symmetric(vertical: 8),
                          shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(10)),
                          child: ListTile(
                            leading: Icon(Icons.hotel, color: Colors.blue),
                            title: Text(hotel),
                            trailing: Icon(Icons.arrow_forward_ios),
                            onTap: () {
                              Navigator.push(
                                context,
                                MaterialPageRoute(
                                  builder: (context) => RoomDetailScreen(hotelName: hotel),
                                ),
                              );
                            },
                          ),
                        );
                      },
                    ),
            ),
          ],
        ),
      ),
    );
  }
}

class RoomDetailScreen extends StatefulWidget {
  final String hotelName;

  RoomDetailScreen({required this.hotelName});

  @override
  _RoomDetailScreenState createState() => _RoomDetailScreenState();
}

class _RoomDetailScreenState extends State<RoomDetailScreen> {
  DateTime? _selectedDate;
  String? _selectedBeds;
  String? _selectedPeople;
  final List<String> bedOptions = ['1', '2', '3'];
  final List<String> peopleOptions = ['1', '2', '3', '4', '5'];

  // Sample room data with availability status
  final Map<String, Map<String, dynamic>> rooms = {
    'Standard Room': {'price': 150.0, 'availability': 'Not Full'},
    'Deluxe Room': {'price': 250.0, 'availability': '3 rooms'},
    'Suite': {'price': 400.0, 'availability': 'Full'},
  };

  // Map of hotels to their image URLs
  static const Map<String, List<String>> hotelImages = {
    'The Royal Hotel': [
      'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&q=80',
      'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=800&q=80',
      'https://images.unsplash.com/photo-1582719508461-905c673e8e66?w=800&q=80',
    ],
    'Mountain View Resort': [
      'https://images.unsplash.com/photo-1501785888041-af3ef285b470?w=800&q=80',
      'https://images.unsplash.com/photo-1472214103451-9374bd1c798e?w=800&q=80',
      'https://images.unsplash.com/photo-1513694203232-719a280e022f?w=800&q=80',
    ],
    'Cozy Stay Inn': [
      'https://images.unsplash.com/photo-1571003123893-5a3e48e5c3a8?w=800&q=80',
      'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&q=80',
      'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=800&q=80',
    ],
    'Sunrise Villa': [
      'https://images.unsplash.com/photo-1586611292717-f828b164dd45?w=800&q=80',
      'https://images.unsplash.com/photo-1501785888041-af3ef285b470?w=800&q=80',
      'https://images.unsplash.com/photo-1472214103451-9374bd1c798e?w=800&q=80',
    ],
    'City Center Hotel': [
      'https://images.unsplash.com/photo-1568084680786-a84f91d3132e?w=800&q=80',
      'https://images.unsplash.com/photo-1513694203232-719a280e022f?w=800&q=80',
      'https://images.unsplash.com/photo-1571003123893-5a3e48e5c3a8?w=800&q=80',
    ],
  };

  Future<void> _selectDate(BuildContext context) async {
    final DateTime? picked = await showDatePicker(
      context: context,
      initialDate: _selectedDate ?? DateTime.now(),
      firstDate: DateTime.now(),
      lastDate: DateTime(2026),
    );
    if (picked != null && picked != _selectedDate) {
      setState(() {
        _selectedDate = picked;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    final images = hotelImages[widget.hotelName] ?? ['https://via.placeholder.com/800x400?text=No+Image'];

    return Scaffold(
      appBar: AppBar(
        title: Text('${widget.hotelName} Details'),
        backgroundColor: Colors.blue,
      ),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: SingleChildScrollView(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Image Gallery
              SizedBox(
                height: 200,
                child: ListView.builder(
                  scrollDirection: Axis.horizontal,
                  itemCount: images.length,
                  itemBuilder: (context, index) {
                    return Padding(
                      padding: const EdgeInsets.only(right: 10),
                      child: ClipRRect(
                        borderRadius: BorderRadius.circular(10),
                        child: Image.network(
                          images[index],
                          width: 300,
                          height: 200,
                          fit: BoxFit.cover,
                          errorBuilder: (context, error, stackTrace) {
                            return Container(
                              width: 300,
                              height: 200,
                              color: Colors.grey[300],
                              child: const Icon(Icons.broken_image, size: 40),
                            );
                          },
                        ),
                      ),
                    );
                  },
                ),
              ),
              SizedBox(height: 20),
              Text(
                widget.hotelName,
                style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
              ),
              SizedBox(height: 20),
              // Selection Fields
              Row(
                children: [
                  Expanded(
                    child: InkWell(
                      onTap: () => _selectDate(context),
                      child: InputDecorator(
                        decoration: InputDecoration(
                          labelText: 'Check-in Date',
                          border: OutlineInputBorder(
                            borderRadius: BorderRadius.circular(10),
                          ),
                        ),
                        child: Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Text(
                              _selectedDate == null
                                  ? 'Select Date'
                                  : '${_selectedDate!.toLocal().toString().split(' ')[0]}',
                            ),
                            Icon(Icons.calendar_today),
                          ],
                        ),
                      ),
                    ),
                  ),
                  SizedBox(width: 10),
                  Expanded(
                    child: DropdownButtonFormField<String>(
                      value: _selectedBeds,
                      hint: Text('Beds'),
                      items: bedOptions.map((String value) {
                        return DropdownMenuItem<String>(
                          value: value,
                          child: Text(value),
                        );
                      }).toList(),
                      onChanged: (String? newValue) {
                        setState(() {
                          _selectedBeds = newValue;
                        });
                      },
                      decoration: InputDecoration(
                        border: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(10),
                        ),
                      ),
                    ),
                  ),
                  SizedBox(width: 10),
                  Expanded(
                    child: DropdownButtonFormField<String>(
                      value: _selectedPeople,
                      hint: Text('People'),
                      items: peopleOptions.map((String value) {
                        return DropdownMenuItem<String>(
                          value: value,
                          child: Text(value),
                        );
                      }).toList(),
                      onChanged: (String? newValue) {
                        setState(() {
                          _selectedPeople = newValue;
                        });
                      },
                      decoration: InputDecoration(
                        border: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(10),
                        ),
                      ),
                    ),
                  ),
                ],
              ),
              SizedBox(height: 20),
              Text(
                'Available Rooms',
                style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
              ),
              SizedBox(height: 10),
              ...rooms.entries.map((entry) {
                final roomName = entry.key;
                final roomData = entry.value;
                final price = roomData['price'];
                final availability = roomData['availability'];
                Color availabilityColor = Colors.black;
                if (availability == 'Full') {
                  availabilityColor = Colors.red;
                } else if (availability == 'Not Full') {
                  availabilityColor = Colors.green;
                } // '3 rooms' can stay default black

                return ListTile(
                  leading: Icon(Icons.bed, color: Colors.blue),
                  title: Text(roomName),
                  subtitle: Text('\$${price}/night'),
                  trailing: Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Text(
                        '($availability)',
                        style: TextStyle(color: availabilityColor, fontWeight: FontWeight.bold),
                      ),
                      SizedBox(width: 10),
                      ElevatedButton(
                        onPressed: availability == 'Full' || _selectedDate == null || _selectedBeds == null || _selectedPeople == null
                            ? null // Disable if full or selections are incomplete
                            : () {
                                // Handle booking action with selections
                                final bookingDetails = {
                                  'hotel': widget.hotelName,
                                  'room': roomName,
                                  'date': _selectedDate,
                                  'beds': _selectedBeds,
                                  'people': _selectedPeople,
                                  'price': price,
                                };
                                print('Booking: $bookingDetails'); // Placeholder for booking logic
                              },
                        child: Text('Book Now'),
                      ),
                    ],
                  ),
                );
              }).toList(),
            ],
          ),
        ),
      ),
    );
  }
}