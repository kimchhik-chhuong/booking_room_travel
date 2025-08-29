import 'package:flutter/material.dart';

enum PaymentMethod {
  creditCard,
  paypal,
  bankTransfer,
  payAtHotel,
}

class PaymentSelectionForm extends StatefulWidget {
  final PaymentMethod? initialMethod;
  final Function(PaymentMethod) onPaymentMethodChanged;
  final bool isProcessing;
  final VoidCallback? onProceed;
  final String? error;

  const PaymentSelectionForm({
    Key? key,
    this.initialMethod,
    required this.onPaymentMethodChanged,
    this.isProcessing = false,
    this.onProceed,
    this.error,
  }) : super(key: key);

  @override
  _PaymentSelectionFormState createState() => _PaymentSelectionFormState();
}

class _PaymentSelectionFormState extends State<PaymentSelectionForm> {
  late PaymentMethod _selectedMethod;
  final _formKey = GlobalKey<FormState>();
  final Map<PaymentMethod, Map<String, dynamic>> _paymentMethods = {
    PaymentMethod.creditCard: {
      'title': 'Credit/Debit Card',
      'icon': Icons.credit_card,
      'description': 'Pay with Visa, Mastercard, or other cards',
    },
    PaymentMethod.paypal: {
      'title': 'PayPal',
      'icon': Icons.payment,
      'description': 'Pay with your PayPal account',
    },
    PaymentMethod.bankTransfer: {
      'title': 'Bank Transfer',
      'icon': Icons.account_balance,
      'description': 'Make a direct bank transfer',
    },
    PaymentMethod.payAtHotel: {
      'title': 'Pay at Hotel',
      'icon': Icons.hotel,
      'description': 'Pay when you arrive at the hotel',
    },
  };

  @override
  void initState() {
    super.initState();
    _selectedMethod = widget.initialMethod ?? PaymentMethod.creditCard;
  }

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(16.0),
      child: Form(
        key: _formKey,
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Select Payment Method',
              style: TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
              ),
            ),
            const SizedBox(height: 16),
            ..._buildPaymentOptions(),
            if (widget.error != null) ...[
              const SizedBox(height: 16),
              Container(
                padding: const EdgeInsets.all(12),
                decoration: BoxDecoration(
                  color: Colors.red[50],
                  borderRadius: BorderRadius.circular(8),
                  border: Border.all(color: Colors.red[200]!), 
                ),
                child: Row(
                  children: [
                    const Icon(Icons.error_outline, color: Colors.red, size: 20),
                    const SizedBox(width: 8),
                    Expanded(
                      child: Text(
                        widget.error!,
                        style: const TextStyle(color: Colors.red, fontSize: 14),
                      ),
                    ),
                  ],
                ),
              ),
            ],
            const SizedBox(height: 24),
            if (widget.onProceed != null)
              SizedBox(
                width: double.infinity,
                child: ElevatedButton(
                  onPressed: widget.isProcessing ? null : widget.onProceed,
                  style: ElevatedButton.styleFrom(
                    padding: const EdgeInsets.symmetric(vertical: 16),
                    backgroundColor: Colors.orange,
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(8.0),
                    ),
                  ),
                  child: widget.isProcessing
                      ? const SizedBox(
                          width: 20,
                          height: 20,
                          child: CircularProgressIndicator(
                            strokeWidth: 2,
                            valueColor: AlwaysStoppedAnimation<Color>(Colors.white),
                          ),
                        )
                      : const Text(
                          'Complete Booking',
                          style: TextStyle(fontSize: 16, color: Colors.white),
                        ),
                ),
              ),
          ],
        ),
      ),
    );
  }

  List<Widget> _buildPaymentOptions() {
    return _paymentMethods.entries.map((entry) {
      final method = entry.key;
      final data = entry.value;
      final isSelected = _selectedMethod == method;

      return Card(
        margin: const EdgeInsets.only(bottom: 12),
        elevation: isSelected ? 2 : 0.5,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(8),
          side: isSelected
              ? BorderSide(color: Theme.of(context).primaryColor, width: 2)
              : BorderSide(color: Colors.grey[300]!),
        ),
        child: InkWell(
          onTap: () {
            setState(() {
              _selectedMethod = method;
            });
            widget.onPaymentMethodChanged(method);
          },
          borderRadius: BorderRadius.circular(8),
          child: Padding(
            padding: const EdgeInsets.all(16),
            child: Row(
              children: [
                Container(
                  padding: const EdgeInsets.all(8),
                  decoration: BoxDecoration(
                    color: isSelected
                        ? Theme.of(context).primaryColor.withOpacity(0.1)
                        : Colors.grey[100],
                    borderRadius: BorderRadius.circular(8),
                  ),
                  child: Icon(
                    data['icon'] as IconData,
                    color: isSelected
                        ? Theme.of(context).primaryColor
                        : Colors.grey[700],
                  ),
                ),
                const SizedBox(width: 16),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        data['title'] as String,
                        style: TextStyle(
                          fontWeight: FontWeight.w500,
                          color: isSelected
                              ? Theme.of(context).primaryColor
                              : Colors.black87,
                        ),
                      ),
                      const SizedBox(height: 4),
                      Text(
                        data['description'] as String,
                        style: TextStyle(
                          fontSize: 12,
                          color: Colors.grey[600],
                        ),
                      ),
                    ],
                  ),
                ),
                if (isSelected)
                  Icon(
                    Icons.check_circle,
                    color: Theme.of(context).primaryColor,
                  ),
              ],
            ),
          ),
        ),
      );
    }).toList();
  }
}
