
import 'package:flutter/material.dart';

class KohKongPage extends StatelessWidget {
  const KohKongPage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Kampot')),
      body: const Center(child: Text('Welcome to Kampot')),
    );
  }
}