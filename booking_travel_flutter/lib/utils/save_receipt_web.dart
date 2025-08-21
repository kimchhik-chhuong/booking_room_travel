import 'dart:html' as html;
import 'dart:typed_data';

Future<void> saveReceipt(Uint8List pngBytes) async {
  final blob = html.Blob([pngBytes], 'image/png');
  final url = html.Url.createObjectUrlFromBlob(blob);
  final anchor = html.AnchorElement(href: url)
    ..setAttribute('download', 'receipt_${DateTime.now().millisecondsSinceEpoch}.png')
    ..click();
  html.Url.revokeObjectUrl(url);
}
