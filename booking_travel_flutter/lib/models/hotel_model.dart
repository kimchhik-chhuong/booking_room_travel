class Hotel {
  final int id;
  final String name;
  final String description;
  final String? image;
  final double? rating;
  final String? priceRange;
  final String? address;
  final String? phone;
  final String? email;
  final int provinceId;
  final String provinceName;

  Hotel({
    required this.id,
    required this.name,
    required this.description,
    this.image,
    this.rating,
    this.priceRange,
    this.address,
    this.phone,
    this.email,
    required this.provinceId,
    required this.provinceName,
  });

  factory Hotel.fromJson(Map<String, dynamic> json) {
    return Hotel(
      id: json['hotel_id'] ?? json['id'] ?? 0,
      name: json['name'] ?? '',
      description: json['description'] ?? '',
      image: json['image_url'] ?? json['image'],
      rating: json['star_rating'] != null
          ? double.tryParse(json['star_rating'].toString())
          : null,
      priceRange: json['price_range'],
      address: json['address'],
      phone: json['contact_phone'] ?? json['phone'],
      email: json['email'],
      provinceId: json['province_id'] ?? 0,
      provinceName: json['province_name'] ?? '',
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'description': description,
      'image': image,
      'rating': rating,
      'price_range': priceRange,
      'address': address,
      'phone': phone,
      'email': email,
      'province_id': provinceId,
      'province_name': provinceName,
    };
  }
}
