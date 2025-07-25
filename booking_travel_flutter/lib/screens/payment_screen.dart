import 'package:flutter/material.dart';

class PaymentScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Payment'),
        backgroundColor: Colors.blue,
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          children: [
            Text('Booking Summary',
                style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold)),

            SizedBox(height: 20),
            _buildBookingDetails(),

            SizedBox(height: 30),
            Text('Payment Method',
                style: TextStyle(fontSize: 18, fontWeight: FontWeight.w600)),

            SizedBox(height: 10),
            _buildPaymentOption(Icons.credit_card, 'Credit Card'),
            _buildPaymentOption(Icons.paypal, 'PayPal'),
            _buildPaymentOption(Icons.account_balance_wallet, 'Wallet'),

            Spacer(),
            ElevatedButton(
              onPressed: () {
                ScaffoldMessenger.of(context).showSnackBar(SnackBar(
                  content: Text('Payment Processed!'),
                ));
              },
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.blue,
                minimumSize: Size(double.infinity, 50),
              ),
              child: Text(
                'Pay Now',
                style: TextStyle(fontSize: 18),
              ),
            )
          ],
        ),
      ),
    );
  }

  Widget _buildBookingDetails() {
    return Container(
      padding: EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.grey.shade100,
        borderRadius: BorderRadius.circular(8),
        border: Border.all(color: Colors.grey.shade300),
      ),
      child: Column(
        children: [
          _buildRow('Hotel:', 'Pan Pacific'),
          _buildRow('Nights:', '3'),
          _buildRow('Guests:', '2'),
          _buildRow('Total:', '\$360'),
        ],
      ),
    );
  }

  Widget _buildRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [Text(label), Text(value)],
      ),
    );
  }

  Widget _buildPaymentOption(IconData icon, String method) {
    return ListTile(
      leading: Icon(icon, color: Colors.blue),
      title: Text(method),
      trailing: Icon(Icons.arrow_forward_ios, size: 16),
      onTap: () {
        // Optional: Handle selection or open detail screen
      },
    );
  }
}
