import 'dart:typed_data';

export 'save_receipt_mobile.dart'
    if (dart.library.html) 'save_receipt_web.dart';
