import 'package:flutter/material.dart';

class FlightsPage extends StatelessWidget {
  const FlightsPage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Flights')),
      body: const Center(child: Text('Welcome to Flights Page')),
    );
  }
}
