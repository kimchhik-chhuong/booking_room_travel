import 'package:flutter/material.dart';
import 'package:booking_travel/screens/adventures/adventures_page.dart';

class PageAdventuresPage extends StatelessWidget {
  final int provinceId;
  final String provinceName;

  const PageAdventuresPage({
    super.key,
    required this.provinceId,
    required this.provinceName,
  });

  @override
  Widget build(BuildContext context) {
    return AdventuresPage(
      provinceId: provinceId,
      provinceName: provinceName,
    );
  }
}
