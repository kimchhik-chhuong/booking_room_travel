import 'package:flutter/material.dart';

class KepPage extends StatelessWidget {
  const KepPage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Kep')),
      body: const Center(child: Text('Welcome to Kep')),
    );
  }
}