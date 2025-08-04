import 'package:flutter/material.dart';

class PaymentScreen extends StatefulWidget {
  const PaymentScreen({super.key});

  @override
  State<PaymentScreen> createState() => _PaymentScreenState();
}

class _PaymentScreenState extends State<PaymentScreen> {
  final _formKey = GlobalKey<FormState>();

  String _cardNumber = '';
  String _expiryDate = '';
  String _cvv = '';
  String _cardHolderName = '';

  bool _isProcessing = false;

  void _handlePayment() {
    if (_formKey.currentState!.validate()) {
      setState(() {
        _isProcessing = true;
      });

      // Simulate payment delay
      Future.delayed(const Duration(seconds: 2), () {
        setState(() {
          _isProcessing = false;
        });

        showDialog(
          context: context,
          builder: (_) => AlertDialog(
            title: const Text('Payment Successful'),
            content: const Text('Thank you for your purchase!'),
            actions: [
              TextButton(
                onPressed: () {
                  Navigator.of(context).pop();
                  Navigator.of(context).pushReplacementNamed('/home');
                },
                child: const Text('OK'),
              ),
            ],
          ),
        );
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Payment'),
        backgroundColor: Colors.blue.shade700,
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Form(
          key: _formKey,
          child: ListView(
            children: [
              const Text(
                'Enter Payment Details',
                style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
              ),
              const SizedBox(height: 20),

              TextFormField(
                decoration: const InputDecoration(
                  labelText: 'Card Holder Name',
                  border: OutlineInputBorder(),
                ),
                onChanged: (val) => _cardHolderName = val,
                validator: (val) => val!.isEmpty ? 'Required' : null,
              ),
              const SizedBox(height: 16),

              TextFormField(
                decoration: const InputDecoration(
                  labelText: 'Card Number',
                  border: OutlineInputBorder(),
                ),
                keyboardType: TextInputType.number,
                maxLength: 16,
                onChanged: (val) => _cardNumber = val,
                validator: (val) =>
                    val!.length < 16 ? 'Enter valid card number' : null,
              ),
              const SizedBox(height: 16),

              Row(
                children: [
                  Expanded(
                    child: TextFormField(
                      decoration: const InputDecoration(
                        labelText: 'Expiry Date (MM/YY)',
                        border: OutlineInputBorder(),
                      ),
                      onChanged: (val) => _expiryDate = val,
                      validator: (val) =>
                          val!.isEmpty ? 'Required' : null,
                    ),
                  ),
                  const SizedBox(width: 16),
                  Expanded(
                    child: TextFormField(
                      decoration: const InputDecoration(
                        labelText: 'CVV',
                        border: OutlineInputBorder(),
                      ),
                      obscureText: true,
                      maxLength: 3,
                      keyboardType: TextInputType.number,
                      onChanged: (val) => _cvv = val,
                      validator: (val) =>
                          val!.length != 3 ? 'Invalid CVV' : null,
                    ),
                  ),
                ],
              ),

              const SizedBox(height: 30),

              _isProcessing
                  ? const Center(child: CircularProgressIndicator())
                  : ElevatedButton.icon(
                      onPressed: _handlePayment,
                      icon: const Icon(Icons.lock),
                      label: const Text(
                        'Pay Now',
                        style: TextStyle(fontSize: 18),
                      ),
                      style: ElevatedButton.styleFrom(
                        padding: const EdgeInsets.symmetric(vertical: 16),
                        backgroundColor: Colors.blue.shade700,
                      ),
                    ),
            ],
          ),
        ),
      ),
    );
  }
}
