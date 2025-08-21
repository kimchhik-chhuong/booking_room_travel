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

  const RoomType({
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
  })  : assert(price >= 0, 'Price cannot be negative'),
        assert(maxOccupancy > 0, 'Max occupancy must be greater than 0'),
        assert(availableRooms >= 0, 'Available rooms cannot be negative');

  factory RoomType.fromJson(Map<String, dynamic> json) {
    return RoomType(
      id: json['id'] as int? ?? 0,
      hotelMetadataId: json['hotel_metadata_id'] as int? ?? 0,
      name: json['name'] as String? ?? 'Unnamed Room',
      description: json['description'] as String?,
      price: (json['price'] as num?)?.toDouble() ?? 0.0,
      maxOccupancy: json['max_occupancy'] as int? ?? 2,
      availableRooms: json['available_rooms'] as int? ?? 0,
      amenities: json['amenities'] != null 
          ? List<String>.from(json['amenities'] as List)
          : null,
      imageUrl: json['image_url'] as String?,
      createdAt: json['created_at'] != null 
          ? DateTime.tryParse(json['created_at'] as String)
          : null,
      updatedAt: json['updated_at'] != null 
          ? DateTime.tryParse(json['updated_at'] as String)
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
    }..removeWhere((key, value) => value == null);
  }

  RoomType copyWith({
    int? id,
    int? hotelMetadataId,
    String? name,
    String? description,
    double? price,
    int? maxOccupancy,
    int? availableRooms,
    List<String>? amenities,
    String? imageUrl,
    DateTime? createdAt,
    DateTime? updatedAt,
  }) {
    return RoomType(
      id: id ?? this.id,
      hotelMetadataId: hotelMetadataId ?? this.hotelMetadataId,
      name: name ?? this.name,
      description: description ?? this.description,
      price: price ?? this.price,
      maxOccupancy: maxOccupancy ?? this.maxOccupancy,
      availableRooms: availableRooms ?? this.availableRooms,
      amenities: amenities ?? this.amenities,
      imageUrl: imageUrl ?? this.imageUrl,
      createdAt: createdAt ?? this.createdAt,
      updatedAt: updatedAt ?? this.updatedAt,
    );
  }

  // Helper getters
  String get formattedPrice => '\$${price.toStringAsFixed(2)}';
  bool get isAvailable => availableRooms > 0;
  bool get isFullyBooked => availableRooms <= 0;
  
  /// Returns the room's availability status as a string
  String get availabilityStatus {
    if (isFullyBooked) return 'Sold Out';
    if (availableRooms < 5) return 'Only $availableRooms left';
    return 'Available';
  }

  /// Returns a list of amenities with default values if null
  List<String> get safeAmenities => amenities ?? [];

  /// Returns a short description or a default message if null
  String get safeDescription => description ?? 'No description available';

  /// Returns the first image URL or a placeholder if none exists
  String get displayImageUrl => imageUrl ?? 'https://via.placeholder.com/300x200?text=No+Image';

  /// Validates if the room can accommodate the requested number of guests
  bool canAccommodate(int numberOfGuests) {
    return numberOfGuests > 0 && numberOfGuests <= maxOccupancy;
  }

  /// Returns a new instance with updated available rooms
  RoomType withUpdatedAvailability(int newAvailableRooms) {
    return copyWith(availableRooms: newAvailableRooms);
  }

  @override
  bool operator ==(Object other) {
    if (identical(this, other)) return true;
    return other is RoomType &&
        other.id == id &&
        other.hotelMetadataId == hotelMetadataId;
  }

  @override
  int get hashCode => id.hashCode ^ hotelMetadataId.hashCode;

  @override
  String toString() {
    return 'RoomType(id: $id, name: $name, price: $formattedPrice, available: $availableRooms)';
  }
}
