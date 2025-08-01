import 'package:flutter/material.dart';

import '../adventures/adventures_page.dart';

class TripsPage extends StatefulWidget {
  const TripsPage({Key? key}) : super(key: key);

  @override
  State<TripsPage> createState() => _TripsPageState();
}

class _TripsPageState extends State<TripsPage> {
  List<Map<String, dynamic>> provinces = [
    {
      'name': 'Phnom Penh',
      'page': const AdventuresPage(provinceId: 1, provinceName: 'Phnom Penh'),
      'image': 'lib/assets/images/phnompenh.jpg'
    },
    {
      'name': 'Siem Reap',
      'page': const AdventuresPage(provinceId: 2, provinceName: 'Siem Reap'),
      'image': 'lib/assets/images/siemreap.jpg'
    },
    {
      'name': 'Banteay Meanchey',
      'page': const AdventuresPage(provinceId: 3, provinceName: 'Banteay Meanchey'),
      'image': 'lib/assets/images/bantaymeanchey.jpg'
    },
    {
      'name': 'Battambang',
      'page': const AdventuresPage(provinceId: 4, provinceName: 'Battambang'),
      'image': 'lib/assets/images/batambong.jpg'
    },
    {
      'name': 'Kampong Cham',
      'page': const AdventuresPage(provinceId: 5, provinceName: 'Kampong Cham'),
      'image': 'lib/assets/images/kampongcham.jpg'
    },
    {
      'name': 'Kampong Chhnang',
      'page': const AdventuresPage(provinceId: 6, provinceName: 'Kampong Chhnang'),
      'image': 'lib/assets/images/kampongchhnang.jpg'
    },
    {
      'name': 'Kampong Speu',
      'page': const AdventuresPage(provinceId: 7, provinceName: 'Kampong Speu'),
      'image': 'lib/assets/images/kampongspeu.jpg'
    },
    {
      'name': 'Kampong Thom',
      'page': const AdventuresPage(provinceId: 8, provinceName: 'Kampong Thom'),
      'image': 'lib/assets/images/kampongthom.jpg'
    },
    {
      'name': 'Kampot',
      'page': const AdventuresPage(provinceId: 9, provinceName: 'Kampot'),
      'image': 'lib/assets/images/kampot.jpg'
    },
    {
      'name': 'Kandal',
      'page': const AdventuresPage(provinceId: 10, provinceName: 'Kandal'),
      'image': 'lib/assets/images/kandal.jpg'
    },
    {
      'name': 'Kep',
      'page': const AdventuresPage(provinceId: 11, provinceName: 'Kep'),
      'image': 'lib/assets/images/kep.jpg'
    },
    {
      'name': 'Koh Kong',
      'page': const AdventuresPage(provinceId: 12, provinceName: 'Koh Kong'),
      'image': 'lib/assets/images/kohkong.jpg'
    },
    {
      'name': 'Mondulkiri',
      'page': const AdventuresPage(provinceId: 13, provinceName: 'Mondulkiri'),
      'image': 'lib/assets/images/mondulkiri.jpg'
    },
    {
      'name': 'Oddar Meanchey',
      'page': const AdventuresPage(provinceId: 14, provinceName: 'Oddar Meanchey'),
      'image': 'lib/assets/images/oddarmeanchey.jpg'
    },
    {
      'name': 'Pailin',
      'page': const AdventuresPage(provinceId: 15, provinceName: 'Pailin'),
      'image': 'lib/assets/images/pailin.jpg'
    },
    {
      'name': 'Preah Vihear',
      'page': const AdventuresPage(provinceId: 16, provinceName: 'Preah Vihear'),
      'image': 'lib/assets/images/preahvihear.jpg'
    },
    {
      'name': 'Prey Veng',
      'page': const AdventuresPage(provinceId: 17, provinceName: 'Prey Veng'),
      'image': 'lib/assets/images/preyveng.jpg'
    },
    {
      'name': 'Pursat',
      'page': const AdventuresPage(provinceId: 18, provinceName: 'Pursat'),
      'image': 'lib/assets/images/pursat.jpg'
    },
    {
      'name': 'Ratanakiri',
      'page': const AdventuresPage(provinceId: 19, provinceName: 'Ratanakiri'),
      'image': 'lib/assets/images/ratanakiri.jpg'
    },
    {
      'name': 'Sihanoukville',
      'page': const AdventuresPage(provinceId: 20, provinceName: 'Sihanoukville'),
      'image': 'lib/assets/images/sihanoukville.jpg'
    },
    {
      'name': 'Stung Treng',
      'page': const AdventuresPage(provinceId: 21, provinceName: 'Stung Treng'),
      'image': 'lib/assets/images/stungtreng.jpg'
    },
    {
      'name': 'Svay Rieng',
      'page': const AdventuresPage(provinceId: 22, provinceName: 'Svay Rieng'),
      'image': 'lib/assets/images/svayrieng.jpg'
    },
    {
      'name': 'Takeo',
      'page': const AdventuresPage(provinceId: 23, provinceName: 'Takeo'),
      'image': 'lib/assets/images/takeo.jpg'
    },
    {
      'name': 'Tboung Khmum',
      'page': const AdventuresPage(provinceId: 24, provinceName: 'Tboung Khmum'),
      'image': 'lib/assets/images/tboungkhmum.jpg'
    },
  ];

  List<Map<String, dynamic>> filteredProvinces = [];

  @override
  void initState() {
    super.initState();
    filteredProvinces = provinces; // Initialize with all provinces
  }

  void filterProvinces(String query) {
    final List<Map<String, dynamic>> results = provinces.where((province) {
      return province['name'].toLowerCase().contains(query.toLowerCase());
    }).toList();

    setState(() {
      filteredProvinces = results;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Cambodia Provinces')),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Search Provinces',
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
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
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 10),
            SizedBox(
              height: 100,
              child: ListView.builder(
                scrollDirection: Axis.horizontal,
                itemCount: provinces.length,
                itemBuilder: (context, index) {
                  return Padding(
                    padding: const EdgeInsets.symmetric(horizontal: 8.0),
                    child: GestureDetector(
                      onTap: () {
                        Navigator.push(
                          context,
                          MaterialPageRoute(
                              builder: (_) =>
                                  provinces[index]['page'] as Widget),
                        );
                      },
                      child: Column(
                        children: [
                          CircleAvatar(
                            backgroundColor: Colors.purple,
                            radius: 30,
                            child: ClipOval(
                              child: Image.asset(
                                provinces[index]['image'] as String,
                                width: 60,
                                height: 60,
                                fit: BoxFit.cover,
                              ),
                            ),
                          ),
                          const SizedBox(height: 8),
                          Text(
                            (provinces[index]['name'] as String)
                                .split(' ')
                                .first,
                            style: const TextStyle(fontWeight: FontWeight.bold),
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
              'All Provinces of Cambodia',
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 10),
            Expanded(
              child: GridView.builder(
                gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
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
                            builder: (_) =>
                                filteredProvinces[index]['page'] as Widget),
                      );
                    },
                    child: Column(
                      children: [
                        CircleAvatar(
                          backgroundColor: Colors.purple,
                          radius: 30,
                          child: ClipOval(
                            child: Image.asset(
                              filteredProvinces[index]['image'] as String,
                              width: 60,
                              height: 60,
                              fit: BoxFit.cover,
                            ),
                          ),
                        ),
                        const SizedBox(height: 8),
                        Text(
                          (filteredProvinces[index]['name'] as String)
                              .split(' ')
                              .first,
                          style: const TextStyle(fontWeight: FontWeight.bold),
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
