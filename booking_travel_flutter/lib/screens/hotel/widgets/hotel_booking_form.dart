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
    // Get the AuthProvider before showing the bottom sheet
    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    
    return showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
builder: (context) => ChangeNotifierProvider<AuthProvider>.value(
  value: authProvider,
  child: HotelBookingForm(
    hotel: hotel,
    roomType: roomType,
    onBookingSuccess: onBookNow,
  ),
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
    
    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    
    // If not authenticated, redirect to login and return
    if (!authProvider.isAuthenticated) {
      print('User not authenticated, redirecting to login...');
      final result = await Navigator.pushNamed(
        context,
        '/login',
        arguments: {
          'returnRoute': ModalRoute.of(context)?.settings.name,
          'showBackButton': true,
        },
      );
      
      // If login was not successful, stop the booking process
      if (result != true) {
        setState(() => _isLoading = false);
        return;
      }
      
      // After successful login, refresh the auth provider state
      await authProvider.initialize();
      
      // If still not authenticated after login, show error
      if (!authProvider.isAuthenticated) {
        throw Exception('Authentication failed. Please try again.');
      }
      
      // Close the login screen and return to the booking form
      Navigator.of(context).pop();
      return;
    }
    
    // At this point, we should be authenticated
    final user = authProvider.user;
    if (user == null) {
      throw Exception('User information not available. Please try logging in again.');
    }

    print('User authenticated, proceeding with booking...');
    
    // Prepare booking data
    final totalPrice = _calculateTotalPrice();
    final Map<String, String> guestInfo = {
      'name': user.name,
      'email': user.email,
    };

    // Process payment and create booking
    print('Creating booking with payment...');
    final result = await BookingService.createBookingWithPayment(
      hotelId: widget.hotel.id,
      roomTypeId: widget.roomType.id,
      checkInDate: _checkInDate,
      checkOutDate: _checkOutDate,
      numberOfGuests: _adults + _children,
      numberOfRooms: _rooms,
      totalAmount: totalPrice,
      paymentMethod: _selectedPaymentMethod.toString().split('.').last,
      guestInfo: guestInfo,
      cardDetails: _selectedPaymentMethod == PaymentMethod.creditCard 
          ? _paymentDetails 
          : null,
    );

    print('Booking result: $result');

    if (mounted) {
      if (result['success'] == true) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Booking successful!'),
            backgroundColor: Colors.green,
          ),
        );

        // Close the booking form
        Navigator.of(context).pop();

        // Notify parent widget about successful booking
        if (widget.onBookingSuccess != null) {
          widget.onBookingSuccess!();
        }
      } else {
        setState(() {
          _error = result['message'] ?? 'Failed to complete booking';
        });
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(_error!),
            backgroundColor: Colors.red,
          ),
        );
      }
    }
  } catch (e) {
    print('Error in _submitBooking: $e');
    if (mounted) {
      setState(() {
        _error = e.toString();
      });
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Error: ${e.toString()}'),
          backgroundColor: Colors.red,
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
