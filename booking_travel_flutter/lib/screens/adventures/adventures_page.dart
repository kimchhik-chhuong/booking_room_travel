import 'package:flutter/material.dart';
import '../services/user_service.dart' as user_service;
import '../hotels/hotels_by_adventure_page.dart'; // New screen for hotels by adventure

class Adventure {
  final int id;
  final String name;
  final String description;
  final String imageUrl;

  Adventure({required this.id, required this.name, required this.description, required this.imageUrl});

  factory Adventure.fromJson(Map<String, dynamic> json) {
    return Adventure(
      id: json['id'],
      name: json['name'],
      description: json['description'] ?? '',
      imageUrl: json['image_url'] ?? '',
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
    _adventuresFuture = user_service.UserService.fetchAdventuresByProvince(widget.provinceId);
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
                  leading: adventure.imageUrl.isNotEmpty
                      ? Image.network(
                          adventure.imageUrl,
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
