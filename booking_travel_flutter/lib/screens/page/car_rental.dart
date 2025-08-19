import 'package:flutter/material.dart';

void main() {
  runApp(CarRentalApp());
}

class CarRentalApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Car Rental Landing',
      theme: ThemeData(
        primaryColor: Color(0xFFF59C1A),
        scaffoldBackgroundColor: Color(0xFFF5F5F5),
        fontFamily: 'Arial',
      ),
      home: LandingPage(),
      debugShowCheckedModeBanner: false,
    );
  }
}

class LandingPage extends StatelessWidget {
  final orange = Color(0xFFF59C1A);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SingleChildScrollView(
        child: Column(
          children: [
            // Hero Section
            Stack(
              clipBehavior: Clip.none,
              children: [
                Container(
                  height: 430,
                  width: double.infinity,
                  decoration: BoxDecoration(
                    image: DecorationImage(
                      image: NetworkImage(
                        'https://images.unsplash.com/photo-1503376780353-7e6692767b70?q=80&w=1920&auto=format&fit=crop',
                      ),
                      fit: BoxFit.cover,
                      colorFilter: ColorFilter.mode(
                          Colors.black.withOpacity(0.3), BlendMode.darken),
                    ),
                  ),
                  child: Center(
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Text(
                          "Lorem Ipsum is simply dummy",
                          style: TextStyle(
                            color: Colors.white,
                            fontSize: 38,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        SizedBox(height: 8),
                        Text(
                          "Lorem Ipsum is simply dummy text",
                          style: TextStyle(
                            color: Colors.white.withOpacity(0.9),
                            fontSize: 16,
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
                Positioned(
                  bottom: -40,
                  left: 20,
                  right: 20,
                  child: Card(
                    color: Colors.black.withOpacity(0.65),
                    shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(8)),
                    child: Padding(
                      padding: const EdgeInsets.all(12.0),
                      child: Wrap(
                        spacing: 10,
                        runSpacing: 10,
                        children: [
                          _buildDropdownField("Location", ["Location"]),
                          _buildDropdownField("Type", ["Car Type"]),
                          _buildDateField("Pickup Date"),
                          _buildTimeField("Pickup Time"),
                          _buildDateField("Return Date"),
                          _buildTimeField("Return Time"),
                          _buildDropdownField("Members", ["Members"]),
                          _buildDropdownField("Driver's Age", ["21 - 30"]),
                          ElevatedButton(
                            onPressed: () {},
                            style: ElevatedButton.styleFrom(
                              backgroundColor: orange,
                              padding: EdgeInsets.symmetric(
                                  vertical: 14, horizontal: 20),
                              shape: RoundedRectangleBorder(
                                  borderRadius: BorderRadius.circular(6)),
                            ),
                            child: Text(
                              "SEARCH NOW",
                              style: TextStyle(
                                  fontWeight: FontWeight.bold, fontSize: 14),
                            ),
                          ),
                        ],
                      ),
                    ),
                  ),
                ),
              ],
            ),
            SizedBox(height: 60), // for overlap spacing

            // Why Choose Us Section
            _sectionTitle("Why Choose Us?"),
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16.0),
              child: LayoutBuilder(
                builder: (context, constraints) {
                  int crossAxis = constraints.maxWidth > 1024
                      ? 4
                      : constraints.maxWidth > 700
                          ? 2
                          : 1;
                  return GridView(
                    shrinkWrap: true,
                    physics: NeverScrollableScrollPhysics(),
                    gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
                        crossAxisCount: crossAxis,
                        crossAxisSpacing: 16,
                        mainAxisSpacing: 16,
                        childAspectRatio: 1),
                    children: [
                      _featureCard("🚗", "Reliable Service",
                          "Lorem ipsum dolor sit amet, consectetur adipiscing elit."),
                      _featureCard("🏷️", "Lowest Prices",
                          "Best rates with no hidden fees and transparent terms."),
                      _featureCard("🕑", "24/7 Support",
                          "Our team is here to help anytime you need us."),
                      _featureCard("🚙", "Best Cars",
                          "Only top-condition vehicles from trusted partners."),
                    ],
                  );
                },
              ),
            ),

            // Car Cards Section
            Container(
              color: Color(0xFFF8F8F8),
              width: double.infinity,
              padding: EdgeInsets.symmetric(vertical: 40),
              child: Column(
                children: [
                  _sectionTitle("Choose your Cab"),
                  Padding(
                    padding: const EdgeInsets.symmetric(horizontal: 16.0),
                    child: LayoutBuilder(
                      builder: (context, constraints) {
                        int crossAxis = constraints.maxWidth > 1024
                            ? 4
                            : constraints.maxWidth > 700
                                ? 2
                                : 1;
                        return GridView(
                          shrinkWrap: true,
                          physics: NeverScrollableScrollPhysics(),
                          gridDelegate:
                              SliverGridDelegateWithFixedCrossAxisCount(
                                  crossAxisCount: crossAxis,
                                  crossAxisSpacing: 16,
                                  mainAxisSpacing: 16,
                                  childAspectRatio: 0.8),
                          children: [
                            _carCard(
                                "https://images.unsplash.com/photo-1606813903025-7b1a6a9cbd02?q=80&w=800&auto=format&fit=crop",
                                "Cci- Fiat 500",
                                "Mileage: 14 000 km • Volume: 2.3 l • Air Conditioning: Yes"),
                            _carCard(
                                "https://images.unsplash.com/photo-1566186769607-3e83c9a3b2a1?q=80&w=800&auto=format&fit=crop",
                                "Cci- Fiat 500",
                                "Mileage: 14 000 km • Volume: 2.0 l • Air Conditioning: Yes"),
                            _carCard(
                                "https://images.unsplash.com/photo-1617965547460-2e3a0b1a7d95?q=80&w=800&auto=format&fit=crop",
                                "Cci- Fiat 500",
                                "Mileage: 14 000 km • Volume: 2.3 l • Air Conditioning: Yes"),
                            _carCard(
                                "https://images.unsplash.com/photo-1503376780353-7e6692767b70?q=80&w=800&auto=format&fit=crop",
                                "Cci- Fiat 500",
                                "Mileage: 14 000 km • Volume: 2.2 l • Air Conditioning: Yes"),
                          ],
                        );
                      },
                    ),
                  ),
                ],
              ),
            ),

            // Testimonials
            _sectionTitle("What our people are saying"),
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16.0),
              child: Container(
                padding: EdgeInsets.all(20),
                decoration: BoxDecoration(
                  color: Colors.white,
                  boxShadow: [BoxShadow(color: Colors.black12, blurRadius: 8)],
                  borderRadius: BorderRadius.circular(8),
                ),
                child: Row(
                  children: [
                    ClipRRect(
                      borderRadius: BorderRadius.circular(40),
                      child: Image.network(
                        "https://images.unsplash.com/photo-1494790108377-be9c29b29330?q=80&w=200&auto=format&fit=crop",
                        width: 80,
                        height: 80,
                        fit: BoxFit.cover,
                      ),
                    ),
                    SizedBox(width: 16),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            "“Lorem ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.”",
                            style: TextStyle(
                                fontStyle: FontStyle.italic, color: Colors.grey[700]),
                          ),
                          SizedBox(height: 6),
                          Text("John Smith Founder",
                              style: TextStyle(fontWeight: FontWeight.w600)),
                          Text("& CEO, Company Name",
                              style: TextStyle(color: Colors.grey[600])),
                        ],
                      ),
                    ),
                  ],
                ),
              ),
            ),

            // Destination Gallery
            SizedBox(height: 40),
            _sectionTitle("Find Destination"),
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16.0),
              child: LayoutBuilder(
                builder: (context, constraints) {
                  int crossAxis = constraints.maxWidth > 1024
                      ? 5
                      : constraints.maxWidth > 700
                          ? 3
                          : 2;
                  return GridView.count(
                    shrinkWrap: true,
                    physics: NeverScrollableScrollPhysics(),
                    crossAxisCount: crossAxis,
                    crossAxisSpacing: 8,
                    mainAxisSpacing: 8,
                    children: List.generate(10, (index) {
                      return ClipRRect(
                        borderRadius: BorderRadius.circular(4),
                        child: Image.network(
                          "https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=300&auto=format&fit=crop&sig=$index",
                          fit: BoxFit.cover,
                        ),
                      );
                    }),
                  );
                },
              ),
            ),
            SizedBox(height: 40),
          ],
        ),
      ),
    );
  }

  Widget _sectionTitle(String text) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 16),
      child: Text(
        text,
        style: TextStyle(fontSize: 26, fontWeight: FontWeight.bold),
        textAlign: TextAlign.center,
      ),
    );
  }

  Widget _featureCard(String icon, String title, String desc) {
    return Container(
      padding: EdgeInsets.all(18),
      decoration: BoxDecoration(
        color: Colors.white,
        boxShadow: [BoxShadow(color: Colors.black12, blurRadius: 8)],
        borderRadius: BorderRadius.circular(8),
      ),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          CircleAvatar(
            radius: 32,
            backgroundColor: Colors.white,
            child: Text(
              icon,
              style: TextStyle(fontSize: 28),
            ),
          ),
          SizedBox(height: 8),
          Text(title, style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
          SizedBox(height: 4),
          Text(desc, style: TextStyle(fontSize: 13, color: Colors.grey[600]), textAlign: TextAlign.center),
        ],
      ),
    );
  }

  Widget _carCard(String imgUrl, String title, String meta) {
    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(6),
        boxShadow: [BoxShadow(color: Colors.black12, blurRadius: 8)],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          ClipRRect(
              borderRadius: BorderRadius.vertical(top: Radius.circular(6)),
              child: Image.network(imgUrl, height: 140, width: double.infinity, fit: BoxFit.cover)),
          Padding(
            padding: EdgeInsets.all(12),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(title, style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
                SizedBox(height: 4),
                Text(meta, style: TextStyle(fontSize: 12, color: Colors.grey[600])),
                SizedBox(height: 6),
                ElevatedButton(
                  onPressed: () {},
                  style: ElevatedButton.styleFrom(
                    backgroundColor: orange,
                    shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(4)),
                  ),
                  child: Text("Book Now", style: TextStyle(fontWeight: FontWeight.bold)),
                )
              ],
            ),
          )
        ],
      ),
    );
  }

  Widget _buildDropdownField(String label, List<String> items) {
    return SizedBox(
      width: 120,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(label, style: TextStyle(color: Colors.white70, fontSize: 12)),
          SizedBox(height: 4),
          Container(
            padding: EdgeInsets.symmetric(horizontal: 8),
            decoration: BoxDecoration(
                color: Colors.white, borderRadius: BorderRadius.circular(4)),
            child: DropdownButton<String>(
              isExpanded: true,
              underline: SizedBox(),
              value: items[0],
              items: items.map((e) => DropdownMenuItem(value: e, child: Text(e))).toList(),
              onChanged: (val) {},
            ),
          )
        ],
      ),
    );
  }

  Widget _buildDateField(String label) {
    return SizedBox(
      width: 120,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(label, style: TextStyle(color: Colors.white70, fontSize: 12)),
          SizedBox(height: 4),
          Container(
            decoration: BoxDecoration(
                color: Colors.white, borderRadius: BorderRadius.circular(4)),
            child: TextField(
              readOnly: true,
              decoration: InputDecoration(
                  contentPadding: EdgeInsets.symmetric(horizontal: 8),
                  hintText: "Select Date",
                  border: InputBorder.none),
              onTap: () {},
            ),
          )
        ],
      ),
    );
  }

  Widget _buildTimeField(String label) {
    return SizedBox(
      width: 120,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(label, style: TextStyle(color: Colors.white70, fontSize: 12)),
          SizedBox(height: 4),
          Container(
            decoration: BoxDecoration(
                color: Colors.white, borderRadius: BorderRadius.circular(4)),
            child: TextField(
              readOnly: true,
              decoration: InputDecoration(
                  contentPadding: EdgeInsets.symmetric(horizontal: 8),
                  hintText: "Select Time",
                  border: InputBorder.none),
              onTap: () {},
            ),
          )
        ],
      ),
    );
  }
}
