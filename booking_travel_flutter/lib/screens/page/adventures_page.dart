import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import '../hotel/hotel_list_page.dart';

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

  void _navigateToHotels(Map<String, dynamic> adventure) {
    Navigator.push(
      context,
      MaterialPageRoute(
        builder: (context) => HotelListPage(
          provinceId: widget.provinceId,
          provinceName: widget.provinceName,
          adventureName: adventure['name'],
          adventureId: adventure['id'],
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('${widget.provinceName} Adventures'),
        backgroundColor: Colors.orange,
      ),
      body: isLoading
          ? Center(child: CircularProgressIndicator())
          : adventures.isEmpty
              ? Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Icon(Icons.explore_outlined,
                          size: 60, color: Colors.grey),
                      SizedBox(height: 16),
                      Text(
                        "No adventures available",
                        style: TextStyle(fontSize: 18, color: Colors.grey),
                      ),
                      SizedBox(height: 8),
                      Text(
                        "Check back later for new adventures in ${widget.provinceName}",
                        style: TextStyle(color: Colors.grey),
                        textAlign: TextAlign.center,
                      ),
                    ],
                  ),
                )
              : ListView.builder(
                  padding: EdgeInsets.all(16),
                  itemCount: adventures.length,
                  itemBuilder: (context, index) {
                    final adventure = adventures[index];
                    return Card(
                      margin: EdgeInsets.only(bottom: 16),
                      elevation: 2,
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(12),
                      ),
                      child: InkWell(
                        borderRadius: BorderRadius.circular(12),
                        onTap: () => _navigateToHotels(adventure),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            if (adventure['image_url'] != null &&
                                adventure['image_url'].toString().isNotEmpty)
                              ClipRRect(
                                borderRadius: BorderRadius.vertical(
                                    top: Radius.circular(12)),
                                child: AspectRatio(
                                  aspectRatio: 16 / 9,
                                  child: Image.network(
                                    adventure['image_url'],
                                    fit: BoxFit.cover,
                                    loadingBuilder:
                                        (context, child, loadingProgress) {
                                      if (loadingProgress == null) return child;
                                      return Center(
                                        child: CircularProgressIndicator(),
                                      );
                                    },
                                    errorBuilder:
                                        (context, error, stackTrace) =>
                                            Container(
                                      color: Colors.grey[300],
                                      child: Icon(Icons.broken_image,
                                          size: 50, color: Colors.grey[600]),
                                    ),
                                  ),
                                ),
                              )
                            else
                              Container(
                                height: 180,
                                decoration: BoxDecoration(
                                  color: Colors.orange[100],
                                  borderRadius: BorderRadius.vertical(
                                      top: Radius.circular(12)),
                                ),
                                child: Center(
                                  child: Icon(Icons.explore_outlined,
                                      size: 60, color: Colors.orange[400]),
                                ),
                              ),
                            Padding(
                              padding: EdgeInsets.all(16),
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Text(
                                    adventure['name'],
                                    style: TextStyle(
                                      fontSize: 18,
                                      fontWeight: FontWeight.bold,
                                    ),
                                  ),
                                  SizedBox(height: 8),
                                  Text(
                                    adventure['description'] ?? '',
                                    style: TextStyle(
                                      fontSize: 14,
                                      color: Colors.grey[600],
                                    ),
                                    maxLines: 3,
                                    overflow: TextOverflow.ellipsis,
                                  ),
                                  SizedBox(height: 8),
                                  Row(
                                    children: [
                                      Icon(Icons.hotel,
                                          size: 16, color: Colors.orange),
                                      SizedBox(width: 4),
                                      Text(
                                        'View Hotels',
                                        style: TextStyle(
                                          color: Colors.orange,
                                          fontWeight: FontWeight.bold,
                                        ),
                                      ),
                                    ],
                                  ),
                                ],
                              ),
                            ),
                          ],
                        ),
                      ),
                    );
                  },
                ),
    );
  }
}
