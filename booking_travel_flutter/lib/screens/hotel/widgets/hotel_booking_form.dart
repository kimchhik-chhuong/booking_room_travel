import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:provider/provider.dart';
import 'package:booking_travel/models/hotel_model.dart';
import 'package:booking_travel/models/room_type_model.dart';
import 'package:booking_travel/providers/auth_provider.dart';
import 'package:booking_travel/services/booking_service.dart';
import 'package:booking_travel/widgets/date_range_picker.dart';
import 'package:booking_travel/widgets/guest_selector.dart';
import 'payment_selection_form.dart';

class HotelBookingForm extends StatefulWidget {
  final Hotel hotel;
  final RoomType roomType;
  final Function()? onBookingSuccess;

  const HotelBookingForm({
    Key? key,
    required this.hotel,
    required this.roomType,
    this.onBookingSuccess,
  }) : super(key: key);

  static Future<void> show(
    BuildContext context, {
    required Hotel hotel,
    required RoomType roomType,
    required Function() onBookNow,
  }) {
    return showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) => HotelBookingForm(
        hotel: hotel,
        roomType: roomType,
        onBookingSuccess: onBookNow,
      ),
    );
  }

  @override
  _HotelBookingFormState createState() => _HotelBookingFormState();
}

class _HotelBookingFormState extends State<HotelBookingForm> {
  final _formKey = GlobalKey<FormState>();
  final BookingService _bookingService = BookingService();
  
  late DateTime _checkInDate;
  late DateTime _checkOutDate;
  int _adults = 1;
  int _children = 0;
  int _rooms = 1;
  bool _isLoading = false;
  String? _error;
  PaymentMethod _selectedPaymentMethod = PaymentMethod.creditCard;
  final Map<String, dynamic> _paymentDetails = {};

  @override
  void initState() {
    super.initState();
    final now = DateTime.now();
    _checkInDate = now.add(const Duration(days: 1));
    _checkOutDate = now.add(const Duration(days: 2));
  }

  Future<void> _submitBooking() async {
    if (_formKey.currentState?.validate() != true) return;

    setState(() {
      _isLoading = true;
      _error = null;
    });

    try {
      print('Starting booking submission...');
      
      // Get the user from AuthProvider using the current context
      final authProvider = Provider.of<AuthProvider>(context, listen: false);
      final user = authProvider.user;
      
      if (user == null) {
        throw Exception('You must be logged in to book a hotel');
      }

      final totalPrice = _calculateTotalPrice();
      print('Calculated total price: $totalPrice');
      
      final booking = {
  'hotel_id': widget.hotel.id,
  'room_type_id': widget.roomType.id,
  'first_name': user.name.split(' ').first, // or collect from form
  'last_name': user.name.split(' ').last,   // or collect from form
  'email': user.email,
  'phone': '1234567890', // add input field in form
  'nationality': 'Cambodian', // add input field in form
  'check_in': DateFormat('yyyy-MM-dd').format(_checkInDate), // 👈 match Laravel
  'check_out': DateFormat('yyyy-MM-dd').format(_checkOutDate), // 👈 match Laravel
  'adults': _adults,
  'children': _children,
  'special_requests': '',
  'payment_method': _selectedPaymentMethod.toString().split('.').last,
};


      print('Attempting to create booking with data: $booking');
      
      // Call the booking service
      final result = await _bookingService.createBooking(booking);
      
      print('Booking service response: $result');
      
      if (!mounted) return;
      
      Navigator.of(context).pop();
      
      // Show success message
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(result['message'] ?? 'Booking successful!'),
          backgroundColor: Colors.green,
          duration: const Duration(seconds: 5),
        ),
      );
      
      // Call the success callback if provided
      if (widget.onBookingSuccess != null) {
        widget.onBookingSuccess!();
      }
      
    } catch (e) {
      print('Error in _submitBooking: $e');
      final errorMessage = e.toString().replaceAll('Exception: ', '');
      
      if (mounted) {
        setState(() {
          _error = errorMessage;
          _isLoading = false;
        });
        
        // Show error message
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Booking failed: $errorMessage'),
            backgroundColor: Colors.red,
            duration: const Duration(seconds: 5),
          ),
        );
      }
    } finally {
      if (mounted) {
        setState(() {
          _isLoading = false;
        });
      }
    }
  }

  double _calculateTotalPrice() {
    final nights = _checkOutDate.difference(_checkInDate).inDays;
    return widget.roomType.price * _rooms * nights;
  }

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final totalPrice = _calculateTotalPrice();
    final nights = _checkOutDate.difference(_checkInDate).inDays;

    return SingleChildScrollView(
      padding: EdgeInsets.only(
        bottom: MediaQuery.of(context).viewInsets.bottom + 16,
      ),
      child: Container(
        padding: const EdgeInsets.all(16.0),
        decoration: const BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.vertical(top: Radius.circular(16.0)),
        ),
        child: Form(
          key: _formKey,
          child: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Center(
                child: Container(
                  width: 40,
                  height: 4,
                  margin: const EdgeInsets.only(bottom: 16),
                  decoration: BoxDecoration(
                    color: Colors.grey[300],
                    borderRadius: BorderRadius.circular(2),
                  ),
                ),
              ),
              Text(
                'Book ${widget.roomType.name}',
                style: theme.textTheme.titleLarge?.copyWith(
                  fontWeight: FontWeight.bold,
                ),
              ),
              const SizedBox(height: 16),
              
              // Date Range Picker
              DateRangePickerField(
                initialStartDate: _checkInDate,
                initialEndDate: _checkOutDate,
                onDatesSelected: (start, end) {
                  setState(() {
                    _checkInDate = start;
                    _checkOutDate = end;
                  });
                },
              ),
              const SizedBox(height: 16),
              
              // Guest Selector
              GuestSelector(
                initialAdults: _adults,
                initialChildren: _children,
                initialRooms: _rooms,
                onChanged: (adults, children, rooms) {
                  setState(() {
                    _adults = adults;
                    _children = children;
                    _rooms = rooms;
                  });
                },
              ),
              const SizedBox(height: 16),
              
              // Payment Selection
              PaymentSelectionForm(
                initialMethod: _selectedPaymentMethod,
                onPaymentMethodChanged: (method) {
                  setState(() {
                    _selectedPaymentMethod = method;
                  });
                },
                isProcessing: _isLoading,
                onProceed: _submitBooking,
                error: _error,
              ),
              
              // Price Summary
              Container(
                margin: const EdgeInsets.only(top: 16, bottom: 8),
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  color: Colors.grey[50],
                  borderRadius: BorderRadius.circular(8),
                  border: Border.all(color: Colors.grey[200]!), 
                ),
                child: Column(
                  children: [
                    _buildPriceRow('\$${widget.roomType.price.toStringAsFixed(2)} x $nights nights x $_rooms rooms', 
                        '\$${(widget.roomType.price * nights * _rooms).toStringAsFixed(2)}'),
                    const SizedBox(height: 8),
                    const Divider(),
                    const SizedBox(height: 8),
                    _buildPriceRow('Total', '\$${totalPrice.toStringAsFixed(2)}', 
                        isBold: true, isLarge: true),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildPriceRow(String label, String value, {bool isBold = false, bool isLarge = false}) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Text(
          label,
          style: TextStyle(
            fontSize: isLarge ? 16 : 14,
            fontWeight: isBold ? FontWeight.bold : FontWeight.normal,
            color: isBold ? Colors.black : Colors.grey[700],
          ),
        ),
        Text(
          value,
          style: TextStyle(
            fontSize: isLarge ? 18 : 14,
            fontWeight: isBold ? FontWeight.bold : FontWeight.normal,
            color: isBold ? Theme.of(context).primaryColor : Colors.black,
          ),
        ),
      ],
    );
  }
}
