import 'package:flutter/material.dart';
import 'package:url_launcher/url_launcher.dart'; // Import for launching URLs

// --- DetailScreen Widget ---
class DetailScreen extends StatelessWidget {
  final Map<String, dynamic> itemDetails;
  final String itemType; // 'Place' or 'Hotel'

  const DetailScreen({
    super.key,
    required this.itemDetails,
    required this.itemType,
  });

  // Function to launch map application
  Future<void> _launchMap(BuildContext context, double latitude, double longitude, String name) async {
    // Formulate Google Maps URL with name and coordinates for better display
    final String googleMapsUrl = 'https://www.google.com/maps/search/?api=1&query=${Uri.encodeComponent(name)},$latitude,$longitude';
    // Formulate Apple Maps URL with name and coordinates
    final String appleMapsUrl = 'http://maps.apple.com/?q=${Uri.encodeComponent(name)}&ll=$latitude,$longitude';

    // Use try-catch to handle potential errors during URL launch
    try {
      if (Theme.of(context).platform == TargetPlatform.android) {
        if (await canLaunchUrl(Uri.parse(googleMapsUrl))) {
          await launchUrl(Uri.parse(googleMapsUrl));
        } else {
          // Fallback for when Google Maps app is not installed, try general geo URI
          if (await canLaunchUrl(Uri.parse('geo:$latitude,$longitude?q=${Uri.encodeComponent(name)}'))) {
            await launchUrl(Uri.parse('geo:$latitude,$longitude?q=${Uri.encodeComponent(name)}'));
          } else {
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(content: Text('Could not launch Google Maps or general map application.')),
            );
          }
        }
      } else if (Theme.of(context).platform == TargetPlatform.iOS) {
        if (await canLaunchUrl(Uri.parse(appleMapsUrl))) {
          await launchUrl(Uri.parse(appleMapsUrl));
        } else {
          // Fallback for when Apple Maps app is not installed
          if (await canLaunchUrl(Uri.parse('http://maps.apple.com/?q=${Uri.encodeComponent(name)}&ll=$latitude,$longitude'))) {
            await launchUrl(Uri.parse('http://maps.apple.com/?q=${Uri.encodeComponent(name)}&ll=$latitude,$longitude'));
          } else {
             ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(content: Text('Could not launch Apple Maps or general map application.')),
            );
          }
        }
      } else {
        // For web or other platforms, try launching in a browser
        if (await canLaunchUrl(Uri.parse(googleMapsUrl))) {
          await launchUrl(Uri.parse(googleMapsUrl));
        } else {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text('Could not launch map in browser.')),
          );
        }
      }
    } catch (e) {
      // Catch any other unexpected errors during launch
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('An error occurred while trying to open the map: $e')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    // Safely cast imageUrls to List<String>
    List<String> imageUrls = (itemDetails['imageUrls'] as List<dynamic>?)?.map((e) => e.toString()).toList() ?? [];
    double? latitude = itemDetails['latitude'];
    double? longitude = itemDetails['longitude'];

    return Scaffold(
      appBar: AppBar(
        title: Text(itemDetails['name'], style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
        backgroundColor: Colors.teal,
        elevation: 4,
        centerTitle: true,
      ),
      body: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Image Gallery (PageView for multiple images)
            if (imageUrls.isNotEmpty)
              Container(
                height: 250,
                color: Colors.grey[200], // Placeholder background
                child: PageView.builder(
                  itemCount: imageUrls.length,
                  itemBuilder: (context, index) {
                    return Image.network(
                      imageUrls[index],
                      fit: BoxFit.cover,
                      errorBuilder: (context, error, stackTrace) => Container(
                        color: Colors.grey[300],
                        child: Center(child: Text('Image not available')),
                      ),
                    );
                  },
                ),
              )
            else
              Container(
                height: 250,
                color: Colors.grey[300],
                child: Center(child: Text('Image not available')),
              ),
            Padding(
              padding: const EdgeInsets.all(16.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    itemDetails['name'],
                    style: TextStyle(fontSize: 28, fontWeight: FontWeight.bold, color: Colors.teal),
                  ),
                  SizedBox(height: 8),
                  Text(
                    itemDetails['shortDescription'] ?? 'No description provided.',
                    style: TextStyle(fontSize: 18, color: Colors.grey[800]),
                  ),
                  SizedBox(height: 24),

                  // Full Description
                  if (itemDetails['fullDescription'] != null && itemDetails['fullDescription'].isNotEmpty)
                    Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          'Full Description:',
                          style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
                        ),
                        SizedBox(height: 8),
                        Text(
                          itemDetails['fullDescription'],
                          style: TextStyle(fontSize: 16, color: Colors.grey[700]),
                        ),
                        SizedBox(height: 16),
                      ],
                    ),

                  // Location and Map Link
                  if (itemDetails['location'] != null)
                    Padding(
                      padding: const EdgeInsets.only(bottom: 8.0),
                      child: Row(
                        children: [
                          Icon(Icons.location_on, color: Colors.blueAccent, size: 20),
                          SizedBox(width: 8),
                          Flexible(
                            child: Text(
                              'Location: ${itemDetails['location']}',
                              style: TextStyle(fontSize: 16),
                            ),
                          ),
                          if (latitude != null && longitude != null)
                            TextButton.icon(
                              onPressed: () {
                                _launchMap(context, latitude, longitude, itemDetails['name']);
                              },
                              icon: Icon(Icons.map, size: 20),
                              label: Text('View on Map'),
                            ),
                        ],
                      ),
                    ),

                  // Best Time to Visit (for places)
                  if (itemDetails['bestTime'] != null)
                    Padding(
                      padding: const EdgeInsets.only(bottom: 8.0),
                      child: Row(
                        children: [
                          Icon(Icons.wb_sunny, color: Colors.orange, size: 20),
                          SizedBox(width: 8),
                          Flexible(
                            child: Text(
                              'Best Time to Visit: ${itemDetails['bestTime']}',
                              style: TextStyle(fontSize: 16),
                            ),
                          ),
                        ],
                      ),
                    ),

                  // Price Range
                  if (itemDetails['priceRange'] != null)
                    Padding(
                      padding: const EdgeInsets.only(bottom: 8.0),
                      child: Row(
                        children: [
                          Icon(Icons.attach_money, color: Colors.green, size: 20),
                          SizedBox(width: 8),
                          Flexible(
                            child: Text(
                              'Price Range: ${itemDetails['priceRange']}',
                              style: TextStyle(fontSize: 16),
                            ),
                          ),
                        ],
                      ),
                    ),

                  // Rating (for hotels)
                  if (itemDetails['rating'] != null)
                    Padding(
                      padding: const EdgeInsets.only(bottom: 8.0),
                      child: Row(
                        children: [
                          Icon(Icons.star, color: Colors.amber, size: 20),
                          SizedBox(width: 8),
                          Flexible(
                            child: Text(
                              'Rating: ${itemDetails['rating']}/5',
                              style: TextStyle(fontSize: 16),
                            ),
                          ),
                        ],
                      ),
                    ),

                  // Amenities (for hotels)
                  if (itemDetails['amenities'] != null && (itemDetails['amenities'] as List).isNotEmpty)
                    Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        SizedBox(height: 8),
                        Text(
                          'Amenities:',
                          style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                        ),
                        Wrap(
                          spacing: 8.0,
                          runSpacing: 4.0,
                          children: (itemDetails['amenities'] as List<dynamic>).map((amenity) => Chip(
                                label: Text(amenity.toString()),
                                backgroundColor: Colors.blue.withOpacity(0.1),
                                labelStyle: TextStyle(color: Colors.blue[700]),
                              )).toList(),
                        ),
                        SizedBox(height: 16),
                      ],
                    ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}

// --- TripScreen Widget ---
class TripScreen extends StatefulWidget {
  const TripScreen({super.key});

  @override
  State<TripScreen> createState() => _TripScreenState();
}

class _TripScreenState extends State<TripScreen> {
  String? selectedProvince;
  bool showDetailedView = false;
  int _visiblePlacesCount = 3; // Initially show 3 places
  int _visibleHotelsCount = 2; // Initially show 2 hotels

  // Data for all 25 provinces
final List<Map<String, dynamic>> _provinces = [
  {
    'name': 'Phnom Penh',
    'details': 'The bustling capital city of Cambodia, known for its royal palace, temples, and historical sites.',
    'places': [
      {
        'name': 'Royal Palace & Silver Pagoda',
        'shortDescription': 'Stunning complex of buildings, home to the King.',
        'fullDescription': 'The Royal Palace complex is a magnificent example of Khmer architecture, housing the King\'s residence and the stunning Silver Pagoda, adorned with over 5,000 silver tiles. It\'s a major cultural landmark and a symbol of the Kingdom. Visitors can explore various pavilions and manicured gardens.',
        'imageUrls': [
          'assets/images/image_3afe4c.png', // Correct: Assumes you have this file in assets/images/
          'https://via.placeholder.com/600x400/ffc107/FFFFFF?text=Royal+Palace+2',
          // ... other images
        ],
        'location': 'Phnom Penh',
        'latitude': 11.5540,
        'longitude': 104.9144,
        'bestTime': 'Morning (closes at lunchtime).'
      },
      {
        'name': 'Tuol Sleng Genocide Museum (S-21)',
        'shortDescription': 'Former prison, a somber but important historical site.',
        'fullDescription': 'Tuol Sleng Genocide Museum, formerly S-21 prison, is a former Khmer Rouge torture center. It\'s a harrowing but essential visit to understand the tragic history of Cambodia under the Khmer Rouge regime, documented through photographs and artifacts. An audio guide is highly recommended.',
        'imageUrls': [
          'https://via.placeholder.com/600x400/FFD700/000000?text=Tuol+Sleng+1',
          'https://via.placeholder.com/600x400/FFD700/000000?text=Tuol+Sleng+2',
          'https://via.placeholder.com/600x400/FFD700/000000?text=Tuol+Sleng+3',
          'https://via.placeholder.com/600x400/FFD700/000000?text=Tuol+Sleng+4',
          'https://via.placeholder.com/600x400/FFD700/000000?text=Tuol+Sleng+5'
        ],
        'location': 'Phnom Penh',
        'latitude': 11.5300,
        'longitude': 104.9180,
        'bestTime': 'Anytime.'
      },
      {
        'name': 'Wat Phnom',
        'shortDescription': 'A historical pagoda on a hill, the namesake of the city.',
        'fullDescription': 'Wat Phnom is a revered pagoda on a small hill, believed to be the birthplace of Phnom Penh. It\'s a peaceful escape from the city bustle and offers great views from the top, often visited by locals for blessings and good fortune.',
        'imageUrls': [
          'https://via.placeholder.com/600x400/DAA520/000000?text=Wat+Phnom+1',
          'https://via.placeholder.com/600x400/DAA520/000000?text=Wat+Phnom+2',
          'https://via.placeholder.com/600x400/DAA520/000000?text=Wat+Phnom+3'
        ],
        'location': 'Phnom Penh',
        'latitude': 11.5794,
        'longitude': 104.9220,
        'bestTime': 'Daytime.'
      },
    ],
    'hotels': [
      {
        'name': 'Raffles Hotel Le Royal',
        'shortDescription': 'Historic luxury hotel.',
        'fullDescription': 'Raffles Hotel Le Royal is a legendary luxury hotel with a rich history, known for its colonial charm, beautiful architecture, and impeccable service. It has hosted many famous figures over the years and remains a benchmark for luxury accommodation in Southeast Asia.',
        'imageUrls': [
          'https://via.placeholder.com/600x400/ADD8E6/000000?text=Raffles+1',
          'https://via.placeholder.com/600x400/ADD8E6/000000?text=Raffles+2',
          'https://via.placeholder.com/600x400/ADD8E6/000000?text=Raffles+3',
          'https://via.placeholder.com/600x400/ADD8E6/000000?text=Raffles+4',
          'https://via.placeholder.com/600x400/ADD8E6/000000?text=Raffles+5'
        ],
        'location': 'Phnom Penh',
        'latitude': 11.5739,
        'longitude': 104.9198,
        'rating': 5.0,
        'priceRange': '\$\$\$\$\$',
        'amenities': ['Spa', 'Pool', 'Fine Dining', 'Historic Bar', 'Concierge', 'Fitness Center']
      },
    ]
  },
  {
    'name': 'Siem Reap',
    'details': 'The gateway to the Angkor Wat complex, a world-renowned archaeological site.',
    'places': [
      {
        'name': 'Angkor Wat',
        'shortDescription': 'The iconic and largest religious monument in the world.',
        'fullDescription': 'Angkor Wat is the most famous and well-preserved temple within the Angkor Archaeological Park. It is a UNESCO World Heritage site and a breathtaking example of Khmer architecture, best visited at sunrise for its unforgettable view and mystical ambiance. Plan to spend several hours exploring its intricate carvings.',
        'imageUrls': [
          'https://via.placeholder.com/600x400/FFDAB9/000000?text=Angkor+Wat+1',
          'https://via.placeholder.com/600x400/FFDAB9/000000?text=Angkor+Wat+2',
          'https://via.placeholder.com/600x400/FFDAB9/000000?text=Angkor+Wat+3',
          'https://via.placeholder.com/600x400/FFDAB9/000000?text=Angkor+Wat+4',
          'https://via.placeholder.com/600x400/FFDAB9/000000?text=Angkor+Wat+5',
          'https://via.placeholder.com/600x400/FFDAB9/000000?text=Angkor+Wat+6',
          'https://via.placeholder.com/600x400/FFDAB9/000000?text=Angkor+Wat+7'
        ],
        'location': 'Siem Reap',
        'latitude': 13.4125,
        'longitude': 103.8670,
        'bestTime': 'Sunrise or Sunset',
        'priceRange': '\$\$\$ (for park pass)'
      },
      {
        'name': 'Bayon Temple',
        'shortDescription': 'Temple known for its smiling stone faces.',
        'fullDescription': 'Located in the center of Angkor Thom, Bayon Temple is famous for its massive stone faces that gaze out from all angles. It\'s a fascinating and mystical temple, representing the grandeur of the Khmer Empire and a unique sculptural marvel. The many faces create a serene yet powerful atmosphere.',
        'imageUrls': [
          'https://via.placeholder.com/600x400/CD853F/FFFFFF?text=Bayon+Temple+1',
          'https://via.placeholder.com/600x400/CD853F/FFFFFF?text=Bayon+Temple+2',
          'https://via.placeholder.com/600x400/CD853F/FFFFFF?text=Bayon+Temple+3',
          'https://via.placeholder.com/600x400/CD853F/FFFFFF?text=Bayon+Temple+4',
          'https://via.placeholder.com/600x400/CD853F/FFFFFF?text=Bayon+Temple+5'
        ],
        'location': 'Angkor Thom',
        'latitude': 13.4402,
        'longitude': 103.8596,
        'bestTime': 'Morning.'
      },
    ],
    'hotels': [
      {
        'name': 'Park Hyatt Siem Reap',
        'shortDescription': 'Luxury hotel in the heart of Siem Reap.',
        'fullDescription': 'The Park Hyatt Siem Reap offers sophisticated luxury with elegant rooms, multiple pools, and world-class dining, ideally located close to Pub Street and the Royal Gardens. It\'s an oasis of calm and luxury amidst the bustling city, perfect for discerning travelers.',
        'imageUrls': [
          'https://via.placeholder.com/600x400/D2B48C/000000?text=Park+Hyatt+1',
          'https://via.placeholder.com/600x400/D2B48C/000000?text=Park+Hyatt+2',
          'https://via.placeholder.com/600x400/D2B48C/000000?text=Park+Hyatt+3',
          'https://via.placeholder.com/600x400/D2B48C/000000?text=Park+Hyatt+4',
          'https://via.placeholder.com/600x400/D2B48C/000000?text=Park+Hyatt+5'
        ],
        'location': 'Siem Reap City',
        'latitude': 13.3540,
        'longitude': 103.8569,
        'rating': 5.0,
        'priceRange': '\$\$\$\$\$',
        'amenities': ['Spa', 'Multiple Pools', 'Fine Dining', 'Gym', 'Concierge', 'Art Gallery']
      },
    ]
  },
  {
    'name': 'Banteay Meanchey',
    'details': 'Known for ancient temples and vibrant markets.',
    'places': [
      {
        'name': 'Poipet Market',
        'shortDescription': 'A bustling market near the Thai border.',
        'fullDescription': 'Poipet Market is a vibrant hub of commerce, offering a wide array of goods from local produce to electronics. It\'s a fascinating place to experience local life and bargain for souvenirs, particularly popular due to its proximity to the Thai border and casinos. Be prepared for a lively atmosphere.',
        'imageUrls': [
          'https://via.placeholder.com/600x400/33FF57/000000?text=Poipet+Market+1', // Changed to a valid placeholder
          'https://via.placeholder.com/600x400/33FF57/000000?text=Poipet+Market+2',
          'https://via.placeholder.com/600x400/33FF57/000000?text=Poipet+Market+3',
          'https://via.placeholder.com/600x400/33FF57/000000?text=Poipet+Market+4',
          'https://via.placeholder.com/600x400/33FF57/000000?text=Poipet+Market+5'
        ],
        'location': 'Poipet City',
        'latitude': 13.6599,
        'longitude': 102.5539,
        'bestTime': 'Morning for the busiest atmosphere.'
      },
    ],
    'hotels': [
      {
        'name': 'Grand Diamond City Hotel',
        'shortDescription': 'Luxury stay with modern amenities.',
        'fullDescription': 'The Grand Diamond City Hotel offers a luxurious experience with spacious rooms, a casino, multiple dining options, and excellent service, popular with visitors from Thailand and those seeking entertainment and comfort near the border. It\'s one of the largest resorts in the area.',
        'imageUrls': [
          'https://via.placeholder.com/600x400/5733FF/FFFFFF?text=Grand+Diamond+1',
          'https://via.placeholder.com/600x400/5733FF/FFFFFF?text=Grand+Diamond+2',
          'https://via.placeholder.com/600x400/5733FF/FFFFFF?text=Grand+Diamond+3'
        ],
        'location': 'Poipet City',
        'latitude': 13.6590,
        'longitude': 102.5550,
        'rating': 4.0,
        'priceRange': '\$\$ - \$\$\$',
        'amenities': ['Casino', 'Spa', 'Pool', 'Multiple Restaurants', 'Fitness Center', 'Conference Facilities']
      },
    ]
  },
  {
    'name': 'Battambang',
    'details': 'Famous for colonial architecture and bamboo train.',
    'places': [
      {
        'name': 'Bamboo Train',
        'shortDescription': 'Unique rail experience through countryside.',
        'fullDescription': 'The Bamboo Train (Norry) is a fascinating, makeshift rail vehicle that offers a thrilling and scenic ride through the Cambodian countryside, passing rice paddies and villages. It\'s a must-do experience for its unique charm and a great way to see rural life and local landscapes up close.',
        'imageUrls': [
          'https://via.placeholder.com/600x400/FF33A1/FFFFFF?text=Bamboo+Train+1',
          'https://via.placeholder.com/600x400/FF33A1/FFFFFF?text=Bamboo+Train+2',
          'https://via.placeholder.com/600x400/FF33A1/FFFFFF?text=Bamboo+Train+3',
          'https://via.placeholder.com/600x400/FF33A1/FFFFFF?text=Bamboo+Train+4',
          'https://via.placeholder.com/600x400/FF33A1/FFFFFF?text=Bamboo+Train+5'
        ],
        'location': 'O Dambong',
        'latitude': 13.0640,
        'longitude': 103.1110,
        'bestTime': 'Morning or late afternoon.',
        'priceRange': '\$'
      },
      {
        'name': 'Phnom Sampeau',
        'shortDescription': 'Hill with caves and bat caves.',
        'fullDescription': 'Phnom Sampeau is a historical and natural site featuring a temple on top of a hill, caves (including the Killing Caves), and a spectacular bat exodus at dusk. The views from the top are panoramic and stunning, especially at sunset when thousands of bats emerge from the caves.',
        'imageUrls': [
          'https://via.placeholder.com/600x400/008080/FFFFFF?text=Phnom+Sampeau+1',
          'https://via.placeholder.com/600x400/008080/FFFFFF?text=Phnom+Sampeau+2',
          'https://via.placeholder.com/600x400/008080/FFFFFF?text=Phnom+Sampeau+3',
          'https://via.placeholder.com/600x400/008080/FFFFFF?text=Phnom+Sampeau+4',
          'https://via.placeholder.com/600x400/008080/FFFFFF?text=Phnom+Sampeau+5'
        ],
        'location': 'Battambang',
        'latitude': 12.9680,
        'longitude': 103.0410,
        'bestTime': 'Late afternoon for bats.'
      },
    ],
    'hotels': [
      {
        'name': 'Au Cabaret Vert',
        'shortDescription': 'Eco-friendly hotel with a pool.',
        'fullDescription': 'This eco-lodge offers a serene escape with beautiful gardens, a large swimming pool, and a focus on sustainability, providing a peaceful retreat. It\'s praised for its natural setting and relaxed vibe, making it a favorite for ethical travelers seeking tranquility and comfort.',
        'imageUrls': [
          'https://via.placeholder.com/600x400/FFA500/FFFFFF?text=Au+Cabaret+Vert+1',
          'https://via.placeholder.com/600x400/FFA500/FFFFFF?text=Au+Cabaret+Vert+2',
          'https://via.placeholder.com/600x400/FFA500/FFFFFF?text=Au+Cabaret+Vert+3'
        ],
        'location': 'Battambang City',
        'latitude': 13.1023,
        'longitude': 103.2005,
        'rating': 4.5,
        'priceRange': '\$\$\$',
        'amenities': ['Pool', 'Garden', 'Restaurant', 'Bike Rental', 'Eco-friendly initiatives', 'Bar']
      },
    ]
  },
  {
    'name': 'Kampong Cham',
    'details': 'A vibrant province along the Mekong River, known for its rubber plantations and historical sites.',
    'places': [
      {
        'name': 'Nokor Bachey Temple',
        'shortDescription': 'An ancient temple with a modern pagoda.',
        'fullDescription': 'Nokor Bachey Temple is a significant historical site with an ancient brick temple from the 11th century nestled within the grounds of a modern pagoda, offering a blend of old and new. It\'s a popular pilgrimage site for locals and visitors alike, showcasing unique Khmer architecture and religious continuity.',
        'imageUrls': [
          'https://via.placeholder.com/600x400/FF00FF/FFFFFF?text=Nokor+Bachey+1',
          'https://via.placeholder.com/600x400/FF00FF/FFFFFF?text=Nokor+Bachey+2',
          'https://via.placeholder.com/600x400/FF00FF/FFFFFF?text=Nokor+Bachey+3',
          'https://via.placeholder.com/600x400/FF00FF/FFFFFF?text=Nokor+Bachey+4',
          'https://via.placeholder.com/600x400/FF00FF/FFFFFF?text=Nokor+Bachey+5'
        ],
        'location': 'Kampong Cham City',
        'latitude': 11.9790,
        'longitude': 105.4410,
        'bestTime': 'Daytime.'
      },
    ],
    'hotels': [
      {
        'name': 'Mekong Hotel',
        'shortDescription': 'Riverside hotel with comfortable rooms.',
        'fullDescription': 'The Mekong Hotel offers pleasant accommodation with views of the Mekong River, popular for its central location and easy access to the city\'s attractions. It\'s a good base for exploring the province and enjoying riverside tranquility, with some rooms offering direct river views.',
        'imageUrls': [
          'https://via.placeholder.com/600x400/ADFF2F/000000?text=Mekong+Hotel+1',
          'https://via.placeholder.com/600x400/ADFF2F/000000?text=Mekong+Hotel+2'
        ],
        'location': 'Kampong Cham City',
        'latitude': 11.9967,
        'longitude': 105.4633,
        'rating': 3.5,
        'priceRange': '\$\$',
        'amenities': ['River View', 'Restaurant', 'Free Wi-Fi', 'Terrace', 'Laundry Service']
      },
    ]
  },
  {
    'name': 'Kampong Chhnang',
    'details': 'Noted for pottery and floating villages.',
    'places': [
      {
        'name': 'Floating Villages',
        'shortDescription': 'Unique water communities on Tonle Sap.',
        'fullDescription': 'The floating villages near Kampong Chhnang offer a fascinating glimpse into a unique way of life, where homes, schools, and markets are built directly on the water of the Tonle Sap Lake. Boat tours are available to explore these vibrant communities and witness their daily activities.',
        'imageUrls': [
          'https://via.placeholder.com/600x400/00CED1/FFFFFF?text=Floating+Village+1', // Changed to a valid placeholder
          'https://via.placeholder.com/600x400/00CED1/FFFFFF?text=Floating+Village+2',
          'https://via.placeholder.com/600x400/00CED1/FFFFFF?text=Floating+Village+3',
          'https://via.placeholder.com/600x400/00CED1/FFFFFF?text=Floating+Village+4',
          'https://via.placeholder.com/600x400/00CED1/FFFFFF?text=Floating+Village+5'
        ],
        'location': 'Tonle Sap Lake',
        'latitude': 12.2420,
        'longitude': 104.5950,
        'bestTime': 'Wet season for higher water levels.'
      },
    ],
    'hotels': [
      {
        'name': 'Chhnang Rose Guesthouse',
        'shortDescription': 'Simple stay with local flavor.',
        'fullDescription': 'A straightforward and friendly guesthouse, providing comfortable and clean accommodation for budget travelers exploring Kampong Chhnang. It offers a good base for local excursions and cultural experiences in a quiet setting.',
        'imageUrls': [
          'https://via.placeholder.com/600x400/FFD700/000000?text=Chhnang+Rose+1'
        ],
        'location': 'Kampong Chhnang',
        'latitude': 12.2618,
        'longitude': 104.6644,
        'rating': 3.0,
        'priceRange': '\$'
      },
    ]
  },
  {
    'name': 'Kampong Speu',
    'details': 'Known for palm sugar and scenic landscapes.',
    'places': [
      {
        'name': 'Kirirom National Park',
        'shortDescription': 'Pine forests and waterfalls.',
        'fullDescription': 'Kirirom National Park is a beautiful highland area covered in pine forests, offering cool temperatures, waterfalls, and hiking trails. It\'s a refreshing escape from the heat of the plains, popular for ecotourism and nature walks, with diverse flora and fauna.',
        'imageUrls': [
          'https://via.placeholder.com/600x400/ADFF2F/000000?text=Kirirom+Park+1',
          'https://via.placeholder.com/600x400/ADFF2F/000000?text=Kirirom+Park+2',
          'https://via.placeholder.com/600x400/ADFF2F/000000?text=Kirirom+Park+3',
          'https://via.placeholder.com/600x400/ADFF2F/000000?text=Kirirom+Park+4',
          'https://via.placeholder.com/600x400/ADFF2F/000000?text=Kirirom+Park+5'
        ],
        'location': 'Kirirom',
        'latitude': 11.4500,
        'longitude': 104.0500,
        'bestTime': 'Cooler months (November to February).'
      },
    ],
    'hotels': [
      {
        'name': 'Kirirom Hillside Resort',
        'shortDescription': 'Nature retreat with stunning views.',
        'fullDescription': 'A serene resort located within Kirirom National Park, offering bungalows and villas amidst pine trees with beautiful mountain views, perfect for nature lovers and those seeking a peaceful getaway. It provides an immersive experience in the Cambodian highlands.',
        'imageUrls': [
          'https://via.placeholder.com/600x400/87CEEB/000000?text=Kirirom+Hillside+1',
          'https://via.placeholder.com/600x400/87CEEB/000000?text=Kirirom+Hillside+2'
        ],
        'location': 'Kirirom National Park',
        'latitude': 11.4300,
        'longitude': 104.0400,
        'rating': 4.0,
        'priceRange': '\$\$\$',
        'amenities': ['Nature Trails', 'Restaurant', 'Fresh Air', 'Hiking Access', 'Private Balconies']
      },
    ]
  },
  {
    'name': 'Kampong Thom',
    'details': 'Home to ancient Sambor Prei Kuk temples.',
    'places': [
      {
        'name': 'Sambor Prei Kuk',
        'shortDescription': 'Pre-Angkorian temple ruins.',
        'fullDescription': 'Sambor Prei Kuk is a UNESCO World Heritage site featuring a vast complex of pre-Angkorian temples (7th-10th centuries), offering a peaceful and historically significant exploration away from the crowds of Angkor. Its brick structures provide a unique perspective on early Khmer architecture.',
        'imageUrls': [
          'https://via.placeholder.com/600x400/FF4500/FFFFFF?text=Sambor+Prei+Kuk+1',
          'https://via.placeholder.com/600x400/FF4500/FFFFFF?text=Sambor+Prei+Kuk+2',
          'https://via.placeholder.com/600x400/FF4500/FFFFFF?text=Sambor+Prei+Kuk+3',
          'https://via.placeholder.com/600x400/FF4500/FFFFFF?text=Sambor+Prei+Kuk+4',
          'https://via.placeholder.com/600x400/FF4500/FFFFFF?text=Sambor+Prei+Kuk+5'
        ],
        'location': 'Kampong Thom',
        'latitude': 12.8710,
        'longitude': 105.9220,
        'bestTime': 'Dry season.'
      },
    ],
    'hotels': [
      {
        'name': 'Sambor Village Hotel',
        'shortDescription': 'Boutique hotel with pool.',
        'fullDescription': 'Sambor Village Hotel offers a charming and tranquil retreat with lush gardens, a swimming pool, and comfortable rooms, providing a great base for visiting Sambor Prei Kuk and exploring Kampong Thom. Its traditional design offers an authentic Cambodian feel.',
        'imageUrls': [
          'https://via.placeholder.com/600x400/BA55D3/FFFFFF?text=Sambor+Village+1',
          'https://via.placeholder.com/600x400/BA55D3/FFFFFF?text=Sambor+Village+2',
          'https://via.placeholder.com/600x400/BA55D3/FFFFFF?text=Sambor+Village+3'
        ],
        'location': 'Kampong Thom City',
        'latitude': 12.7100,
        'longitude': 104.9910,
        'rating': 4.0,
        'priceRange': '\$\$\$',
        'amenities': ['Pool', 'Garden', 'Restaurant', 'Free Wi-Fi', 'Bicycle Rental', 'Terrace']
      },
    ]
  },
  {
    'name': 'Kampot',
    'details': 'Famous for its pepper, salt fields, and charming French colonial architecture.',
    'places': [
      {
        'name': 'Bokor National Park',
        'shortDescription': 'Mountain resort with old French buildings and waterfalls.',
        'fullDescription': 'Bokor National Park offers a cool escape to a mountain plateau with historical remnants like the old French casino, a church, and beautiful waterfalls like Popokvil Waterfall. The park provides stunning views and a refreshing climate, along with a misty, atmospheric ambiance.',
        'imageUrls': [
          'https://via.placeholder.com/600x400/C70039/FFFFFF?text=Bokor+Park+1',
          'https://via.placeholder.com/600x400/C70039/FFFFFF?text=Bokor+Park+2',
          'https://via.placeholder.com/600x400/C70039/FFFFFF?text=Bokor+Park+3',
          'https://via.placeholder.com/600x400/C70039/FFFFFF?text=Bokor+Park+4',
          'https://via.placeholder.com/600x400/C70039/FFFFFF?text=Bokor+Park+5'
        ],
        'location': 'Bokor Mountain',
        'latitude': 10.7490,
        'longitude': 104.0930,
        'bestTime': 'Anytime, cooler weather.'
      },
    ],
    'hotels': [
      {
        'name': 'Bokor Palace Hotel',
        'shortDescription': 'Historic hotel on Bokor Mountain.',
        'fullDescription': 'The renovated Bokor Palace Hotel offers a unique stay with historical charm and panoramic views from Bokor Mountain, blending colonial elegance with modern comforts. It\'s an iconic landmark within the national park, providing a luxurious experience with a rich past.',
        'imageUrls': [
          'https://via.placeholder.com/600x400/DAF7A6/000000?text=Bokor+Palace+1',
          'https://via.placeholder.com/600x400/DAF7A6/000000?text=Bokor+Palace+2',
          'https://via.placeholder.com/600x400/DAF7A6/000000?text=Bokor+Palace+3'
        ],
        'location': 'Bokor Mountain',
        'latitude': 10.7480,
        'longitude': 104.0920,
        'rating': 4.0,
        'priceRange': '\$\$\$',
        'amenities': ['History', 'Panoramic Views', 'Restaurant', 'Cafe', 'Bar', 'Spa']
      },
    ]
  },
  {
    'name': 'Kandal',
    'details': 'Surrounding Phnom Penh, Kandal is known for its agriculture and silk weaving.',
    'places': [
      {
        'name': 'Koh Dach (Silk Island)',
        'shortDescription': 'Island known for traditional silk weaving and handicrafts.',
        'fullDescription': 'Take a ferry to Koh Dach, a tranquil island famous for its traditional silk weaving, pottery, and farming. It\'s a great place to cycle and observe local crafts, offering a peaceful rural escape from the city and a chance to buy handmade souvenirs.',
        'imageUrls': [
          'https://via.placeholder.com/600x400/FFC300/000000?text=Silk+Island+1',
          'https://via.placeholder.com/600x400/FFC300/000000?text=Silk+Island+2',
          'https://via.placeholder.com/600x400/FFC300/000000?text=Silk+Island+3'
        ],
        'location': 'Mekong River near Phnom Penh',
        'latitude': 11.7100,
        'longitude': 104.9700,
        'bestTime': 'Anytime.'
      },
    ],
    'hotels': [
      {
        'name': 'Kandal Riverside Guesthouse',
        'shortDescription': 'Simple guesthouse by the river.',
        'fullDescription': 'A basic and clean guesthouse offering affordable accommodation along the river, providing easy access to local transport and a feel for rural life. Ideal for budget travelers seeking tranquility and a glimpse into authentic Cambodian living.',
        'imageUrls': [
          'https://via.placeholder.com/600x400/C0C0C0/000000?text=Kandal+Guesthouse+1'
        ],
        'location': 'Various in Kandal',
        'latitude': 11.4500,
        'longitude': 104.9100,
        'rating': 2.5,
        'priceRange': '\$'
      },
    ]
  },
  {
    'name': 'Kep',
    'details': 'A small coastal province famous for its fresh seafood, particularly crab, and serene beaches.',
    'places': [
      {
        'name': 'Kep Crab Market',
        'shortDescription': 'A vibrant market where you can buy and eat fresh crab.',
        'fullDescription': 'The iconic Kep Crab Market is a must-visit for seafood lovers. Enjoy freshly caught crabs and other seafood cooked to order at the many seaside shacks and restaurants. It\'s a true culinary highlight of Kep, offering a lively atmosphere and delicious fresh produce.',
        'imageUrls': [
          'https://via.placeholder.com/600x400/FF0000/FFFFFF?text=Crab+Market+1',
          'https://via.placeholder.com/600x400/FF0000/FFFFFF?text=Crab+Market+2',
          'https://via.placeholder.com/600x400/FF0000/FFFFFF?text=Crab+Market+3',
          'https://via.placeholder.com/600x400/FF0000/FFFFFF?text=Crab+Market+4',
          'https://via.placeholder.com/600x400/FF0000/FFFFFF?text=Crab+Market+5'
        ],
        'location': 'Kep City',
        'latitude': 10.4850,
        'longitude': 104.3000,
        'bestTime': 'Lunch or dinner.'
      },
    ],
    'hotels': [
      {
        'name': 'Knai Bang Chatt',
        'shortDescription': 'Luxury resort with ocean views.',
        'fullDescription': 'Knai Bang Chatt is an exquisite luxury resort built with an emphasis on minimalist design and sustainability, offering stunning ocean views, a private beach, and exceptional service. It\'s renowned for its unique architecture and serene environment, perfect for a high-end escape and relaxation.',
        'imageUrls': [
          'https://via.placeholder.com/600x400/0000FF/FFFFFF?text=Knai+Bang+Chatt+1',
          'https://via.placeholder.com/600x400/0000FF/FFFFFF?text=Knai+Bang+Chatt+2',
          'https://via.placeholder.com/600x400/0000FF/FFFFFF?text=Knai+Bang+Chatt+3'
        ],
        'location': 'Kep City',
        'latitude': 10.4800,
        'longitude': 104.3100,
        'rating': 5.0,
        'priceRange': '\$\$\$\$\$',
        'amenities': ['Private Beach', 'Infinity Pool', 'Spa', 'Fine Dining', 'Sailing', 'Yoga']
      },
    ]
  },

    // this
    {
      'name': 'Koh Kong',
      'details': 'A largely undeveloped coastal province with rich biodiversity, rainforests, and waterfalls.',
      'places': [
        {
          'name': 'Koh Kong Island',
          'shortDescription': 'One of Cambodia\'s largest islands, offering pristine beaches and jungle.',
          'fullDescription': 'Koh Kong Island is a large, sparsely populated island with pristine beaches and lush jungle, ideal for unspoiled nature exploration, snorkeling, and relaxation. It\'s a true untouched paradise for eco-tourists and adventurers.',
          'imageUrls': [
            'https://via.placeholder.com/600x400/20B2AA/FFFFFF?text=Koh+Kong+Island+1',
            'https://via.placeholder.com/600x400/20B2AA/FFFFFF?text=Koh+Kong+Island+2',
            'https://via.placeholder.com/600x400/20B2AA/FFFFFF?text=Koh+Kong+Island+3',
            'https://via.placeholder.com/600x400/20B2AA/FFFFFF?text=Koh+Kong+Island+4',
            'https://via.placeholder.com/600x400/20B2AA/FFFFFF?text=Koh+Kong+Island+5'
          ],
          'location': 'Off Koh Kong Coast',
          'latitude': 10.9600,
          'longitude': 103.0000,
          'bestTime': 'Dry season.'
        },
      ],
      'hotels': [
        {
          'name': '4 Rivers Floating Lodge',
          'shortDescription': 'Luxury eco-lodge on the Tataii River.',
          'fullDescription': '4 Rivers Floating Lodge offers exclusive luxury tented villas that float on the tranquil Tataii River, providing an intimate and immersive eco-tourism experience within the Cardamom Mountains. It\'s an unforgettable stay for nature lovers seeking comfort and unique accommodation.',
          'imageUrls': [
            'https://via.placeholder.com/600x400/48D1CC/000000?text=4+Rivers+1',
            'https://via.placeholder.com/600x400/48D1CC/000000?text=4+Rivers+2',
            'https://via.placeholder.com/600x400/48D1CC/000000?text=4+Rivers+3',
            'https://via.placeholder.com/600x400/48D1CC/000000?text=4+Rivers+4',
            'https://via.placeholder.com/600x400/48D1CC/000000?text=4+Rivers+5'
          ],
          'location': 'Tataii River',
          'latitude': 11.5833,
          'longitude': 103.3500,
          'rating': 4.9,
          'priceRange': '\$\$\$\$\$',
          'amenities': ['Floating Villas', 'Eco-friendly', 'Kayaking', 'Fine Dining', 'Wildlife Tours', 'Bar']
        },
      ]
    },
    {
      'name': 'Kratié',
      'details': 'Known for its Irrawaddy dolphins and riverside scenery along the Mekong.',
      'places': [
        {
          'name': 'Phnom Sambok',
          'shortDescription': 'Hilltop pagoda with panoramic views.',
          'fullDescription': 'Phnom Sambok is a hill with a pagoda at its summit, offering serene views of the Mekong River and surrounding countryside. It\'s a peaceful spiritual site with a rich local history and often visited for contemplation and panoramic views, especially at sunset.',
          'imageUrls': [
            'https://via.placeholder.com/600x400/008080/FFFFFF?text=Phnom+Sambok+1'
          ],
          'location': 'Kratié',
          'latitude': 12.5000,
          'longitude': 106.0000,
          'bestTime': 'Daytime.'
        },
      ],
      'hotels': [
        {
          'name': 'River Dolphin Hotel',
          'shortDescription': 'Comfortable hotel with river views.',
          'fullDescription': 'River Dolphin Hotel offers comfortable rooms, many with views of the Mekong River, and is conveniently located for dolphin spotting tours and exploring Kratié town. It provides a relaxing stay with natural beauty nearby and easy access to the riverfront promenade.',
          'imageUrls': [
            'https://via.placeholder.com/600x400/40E0D0/000000?text=River+Dolphin+Hotel+1',
            'https://via.placeholder.com/600x400/40E0D0/000000?text=River+Dolphin+Hotel+2'
          ],
          'location': 'Kratié City',
          'latitude': 12.4867,
          'longitude': 106.0177,
          'rating': 3.5,
          'priceRange': '\$\$',
          'amenities': ['River View', 'Restaurant', 'Free Wi-Fi', 'Terrace']
        },
      ]
    },
    {
      'name': 'Mondulkiri',
      'details': 'A highland province known for its rolling hills, waterfalls, and elephant conservation projects.',
      'places': [
        {
          'name': 'Sea Forest',
          'shortDescription': 'Panoramic view of endless green hills.',
          'fullDescription': 'The Sea Forest viewpoint offers a breathtaking panoramic vista of rolling green hills that stretch as far as the eye can see, resembling a vast green ocean. It\'s a prime spot for photography and enjoying the vastness of nature, especially at sunrise or sunset.',
          'imageUrls': [
            'https://via.placeholder.com/600x400/ADD8E6/000000?text=Sea+Forest+1',
            'https://via.placeholder.com/600x400/ADD8E6/000000?text=Sea+Forest+2',
            'https://via.placeholder.com/600x400/ADD8E6/000000?text=Sea+Forest+3'
          ],
          'location': 'Sen Monorom',
          'latitude': 12.4800,
          'longitude': 107.1800,
          'bestTime': 'Sunrise or sunset.'
        },
      ],
      'hotels': [
        {
          'name': 'Mayura Hill Resort',
          'shortDescription': 'Luxury eco-friendly resort.',
          'fullDescription': 'Mayura Hill Resort offers luxurious, eco-friendly bungalows nestled in lush gardens, providing a peaceful retreat with stunning views and a focus on sustainable tourism practices. It\'s an ideal choice for a serene and responsible stay, highly rated for its service and natural beauty.',
          'imageUrls': [
            'https://via.placeholder.com/600x400/DDA0DD/FFFFFF?text=Mayura+Hill+Resort+1',
            'https://via.placeholder.com/600x400/DDA0DD/FFFFFF?text=Mayura+Hill+Resort+2',
            'https://via.placeholder.com/600x400/DDA0DD/FFFFFF?text=Mayura+Hill+Resort+3'
          ],
          'location': 'Sen Monorom',
          'latitude': 12.4700,
          'longitude': 107.1900,
          'rating': 4.8,
          'priceRange': '\$\$\$\$',
          'amenities': ['Eco-friendly', 'Pool', 'Restaurant', 'Views', 'Garden', 'Spa Services']
        },
      ]
    },
    {
      'name': 'Oddar Meanchey',
      'details': 'A northern province with historical significance and border crossings.',
      'places': [
        {
          'name': 'O\'Smach Border Market',
          'shortDescription': 'Bustling border market.',
          'fullDescription': 'O\'Smach Border Market is a lively commercial zone at the Thai border, offering a variety of goods and a glimpse into cross-border trade and local life. It\'s a vibrant and active place, particularly busy on market days.',
          'imageUrls': [
            'https://via.placeholder.com/600x400/F08080/FFFFFF?text=OSmach+Market+1'
          ],
          'location': 'O\'Smach',
          'latitude': 14.3500,
          'longitude': 103.5800,
          'bestTime': 'Daytime.'
        },
      ],
      'hotels': [
        {
          'name': 'O\'Smach Hotel & Casino',
          'shortDescription': 'Accommodation near the border.',
          'fullDescription': 'O\'Smach Hotel & Casino provides accommodation and entertainment options for visitors to the border town, primarily catering to those crossing into Thailand and seeking a convenient stay with access to gaming facilities.',
          'imageUrls': [
            'https://via.placeholder.com/600x400/FAEBD7/000000?text=OSmach+Hotel+1'
          ],
          'location': 'O\'Smach',
          'latitude': 14.3520,
          'longitude': 103.5820,
          'rating': 3.0,
          'priceRange': '\$\$'
        },
      ]
    },
    {
      'name': 'Pursat',
      'details': 'A large province in western Cambodia, featuring parts of the Cardamom Mountains and Tonle Sap Lake.',
      'places': [
        {
          'name': 'Phnom Kravanh',
          'shortDescription': 'A mountain known for its a cave system and religious sites.',
          'fullDescription': 'Phnom Kravanh is a mountain featuring a fascinating cave system and several religious shrines, offering both natural exploration and cultural insight. It\'s a site of local pilgrimage and natural beauty.',
          'imageUrls': [
            'https://via.placeholder.com/600x400/CD853F/FFFFFF?text=Phnom+Kravanh+1'
          ],
          'location': 'Pursat',
          'latitude': 12.0000,
          'longitude': 103.4500,
          'bestTime': 'Daytime.'
        },
      ],
      'hotels': [
        {
          'name': 'Lotus Resort',
          'shortDescription': 'A tranquil resort amidst nature.',
          'fullDescription': 'Lotus Resort provides a peaceful and comfortable stay amidst natural surroundings, offering a serene escape for relaxation and easy access to local attractions. It\'s ideal for those seeking tranquility and a connection with nature.',
          'imageUrls': [
            'https://via.placeholder.com/600x400/D2B48C/000000?text=Lotus+Resort+1'
          ],
          'location': 'Pursat',
          'latitude': 12.5500,
          'longitude': 103.9000,
          'rating': 3.8,
          'priceRange': '\$\$'
        },
      ]
    },
    {
      'name': 'Ratanakiri',
      'details': 'A remote northeastern province known for its rugged landscapes, crater lakes, and indigenous communities.',
      'places': [
        {
          'name': 'Norng Kabat Waterfall',
          'shortDescription': 'Scenic waterfall surrounded by jungle.',
          'fullDescription': 'Norng Kabat is one of the many picturesque waterfalls in Ratanakiri, offering a refreshing natural experience amidst the dense jungle, ideal for those exploring the region\'s natural beauty and seeking tranquility. It\'s a serene spot for relaxation.',
          'imageUrls': [
            'https://via.placeholder.com/600x400/C71585/FFFFFF?text=Norng+Kabat+1',
            'https://via.placeholder.com/600x400/C71585/FFFFFF?text=Norng+Kabat+2'
          ],
          'location': 'Banlung outskirts',
          'latitude': 13.8000,
          'longitude': 106.9000,
          'bestTime': 'Wet season for fuller flow.'
        },
      ],
      'hotels': [
        {
          'name': 'Ratanakiri Bousra Resort',
          'shortDescription': 'Offers bungalows near the famous Bousra Waterfall.',
          'fullDescription': 'Ratanakiri Bousra Resort provides comfortable bungalows and amenities near the famous Bousra Waterfall, offering a convenient base for exploring the area\'s natural attractions and cooler climate. It\'s a good choice for nature-focused travelers.',
          'imageUrls': [
            'https://via.placeholder.com/600x400/FF69B4/FFFFFF?text=Bousra+Resort+1'
          ],
          'location': 'Near Bousra Waterfall',
          'latitude': 12.4790,
          'longitude': 107.4100,
          'rating': 3.5,
          'priceRange': '\$\$\$',
          'amenities': ['Near Waterfall', 'Restaurant', 'Garden', 'Outdoor Seating']
        },
      ]
    },
    {
      'name': 'Stung Treng',
      'details': 'A remote northeastern province, known for its Mekong River scenery and ecotourism opportunities.',
      'places': [
        {
          'name': 'Thala Barivat',
          'shortDescription': 'Ancient temple ruins near the Mekong.',
          'fullDescription': 'Thala Barivat features ancient temple ruins that offer a glimpse into Cambodia\'s past, situated in a serene setting near the Mekong River, ideal for historical exploration and quiet reflection. It provides a peaceful contrast to bustling city life.',
          'imageUrls': [
            'https://via.placeholder.com/600x400/87CEEB/000000?text=Thala+Barivat+1'
          ],
          'location': 'Stung Treng',
          'latitude': 13.8800,
          'longitude': 106.0000,
          'bestTime': 'Daytime.'
        },
      ],
      'hotels': [
        {
          'name': 'Sekong Hotel',
          'shortDescription': 'Riverside hotel with comfortable amenities.',
          'fullDescription': 'Sekong Hotel offers comfortable rooms and a prime location overlooking the Sekong River, a tributary of the Mekong, providing a relaxing stay and easy access to local attractions and boat tours. It\'s a popular choice for river views.',
          'imageUrls': [
            'https://via.placeholder.com/600x400/4682B4/FFFFFF?text=Sekong+Hotel+1'
          ],
          'location': 'Stung Treng City',
          'latitude': 13.5270,
          'longitude': 105.9960,
          'rating': 3.2,
          'priceRange': '\$\$'
        },
      ]
    },
    {
      'name': 'Svay Rieng',
      'details': 'An eastern province bordering Vietnam, primarily agricultural with some historical sites.',
      'places': [
        {
          'name': 'Svay Rieng Museum',
          'shortDescription': 'Small museum showcasing local history and culture.',
          'fullDescription': 'The Svay Rieng Museum offers insights into the local history, culture, and agricultural traditions of the province, providing a brief but informative stop for those interested in regional heritage.',
          'imageUrls': [
            'https://via.placeholder.com/600x400/7B68EE/FFFFFF?text=Svay+Rieng+Museum+1'
          ],
          'location': 'Svay Rieng City',
          'latitude': 11.0900,
          'longitude': 105.7900,
          'bestTime': 'Daytime.'
        },
      ],
      'hotels': [
        {
          'name': 'Green Hotel Svay Rieng',
          'shortDescription': 'Modern hotel with good facilities.',
          'fullDescription': 'Green Hotel Svay Rieng provides modern facilities and comfortable accommodation, catering to both business and leisure travelers visiting the province. It offers a contemporary stay with essential amenities.',
          'imageUrls': [
            'https://via.placeholder.com/600x400/483D8B/FFFFFF?text=Green+Hotel+1'
          ],
          'location': 'Svay Rieng City',
          'latitude': 11.0800,
          'longitude': 105.7900,
          'rating': 3.7,
          'priceRange': '\$\$\$',
          'amenities': ['Restaurant', 'Fitness Center', 'Conference Room', 'Free Wi-Fi']
        },
      ]
    },
    {
      'name': 'Takéo',
      'details': 'A southern province known for its ancient Funan-era sites and rich history.',
      'places': [
        {
          'name': 'Chisor Mountain Temple',
          'shortDescription': '11th-century temple on a hilltop with panoramic views.',
          'fullDescription': 'Prasat Neang Khmau (Chisor Mountain Temple) is an ancient 11th-century temple located on a hilltop, offering panoramic views of the Cambodian countryside and a peaceful, historically rich experience. It\'s a popular pilgrimage site with unique architectural features.',
          'imageUrls': [
            'https://via.placeholder.com/600x400/B22222/FFFFFF?text=Chisor+Temple+1',
            'https://via.placeholder.com/600x400/B22222/FFFFFF?text=Chisor+Temple+2'
          ],
          'location': 'Takéo Province',
          'latitude': 10.9990,
          'longitude': 104.7890,
          'bestTime': 'Morning.'
        },
      ],
      'hotels': [
        {
          'name': 'Takeo Royal Hotel',
          'shortDescription': 'A comfortable hotel in Takeo City.',
          'fullDescription': 'Takeo Royal Hotel offers comfortable rooms and a pleasant stay in Takeo City, suitable for both short visits and longer stays for those exploring the province\'s historical and natural sites. It provides a good balance of comfort and convenience.',
          'imageUrls': [
            'https://via.placeholder.com/600x400/DC143C/FFFFFF?text=Takeo+Royal+Hotel+1'
          ],
          'location': 'Takeo City',
          'latitude': 10.9900,
          'longitude': 104.7900,
          'rating': 3.3,
          'priceRange': '\$\$'
        },
      ]
    },
    {
      'name': 'Tboung Khmum',
      'details': 'A newly formed province, separated from Kampong Cham, known for its agriculture and local life.',
      'places': [
        {
          'name': 'Rubber Plantation Tour',
          'shortDescription': 'Experience rubber harvesting and processing.',
          'fullDescription': 'Take a guided tour through vast rubber plantations to learn about the process of rubber tapping and production. It offers an insightful look into a key agricultural industry of the province and a unique rural experience.',
          'imageUrls': [
            'https://via.placeholder.com/600x400/FF6347/FFFFFF?text=Rubber+Tour+1',
            'https://via.placeholder.com/600x400/FF6347/FFFFFF?text=Rubber+Tour+2',
            'https://via.placeholder.com/600x400/FF6347/FFFFFF?text=Rubber+Tour+3'
          ],
          'location': 'Various',
          'latitude': 11.9000,
          'longitude': 105.8000,
          'bestTime': 'Anytime.'
        },
      ],
      'hotels': [
        {
          'name': 'Suong Riverside Hotel',
          'shortDescription': 'Comfortable hotel by the river in Suong.',
          'fullDescription': 'Suong Riverside Hotel offers pleasant accommodation with views of the river, providing a comfortable and serene stay in the provincial capital. It\'s a good option for both business and leisure travelers.',
          'imageUrls': [
            'https://via.placeholder.com/600x400/FF7F50/FFFFFF?text=Suong+Riverside+1'
          ],
          'location': 'Suong City',
          'latitude': 11.9610,
          'longitude': 105.7950,
          'rating': 3.6,
          'priceRange': '\$\$'
        },
      ]
    },
    {
      'name': 'Kampong Thom',
      'details': 'Home to ancient Sambor Prei Kuk temples and a rich historical heritage.',
      'places': [
        {
          'name': 'Prasat Andet Temple',
          'shortDescription': 'Ancient temple ruins near the river.',
          'fullDescription': 'Prasat Andet is a small but significant brick temple ruin dating back to the 7th-8th century, offering a glimpse into pre-Angkorian architecture and historical artistry. It provides a peaceful and less-visited historical site.',
          'imageUrls': [
            'https://via.placeholder.com/600x400/FF4500/FFFFFF?text=Prasat+Andet+1'
          ],
          'location': 'Kampong Thom',
          'latitude': 12.8000,
          'longitude': 105.0500,
          'bestTime': 'Daytime.'
        },
      ],
      'hotels': [
        {
          'name': 'Kampong Thom Grand Hotel',
          'shortDescription': 'A central hotel with good amenities.',
          'fullDescription': 'Kampong Thom Grand Hotel offers comfortable rooms and convenient facilities in the heart of Kampong Thom city, providing a good base for exploring Sambor Prei Kuk and other provincial attractions. It caters to various traveler needs.',
          'imageUrls': [
            'https://via.placeholder.com/600x400/BA55D3/FFFFFF?text=Kampong+Thom+Grand+Hotel+1'
          ],
          'location': 'Kampong Thom City',
          'latitude': 12.7100,
          'longitude': 104.9910,
          'rating': 3.5,
          'priceRange': '\$\$'
        },
      ]
    },
    {
      'name': 'Banteay Meanchey',
      'details': 'Known for ancient temples and vibrant markets.',
      'places': [
        {
          'name': 'Banteay Chhmar Temple',
          'shortDescription': 'A remote temple complex with intricate carvings.',
          'fullDescription': 'Banteay Chhmar is a sprawling, remote temple complex built by Jayavarman VII in the late 12th or early 13th century. It\'s known for its extensive bas-reliefs, especially one depicting a multi-armed Avalokiteshvara. It offers a glimpse into Cambodia\'s rich history away from the larger tourist crowds.',
          'imageUrls': [
            'https://via.placeholder.com/600x400/FF5733/FFFFFF?text=Banteay+Chhmar+1',
            'https://via.placeholder.com/600x400/FF5733/FFFFFF?text=Banteay+Chhmar+2',
            'https://via.placeholder.com/600x400/FF5733/FFFFFF?text=Banteay+Chhmar+3',
            'https://via.placeholder.com/600x400/FF5733/FFFFFF?text=Banteay+Chhmar+4',
            'https://via.placeholder.com/600x400/FF5733/FFFFFF?text=Banteay+Chhmar+5'
          ],
          'location': 'Thma Puok District',
          'latitude': 14.0800,
          'longitude': 103.0700,
          'bestTime': 'Dry season (November to April) for best access.'
        },
      ],
      'hotels': [
        {
          'name': 'Olympic Hotel & Casino',
          'shortDescription': 'Entertainment and stay near the border.',
          'fullDescription': 'Olympic Hotel & Casino offers a combination of accommodation and entertainment options, primarily catering to visitors interested in the casino and cross-border trade. It provides a convenient and bustling experience near the Thai border.',
          'imageUrls': [
            'https://via.placeholder.com/600x400/5733FF/FFFFFF?text=Olympic+Hotel+1'
          ],
          'location': 'Poipet',
          'latitude': 13.6595,
          'longitude': 102.5545,
          'rating': 3.8,
          'priceRange': '\$\$\$',
          'amenities': ['Casino', 'Restaurant', 'Spa', 'Entertainment']
        },
      ]
    },
  ];

  void _showProvinceDetails(String provinceName) {
    setState(() {
      selectedProvince = provinceName;
      showDetailedView = true;
      _visiblePlacesCount = 3; // Reset count when a new province is selected
      _visibleHotelsCount = 2; // Reset count when a new province is selected
    });
  }

  void _showMorePlaces() {
    setState(() {
      final selectedProvinceData = _provinces.firstWhere((p) => p['name'] == selectedProvince);
      final totalPlaces = (selectedProvinceData['places'] as List).length;
      // Increase visibility by 5, but not beyond the total number of places
      _visiblePlacesCount = (_visiblePlacesCount + 5).clamp(0, totalPlaces);
    });
  }

  void _showMoreHotels() {
    setState(() {
      final selectedProvinceData = _provinces.firstWhere((p) => p['name'] == selectedProvince);
      final totalHotels = (selectedProvinceData['hotels'] as List).length;
      // Increase visibility by 5, but not beyond the total number of hotels
      _visibleHotelsCount = (_visibleHotelsCount + 5).clamp(0, totalHotels);
    });
  }

  @override
Widget build(BuildContext context) {
  final selectedProvinceData = selectedProvince != null
      ? _provinces.firstWhere((p) => p['name'] == selectedProvince)
      : null;

  return Scaffold(
    appBar: AppBar(
      title: const Text('Trips Page', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
      backgroundColor: Colors.teal,
      elevation: 4,
      centerTitle: true,
    ),
    body: SingleChildScrollView(
      child: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            _buildSectionTitle('Popular Destinations'),
            SizedBox(
              height: 140, // Adjusted height to accommodate the names below
              child: ListView.builder(
                scrollDirection: Axis.horizontal,
                itemCount: _provinces.length,
                itemBuilder: (context, index) {
                  final province = _provinces[index];
                  return Padding(
                    padding: const EdgeInsets.only(right: 16.0),
                    child: Column(
                      children: [
                        GestureDetector(
                          onTap: () => _showProvinceDetails(province['name']),
                          child: CircleAvatar(
                            radius: 40,
                            backgroundColor: Colors.purple[100],
                            child: Center(
                              child: Padding(
                                padding: const EdgeInsets.all(4.0),
                                child: FittedBox(
                                  fit: BoxFit.scaleDown,
                                  child: Text(
                                    province['name'][0], // Show first letter
                                    style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
                                  ),
                                ),
                              ),
                            ),
                          ),
                        ),
                        SizedBox(height: 8),
                        SizedBox(
                          width: 80, // Constrain width to prevent overflow of name below avatar
                          child: Text(
                            province['name'],
                            overflow: TextOverflow.ellipsis,
                            maxLines: 1,
                            textAlign: TextAlign.center,
                            style: TextStyle(fontSize: 12),
                          ),
                        ),
                      ],
                    ),
                  );
                },
              ),
            ),
            SizedBox(height: 20),
            _buildSectionTitle('Explore Cambodia'),
            if (!showDetailedView || selectedProvinceData == null)
              Center(child: Text('Select a province above to see details', style: TextStyle(fontSize: 16, color: Colors.grey)))
            else
              Card(
                elevation: 6,
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(15.0)),
                child: Padding(
                  padding: const EdgeInsets.all(16.0),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        selectedProvinceData['name'],
                        style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold, color: Colors.teal),
                      ),
                      SizedBox(height: 16),
                      Text(
                        selectedProvinceData['details'],
                        style: TextStyle(fontSize: 16, color: Colors.grey[700]),
                      ),
                      SizedBox(height: 16),
                      Text(
                        'Places to Visit:',
                        style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                      ),
                      // Display visible places
                      ...selectedProvinceData['places'].take(_visiblePlacesCount).map((place) => Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              SizedBox(height: 10),
                              Text(place['name'], style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                              Text(place['shortDescription'], style: TextStyle(fontSize: 14, color: Colors.grey[700])),
                              SizedBox(height: 10),
                              // Display first image from the list in the main view
                              if ((place['imageUrls'] as List).isNotEmpty)
                                Image.network(
                                  place['imageUrls'][0],
                                  fit: BoxFit.cover,
                                  height: 150,
                                  width: double.infinity,
                                  errorBuilder: (context, error, stackTrace) => Container(
                                    height: 150,
                                    color: Colors.grey[300],
                                    child: Center(child: Text('Image not available')),
                                  ),
                                )
                              else
                                Container(
                                  height: 150,
                                  color: Colors.grey[300],
                                  child: Center(child: Text('Image not available')),
                                ),
                              SizedBox(height: 10),
                              Align(
                                alignment: Alignment.centerRight,
                                child: TextButton(
                                  onPressed: () {
                                    Navigator.push(
                                      context,
                                      MaterialPageRoute(
                                        builder: (context) => DetailScreen(itemDetails: place, itemType: 'Place'),
                                      ),
                                    );
                                  },
                                  child: Text('More Details'),
                                ),
                              ),
                            ],
                          )),
                      // Show More Places button
                      if (_visiblePlacesCount < (selectedProvinceData['places'] as List).length)
                        Align(
                          alignment: Alignment.centerRight,
                          child: TextButton(
                            onPressed: _showMorePlaces,
                            child: Text('Show More Places (${(selectedProvinceData['places'] as List).length - _visiblePlacesCount} more)'),
                          ),
                        ),
                      SizedBox(height: 16),
                      Text(
                        'Hotels:',
                        style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                      ),
                      // Display visible hotels
                      ...selectedProvinceData['hotels'].take(_visibleHotelsCount).map((hotel) => Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              SizedBox(height: 10),
                              Text(hotel['name'], style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                              Text(hotel['shortDescription'], style: TextStyle(fontSize: 14, color: Colors.grey[700])),
                              SizedBox(height: 10),
                              // Display first image from the list in the main view
                              if ((hotel['imageUrls'] as List).isNotEmpty)
                                Image.network(
                                  hotel['imageUrls'][0],
                                  fit: BoxFit.cover,
                                  height: 150,
                                  width: double.infinity,
                                  errorBuilder: (context, error, stackTrace) => Container(
                                    height: 150,
                                    color: Colors.grey[300],
                                    child: Center(child: Text('Image not available')),
                                  ),
                                )
                              else
                                Container(
                                  height: 150,
                                  color: Colors.grey[300],
                                  child: Center(child: Text('Image not available')),
                                ),
                              SizedBox(height: 10),
                              Align(
                                alignment: Alignment.centerRight,
                                child: TextButton(
                                  onPressed: () {
                                    Navigator.push(
                                      context,
                                      MaterialPageRoute(
                                        builder: (context) => DetailScreen(itemDetails: hotel, itemType: 'Hotel'),
                                      ),
                                    );
                                  },
                                  child: Text('More Details'),
                                ),
                              ),
                            ],
                          )),
                      // Show More Hotels button
                      if (_visibleHotelsCount < (selectedProvinceData['hotels'] as List).length)
                        Align(
                          alignment: Alignment.centerRight,
                          child: TextButton(
                            onPressed: _showMoreHotels,
                            child: Text('Show More Hotels (${(selectedProvinceData['hotels'] as List).length - _visibleHotelsCount} more)'),
                          ),
                        ),
                    ],
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

Widget _buildSectionTitle(String title) {
  return Padding(
    padding: const EdgeInsets.symmetric(vertical: 8.0),
    child: Text(
      title,
      style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
    ),
  );
}
  
}