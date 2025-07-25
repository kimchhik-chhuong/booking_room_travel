import 'package:flutter/material.dart';

class HotelsPage extends StatelessWidget {
  const HotelsPage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Hotels')),
      body: const Center(child: Text('Welcome to Hotels Page')),
    );
  }
}
