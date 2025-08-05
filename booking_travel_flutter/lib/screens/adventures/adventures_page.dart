import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'hotels_page.dart';

class AdventuresPage extends StatefulWidget {
  final int provinceId;
  final String provinceName;

  const AdventuresPage(
      {Key? key, required this.provinceId, required this.provinceName})
      : super(key: key);

  @override
  State<AdventuresPage> createState() => _AdventuresPageState();
}

class _AdventuresPageState extends State<AdventuresPage> {
  List<Map<String, dynamic>> adventures = [];
  bool isLoading = true;
  String? errorMessage;


final String baseUrl = 'http://127.0.0.1:8000/api'; 

  @override
  void initState() {
    super.initState();
    fetchAdventures();
  }

  Future<void> fetchAdventures() async {
    try {
      final response = await http.get(Uri.parse(
          '$baseUrl/provinces/${widget.provinceId}/adventures'));
      if (response.statusCode == 200) {
        final List<dynamic> data = jsonDecode(response.body);
        setState(() {
          adventures = data
              .map((item) => {
                    'id': item['id'],
                    'name': item['name'],
                    'description': item['description'],
                    'created_at': item['created_at'],
                  })
              .toList();
          isLoading = false;
        });
      } else {
        setState(() {
          errorMessage =
              'Failed to load adventures (Status: ${response.statusCode})';
          isLoading = false;
        });
      }
    } catch (e) {
      setState(() {
        errorMessage = 'Error fetching adventures: $e';
        isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Adventures in ${widget.provinceName}'),
      ),
      body: isLoading
          ? const Center(child: CircularProgressIndicator())
          : errorMessage != null
              ? Center(child: Text(errorMessage!))
              : ListView.builder(
                  padding: const EdgeInsets.all(16.0),
                  itemCount: adventures.length,
                  itemBuilder: (context, index) {
                    final adventure = adventures[index];
                    return Card(
                      margin: const EdgeInsets.symmetric(vertical: 8.0),
                      child: ListTile(
                        title: Text(adventure['name']),
                        subtitle: Text(adventure['description'] ?? ''),
                        trailing: Text('Added: ${adventure['created_at']}'),
                        onTap: () {
                          Navigator.push(
                            context,
                            MaterialPageRoute(
                              builder: (context) =>
                                  HotelsPage(adventureId: adventure['id']),
                            ),
                          );
                        },
                      ),
                    );
                  },
                ),
    );
  }
}
