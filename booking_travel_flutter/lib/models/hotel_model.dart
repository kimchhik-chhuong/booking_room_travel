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
  });

  factory Hotel.fromJson(Map<String, dynamic> json) {
    return Hotel(
      id: json['hotel_id'] ?? json['id'] ?? 0,
      name: json['name'] ?? '',
      description: json['description'] ?? '',
      address: json['address'],
      latitude: json['latitude'] != null
          ? double.tryParse(json['latitude'].toString())
          : null,
      longitude: json['longitude'] != null
          ? double.tryParse(json['longitude'].toString())
          : null,
      image: json['image_url'] ?? json['image'],
      images: json['images'] != null
          ? List<String>.from(json['images'])
          : null,
      rating: json['star_rating'] != null
          ? double.tryParse(json['star_rating'].toString())
          : null,
      amenities: json['amenities'] != null
          ? List<String>.from(json['amenities'])
          : null,
      phone: json['contact_phone'] ?? json['phone'],
      email: json['email'],
      website: json['website_url'] ?? json['website'],
      checkInTime: json['check_in_time'],
      checkOutTime: json['check_out_time'],
      provinceId: json['province_id'],
      provinceName: json['province_name'] ?? json['province']?['name'],
      status: json['status'] ?? 'active',
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
    };
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
}
