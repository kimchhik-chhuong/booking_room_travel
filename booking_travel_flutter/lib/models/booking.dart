class Booking {
  final String id;
  final String hotelName;
  final String imageUrl;
  final String location;
  final double rating;
  final int reviewCount;
  final DateTime checkInDate;
  final DateTime checkOutDate;
  final int nights;
  final int guests;
  final int beds;
  final double pricePerNight;
  final double totalPrice;
  final String paymentMethod;
  final String status;

  Booking({
    required this.id,
    required this.hotelName,
    required this.imageUrl,
    required this.location,
    required this.rating,
    required this.reviewCount,
    required this.checkInDate,
    required this.checkOutDate,
    required this.nights,
    required this.guests,
    required this.beds,
    required this.pricePerNight,
    required this.totalPrice,
    required this.paymentMethod,
    required this.status,
  });

  factory Booking.fromJson(Map<String, dynamic> json) {
    return Booking(
      id: json['id']?.toString() ?? '',
      hotelName: json['hotelName']?.toString() ?? '',
      imageUrl: json['imageUrl']?.toString() ?? '',
      location: json['location']?.toString() ?? '',
      rating: (json['rating'] as num?)?.toDouble() ?? 0.0,
      reviewCount: (json['reviewCount'] as num?)?.toInt() ?? 0,
      checkInDate: DateTime.parse(json['checkInDate']?.toString() ?? DateTime.now().toString()),
      checkOutDate: DateTime.parse(json['checkOutDate']?.toString() ?? DateTime.now().toString()),
      nights: (json['nights'] as num?)?.toInt() ?? 0,
      guests: (json['guests'] as num?)?.toInt() ?? 0,
      beds: (json['beds'] as num?)?.toInt() ?? 0,
      pricePerNight: (json['pricePerNight'] as num?)?.toDouble() ?? 0.0,
      totalPrice: (json['totalPrice'] as num?)?.toDouble() ?? 0.0,
      paymentMethod: json['paymentMethod']?.toString() ?? '',
      status: json['status']?.toString() ?? '',
    );
  }

  Booking copyWith({
    String? id,
    String? hotelName,
    String? imageUrl,
    String? location,
    double? rating,
    int? reviewCount,
    DateTime? checkInDate,
    DateTime? checkOutDate,
    int? nights,
    int? guests,
    int? beds,
    double? pricePerNight,
    double? totalPrice,
    String? paymentMethod,
    String? status,
  }) {
    return Booking(
      id: id ?? this.id,
      hotelName: hotelName ?? this.hotelName,
      imageUrl: imageUrl ?? this.imageUrl,
      location: location ?? this.location,
      rating: rating ?? this.rating,
      reviewCount: reviewCount ?? this.reviewCount,
      checkInDate: checkInDate ?? this.checkInDate,
      checkOutDate: checkOutDate ?? this.checkOutDate,
      nights: nights ?? this.nights,
      guests: guests ?? this.guests,
      beds: beds ?? this.beds,
      pricePerNight: pricePerNight ?? this.pricePerNight,
      totalPrice: totalPrice ?? this.totalPrice,
      paymentMethod: paymentMethod ?? this.paymentMethod,
      status: status ?? this.status,
    );
  }
}