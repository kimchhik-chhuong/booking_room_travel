import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:cached_network_image/cached_network_image.dart';

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

  Future<void> fetchProvinces() async {
    try {
      final response =
          await http.get(Uri.parse('http://localhost:8000/api/provinces'));
      if (response.statusCode == 200) {
        if (response.headers['content-type']?.contains('application/json') ??
            false) {
          final List<dynamic> data = jsonDecode(response.body);
          setState(() {
            provinces = data.map((item) {
              final assetName =
                  item['name'].toLowerCase().replaceAll(' ', '') + '.jpg';
              final assetPath = 'lib/assets/images/$assetName';
              return {
                'id': item['id'],
                'name': item['name'],
                'image': assetPath,
                'page': AdventuresPage(
                    provinceId: item['id'], provinceName: item['name']),
              };
            }).toList();
            filteredProvinces = provinces;
            isLoading = false;
          });
        } else {
          throw Exception('Response is not JSON');
        }
      } else {
        setState(() {
          errorMessage =
              'Failed to load provinces (Status: ${response.statusCode})';
          isLoading = false;
        });
      }
    } catch (e) {
      setState(() {
        errorMessage = 'Error fetching data: $e';
        isLoading = false;
      });
    }
  }

  void filterProvinces(String query) {
    final results = provinces.where((province) {
      return province['name'].toLowerCase().contains(query.toLowerCase());
    }).toList();
    setState(() {
      filteredProvinces = results;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Provinces'),
        // Ensure no Positioned inside AppBar's children
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
                        style: TextStyle(
                            fontSize: 18, fontWeight: FontWeight.bold),
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
                        style: TextStyle(
                            fontSize: 18, fontWeight: FontWeight.bold),
                      ),
                      const SizedBox(height: 10),
                      SizedBox(
                        height: 100,
                        child: ListView.builder(
                          scrollDirection: Axis.horizontal,
                          itemCount: provinces.length,
                          itemBuilder: (context, index) {
                            return Padding(
                              padding:
                                  const EdgeInsets.symmetric(horizontal: 8.0),
                              child: GestureDetector(
                                onTap: () {
                                  Navigator.push(
                                    context,
                                    MaterialPageRoute(
                                      builder: (_) =>
                                          provinces[index]['page'] as Widget,
                                    ),
                                  );
                                },
                                child: Column(
                                  children: [
                                    CircleAvatar(
                                      backgroundColor: Colors.purple,
                                      radius: 30,
                                      child: ClipOval(
                                        child: CachedNetworkImage(
                                          imageUrl: provinces[index]['image'],
                                          width: 60,
                                          height: 60,
                                          fit: BoxFit.cover,
                                          placeholder: (context, url) =>
                                              const CircularProgressIndicator(),
                                          errorWidget: (context, url, error) =>
                                              const Icon(Icons.error),
                                        ),
                                      ),
                                    ),
                                    const SizedBox(height: 8),
                                    Text(
                                      (provinces[index]['name'] as String)
                                          .split(' ')
                                          .first,
                                      style: const TextStyle(
                                          fontWeight: FontWeight.bold),
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
                        style: TextStyle(
                            fontSize: 18, fontWeight: FontWeight.bold),
                      ),
                      const SizedBox(height: 10),
                      Expanded(
                        child: GridView.builder(
                          gridDelegate:
                              const SliverGridDelegateWithFixedCrossAxisCount(
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
                                    builder: (_) => filteredProvinces[index]
                                        ['page'] as Widget,
                                  ),
                                );
                              },
                              child: Column(
                                children: [
                                  CircleAvatar(
                                    backgroundColor: Colors.purple,
                                    radius: 30,
                                    child: ClipOval(
                                      child: CachedNetworkImage(
                                        imageUrl: filteredProvinces[index]
                                            ['image'],
                                        width: 60,
                                        height: 60,
                                        fit: BoxFit.cover,
                                        placeholder: (context, url) =>
                                            const CircularProgressIndicator(),
                                        errorWidget: (context, url, error) =>
                                            const Icon(Icons.error),
                                      ),
                                    ),
                                  ),
                                  const SizedBox(height: 8),
                                  Text(
                                    (filteredProvinces[index]['name'] as String)
                                        .split(' ')
                                        .first,
                                    style: const TextStyle(
                                        fontWeight: FontWeight.bold),
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
