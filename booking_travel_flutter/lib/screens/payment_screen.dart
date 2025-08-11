import 'package:flutter/material.dart';
import 'package:flutter/rendering.dart';
import 'dart:ui' as ui;
import 'package:path_provider/path_provider.dart';
import 'dart:io';
import 'package:permission_handler/permission_handler.dart';
import 'package:flutter/services.dart';
import 'package:flutter/foundation.dart' show kIsWeb;
import 'dart:typed_data';
import 'dart:html' as html;

void main() {
  runApp(MaterialApp(
    home: HotelsPage(),
    theme: ThemeData(primarySwatch: Colors.blue),
    routes: {
      '/payment': (context) => PaymentScreen(),
    },
  ));
}

class HotelsPage extends StatelessWidget {
  const HotelsPage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Hotels'),
        backgroundColor: Colors.blue,
      ),
      body: ListView(
        padding: EdgeInsets.zero,
        children: [
          _buildSectionTitle('Featured Hotels'),
          _buildHotelList(context),
        ],
      ),
    );
  }

  Widget _buildSectionTitle(String title) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      child: Text(
        title,
        style: const TextStyle(
          fontSize: 18,
          fontWeight: FontWeight.bold,
        ),
      ),
    );
  }

  Widget _buildHotelList(BuildContext context) {
    final hotels = [
      {
        'name': 'Taj Hotel',
        'price': '\$200/Night',
        'imageUrl': 'assets/room2.jpg',
        'rating': 4.5,
        'reviews': 20,
        'description': 'The ONOMO Hotels chain established...',
      },
      {
        'name': 'AR Hotel',
        'price': '\$200/Night',
        'imageUrl': 'assets/room2.jpg',
        'rating': 4.5,
        'reviews': 20,
        'description': 'The ONOMO Hotels chain established...',
      },
      {
        'name': 'Al Rahman Hotel',
        'price': '\$200/Night',
        'imageUrl': 'assets/room2.jpg',
        'rating': 4.5,
        'reviews': 20,
        'description': 'The ONOMO Hotels chain established...',
      },
      {
        'name': 'Oberoy Hotel',
        'price': '\$200/Night',
        'imageUrl': 'assets/room2.jpg',
        'rating': 4.5,
        'reviews': 20,
        'description': 'The ONOMO Hotels chain established...',
      },
    ];

    return ListView.builder(
      padding: const EdgeInsets.all(8),
      shrinkWrap: true,
      physics: NeverScrollableScrollPhysics(),
      itemCount: hotels.length,
      itemBuilder: (context, index) {
        final hotel = hotels[index];
        return _buildHotelCard(
          context,
          hotelName: hotel['name'] as String,
          price: hotel['price'] as String,
          imageUrl: hotel['imageUrl'] as String,
          rating: (hotel['rating'] as double?) ?? 0.0,
          reviews: (hotel['reviews'] as int?) ?? 0,
          description: hotel['description'] as String,
        );
      },
    );
  }

  Widget _buildHotelCard(BuildContext context,
      {required String hotelName,
      required String price,
      required String imageUrl,
      required double rating,
      required int reviews,
      required String description}) {
    final isNetwork = imageUrl.startsWith('http');
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Card(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
        clipBehavior: Clip.antiAlias,
        elevation: 4,
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            ClipRRect(
              borderRadius: BorderRadius.circular(12),
              child: isNetwork
                  ? Image.network(
                      imageUrl,
                      height: 120,
                      width: 120,
                      fit: BoxFit.cover,
                      errorBuilder: (context, error, stackTrace) {
                        return Container(
                          height: 120,
                          width: 120,
                          color: Colors.grey[300],
                          child: const Icon(Icons.broken_image, size: 40),
                        );
                      },
                    )
                  : Image.asset(
                      imageUrl,
                      height: 120,
                      width: 120,
                      fit: BoxFit.cover,
                    ),
            ),
            Expanded(
              child: Padding(
                padding: const EdgeInsets.all(12),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Text(
                          hotelName,
                          style: const TextStyle(
                              fontSize: 18, fontWeight: FontWeight.bold),
                        ),
                        const Icon(Icons.favorite_border, color: Colors.grey),
                      ],
                    ),
                    const SizedBox(height: 4),
                    Row(
                      children: [
                        Icon(Icons.star, size: 16, color: Colors.yellow[700]),
                        const SizedBox(width: 4),
                        Text(
                          '$rating Reviews ($reviews)',
                          style: TextStyle(fontSize: 14, color: Colors.grey[600]),
                        ),
                      ],
                    ),
                    const SizedBox(height: 4),
                    Text(
                      description,
                      style: TextStyle(fontSize: 12, color: Colors.grey[600]),
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                    ),
                    const SizedBox(height: 8),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Text(price, style: TextStyle(color: Colors.blue, fontSize: 16)),
                        ElevatedButton(
                          onPressed: () {
                            Navigator.push(
                              context,
                              MaterialPageRoute(
                                builder: (context) => BookingScreen(
                                  hotelName: hotelName,
                                  address: '12 Eze Adele Road Rumuomasi Lagos Nigeria',
                                  price: price,
                                  imageUrl: imageUrl,
                                  description: description,
                                ),
                              ),
                            );
                          },
                          style: ElevatedButton.styleFrom(
                            backgroundColor: Colors.blue,
                            shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(8),
                            ),
                          ),
                          child: const Text('Book now'),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class BookingScreen extends StatefulWidget {
  final String hotelName;
  final String address;
  final String price;
  final String imageUrl;
  final String description;

  const BookingScreen({
    Key? key,
    required this.hotelName,
    required this.address,
    required this.price,
    required this.imageUrl,
    required this.description,
  }) : super(key: key);

  @override
  _BookingScreenState createState() => _BookingScreenState();
}

class _BookingScreenState extends State<BookingScreen> {
  late TextEditingController _destinationController;
  late TextEditingController _hotelNameController;
  late TextEditingController _bedsController;
  late TextEditingController _peopleController;
  DateTime? _selectedDate;

  @override
  void initState() {
    super.initState();
    _destinationController = TextEditingController();
    _hotelNameController = TextEditingController(text: widget.hotelName);
    _bedsController = TextEditingController(text: '1');
    _peopleController = TextEditingController(text: '1');
    _selectedDate = null;
  }

  @override
  void dispose() {
    _destinationController.dispose();
    _hotelNameController.dispose();
    _bedsController.dispose();
    _peopleController.dispose();
    super.dispose();
  }

  Future<void> _selectDate(BuildContext context) async {
    final DateTime now = DateTime.now();
    final DateTime? picked = await showDatePicker(
      context: context,
      initialDate: _selectedDate ?? now,
      firstDate: now,
      lastDate: DateTime(now.year + 1),
    );
    if (picked != null && picked != _selectedDate) {
      setState(() {
        _selectedDate = picked;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            ClipRRect(
              borderRadius: BorderRadius.vertical(bottom: Radius.circular(12)),
              child: Image.asset(
                widget.imageUrl,
                height: 200,
                width: double.infinity,
                fit: BoxFit.cover,
                errorBuilder: (context, error, stackTrace) {
                  return Container(
                    height: 200,
                    color: Colors.grey[300],
                    child: const Icon(Icons.broken_image, size: 40),
                  );
                },
              ),
            ),
            Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text(
                        widget.hotelName,
                        style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
                      ),
                      Text(
                        widget.price,
                        style: TextStyle(fontSize: 18, color: Colors.green),
                      ),
                    ],
                  ),
                  const SizedBox(height: 4),
                  Row(
                    children: [
                      Icon(Icons.star, size: 16, color: Colors.yellow[700]),
                      const SizedBox(width: 4),
                      Text(
                        '4.9 (1,092 Reviews)',
                        style: TextStyle(fontSize: 14, color: Colors.grey[600]),
                      ),
                    ],
                  ),
                  const SizedBox(height: 8),
                  Text(
                    widget.address,
                    style: TextStyle(fontSize: 14, color: Colors.grey[600]),
                  ),
                  const SizedBox(height: 8),
                  Text(
                    widget.description,
                    style: TextStyle(fontSize: 14, color: Colors.grey[600]),
                  ),
                  const SizedBox(height: 16),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text(
                        'Amenities',
                        style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                      ),
                      TextButton(
                        onPressed: () {},
                        child: Text('View All', style: TextStyle(color: Colors.blue)),
                      ),
                    ],
                  ),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceAround,
                    children: [
                      _buildAmenityIcon(Icons.local_cafe, 'Café'),
                      _buildAmenityIcon(Icons.restaurant, 'Restaurant'),
                      _buildAmenityIcon(Icons.local_dining, 'Garden'),
                      _buildAmenityIcon(Icons.golf_course, 'Golf Course'),
                      _buildAmenityIcon(Icons.wifi, 'Free WiFi'),
                    ],
                  ),
                  const SizedBox(height: 16),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text(
                        'Gallery Photos',
                        style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                      ),
                      TextButton(
                        onPressed: () {},
                        child: Text('See All', style: TextStyle(color: Colors.blue)),
                      ),
                    ],
                  ),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      _buildGalleryImage('assets/room1.jpg'),
                      _buildGalleryImage('assets/room2.jpg'),
                      _buildGalleryImage('assets/room3.jpg'),
                    ],
                  ),
                  const SizedBox(height: 20),
                  _buildBookingForm(context),
                  const SizedBox(height: 20),
                  Center(
                    child: ElevatedButton(
                      onPressed: () {
                        Navigator.pushNamed(
                          context,
                          '/payment',
                          arguments: {
                            'hotelName': _hotelNameController.text,
                            'price': widget.price,
                            'destination': _destinationController.text.isNotEmpty
                                ? _destinationController.text
                                : 'Unknown',
                            'date': _selectedDate != null
                                ? '${_selectedDate!.day}/${_selectedDate!.month}/${_selectedDate!.year}'
                                : 'Not selected',
                            'beds': _bedsController.text.isNotEmpty
                                ? _bedsController.text
                                : '1',
                            'people': _peopleController.text.isNotEmpty
                                ? _peopleController.text
                                : '1',
                          },
                        );
                      },
                      style: ElevatedButton.styleFrom(
                        backgroundColor: Colors.blue,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(8),
                        ),
                        padding: EdgeInsets.symmetric(horizontal: 40, vertical: 15),
                      ),
                      child: const Text('Confirm Booking'),
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildAmenityIcon(IconData icon, String label) {
    return Column(
      children: [
        Icon(icon, color: Colors.blue, size: 24),
        const SizedBox(height: 4),
        Text(label, style: TextStyle(fontSize: 12, color: Colors.grey[600])),
      ],
    );
  }

  Widget _buildGalleryImage(String imageUrl) {
    return Padding(
      padding: const EdgeInsets.only(right: 8),
      child: ClipRRect(
        borderRadius: BorderRadius.circular(8),
        child: Image.asset(
          imageUrl,
          height: 80,
          width: 80,
          fit: BoxFit.cover,
          errorBuilder: (context, error, stackTrace) {
            return Container(
              height: 80,
              width: 80,
              color: Colors.grey[300],
              child: const Icon(Icons.broken_image, size: 40),
            );
          },
        ),
      ),
    );
  }

  Widget _buildBookingForm(BuildContext context) {
    return Card(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      elevation: 4,
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Destination',
              style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 8),
            TextField(
              controller: _destinationController,
              decoration: InputDecoration(
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(8),
                ),
                hintText: 'Enter destination',
                prefixIcon: Icon(Icons.location_on),
              ),
            ),
            const SizedBox(height: 16),
            Text(
              'Hotel Name',
              style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 8),
            TextField(
              controller: _hotelNameController,
              decoration: InputDecoration(
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(8),
                ),
                hintText: 'Enter hotel name',
                prefixIcon: Icon(Icons.hotel),
              ),
            ),
            const SizedBox(height: 16),
            Text(
              'Details',
              style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 8),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Expanded(
                  child: TextField(
                    controller: TextEditingController(
                      text: _selectedDate != null
                          ? '${_selectedDate!.day}/${_selectedDate!.month}/${_selectedDate!.year}'
                          : '',
                    ),
                    onTap: () => _selectDate(context),
                    readOnly: true,
                    decoration: InputDecoration(
                      border: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(8),
                      ),
                      hintText: 'Select date',
                      prefixIcon: Icon(Icons.calendar_today),
                      labelText: 'Date',
                    ),
                  ),
                ),
                const SizedBox(width: 8),
                Expanded(
                  child: TextField(
                    controller: _bedsController,
                    keyboardType: TextInputType.number,
                    decoration: InputDecoration(
                      border: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(8),
                      ),
                      hintText: '2',
                      prefixIcon: Icon(Icons.bed),
                      labelText: 'Beds',
                    ),
                  ),
                ),
                const SizedBox(width: 8),
                Expanded(
                  child: TextField(
                    controller: _peopleController,
                    keyboardType: TextInputType.number,
                    decoration: InputDecoration(
                      border: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(8),
                      ),
                      hintText: '4',
                      prefixIcon: Icon(Icons.people),
                      labelText: 'People',
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
}

class PaymentScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    final args = ModalRoute.of(context)?.settings.arguments as Map<String, dynamic>? ?? {
      'hotelName': 'Unknown Hotel',
      'price': '\$0',
      'destination': 'Unknown',
      'date': 'Not selected',
      'beds': '1',
      'people': '1',
    };

    return Scaffold(
      appBar: AppBar(
        title: Text('Payment for ${args['hotelName']}'),
        backgroundColor: Colors.blue,
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          children: [
            Text('Booking Summary',
                style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold)),
            SizedBox(height: 20),
            _buildBookingDetails(args),
            SizedBox(height: 30),
            Text('Payment Method',
                style: TextStyle(fontSize: 18, fontWeight: FontWeight.w600)),
            SizedBox(height: 10),
            _buildPaymentOption(Icons.credit_card, 'Credit Card'),
            _buildPaymentOption(Icons.paypal, 'PayPal'),
            _buildPaymentOption(Icons.account_balance_wallet, 'Wallet'),
            Spacer(),
            ElevatedButton(
              onPressed: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(
                    builder: (context) => ReceiptScreen(
                      hotelName: args['hotelName'] ?? 'Unknown Hotel',
                      nights: args['beds'] ?? '1',
                      guests: args['people'] ?? '1',
                      total: args['price'] ?? '\$0',
                    ),
                  ),
                );
              },
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.blue,
                minimumSize: Size(double.infinity, 50),
              ),
              child: Text(
                'Pay Now',
                style: TextStyle(fontSize: 18),
              ),
            )
          ],
        ),
      ),
    );
  }

  Widget _buildBookingDetails(Map<String, dynamic> args) {
    return Container(
      padding: EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.grey.shade100,
        borderRadius: BorderRadius.circular(8),
        border: Border.all(color: Colors.grey.shade300),
      ),
      child: Column(
        children: [
          _buildRow('Hotel:', args['hotelName'] ?? 'Unknown'),
          _buildRow('Nights:', args['beds'] ?? '1'),
          _buildRow('Guests:', args['people'] ?? '1'),
          _buildRow('Total:', args['price'] ?? '\$0'),
          _buildRow('Destination:', args['destination'] ?? 'Unknown'),
          _buildRow('Date:', args['date'] ?? 'Not selected'),
        ],
      ),
    );
  }

  Widget _buildRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [Text(label), Text(value)],
      ),
    );
  }

  Widget _buildPaymentOption(IconData icon, String method) {
    return ListTile(
      leading: Icon(icon, color: Colors.blue),
      title: Text(method),
      trailing: Icon(Icons.arrow_forward_ios, size: 16),
      onTap: () {},
    );
  }
}

class ReceiptScreen extends StatelessWidget {
  final String hotelName;
  final String nights;
  final String guests;
  final String total;

  ReceiptScreen({
    super.key,
    required this.hotelName,
    required this.nights,
    required this.guests,
    required this.total,
  });

  final GlobalKey _receiptKey = GlobalKey();

  Future<void> _captureAndSaveReceipt(BuildContext context) async {
    try {
      if (kIsWeb) {
        await _saveReceiptForWeb(context);
        return;
      }

      if (Platform.isAndroid || Platform.isIOS) {
        await _saveReceiptForMobile(context);
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Receipt saving not supported on this platform')),
        );
      }
    } catch (e) {
      print('Error saving receipt: $e');
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Failed to save receipt: ${e.toString()}')),
      );
    }
  }

  Future<void> _saveReceiptForMobile(BuildContext context) async {
    if (Platform.isAndroid) {
      var status = await Permission.storage.status;
      if (!status.isGranted) {
        status = await Permission.storage.request();
        if (!status.isGranted) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text('Storage permission denied')),
          );
          return;
        }
      }
    }

    RenderRepaintBoundary boundary = _receiptKey.currentContext!.findRenderObject() as RenderRepaintBoundary;
    ui.Image image = await boundary.toImage(pixelRatio: 3.0);
    ByteData? byteData = await image.toByteData(format: ui.ImageByteFormat.png);
    Uint8List pngBytes = byteData!.buffer.asUint8List();

    final directory = await getApplicationDocumentsDirectory();
    final String filePath = '${directory.path}/receipt_${DateTime.now().millisecondsSinceEpoch}.png';
    await File(filePath).writeAsBytes(pngBytes);

    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text('Receipt saved to $filePath'),
        duration: Duration(seconds: 3),
      ),
    );
  }

  Future<void> _saveReceiptForWeb(BuildContext context) async {
    try {
      RenderRepaintBoundary boundary = _receiptKey.currentContext!.findRenderObject() as RenderRepaintBoundary;
      ui.Image image = await boundary.toImage(pixelRatio: 3.0);
      ByteData? byteData = await image.toByteData(format: ui.ImageByteFormat.png);
      Uint8List pngBytes = byteData!.buffer.asUint8List();

      final blob = html.Blob([pngBytes], 'image/png');
      final url = html.Url.createObjectUrlFromBlob(blob);
      final anchor = html.AnchorElement(href: url)
        ..setAttribute('download', 'receipt_${DateTime.now().millisecondsSinceEpoch}.png')
        ..click();
      html.Url.revokeObjectUrl(url);

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Receipt download started')),
      );
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Failed to save receipt: ${e.toString()}')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.grey.shade100,
      appBar: AppBar(
        title: Text('Receipt'),
        backgroundColor: Colors.blue,
        centerTitle: true,
      ),
      body: Padding(
        padding: const EdgeInsets.all(20.0),
        child: Column(
          children: [
            Expanded(
              child: SingleChildScrollView(
                child: RepaintBoundary(
                  key: _receiptKey,
                  child: Card(
                    elevation: 4,
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(16),
                    ),
                    child: Padding(
                      padding: const EdgeInsets.all(24.0),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.stretch,
                        children: [
                          Icon(Icons.check_circle, color: Colors.green, size: 80),
                          SizedBox(height: 10),
                          Text(
                            'Payment Successful!',
                            textAlign: TextAlign.center,
                            style: TextStyle(
                              fontSize: 22,
                              fontWeight: FontWeight.bold,
                              color: Colors.green.shade700,
                            ),
                          ),
                          SizedBox(height: 30),
                          _buildReceiptItem(Icons.hotel, 'Hotel', hotelName),
                          _buildReceiptItem(Icons.nights_stay, 'Nights', nights),
                          _buildReceiptItem(Icons.group, 'Guests', guests),
                          _buildReceiptItem(Icons.attach_money, 'Total', total),
                          SizedBox(height: 30),
                          Text(
                            'Thank you for booking with us!',
                            textAlign: TextAlign.center,
                            style: TextStyle(
                              fontSize: 16,
                              color: Colors.grey.shade600,
                            ),
                          ),
                        ],
                      ),
                    ),
                  ),
                ),
              ),
            ),
            SizedBox(height: 20),
            ElevatedButton.icon(
              onPressed: () => _captureAndSaveReceipt(context),
              icon: Icon(Icons.download),
              label: Text('Download Receipt'),
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.blue,
                padding: EdgeInsets.symmetric(vertical: 14),
                minimumSize: Size(double.infinity, 50),
                textStyle: TextStyle(fontSize: 18),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(12),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildReceiptItem(IconData icon, String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 10.0),
      child: Row(
        children: [
          Icon(icon, color: Colors.blue),
          SizedBox(width: 12),
          Text(
            '$label:',
            style: TextStyle(
              fontSize: 16,
              fontWeight: FontWeight.w600,
            ),
          ),
          Spacer(),
          Text(
            value,
            style: TextStyle(fontSize: 16),
          ),
        ],
      ),
    );
  }
}