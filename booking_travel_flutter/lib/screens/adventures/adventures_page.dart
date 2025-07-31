import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

import '../hotels/hotels_by_adventure_page.dart'; // New screen for hotels by adventure

class Adventure {
  final int id;
  final String name;
  final String description;

  Adventure({required this.id, required this.name, required this.description});

  factory Adventure.fromJson(Map<String, dynamic> json) {
    return Adventure(
      id: json['id'],
      name: json['name'],
      description: json['description'] ?? '',
    );
  }
}

class AdventuresPage extends StatefulWidget {
  final int provinceId;
  final String provinceName;

  const AdventuresPage({Key? key, required this.provinceId, required this.provinceName}) : super(key: key);

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
        final response = await http.get(Uri.parse('http://localhost:8000/api/provinces/${widget.provinceId}/adventures-fake'));

        if (response.statusCode == 200) {
            final List<dynamic> jsonList = json.decode(response.body)['data'];
            return jsonList.map((json) => Adventure.fromJson(json)).toList();
        } else {
            throw Exception('Failed to load adventures');
        }
    }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('${widget.provinceName} Adventures'),
        backgroundColor: Colors.deepPurple,
        foregroundColor: Colors.white,
        centerTitle: true,
      ),
      body: FutureBuilder<List<Adventure>>(
        future: _adventuresFuture,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          } else if (snapshot.hasError) {
            return Center(child: Text('Failed to load adventures: ${snapshot.error}'));
          } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
            return const Center(child: Text('No adventures available.'));
          } else {
            final adventures = snapshot.data!;
            return ListView.builder(
              itemCount: adventures.length,
              itemBuilder: (context, index) {
                final adventure = adventures[index];
                return ListTile(
                  title: Text(adventure.name),
                  subtitle: Text(adventure.description),
                  trailing: const Icon(Icons.arrow_forward),
                  onTap: () {
                    // Navigate to hotels by adventure page
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (_) => HotelsByAdventurePage(
                          adventureId: adventure.id.toString(),
                          adventureName: adventure.name,
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
