import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

class AdventuresPage extends StatefulWidget {
  final int provinceId;
  final String provinceName;

  AdventuresPage({required this.provinceId, required this.provinceName});

  @override
  _AdventuresPageState createState() => _AdventuresPageState();
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
    final url =
        'http://127.0.0.1:8000/api/provinces/${widget.provinceId}/adventures';
    final response = await http.get(Uri.parse(url));

    if (response.statusCode == 200) {
      final Map<String, dynamic> body = json.decode(response.body);
      setState(() {
        adventures = body['data'];
        isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('${widget.provinceName} Adventures')),
      body: isLoading
          ? Center(child: CircularProgressIndicator())
          : adventures.isEmpty
              ? Center(child: Text("No adventures found"))
              : ListView.builder(
                  itemCount: adventures.length,
                  itemBuilder: (context, index) {
                    final adventure = adventures[index];
                    return Card(
                      margin: EdgeInsets.all(8),
                      child: ListTile(
                        leading: adventure['image_url'] != null &&
                                adventure['image_url'].toString().isNotEmpty
                            ? Image.network(adventure['image_url'],
                                width: 60, height: 60, fit: BoxFit.cover)
                            : Icon(Icons.landscape),
                        title: Text(adventure['name']),
                        subtitle: Text(adventure['description'] ?? ''),
                      ),
                    );
                  },
                ),
    );
  }
}
