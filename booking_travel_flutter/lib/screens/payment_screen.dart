import 'package:flutter/material.dart';

void main() {
  runApp(MaterialApp(
    home: const PaymentScreen(),
    theme: ThemeData(primarySwatch: Colors.blue),
  ));
}

class PaymentScreen extends StatefulWidget {
  const PaymentScreen({Key? key}) : super(key: key);

  @override
  State<PaymentScreen> createState() => _PaymentScreenState();
}

class _PaymentScreenState extends State<PaymentScreen> {
  final _formKey = GlobalKey<FormState>();
  final _cardNumberController = TextEditingController();
  final _expiryDateController = TextEditingController();
  final _cvvController = TextEditingController();
  bool _isProcessing = false;

  @override
  void dispose() {
    _cardNumberController.dispose();
    _expiryDateController.dispose();
    _cvvController.dispose();
    super.dispose();
  }

  String? _validateCardNumber(String? value) {
    if (value == null || value.isEmpty) return 'Please enter card number';
    if (value.length < 16) return 'Invalid card number';
    return null;
  }

  String? _validateExpiryDate(String? value) {
    if (value == null || value.isEmpty) return 'Please enter expiry date';
    return null;
  }

  String? _validateCVV(String? value) {
    if (value == null || value.isEmpty) return 'Please enter CVV';
    if (value.length < 3) return 'Invalid CVV';
    return null;
  }

  void _submitPayment() {
    if (_formKey.currentState!.validate()) {
      setState(() => _isProcessing = true);
      // Process payment here
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Payment')),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16.0),
        child: Form(
          key: _formKey,
          child: Column(
            children: [
              TextFormField(
                controller: _cardNumberController,
                decoration: const InputDecoration(
                  labelText: 'Card Number',
                  border: OutlineInputBorder(),
                ),
                keyboardType: TextInputType.number,
                validator: _validateCardNumber,
              ),
              const SizedBox(height: 16),
              Row(
                children: [
                  Expanded(
                    child: TextFormField(
                      controller: _expiryDateController,
                      decoration: const InputDecoration(
                        labelText: 'Expiry Date',
                        border: OutlineInputBorder(),
                      ),
                      validator: _validateExpiryDate,
                    ),
                  ),
                  const SizedBox(width: 16),
                  Expanded(
                    child: TextFormField(
                      controller: _cvvController,
                      decoration: const InputDecoration(
                        labelText: 'CVV',
                        border: OutlineInputBorder(),
                      ),
                      keyboardType: TextInputType.number,
                      validator: _validateCVV,
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 24),
              ElevatedButton(
                onPressed: _isProcessing ? null : _submitPayment,
                child: _isProcessing
                    ? const CircularProgressIndicator()
                    : const Text('Submit Payment'),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
