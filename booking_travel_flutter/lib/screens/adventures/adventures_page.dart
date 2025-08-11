import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

class Adventure {
  final int id;
  final String name;
  final String description;
  final String image;

  Adventure({
    required this.id,
    required this.name,
    required this.description,
    required this.image,
  });

  factory Adventure.fromJson(Map<String, dynamic> json) {
    return Adventure(
      id: json['id'],
      name: json['name'] ?? 'Unnamed Adventure',
      description: json['description'] ?? '',
      image: json['image_url'] ?? json['image'] ?? '',
    );
  }
}

class AdventuresPage extends StatefulWidget {
  final int provinceId;
  final String provinceName;
  final Function(Map<String, dynamic>) onAdventureTap;

  const AdventuresPage({
    Key? key,
    required this.provinceId,
    required this.provinceName,
    required this.onAdventureTap,
  }) : super(key: key);

  @override
  _AdventuresPageState createState() => _AdventuresPageState();
}

class _AdventuresPageState extends State<AdventuresPage> {
  late Future<List<Adventure>> _adventuresFuture;

  @override
  void initState() {
    super.initState();
    _adventuresFuture = fetchAdventures();
  }

  Future<List<Adventure>> fetchAdventures() async {
    try {
      final response = await http.get(
        Uri.parse(
            'http://localhost:8000/api/provinces/${widget.provinceId}/adventures'),
        headers: {'Content-Type': 'application/json'},
      );

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        final adventuresData = data['data'] as List;
        return adventuresData.map((json) => Adventure.fromJson(json)).toList();
      } else if (response.statusCode == 404) {
        return []; // Return empty list if province not found
      } else {
        throw Exception('Failed to load adventures: ${response.statusCode}');
      }
    } catch (e) {
      debugPrint('Error fetching adventures: $e');
      throw Exception('Failed to load adventures: $e');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('${widget.provinceName} Adventures'),
        backgroundColor: Colors.orange,
        elevation: 0,
      ),
      body: FutureBuilder<List<Adventure>>(
        future: _adventuresFuture,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          } else if (snapshot.hasError) {
            return Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  const Icon(Icons.error_outline,
                      size: 60, color: Colors.orange),
                  const SizedBox(height: 16),
                  Text(
                    'Error: ${snapshot.error}',
                    style: const TextStyle(fontSize: 16),
                    textAlign: TextAlign.center,
                  ),
                  const SizedBox(height: 16),
                  ElevatedButton(
                    onPressed: () {
                      setState(() {
                        _adventuresFuture = fetchAdventures();
                      });
                    },
                    child: const Text('Retry'),
                  ),
                ],
              ),
            );
          } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
            return Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  const Icon(Icons.explore_outlined,
                      size: 60, color: Colors.grey),
                  const SizedBox(height: 16),
                  const Text(
                    'No adventures available',
                    style: TextStyle(fontSize: 18, color: Colors.grey),
                  ),
                  const SizedBox(height: 8),
                  Text(
                    'Check back later for new adventures in ${widget.provinceName}',
                    style: const TextStyle(color: Colors.grey),
                    textAlign: TextAlign.center,
                  ),
                ],
              ),
            );
          }

          final adventures = snapshot.data!;
          return RefreshIndicator(
            onRefresh: () async {
              setState(() {
                _adventuresFuture = fetchAdventures();
              });
            },
            child: ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: adventures.length,
              itemBuilder: (context, index) {
                final adventure = adventures[index];
                return AdventureCard(
                  adventure: adventure,
                  onTap: () => widget.onAdventureTap({
                    'id': adventure.id,
                    'name': adventure.name,
                    'description': adventure.description,
                  }),
                );
              },
            ),
          );
        },
      ),
    );
  }
}

class AdventureCard extends StatelessWidget {
  final Adventure adventure;
  final VoidCallback onTap;

  const AdventureCard({
    Key? key,
    required this.adventure,
    required this.onTap,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.only(bottom: 16),
      elevation: 2,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(12),
      ),
      child: InkWell(
        borderRadius: BorderRadius.circular(12),
        onTap: onTap,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            if (adventure.image.isNotEmpty)
              ClipRRect(
                borderRadius:
                    const BorderRadius.vertical(top: Radius.circular(12)),
                child: AspectRatio(
                  aspectRatio: 16 / 9,
                  child: Image.network(
                    adventure.image,
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
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Icon(Icons.broken_image,
                              size: 50, color: Colors.grey[600]),
                          const SizedBox(height: 8),
                          Text(
                            'Image not available',
                            style: TextStyle(color: Colors.grey[600]),
                          ),
                        ],
                      ),
                    ),
                  ),
                ),
              )
            else
              Container(
                height: 180,
                decoration: BoxDecoration(
                  color: Colors.orange[100],
                  borderRadius:
                      const BorderRadius.vertical(top: Radius.circular(12)),
                ),
                child: Center(
                  child: Icon(
                    Icons.explore_outlined,
                    size: 60,
                    color: Colors.orange[400],
                  ),
                ),
              ),
            Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    adventure.name,
                    style: const TextStyle(
                      fontSize: 18,
                      fontWeight: FontWeight.bold,
                    ),
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                  ),
                  const SizedBox(height: 8),
                  Text(
                    adventure.description,
                    style: TextStyle(
                      fontSize: 14,
                      color: Colors.grey[600],
                    ),
                    maxLines: 3,
                    overflow: TextOverflow.ellipsis,
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}
