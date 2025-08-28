import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import '../adventures/adventures_page.dart';

class TripsPage extends StatefulWidget {
  const TripsPage({Key? key}) : super(key: key);

  @override
  State<TripsPage> createState() => _TripsPageState();
}

class _TripsPageState extends State<TripsPage> {
  List<Map<String, dynamic>> provinces = [];
  List<Map<String, dynamic>> filteredProvinces = [];
  bool isLoading = true;
  String? errorMessage;

  @override
  void initState() {
    super.initState();
    fetchProvinces();
  }

  // Default image to use when loading fails
  final String defaultImage = 'assets/images/default_province.jpg';

  // Base URL for backend
  final String apiBaseUrl = 'http://localhost:8000';

  String _getImageUrl(String? imagePath) {
    if (imagePath == null || imagePath.isEmpty) {
      debugPrint('No image path provided, using default image');
      return 'assets/images/default_province.jpg';
    }
    
    // If it's already a full URL, return it as is
    if (imagePath.startsWith('http')) {
      debugPrint('Using full URL: $imagePath');
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
    
    // For web, we need to use the full URL with the correct port
    String url = '$apiBaseUrl/storage/$cleanPath';
    debugPrint('Constructed image URL: $url');
    
    return url;
  }

  Future<void> fetchProvinces() async {
    try {
      final response = await http.get(
        Uri.parse('$apiBaseUrl/api/provinces'),
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
      );
      
      if (response.statusCode == 200) {
        if (response.headers['content-type']?.contains('application/json') ?? false) {
          final Map<String, dynamic> jsonResponse = jsonDecode(response.body);
          final List<dynamic> data = jsonResponse['data'] ?? [];
          
          setState(() {
            provinces = data.map<Map<String, dynamic>>((item) {
              // Get the image URL using the helper method
              String imageUrl = _getImageUrl(item['image']?.toString());
              debugPrint('Province Image URL: $imageUrl');
              
              return {
                'id': item['id'],
                'name': item['name'] ?? 'Unknown Province',
                'image': imageUrl,
                'page': AdventuresPage(
                  provinceId: item['id'],
                  provinceName: item['name'] ?? 'Unknown',
                ),
              };
            }).toList();
            
            filteredProvinces = List.from(provinces);
            isLoading = false;
          });
        } else {
          throw Exception('Response is not JSON');
        }
      } else {
        throw Exception('Failed to load provinces');
      }
    } catch (e) {
      setState(() {
        errorMessage = 'Error fetching data: $e';
        isLoading = false;
      });
    }
  }

  void filterProvinces(String query) {
    setState(() {
      filteredProvinces = provinces
          .where((province) => province['name']
              .toString()
              .toLowerCase()
              .contains(query.toLowerCase()))
          .toList();
    });
  }

  Widget _buildProvinceImage(String imagePath) {
    debugPrint('Building image with path: $imagePath');
    
    // If no image path is provided, return default image
    if (imagePath.isEmpty || imagePath == defaultImage) {
      debugPrint('Using default image');
      return _buildDefaultImage();
    }

    // Handle network images
    if (imagePath.startsWith('http')) {
      debugPrint('Loading network image: $imagePath');
      
      // For web, we need to ensure CORS is properly configured
      Map<String, String> headers = {
        'Accept': 'image/*',
        'Access-Control-Allow-Origin': '*',
      };
      
      return Image.network(
        imagePath,
        width: 60,
        height: 60,
        fit: BoxFit.cover,
        headers: headers,
        errorBuilder: (context, error, stackTrace) {
          debugPrint('Error loading image: $imagePath\nError: $error\nStack: $stackTrace');
          
          // If the error is due to CORS, try a different approach
          if (error is NetworkImageLoadException && error.statusCode == 0) {
            debugPrint('Possible CORS issue. Trying with cache buster...');
            String cacheBuster = '?t=${DateTime.now().millisecondsSinceEpoch}';
            return Image.network(
              '$imagePath$cacheBuster',
              width: 60,
              height: 60,
              fit: BoxFit.cover,
              headers: headers,
              errorBuilder: (context, error, stackTrace) {
                debugPrint('Still failed to load image: $imagePath');
                return _buildDefaultImage();
              },
            );
          }
          
          return _buildDefaultImage();
        },
        loadingBuilder: (context, child, loadingProgress) {
          if (loadingProgress == null) {
            debugPrint('Image loaded successfully: $imagePath');
            return child;
          }
          debugPrint('Loading image: $imagePath - ${loadingProgress.cumulativeBytesLoaded} / ${loadingProgress.expectedTotalBytes}');
          return Center(
            child: CircularProgressIndicator(
              value: loadingProgress.expectedTotalBytes != null
                  ? loadingProgress.cumulativeBytesLoaded / loadingProgress.expectedTotalBytes!
                  : null,
            ),
          );
        },
      );
    }
    // Check if it's an asset image
    else if (imagePath.startsWith('assets/')) {
      return Image.asset(
        imagePath,
        width: 60,
        height: 60,
        fit: BoxFit.cover,
        errorBuilder: (context, error, stackTrace) => _buildDefaultImage(),
      );
    }
    // Default case
    else {
      return _buildDefaultImage();
    }
  }

  Widget _buildDefaultImage() {
    return Container(
      width: 60,
      height: 60,
      decoration: BoxDecoration(
        color: Colors.grey[300],
        shape: BoxShape.circle,
      ),
      child: const Icon(Icons.landscape, color: Colors.grey),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Provinces'),
      ),
      body: isLoading
          ? const Center(child: CircularProgressIndicator())
          : errorMessage != null
              ? Center(child: Text(errorMessage!))
              : Padding(
                  padding: const EdgeInsets.all(16.0),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text(
                        'Search Provinces',
                        style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                      ),
                      const SizedBox(height: 10),
                      TextField(
                        onChanged: filterProvinces,
                        decoration: const InputDecoration(
                          border: OutlineInputBorder(),
                          hintText: 'Enter province name',
                          suffixIcon: Icon(Icons.search),
                        ),
                      ),
                      const SizedBox(height: 20),
                      const Text(
                        'Popular Destinations',
                        style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                      ),
                      const SizedBox(height: 10),
                      SizedBox(
                        height: 100,
                        child: ListView.builder(
                          scrollDirection: Axis.horizontal,
                          itemCount: provinces.length,
                          itemBuilder: (context, index) {
                            return Padding(
                              padding: const EdgeInsets.symmetric(horizontal: 8.0),
                              child: GestureDetector(
                                onTap: () {
                                  Navigator.push(
                                    context,
                                    MaterialPageRoute(
                                      builder: (_) => provinces[index]['page'] as Widget,
                                    ),
                                  );
                                },
                                child: Column(
                                  children: [
                                    CircleAvatar(
                                      backgroundColor: Colors.grey[200],
                                      radius: 30,
                                      child: ClipOval(
                                        child: _buildProvinceImage(provinces[index]['image']),
                                      ),
                                    ),
                                    const SizedBox(height: 8),
                                    Text(
                                      (provinces[index]['name'] as String).split(' ').first,
                                      style: const TextStyle(fontWeight: FontWeight.bold),
                                    ),
                                  ],
                                ),
                              ),
                            );
                          },
                        ),
                      ),
                      const SizedBox(height: 20),
                      const Text(
                        'All Provinces',
                        style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                      ),
                      const SizedBox(height: 10),
                      Expanded(
                        child: GridView.builder(
                          gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                            crossAxisCount: 3,
                            crossAxisSpacing: 10,
                            mainAxisSpacing: 10,
                            childAspectRatio: 1,
                          ),
                          itemCount: filteredProvinces.length,
                          itemBuilder: (context, index) {
                            return GestureDetector(
                              onTap: () {
                                Navigator.push(
                                  context,
                                  MaterialPageRoute(
                                    builder: (_) => filteredProvinces[index]['page'] as Widget,
                                  ),
                                );
                              },
                              child: Column(
                                children: [
                                  CircleAvatar(
                                    backgroundColor: Colors.grey[200],
                                    radius: 30,
                                    child: ClipOval(
                                      child: _buildProvinceImage(filteredProvinces[index]['image']),
                                    ),
                                  ),
                                  const SizedBox(height: 8),
                                  Text(
                                    (filteredProvinces[index]['name'] as String).split(' ').first,
                                    textAlign: TextAlign.center,
                                    style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12),
                                    maxLines: 2,
                                    overflow: TextOverflow.ellipsis,
                                  ),
                                ],
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