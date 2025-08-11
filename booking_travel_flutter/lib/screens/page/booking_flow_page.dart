import 'package:flutter/material.dart';
import 'package:booking_travel_flutter/screens/provinces/provinces_page.dart';
import 'package:booking_travel_flutter/screens/page/adventures_page.dart';
import 'package:booking_travel_flutter/screens/hotel/hotel_list_page.dart';
import 'package:booking_travel_flutter/screens/booking/booking_page.dart';

class BookingFlowPage extends StatefulWidget {
  const BookingFlowPage({Key? key}) : super(key: key);

  @override
  _BookingFlowPageState createState() => _BookingFlowPageState();
}

class _BookingFlowPageState extends State<BookingFlowPage> {
  int _currentStep = 0;
  Map<String, dynamic> bookingData = {};

  void _nextStep(Map<String, dynamic> data) {
    setState(() {
      bookingData.addAll(data);
      _currentStep++;
    });
  }

  void _previousStep() {
    setState(() {
      _currentStep--;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Book Your Trip'),
        backgroundColor: Colors.deepPurple,
        foregroundColor: Colors.white,
      ),
      body: _buildCurrentStep(),
    );
  }

  Widget _buildCurrentStep() {
    switch (_currentStep) {
      case 0:
        return ProvincesSelectionPage(
          onProvinceSelected: (province) => _nextStep({'province': province}),
        );
      case 1:
        return AdventuresSelectionPage(
          provinceId: bookingData['province']['id'],
          onAdventureSelected: (adventure) =>
              _nextStep({'adventure': adventure}),
          onBack: _previousStep,
        );
      case 2:
        return HotelsSelectionPage(
          provinceId: bookingData['province']['id'],
          onHotelSelected: (hotel) => _nextStep({'hotel': hotel}),
          onBack: _previousStep,
        );
      case 3:
        return RoomSelectionPage(
          hotel: bookingData['hotel'],
          onRoomSelected: (room) => _nextStep({'room': room}),
          onBack: _previousStep,
        );
      case 4:
        return BookingConfirmationPage(
          bookingData: bookingData,
          onBack: _previousStep,
        );
      default:
        return const Center(child: Text('Invalid step'));
    }
  }
}

class ProvincesSelectionPage extends StatelessWidget {
  final Function(Map<String, dynamic>) onProvinceSelected;

  const ProvincesSelectionPage({Key? key, required this.onProvinceSelected})
      : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Container(
          padding: const EdgeInsets.all(16),
          color: Colors.deepPurple.withOpacity(0.1),
          child: const Column(
            children: [
              Icon(Icons.map, size: 48, color: Colors.deepPurple),
              SizedBox(height: 8),
              Text(
                'Step 1: Choose Your Destination',
                style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
              ),
              Text('Select a province to explore amazing destinations'),
            ],
          ),
        ),
        Expanded(
          child: ProvincesPage(
            onProvinceTap: onProvinceSelected,
          ),
        ),
      ],
    );
  }
}

class AdventuresSelectionPage extends StatelessWidget {
  final int provinceId;
  final Function(Map<String, dynamic>) onAdventureSelected;
  final VoidCallback onBack;

  const AdventuresSelectionPage({
    Key? key,
    required this.provinceId,
    required this.onAdventureSelected,
    required this.onBack,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Container(
          padding: const EdgeInsets.all(16),
          color: Colors.orange.withOpacity(0.1),
          child: Column(
            children: [
              const Icon(Icons.hiking, size: 48, color: Colors.orange),
              const SizedBox(height: 8),
              const Text(
                'Step 2: Choose Your Adventure',
                style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
              ),
              const Text('Select an adventure activity'),
              Row(
                children: [
                  IconButton(
                    icon: const Icon(Icons.arrow_back),
                    onPressed: onBack,
                  ),
                  const Text('Back to Provinces'),
                ],
              ),
            ],
          ),
        ),
        Expanded(
          child: AdventuresPage(
            provinceId: provinceId,
            onAdventureTap: onAdventureSelected,
          ),
        ),
      ],
    );
  }
}

class HotelsSelectionPage extends StatelessWidget {
  final int provinceId;
  final Function(Map<String, dynamic>) onHotelSelected;
  final VoidCallback onBack;

  const HotelsSelectionPage({
    Key? key,
    required this.provinceId,
    required this.onHotelSelected,
    required this.onBack,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Container(
          padding: const EdgeInsets.all(16),
          color: Colors.blue.withOpacity(0.1),
          child: Column(
            children: [
              const Icon(Icons.hotel, size: 48, color: Colors.blue),
              const SizedBox(height: 8),
              const Text(
                'Step 3: Choose Your Hotel',
                style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
              ),
              const Text('Select from our premium hotel collection'),
              Row(
                children: [
                  IconButton(
                    icon: const Icon(Icons.arrow_back),
                    onPressed: onBack,
                  ),
                  const Text('Back to Adventures'),
                ],
              ),
            ],
          ),
        ),
        Expanded(
          child: HotelListPage(
            provinceId: provinceId,
            onHotelTap: onHotelSelected,
          ),
        ),
      ],
    );
  }
}

class RoomSelectionPage extends StatelessWidget {
  final Map<String, dynamic> hotel;
  final Function(Map<String, dynamic>) onRoomSelected;
  final VoidCallback onBack;

  const RoomSelectionPage({
    Key? key,
    required this.hotel,
    required this.onRoomSelected,
    required this.onBack,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Container(
          padding: const EdgeInsets.all(16),
          color: Colors.green.withOpacity(0.1),
          child: Column(
            children: [
              const Icon(Icons.bed, size: 48, color: Colors.green),
              const SizedBox(height: 8),
              const Text(
                'Step 4: Choose Your Room',
                style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
              ),
              Text('Available rooms at ${hotel['name']}'),
              Row(
                children: [
                  IconButton(
                    icon: const Icon(Icons.arrow_back),
                    onPressed: onBack,
                  ),
                  const Text('Back to Hotels'),
                ],
              ),
            ],
          ),
        ),
        Expanded(
          child: RoomListPage(
            hotelId: hotel['id'],
            onRoomTap: onRoomSelected,
          ),
        ),
      ],
    );
  }
}

class BookingConfirmationPage extends StatelessWidget {
  final Map<String, dynamic> bookingData;
  final VoidCallback onBack;

  const BookingConfirmationPage({
    Key? key,
    required this.bookingData,
    required this.onBack,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      padding: const EdgeInsets.all(16),
      child: Column(
        children: [
          Container(
            padding: const EdgeInsets.all(16),
            color: Colors.purple.withOpacity(0.1),
            child: Column(
              children: [
                const Icon(Icons.check_circle, size: 48, color: Colors.purple),
                const SizedBox(height: 8),
                const Text(
                  'Step 5: Confirm Your Booking',
                  style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
                ),
                const Text('Review your selection before booking'),
              ],
            ),
          ),
          const SizedBox(height: 20),
          Card(
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
                  _buildSummaryRow('Province', bookingData['province']['name']),
                  _buildSummaryRow(
                      'Adventure', bookingData['adventure']['name']),
                  _buildSummaryRow('Hotel', bookingData['hotel']['name']),
                  _buildSummaryRow('Room', bookingData['room']['type']),
                  _buildSummaryRow(
                      'Price', '\$${bookingData['room']['price']}'),
                  const SizedBox(height: 20),
                  Row(
                    children: [
                      Expanded(
                        child: OutlinedButton(
                          onPressed: onBack,
                          child: const Text('Back'),
                        ),
                      ),
                      const SizedBox(width: 16),
                      Expanded(
                        child: ElevatedButton(
                          onPressed: () {
                            // Proceed with booking
                            _handleBooking(context);
                          },
                          child: const Text('Confirm Booking'),
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildSummaryRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label, style: const TextStyle(color: Colors.grey)),
          Text(value, style: const TextStyle(fontWeight: FontWeight.bold)),
        ],
      ),
    );
  }

  void _handleBooking(BuildContext context) {
    // Implement booking logic
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(content: Text('Booking confirmed successfully!')),
    );
    Navigator.pop(context);
  }
}
