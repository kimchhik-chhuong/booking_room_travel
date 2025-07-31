import 'package:flutter/material.dart';

import '../trips/kep.dart';
import '../trips/kompot.dart';
import '../trips/phnompenh.dart';
import '../trips/ratanakiri.dart';
import '../trips/stugteang.dart'; // Corrected spelling
import '../trips/siemreap.dart';
import '../trips/banteaymeanchey.dart';
import '../trips/battambang.dart';
import '../trips/kampongcham.dart';
import '../trips/kampongchhnang.dart';
import '../trips/kampongspeu.dart';
import '../trips/kampongthom.dart';
import '../trips/kandal.dart';
import '../trips/kohkong.dart';
import '../trips/mondulkiri.dart';
import '../trips/oddarmeanchey.dart';
import '../trips/pailin.dart';
import '../trips/preahvihear.dart';
import '../trips/preyveng.dart';
import '../trips/pursat.dart';
import '../trips/sihanoukville.dart';
import '../trips/svayrieng.dart';
import '../trips/takeo.dart';
import '../trips/tboungkhmum.dart';

class TripsPage extends StatefulWidget {
  const TripsPage({Key? key}) : super(key: key);

  @override
  _TripsPageState createState() => _TripsPageState();
}

class _TripsPageState extends State<TripsPage> {
  List<Map<String, dynamic>> provinces = [
    {
      'name': 'PhnomPenh',
      'page': const PhnompenhPage(),
      'image': 'lib/assets/images/phnompenh.jpg'
    },
    {
      'name': 'SiemReap',
      'page': const SiemReapPage(),
      'image': 'lib/assets/images/siemreap.jpg'
    },
    {
      'name': 'BanteayMeanchey',
      'page': const BanteayMeancheyPage(),
      'image': 'lib/assets/images/bantaymeanchey.jpg'
    },
    {
      'name': 'Battambang',
      'page': const BattambangPage(),
      'image': 'lib/assets/images/batambong.jpg'
    },
    {
      'name': 'K.Cham',
      'page': const KampongChamPage(),
      'image': 'lib/assets/images/kompongcham.jpg'
    },
    {
      'name': 'K.Chhnang',
      'page': const KampongChhnangPage(),
      'image': 'lib/assets/images/kampongchhnang.jpg'
    },
    {
      'name': 'K.Speu',
      'page': const KampongSpeuPage(),
      'image': 'lib/assets/images/kampongspue.jpg'
    },
    {
      'name': 'K.Thom',
      'page': const KampongThomPage(),
      'image': 'lib/assets/images/kompong-thom.jpg'
    },
    {
      'name': 'Kampot',
      'page': const KompotPage(),
      'image': 'lib/assets/images/kampot.jpg'
    },
    {
      'name': 'Kandal',
      'page': const KandalPage(),
      'image': 'lib/assets/images/kaldal.jpg'
    },
    {
      'name': 'Kep',
      'page': const KepPage(),
      'image': 'lib/assets/images/kep.jpg'
    },
    {
      'name': 'KohKong',
      'page': const KohKongPage(),
      'image': 'lib/assets/images/kohkong.jpg'
    },
    {
      'name': 'Mondulkiri',
      'page': const MondulkiriPage(),
      'image': 'lib/assets/images/mundoulkiri.jpg'
    },
    {
      'name': 'OddarMeanchey',
      'page': const OddarMeancheyPage(),
      'image': 'lib/assets/images/oddar-Meanchey.jpg'
    },
    {
      'name': 'Pailin',
      'page': const PailinPage(),
      'image': 'lib/assets/images/pailin.jpg'
    },
    {
      'name': 'PreahVihear',
      'page': const PreahVihearPage(),
      'image': 'lib/assets/images/preakvihea.jpg'
    },
    {
      'name': 'Prey Veng',
      'page': const PreyVengPage(),
      'image': 'lib/assets/images/preyveng.jpg'
    },
    {
      'name': 'Pursat',
      'page': const PursatPage(),
      'image': 'lib/assets/images/pursat.jpg'
    },
    {
      'name': 'Ratanakiri',
      'page': const RatanakiriPage(),
      'image': 'lib/assets/images/ratanakiri.jpg'
    },
    {
      'name': 'Sihanouk',
      'page': const SihanoukvillePage(),
      'image': 'lib/assets/images/syhanus.jpg'
    },
    {
      'name': 'StungTreng',
      'page': const StungteangPage(),
      'image': 'lib/assets/images/steangtreang.jpg'
    },
    {
      'name': 'SvayRieng',
      'page': const SvayRiengPage(),
      'image': 'lib/assets/images/svayrieng.jpg'
    },
    {
      'name': 'Takeo',
      'page': const TakeoPage(),
      'image': 'lib/assets/images/takeo.jpg'
    },
    // {
    //   'name': 'TboungKhmum',
    //   'page': const TboungKhmumPage(),
    //   'image': 'lib/assets/images/tboungkhmum.jpg'
    // },
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
              decoration: InputDecoration(
                border: OutlineInputBorder(),
                hintText: 'Enter province name',
                suffixIcon: const Icon(Icons.search),
              ),
            ),
            const SizedBox(height: 20),
            const Text(
              'All Provinces of Cambodia',
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
          ],
        ),
      ),
    );
  }
}
