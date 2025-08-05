import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

import '../adventures/adventures_page.dart';

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
}

class ProvincesPage extends StatefulWidget {
  const ProvincesPage({Key? key}) : super(key: key);

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
    final response = await http.get(Uri.parse('http://localhost:8000/api/provinces'));

    if (response.statusCode == 200) {
      final List<dynamic> jsonList = json.decode(response.body)['data'];
      return jsonList.map((json) => Province.fromJson(json)).toList();
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
        centerTitle: true,
      ),
      body: FutureBuilder<List<Province>>(
        future: _provincesFuture,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          } else if (snapshot.hasError) {
            return Center(child: Text('Failed to load provinces: ${snapshot.error}'));
          } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
            return const Center(child: Text('No provinces available.'));
          } else {
            final provinces = snapshot.data!;
            return ListView.builder(
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
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (_) => AdventuresPage(
                          provinceId: province.id,
                          provinceName: province.name,
                        ),
                      ),
                    );
                  },
                );
              },
            );
          }
        },
      ),
    );
  }
}
