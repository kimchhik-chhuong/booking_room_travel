class Hotel {
  final int id;
  final String name;
  final String? description;
  final String? imageUrl;
  final String? contactPhone;
  final String? websiteUrl;
  final String? map;
  final int? destinationId;
  final int? adventureId;
  final List<RoomType> roomTypes;

  Hotel({
    required this.id,
    required this.name,
    this.description,
    this.imageUrl,
    this.contactPhone,
    this.websiteUrl,
    this.map,
    this.destinationId,
    this.adventureId,
    this.roomTypes = const [],
  });

  factory Hotel.fromJson(Map<String, dynamic> json) {
    return Hotel(
      id: json['hotel_id'] ?? json['id'] ?? 0,
      name: json['name'] ?? '',
      description: json['description'],
      imageUrl: json['image_url'],
      contactPhone: json['contact_phone'],
      websiteUrl: json['website_url'],
      map: json['map'],
      destinationId: json['destination_id'],
      adventureId: json['adventure_id'],
      roomTypes: json['room_types'] != null
          ? List<RoomType>.from(
              json['room_types'].map((x) => RoomType.fromJson(x)))
          : [],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'name': name,
      'description': description,
      'image_url': imageUrl,
      'contact_phone': contactPhone,
      'website_url': websiteUrl,
      'map': map,
      'destination_id': destinationId,
      'adventure_id': adventureId,
    };
  }
}

class RoomType {
  final int id;
  final int hotelMetadataId;
  final String name;
  final String? description;
  final double price;
  final int maxOccupancy;
  final int availableRooms;
  final List<String>? amenities;
  final String? imageUrl;

  RoomType({
    required this.id,
    required this.hotelMetadataId,
    required this.name,
    this.description,
    required this.price,
    required this.maxOccupancy,
    required this.availableRooms,
    this.amenities,
    this.imageUrl,
  });

  factory RoomType.fromJson(Map<String, dynamic> json) {
    return RoomType(
      id: json['id'] ?? 0,
      hotelMetadataId: json['hotel_metadata_id'] ?? 0,
      name: json['name'] ?? '',
      description: json['description'],
      price: (json['price'] ?? 0.0).toDouble(),
      maxOccupancy: json['max_occupancy'] ?? 2,
      availableRooms: json['available_rooms'] ?? 0,
      amenities:
          json['amenities'] != null ? List<String>.from(json['amenities']) : [],
      imageUrl: json['image_url'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'hotel_metadata_id': hotelMetadataId,
      'name': name,
      'description': description,
      'price': price,
      'max_occupancy': maxOccupancy,
      'available_rooms': availableRooms,
      'amenities': amenities,
      'image_url': imageUrl,
    };
  }
}
