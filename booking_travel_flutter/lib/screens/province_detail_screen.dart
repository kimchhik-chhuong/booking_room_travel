// import 'package:flutter/material.dart';

// class ProvinceDetailScreen extends StatelessWidget {
//   final String provinceName;

//   const ProvinceDetailScreen({super.key, required this.provinceName});

//   @override
//   Widget build(BuildContext context) {
//     final Map<String, dynamic> provinceData = {
//       'Phnom Penh': {
//         'hotels': [
//           {'name': 'Raffles Hotel Le Royal', 'price': '\$150/night', 'image': 'https://placehold.co/200x150/FF6F61/FFFFFF?text=RHLR'},
//           {'name': 'Sokha Phnom Penh Hotel', 'price': '\$120/night', 'image': 'https://placehold.co/200x150/6B7280/FFFFFF?text=SPPH'},
//         ],
//         'travel': [
//           {'name': 'City Tour', 'price': '\$30', 'description': 'Explore Royal Palace and markets'},
//           {'name': 'Tuk Tuk Adventure', 'price': '\$20', 'description': 'Guided city ride'},
//         ],
//       },
//       'Siem Reap': {
//         'hotels': [
//           {'name': 'Angkor Miracle Resort', 'price': '\$100/night', 'image': 'https://placehold.co/200x150/9333EA/FFFFFF?text=AMR'},
//           {'name': 'Sofitel Angkor', 'price': '\$130/night', 'image': 'https://placehold.co/200x150/F59E0B/FFFFFF?text=SA'},
//         ],
//         'travel': [
//           {'name': 'Angkor Wat Tour', 'price': '\$40', 'description': 'Visit ancient temples'},
//           {'name': 'Sunrise Trip', 'price': '\$25', 'description': 'Early morning temple visit'},
//         ],
//       },
//       'Battambang': {
//         'hotels': [
//           {'name': 'Bamboo Battambang', 'price': '\$80/night', 'image': 'https://placehold.co/200x150/10B981/FFFFFF?text=BB'},
//           {'name': 'Classy Hotel', 'price': '\$90/night', 'image': 'https://placehold.co/200x150/E11D48/FFFFFF?text=CH'},
//         ],
//         'travel': [
//           {'name': 'Bamboo Train', 'price': '\$15', 'description': 'Unique rail experience'},
//           {'name': 'City Exploration', 'price': '\$20', 'description': 'Local sights tour'},
//         ],
//       },
//       // Add more provinces as needed
//     };

//     final data = provinceData[provinceName] ?? {'hotels': [], 'travel': []};

//     return Scaffold(
//       body: Container(
//         decoration: const BoxDecoration(
//           gradient: LinearGradient(
//             begin: Alignment.topCenter,
//             end: Alignment.bottomCenter,
//             colors: [Color(0xFFF5F7FA), Color(0xFFE0E7FF)],
//           ),
//         ),
//         child: SafeArea(
//           child: Column(
//             children: [
//               Padding(
//                 padding: const EdgeInsets.all(16.0),
//                 child: Row(
//                   mainAxisAlignment: MainAxisAlignment.spaceBetween,
//                   children: [
//                     IconButton(
//                       icon: const Icon(Icons.arrow_back_ios, color: Colors.black87),
//                       onPressed: () => Navigator.pop(context),
//                     ),
//                     Text(
//                       provinceName,
//                       style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: Colors.black87),
//                     ),
//                     const SizedBox(width: 48), // Placeholder for symmetry
//                   ],
//                 ),
//               ),
//               Expanded(
//                 child: ListView(
//                   padding: const EdgeInsets.all(16.0),
//                   children: [
//                     const Text(
//                       'Hotels',
//                       style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold, color: Colors.black87),
//                     ),
//                     const SizedBox(height: 15),
//                     ...data['hotels'].map<Widget>((hotel) => Card(
//                       elevation: 4,
//                       margin: const EdgeInsets.only(bottom: 15),
//                       shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(15)),
//                       child: ListTile(
//                         contentPadding: const EdgeInsets.all(10),
//                         leading: Hero(
//                           tag: 'hotel-${hotel['name']}',
//                           child: ClipRRect(
//                             borderRadius: BorderRadius.circular(10),
//                             child: Image.network(
//                               hotel['image'],
//                               width: 80,
//                               height: 60,
//                               fit: BoxFit.cover,
//                             ),
//                           ),
//                         ),
//                         title: Text(
//                           hotel['name'],
//                           style: const TextStyle(fontSize: 16, fontWeight: FontWeight.w500, color: Colors.black87),
//                         ),
//                         subtitle: Text(
//                           hotel['price'],
//                         ),
//                         onTap: () {
//                           // Handle hotel selection
//                         },
//                       ),
//                     )),
//                     const SizedBox(height: 20),
//                     const Text(
//                       'Travel Options',
//                       style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold, color: Colors.black87),
//                     ),
//                     const SizedBox(height: 15),
//                     ...data['travel'].map<Widget>((travel) => Card(
//                       elevation: 4,
//                       margin: const EdgeInsets.only(bottom: 15),
//                       shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(15)),
//                       child: ListTile(
//                         contentPadding: const EdgeInsets.all(10),
//                         title: Text(
//                           travel['name'],
//                           style: const TextStyle(fontSize: 16, fontWeight: FontWeight.w500, color: Colors.black87),
//                         ),
//                         subtitle: Text(
//                           '${travel['price']} - ${travel['description']}',
//                           style: TextStyle(color: Colors.grey[600], fontSize: 14),
//                         ),
//                         onTap: () {
//                           // Handle travel option selection
//                         },
//                       ),
//                     )),
//                   ],
//                 ),
//               ),
//             ],
//           ),
//         ),
//       ),
//     );
//   }
// }