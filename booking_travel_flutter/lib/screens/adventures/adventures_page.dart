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
      return 'http://10.0.2.2:8000/images/default-adventure.jpg';
    }
    
    // If it's already a full URL, return it as is
    if (imagePath.startsWith('http')) {
      return imagePath;
    }
    
    // Handle different path formats
    String cleanPath = imagePath.replaceAll('\\', '/');
    
    // Remove any storage/ prefix if it exists
    if (cleanPath.startsWith('storage/')) {
      cleanPath = cleanPath.substring('storage/'.length);
    }
    
    // Remove any leading slashes
    while (cleanPath.startsWith('/')) {
      cleanPath = cleanPath.substring(1);
    }
    
    // Construct the full URL - use the same domain as your API
    String baseUrl = 'http://10.0.2.2:8000';
    String fullUrl = '$baseUrl/storage/$cleanPath';
    
    // For debugging
    debugPrint('Image URL Debug:');
    debugPrint('  Original path: $imagePath');
    debugPrint('  Cleaned path: $cleanPath');
    debugPrint('  Full URL: $fullUrl');
    
    return fullUrl;
  }

  void _handleAdventureTap(Map<String, dynamic> adventure) {
    print('Adventure tapped: ${adventure['name']} (ID: ${adventure['id']})');
    print('Province ID: ${widget.provinceId}, Province Name: ${widget.provinceName}');
    print('Adventure ID: ${adventure['id']}, Adventure Name: ${adventure['name']}');
    
    if (widget.onAdventureTap != null) {
      print('Using onAdventureTap callback');
      widget.onAdventureTap!(adventure);
    } else {
      print('Navigating to HotelListPage...');
      try {
        Navigator.push(
          context,
          MaterialPageRoute(
            builder: (context) {
              print('Building HotelListPage with:');
              print('  - provinceId: ${widget.provinceId}');
              print('  - provinceName: ${widget.provinceName}');
              print('  - adventureName: ${adventure['name']}');
              print('  - adventureId: ${adventure['id']}');
              
              return HotelListPage(
                provinceId: widget.provinceId,
                provinceName: widget.provinceName,
                adventureName: adventure['name'],
                adventureId: adventure['id'],
              );
            },
          ),
        );
        print('Navigation initiated successfully');
      } catch (e) {
        print('Navigation error: $e');
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.grey[50],
      appBar: AppBar(
        title: Text(
          '${widget.provinceName} Adventures',
          style: const TextStyle(
            fontWeight: FontWeight.bold,
            color: Colors.white,
          ),
        ),
        backgroundColor: Colors.deepOrange,
        elevation: 0,
        iconTheme: const IconThemeData(color: Colors.white),
      ),
      body: isLoading
          ? const Center(
              child: CircularProgressIndicator(
                valueColor: AlwaysStoppedAnimation<Color>(Colors.deepOrange),
              ),
            )
          : adventures.isEmpty
              ? Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Icon(
                        Icons.explore_off,
                        size: 80,
                        color: Colors.grey[400],
                      ),
                      const SizedBox(height: 16),
                      Text(
                        'No adventures found',
                        style: TextStyle(
                          fontSize: 18,
                          color: Colors.grey[600],
                          fontWeight: FontWeight.w500,
                        ),
                      ),
                    ],
                  ),
                )
              : ListView.builder(
                  padding: const EdgeInsets.all(16),
                  itemCount: adventures.length,
                  itemBuilder: (context, index) {
                    final adventure = adventures[index];
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
                      child: ClipRRect(
                        borderRadius: BorderRadius.circular(16),
                        child: InkWell(
                          onTap: () => _handleAdventureTap(adventure),
                          child: Container(
                            height: 220,
                            decoration: BoxDecoration(
                              color: Colors.white,
                            ),
                            child: Stack(
                              children: [
                                // Background Image
                                Positioned.fill(
                                  child: Image.network(
                                    _getImageUrl(adventure['image_url']),
                                    fit: BoxFit.cover,
                                    loadingBuilder: (context, child, loadingProgress) {
                                      if (loadingProgress == null) return child;
                                      return Container(
                                        color: Colors.grey[200],
                                        child: Center(
                                          child: CircularProgressIndicator(
                                            value: loadingProgress.expectedTotalBytes != null
                                                ? loadingProgress.cumulativeBytesLoaded /
                                                    loadingProgress.expectedTotalBytes!
                                                : null,
                                            valueColor: const AlwaysStoppedAnimation<Color>(Colors.deepOrange),
                                          ),
                                        ),
                                      );
                                    },
                                    errorBuilder: (context, error, stackTrace) {
                                      print('Image load error: $error');
                                      print('Image URL that failed: ${_getImageUrl(adventure['image_url'])}');
                                      return Container(
                                        color: Colors.grey[300],
                                        child: const Center(
                                          child: Icon(
                                            Icons.landscape,
                                            size: 50,
                                            color: Colors.grey,
                                          ),
                                        ),
                                      );
                                    },
                                  ),
                                ),
                                // Gradient Overlay
                                Positioned.fill(
                                  child: Container(
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
                                ),
                                // Content
                                Positioned(
                                  bottom: 0,
                                  left: 0,
                                  right: 0,
                                  child: Padding(
                                    padding: const EdgeInsets.all(20),
                                    child: Column(
                                      crossAxisAlignment: CrossAxisAlignment.start,
                                      mainAxisSize: MainAxisSize.min,
                                      children: [
                                        Text(
                                          adventure['name'] ?? 'Unnamed Adventure',
                                          style: const TextStyle(
                                            fontSize: 20,
                                            fontWeight: FontWeight.bold,
                                            color: Colors.white,
                                          ),
                                        ),
                                        const SizedBox(height: 8),
                                        Text(
                                          adventure['description'] ?? '',
                                          maxLines: 2,
                                          overflow: TextOverflow.ellipsis,
                                          style: const TextStyle(
                                            fontSize: 14,
                                            color: Colors.white70,
                                            height: 1.4,
                                          ),
                                        ),
                                        const SizedBox(height: 12),
                                        Row(
                                          children: [
                                            Container(
                                              padding: const EdgeInsets.symmetric(
                                                horizontal: 12,
                                                vertical: 6,
                                              ),
                                              decoration: BoxDecoration(
                                                color: Colors.deepOrange,
                                                borderRadius: BorderRadius.circular(20),
                                              ),
                                              child: const Text(
                                                'Explore Now',
                                                style: TextStyle(
                                                  color: Colors.white,
                                                  fontSize: 12,
                                                  fontWeight: FontWeight.w600,
                                                ),
                                              ),
                                            ),
                                            const Spacer(),
                                            const Icon(
                                              Icons.arrow_forward_ios,
                                              color: Colors.white,
                                              size: 16,
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
                        ),
                      ),
                    );
                  },
                ),
    );
  }
}
