import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:booking_travel/screens/hotel/hotel_list_page.dart';

class AdventuresPage extends StatefulWidget {
  final int provinceId;
  final String provinceName;
  final Function(Map<String, dynamic>)? onAdventureTap;

  const AdventuresPage({
    super.key,
    required this.provinceId,
    required this.provinceName,
    this.onAdventureTap,
  });

  @override
  State<AdventuresPage> createState() => _AdventuresPageState();
}

class _AdventuresPageState extends State<AdventuresPage> {
  List adventures = [];
  bool isLoading = true;

  @override
  void initState() {
    super.initState();
    fetchAdventures();
  }

  Future<void> fetchAdventures() async {
    try {
      final response = await http.get(
        Uri.parse(
            'http://localhost:8000/api/provinces/${widget.provinceId}/adventures'),
      );

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        setState(() {
          adventures = data['data'] ?? data;
          isLoading = false;
        });
      } else {
        throw Exception('Failed to load adventures');
      }
    } catch (e) {
      setState(() {
        isLoading = false;
      });
      // Handle error appropriately
    }
  }

  String _getImageUrl(String? imagePath) {
    if (imagePath == null || imagePath.isEmpty) {
      return '';
    }
    if (imagePath.startsWith('http')) {
      return imagePath;
    }
    return 'http://localhost:8000$imagePath';
  }

  void _handleAdventureTap(Map<String, dynamic> adventure) {
    if (widget.onAdventureTap != null) {
      widget.onAdventureTap!(adventure);
    } else {
      Navigator.push(
        context,
        MaterialPageRoute(
          builder: (context) => HotelListPage(
            provinceId: widget.provinceId,
            provinceName: widget.provinceName,
            adventureName: adventure['name'],
            adventureId: adventure['id'],
          ),
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('${widget.provinceName} Adventures'),
        backgroundColor: Colors.orange,
      ),
      body: isLoading
          ? const Center(child: CircularProgressIndicator())
          : adventures.isEmpty
              ? const Center(child: Text('No adventures found'))
              : ListView.builder(
                  padding: const EdgeInsets.all(16),
                  itemCount: adventures.length,
                  itemBuilder: (context, index) {
                    final adventure = adventures[index];
                    return Card(
                      margin: const EdgeInsets.only(bottom: 16),
                      child: InkWell(
                        onTap: () => _handleAdventureTap(adventure),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            if (adventure['image'] != null)
                              ClipRRect(
                                borderRadius: const BorderRadius.vertical(
                                    top: Radius.circular(8)),
                                child: Image.network(
                                  _getImageUrl(adventure['image']),
                                  height: 150,
                                  width: double.infinity,
                                  fit: BoxFit.cover,
                                  errorBuilder: (context, error, stackTrace) =>
                                      Container(
                                    height: 150,
                                    color: Colors.grey[300],
                                    child: const Icon(Icons.landscape),
                                  ),
                                ),
                              ),
                            Padding(
                              padding: const EdgeInsets.all(16),
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Text(
                                    adventure['name'] ?? 'Unnamed Adventure',
                                    style: const TextStyle(
                                      fontSize: 18,
                                      fontWeight: FontWeight.bold,
                                    ),
                                  ),
                                  const SizedBox(height: 8),
                                  Text(
                                    adventure['description'] ?? '',
                                    maxLines: 2,
                                    overflow: TextOverflow.ellipsis,
                                  ),
                                  const SizedBox(height: 8),
                                  Text(
                                    'Location: ${adventure['location'] ?? 'N/A'}',
                                    style: const TextStyle(
                                      color: Colors.grey,
                                    ),
                                  ),
                                ],
                              ),
                            ),
                          ],
                        ),
                      ),
                    );
                  },
                ),
    );
  }
}
