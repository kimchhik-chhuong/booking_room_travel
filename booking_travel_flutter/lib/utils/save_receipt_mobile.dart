import 'dart:io';
import 'dart:typed_data';
import 'package:path_provider/path_provider.dart';

Future<void> saveReceipt(Uint8List pngBytes) async {
  final directory = await getApplicationDocumentsDirectory();
  final filePath = '${directory.path}/receipt_${DateTime.now().millisecondsSinceEpoch}.png';
  final file = File(filePath);
  await file.writeAsBytes(pngBytes);
}
