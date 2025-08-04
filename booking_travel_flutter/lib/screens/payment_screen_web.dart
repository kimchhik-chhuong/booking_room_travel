class Blob {}

String createObjectUrlFromBlob(Blob blob) => '';

void revokeObjectUrl(String url) {}

class AnchorElement {
  AnchorElement({required String href});
  void setAttribute(String name, String value) {}
  void click() {}
}

Blob createBlob(List<int> bytes, String type) => Blob();

AnchorElement createAnchor(String href) => AnchorElement(href: href);