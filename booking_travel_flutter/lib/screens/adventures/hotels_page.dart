import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:cached_network_image/cached_network_image.dart';

class HotelsPage extends StatefulWidget {
  final int adventureId;

  const HotelsPage({Key? key, required this.adventureId}) : super(key: key);

  @override
  State<HotelsPage> createState() => _HotelsPageState();
}

class _HotelsPageState extends State<HotelsPage> {
  List<Map<String, dynamic>> hotels = [];
  bool isLoading = true;
  String? errorMessage;

  final String baseUrl = 'http://127.0.0.1:8000/api'; 

  @override
  void initState() {
    super.initState();
    fetchHotels();
  }

  Future<void> fetchHotels() async {
    try {
      final response = await http.get(
          Uri.parse('$baseUrl/adventures/${widget.adventureId}/hotels'));
      if (response.statusCode == 200) {
        final List<dynamic> data = jsonDecode(response.body);
        setState(() {
          hotels = data
              .map((item) => {
                    'id': item['id'],
                    'name': item['name'],
                    'image': item['image'],
                    'price': item['price'],
                    'day': item['day'],
                    'description': item['description'],
                    'created_at': item['created_at'],
                  })
              .toList();
          isLoading = false;
        });
      } else {
        setState(() {
          errorMessage =
              'Failed to load hotels (Status: ${response.statusCode})';
          isLoading = false;
        });
      }
    } catch (e) {
      setState(() {
        errorMessage = 'Error fetching hotels: $e';
        isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Hotels'),
      ),
      body: isLoading
          ? const Center(child: CircularProgressIndicator())
          : errorMessage != null
              ? Center(child: Text(errorMessage!))
              : hotels.isEmpty
                  ? const Center(
                      child: Text('No hotels available for this adventure.'))
                  : ListView.builder(
                      padding: const EdgeInsets.all(16.0),
                      itemCount: hotels.length,
                      itemBuilder: (context, index) {
                        final hotel = hotels[index];
                        return Card(
                          margin: const EdgeInsets.symmetric(vertical: 8.0),
                          child: ListTile(
                            leading: hotel['image'] != null &&
                                    hotel['image'].isNotEmpty
                                ? CachedNetworkImage(
                                    imageUrl: hotel['image'],
                                    width: 60,
                                    height: 60,
                                    fit: BoxFit.cover,
                                    placeholder: (context, url) =>
                                        const CircularProgressIndicator(),
                                    errorWidget: (context, url, error) =>
                                        const Icon(Icons.error),
                                  )
                                : const Icon(Icons.hotel),
                            title: Text(hotel['name']),
                            subtitle: Text(
                                '${hotel['price']} USD / ${hotel['day']} days\n${hotel['description']}'),
                            trailing: Text('Added: ${hotel['created_at']}'),
                          ),
                        );
                      },
                    ),
    );
  }
}
