import 'package:flutter/material.dart';

class KompotPage extends StatelessWidget {
  const KompotPage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Kampot')),
      body: const Center(child: Text('Welcome to Kampot')),
    );
  }
}