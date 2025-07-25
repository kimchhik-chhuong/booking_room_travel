import 'package:flutter/material.dart';

class OffersPage extends StatelessWidget {
  const OffersPage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Offers')),
      body: const Center(child: Text('Welcome to Offers Page')),
    );
  }
}
