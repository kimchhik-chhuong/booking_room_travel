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
  final DateTime? createdAt;
  final DateTime? updatedAt;

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
    this.createdAt,
    this.updatedAt,
  });

  factory RoomType.fromJson(Map<String, dynamic> json) {
    return RoomType(
      id: json['id'] ?? 0,
      hotelMetadataId: json['hotel_metadata_id'] ?? 0,
      name: json['name'] ?? '',
      description: json['description'],
      price: double.tryParse(json['price'].toString()) ?? 0.0,
      maxOccupancy: json['max_occupancy'] ?? 2,
      availableRooms: json['available_rooms'] ?? 0,
      amenities: json['amenities'] != null 
          ? List<String>.from(json['amenities'])
          : null,
      imageUrl: json['image_url'],
      createdAt: json['created_at'] != null 
          ? DateTime.tryParse(json['created_at'])
          : null,
      updatedAt: json['updated_at'] != null 
          ? DateTime.tryParse(json['updated_at'])
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'hotel_metadata_id': hotelMetadataId,
      'name': name,
      'description': description,
      'price': price,
      'max_occupancy': maxOccupancy,
      'available_rooms': availableRooms,
      'amenities': amenities,
      'image_url': imageUrl,
      'created_at': createdAt?.toIso8601String(),
      'updated_at': updatedAt?.toIso8601String(),
    };
  }

  String get formattedPrice => '\$${price.toStringAsFixed(2)}';
  
  bool get isAvailable => availableRooms > 0;
}
