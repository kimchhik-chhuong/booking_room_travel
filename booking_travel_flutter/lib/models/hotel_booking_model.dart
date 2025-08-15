class HotelBooking {
  final int id;
  final int bookingId;
  final int hotelId;
  final DateTime checkInDate;
  final DateTime checkOutDate;
  final int roomTypeId;
  final int numRooms;
  final int numGuests;
  final double pricePerNight;
  final double totalHotelPrice;
  final String status;
  final DateTime? createdAt;
  final DateTime? updatedAt;

  HotelBooking({
    required this.id,
    required this.bookingId,
    required this.hotelId,
    required this.checkInDate,
    required this.checkOutDate,
    required this.roomTypeId,
    required this.numRooms,
    required this.numGuests,
    required this.pricePerNight,
    required this.totalHotelPrice,
    required this.status,
    this.createdAt,
    this.updatedAt,
  });

  factory HotelBooking.fromJson(Map<String, dynamic> json) {
    return HotelBooking(
      id: json['id'] ?? 0,
      bookingId: json['booking_id'] ?? 0,
      hotelId: json['hotel_id'] ?? 0,
      checkInDate: DateTime.parse(json['check_in_date']),
      checkOutDate: DateTime.parse(json['check_out_date']),
      roomTypeId: json['room_type_id'] ?? 0,
      numRooms: json['num_rooms'] ?? 1,
      numGuests: json['num_guests'] ?? 1,
      pricePerNight: double.tryParse(json['price_per_night'].toString()) ?? 0.0,
      totalHotelPrice: double.tryParse(json['total_hotel_price'].toString()) ?? 0.0,
      status: json['status'] ?? 'pending',
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
      'booking_id': bookingId,
      'hotel_id': hotelId,
      'check_in_date': checkInDate.toIso8601String().split('T')[0],
      'check_out_date': checkOutDate.toIso8601String().split('T')[0],
      'room_type_id': roomTypeId,
      'num_rooms': numRooms,
      'num_guests': numGuests,
      'price_per_night': pricePerNight,
      'total_hotel_price': totalHotelPrice,
      'status': status,
    };
  }

  int get nights => checkOutDate.difference(checkInDate).inDays;
  
  String get formattedPricePerNight => '\$${pricePerNight.toStringAsFixed(2)}';
  
  String get formattedTotalPrice => '\$${totalHotelPrice.toStringAsFixed(2)}';
  
  bool get canCancel => status == 'pending' || status == 'confirmed';
}
