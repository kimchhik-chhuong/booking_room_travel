import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:booking_travel_flutter/screens/adventures/adventures_page.dart';
import 'package:booking_travel_flutter/screens/hotel/hotel_list_page.dart';

class Province {
  final int id;
  final String name;
  final String description;
  final String imageUrl;

  Province({
    required this.id,
    required this.name,
    required this.description,
    required this.imageUrl,
  });

  factory Province.fromJson(Map<String, dynamic> json) {
    return Province(
      id: json['id'],
      name: json['name'],
      description: json['description'] ?? '',
      imageUrl: json['image_url'] ?? '',
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'description': description,
      'image_url': imageUrl,
    };
  }
}

class ProvincesPage extends StatefulWidget {
  final Function(Map<String, dynamic>) onProvinceTap;

  const ProvincesPage({
    Key? key,
    required this.onProvinceTap,
  }) : super(key: key);

  @override
  _ProvincesPageState createState() => _ProvincesPageState();
}

class _ProvincesPageState extends State<ProvincesPage> {
  late Future<List<Province>> _provincesFuture;

  @override
  void initState() {
    super.initState();
    _provincesFuture = fetchProvinces();
  }

  Future<List<Province>> fetchProvinces() async {
    final response = await http.get(
      Uri.parse('http://localhost:8000/api/provinces'),
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      return List<Province>.from(
          data['data'].map((json) => Province.fromJson(json)));
    } else {
      throw Exception('Failed to load provinces');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Provinces'),
        backgroundColor: Colors.deepPurple,
        foregroundColor: Colors.white,
      ),
      body: FutureBuilder<List<Province>>(
        future: _provincesFuture,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          } else if (snapshot.hasError) {
            return Center(child: Text('Error: ${snapshot.error}'));
          } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
            return const Center(child: Text('No provinces available'));
          }

          final provinces = snapshot.data!;
          return ListView.builder(
            padding: const EdgeInsets.all(16),
            itemCount: provinces.length,
            itemBuilder: (context, index) {
              final province = provinces[index];
              return ListTile(
                leading: province.imageUrl.isNotEmpty
                    ? Image.network(
                        province.imageUrl,
                        width: 60,
                        height: 60,
                        fit: BoxFit.cover,
                        errorBuilder: (context, error, stackTrace) => Container(
                          width: 60,
                          height: 60,
                          color: Colors.grey[300],
                          child: const Icon(Icons.image_not_supported),
                        ),
                      )
                    : Container(
                        width: 60,
                        height: 60,
                        color: Colors.grey[300],
                        child: const Icon(Icons.image_not_supported),
                      ),
                title: Text(province.name),
                subtitle: Text(province.description),
                trailing: const Icon(Icons.arrow_forward),
                onTap: () {
                  // Navigate to adventures page for this province
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (context) => AdventuresPage(
                        provinceId: province.id,
                        provinceName: province.name,
                        onAdventureTap: (adventure) {
                          // When adventure is tapped, navigate to hotel list
                          Navigator.push(
                            context,
                            MaterialPageRoute(
                              builder: (context) => HotelListPage(
                                provinceId: province.id,
                                provinceName: province.name,
                                adventureId: adventure['id'],
                                adventureName: adventure['name'],
                              ),
                            ),
                          );
                        },
                      ),
                    ),
                  );
                },
              );
            },
          );
        },
      ),
    );
  }
}
