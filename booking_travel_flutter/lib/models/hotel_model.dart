import 'dart:convert';
import 'package:booking_travel/models/room_type_model.dart';

class Hotel {
  final int id;
  final String name;
  final String description;
  final String? address;
  final double? latitude;
  final double? longitude;
  final String? image;
  final List<String>? images;
  final double? rating;
  final List<String>? amenities;
  final String? phone;
  final String? email;
  final String? website;
  final String? checkInTime;
  final String? checkOutTime;
  final int? provinceId;
  final String? provinceName;
  final String status;
  final List<RoomType>? roomTypes;

  Hotel({
    required this.id,
    required this.name,
    required this.description,
    this.address,
    this.latitude,
    this.longitude,
    this.image,
    this.images,
    this.rating,
    this.amenities,
    this.phone,
    this.email,
    this.website,
    this.checkInTime,
    this.checkOutTime,
    this.provinceId,
    this.provinceName,
    this.status = 'active',
    this.roomTypes,
  });

  factory Hotel.fromJson(Map<String, dynamic> json) {
    // Parse amenities - handle both string and list formats
    List<String>? parseAmenities(dynamic amenitiesData) {
      if (amenitiesData == null) return null;
      
      if (amenitiesData is String) {
        try {
          final parsed = jsonDecode(amenitiesData);
          if (parsed is List) {
            return List<String>.from(parsed);
          }
          return [];
        } catch (e) {
          return [amenitiesData];
        }
      } else if (amenitiesData is List) {
        return List<String>.from(amenitiesData);
      }
      return [];
    }

    // Parse room types if they exist in the JSON
    List<RoomType>? parseRoomTypes(dynamic roomTypesData) {
      if (roomTypesData == null) return null;
      if (roomTypesData is List) {
        return roomTypesData
            .map((rt) => rt is RoomType ? rt : RoomType.fromJson(rt))
            .toList();
      }
      return null;
    }

    return Hotel(
      id: json['hotel_id'] ?? json['id'],
      name: json['name'] ?? '',
      description: json['description'] ?? '',
      address: json['address'] ?? '',
      latitude: json['latitude'] != null
          ? double.tryParse(json['latitude'].toString())
          : null,
      longitude: json['longitude'] != null
          ? double.tryParse(json['longitude'].toString())
          : null,
      image: json['full_image_url'] ?? json['image_url'] ?? json['image'],
      images: (json['full_images'] as List<dynamic>?)?.cast<String>() ?? 
             (json['images'] as List<dynamic>?)?.cast<String>(),
      rating: json['star_rating'] != null
          ? double.tryParse(json['star_rating'].toString())
          : null,
      amenities: parseAmenities(json['amenities']),
      phone: json['contact_phone'] ?? json['phone'],
      email: json['email'],
      website: json['website_url'] ?? json['website'],
      checkInTime: json['check_in_time'],
      checkOutTime: json['check_out_time'],
      provinceId: json['province_id'],
      provinceName: json['province_name'] ?? json['province']?['name'],
      status: json['status'] ?? 'active',
      roomTypes: parseRoomTypes(json['room_types']),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'description': description,
      'address': address,
      'latitude': latitude,
      'longitude': longitude,
      'image': image,
      'images': images,
      'rating': rating,
      'amenities': amenities,
      'phone': phone,
      'email': email,
      'website': website,
      'check_in_time': checkInTime,
      'check_out_time': checkOutTime,
      'province_id': provinceId,
      'province_name': provinceName,
      'status': status,
      'room_types': roomTypes?.map((rt) => rt.toJson()).toList(),
    };
  }

  // Get the first available image
  String? get firstImage {
    if (images != null && images!.isNotEmpty) {
      return images!.first;
    }
    return image;
  }

  // Get the main image (first from images array or fallback to single image)
  String? get mainImage {
    if (images != null && images!.isNotEmpty) {
      return images!.first;
    }
    return image;
  }

  // Get all images including the main image
  List<String> get allImages {
    List<String> allImagesList = [];
    if (images != null && images!.isNotEmpty) {
      allImagesList.addAll(images!);
    } else if (image != null) {
      allImagesList.add(image!);
    }
    return allImagesList;
  }

  // Check if hotel has location coordinates
  bool get hasLocation => latitude != null && longitude != null;

  // Get formatted rating
  String get formattedRating {
    if (rating == null) return 'No rating';
    return rating!.toStringAsFixed(1);
  }

  // Check if hotel is active
  bool get isActive => status == 'active';

  static List<String>? _parseAmenities(dynamic amenities) {
    if (amenities == null) return null;
    
    try {
      // If it's already a List, return it
      if (amenities is List) {
        return List<String>.from(amenities.map((item) => item.toString()));
      }
      
      // If it's a String, try to parse it
      if (amenities is String) {
        // Try to parse as JSON array first
        try {
          final parsed = jsonDecode(amenities);
          if (parsed is List) {
            return List<String>.from(parsed.map((item) => item.toString()));
          }
        } catch (e) {
          // If JSON parsing fails, try splitting by comma
          return amenities
              .replaceAll('[', '')
              .replaceAll(']', '')
              .replaceAll('"', '')
              .split(',')
              .map((e) => e.trim())
              .where((e) => e.isNotEmpty)
              .toList();
        }
      }
      
      // If we get here, return an empty list as fallback
      return [];
    } catch (e) {
      // If anything goes wrong, return an empty list
      return [];
    }
  }
}
