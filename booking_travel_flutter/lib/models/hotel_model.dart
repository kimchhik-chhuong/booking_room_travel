import 'dart:convert';

class Hotel {
  final int id;
  final String name;
  final String? description;
  final double? starRating;
  final String? location;
  final String? address;
  final String? imageUrl;
  final List<String>? images;
  final List<String>? amenities;
  final double? latitude;
  final double? longitude;
  final String? contactPhone;
  final String? websiteUrl;
  final String? email;
  final String? checkInTime;
  final String? checkOutTime;

  Hotel({
    required this.id,
    required this.name,
    this.description,
    this.starRating,
    this.location,
    this.address,
    this.imageUrl,
    this.images,
    this.amenities,
    this.latitude,
    this.longitude,
    this.contactPhone,
    this.websiteUrl,
    this.email,
    this.checkInTime,
    this.checkOutTime,
  });

  factory Hotel.fromJson(Map<String, dynamic> json) {
    // Parse amenities - handle both string and list formats
    List<String>? parseAmenities(dynamic amenitiesData) {
      if (amenitiesData == null) return null;
      
      if (amenitiesData is String) {
        try {
          // Try to parse the string as JSON
          if (amenitiesData.startsWith('[') && amenitiesData.endsWith(']')) {
            // Remove the square brackets and split by comma
            final cleanString = amenitiesData.substring(1, amenitiesData.length - 1);
            return cleanString
                .split(',')
                .map((e) => e.trim().replaceAll('"', '').replaceAll("'", ''))
                .where((e) => e.isNotEmpty)
                .toList();
          }
          // If it's not in JSON array format, try to split by comma
          return amenitiesData
              .split(',')
              .map((e) => e.trim().replaceAll('"', '').replaceAll("'", ''))
              .where((e) => e.isNotEmpty)
              .toList();
        } catch (e) {
          // If anything fails, return an empty list
          return [];
        }
      } else if (amenitiesData is List) {
        // If it's already a list, convert each item to String
        return amenitiesData.map((e) => e.toString()).toList();
      }
      return [];
    }

    return Hotel(
      id: json['hotel_id'] ?? json['id'],
      name: json['name'] ?? '',
      description: json['description'] ?? '',
      starRating: json['star_rating'] != null
          ? double.tryParse(json['star_rating'].toString())
          : null,
      location: json['location'] ?? '',
      address: json['address'] ?? '',
      imageUrl: json['full_image_url'] ?? json['image_url'] ?? json['image'],
      images: (json['full_images'] as List<dynamic>?)?.cast<String>() ?? 
             (json['images'] as List<dynamic>?)?.cast<String>(),
      amenities: parseAmenities(json['amenities']),
      latitude: json['latitude'] != null
          ? double.tryParse(json['latitude'].toString())
          : null,
      longitude: json['longitude'] != null
          ? double.tryParse(json['longitude'].toString())
          : null,
      contactPhone: json['contact_phone'] ?? json['phone'],
      websiteUrl: json['website_url'] ?? json['website'],
      email: json['email'],
      checkInTime: json['check_in_time'],
      checkOutTime: json['check_out_time'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'description': description,
      'star_rating': starRating,
      'location': location,
      'address': address,
      'image_url': imageUrl,
      'images': images,
      'amenities': amenities,
      'latitude': latitude,
      'longitude': longitude,
      'contact_phone': contactPhone,
      'website_url': websiteUrl,
      'email': email,
      'check_in_time': checkInTime,
      'check_out_time': checkOutTime,
    }..removeWhere((key, value) => value == null);
  }

  // Get the first available image
  String? get firstImage {
    if (images != null && images!.isNotEmpty) {
      return images!.first;
    }
    return imageUrl;
  }

  // Get the main image (first from images array or fallback to single image)
  String? get mainImage {
    if (images != null && images!.isNotEmpty) {
      return images!.first;
    }
    return imageUrl;
  }

  // Get all images including the main image
  List<String> get allImages {
    List<String> allImagesList = [];
    if (images != null && images!.isNotEmpty) {
      allImagesList.addAll(images!);
    } else if (imageUrl != null) {
      allImagesList.add(imageUrl!);
    }
    return allImagesList;
  }

  // Check if hotel has location coordinates
  bool get hasLocation => latitude != null && longitude != null;

  // Get formatted rating
  String get formattedRating {
    if (starRating == null) return 'No rating';
    return starRating!.toStringAsFixed(1);
  }
}
