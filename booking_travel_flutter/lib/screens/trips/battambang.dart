import 'package:flutter/material.dart';

class BattambangPage extends StatelessWidget {
  const BattambangPage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Stung Treng')),
      body: const Center(child: Text('Welcome to Stung Treng')),
    );
  }
}