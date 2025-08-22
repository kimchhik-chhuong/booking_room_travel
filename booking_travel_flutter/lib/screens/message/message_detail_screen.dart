// import 'package:flutter/material.dart';

// import 'message_screen.dart' show Message, MessageThread;

// class MessageDetailScreen extends StatefulWidget {
//   final MessageThread thread;

//   MessageDetailScreen({required this.thread});

//   @override
//   _MessageDetailScreenState createState() => _MessageDetailScreenState();
// }

// class _MessageDetailScreenState extends State<MessageDetailScreen> {
//   final TextEditingController _messageController = TextEditingController();
//   late List<Message> _messages;

//   @override
//   void initState() {
//     super.initState();
//     _messages = List.from(widget.thread.messages);
//   }

//   void _sendMessage() {
//     if (_messageController.text.trim().isEmpty) return;

//     setState(() {
//       _messages.add(
//         Message(
//           id: DateTime.now().millisecondsSinceEpoch.toString(),
//           sender: 'You',
//           content: _messageController.text.trim(),
//           timestamp: DateTime.now(),
//           isSentByUser: true,
//         ),
//       );
//       _messageController.clear();
//     });

//     Future.delayed(Duration(seconds: 2), () {
//       setState(() {
//         _messages.add(
//           Message(
//             id: DateTime.now().millisecondsSinceEpoch.toString(),
//             sender: widget.thread.sender,
//             content: 'Thank you for your message! I’ll get back to you soon with more details.',
//             timestamp: DateTime.now(),
//             isSentByUser: false,
//           ),
//         );
//       });
//     });
//   }

//   String _formatTimestamp(DateTime timestamp) {
//     final now = DateTime.now();
//     final difference = now.difference(timestamp);

//     if (difference.inMinutes < 60) {
//       return '${difference.inMinutes}m ago';
//     } else if (difference.inHours < 24) {
//       return '${difference.inHours}h ago';
//     } else {
//       return '${timestamp.day}/${timestamp.month}/${timestamp.year}';
//     }
//   }

//   @override
//   Widget build(BuildContext context) {
//     final senderImages = {
//       'Travel Agent - Paris': [
//         'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&q=80',
//         'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=800&q=80',
//       ],
//       'Hotel Manager - Pan Pacific': [
//         'https://images.unsplash.com/photo-1527980965255-d3b416303d12?w=800&q=80',
//         'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=800&q=80',
//       ],
//       'Customer Support': [
//         'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=800&q=80',
//         'https://images.unsplash.com/photo-1544725176-7c40e5a71c5e?w=800&q=80',
//       ],
//     };

//     final images = senderImages[widget.thread.sender] ?? ['https://via.placeholder.com/800x400?text=No+Image'];

//     return Scaffold(
//       appBar: AppBar(
//         title: Text(widget.thread.sender),
//         backgroundColor: Colors.blue,
//       ),
//       body: Padding(
//         padding: const EdgeInsets.all(16),
//         child: SingleChildScrollView(
//           child: Column(
//             crossAxisAlignment: CrossAxisAlignment.start,
//             children: [
//               SizedBox(
//                 height: 200,
//                 child: ListView.builder(
//                   scrollDirection: Axis.horizontal,
//                   itemCount: images.length,
//                   itemBuilder: (context, index) {
//                     return Padding(
//                       padding: const EdgeInsets.only(right: 10),
//                       child: ClipRRect(
//                         borderRadius: BorderRadius.circular(10),
//                         child: Image.network(
//                           images[index],
//                           width: 300,
//                           height: 200,
//                           fit: BoxFit.cover,
//                           errorBuilder: (context, error, stackTrace) {
//                             return Container(
//                               width: 300,
//                               height: 200,
//                               color: Colors.grey[300],
//                               child: const Icon(Icons.broken_image, size: 40),
//                             );
//                           },
//                         ),
//                       ),
//                     );
//                   },
//                 ),
//               ),
//               SizedBox(height: 20),
//               Text(
//                 widget.thread.sender,
//                 style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
//               ),
//               SizedBox(height: 20),
//               Text(
//                 'Conversation',
//                 style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
//               ),
//               SizedBox(height: 10),
//               Container(
//                 height: 300,
//                 child: ListView.builder(
//                   itemCount: _messages.length,
//                   itemBuilder: (context, index) {
//                     final message = _messages[index];
//                     final isSentByUser = message.isSentByUser;
//                     return Align(
//                       alignment: isSentByUser ? Alignment.centerRight : Alignment.centerLeft,
//                       child: Container(
//                         margin: EdgeInsets.symmetric(vertical: 4, horizontal: 8),
//                         padding: EdgeInsets.all(12),
//                         decoration: BoxDecoration(
//                           color: isSentByUser ? Colors.blue[100] : Colors.grey[200],
//                           borderRadius: BorderRadius.circular(10),
//                           boxShadow: [
//                             BoxShadow(
//                               color: Colors.black12,
//                               blurRadius: 4,
//                               offset: Offset(0, 2),
//                             ),
//                           ],
//                         ),
//                         constraints: BoxConstraints(maxWidth: MediaQuery.of(context).size.width * 0.7),
//                         child: Column(
//                           crossAxisAlignment: isSentByUser ? CrossAxisAlignment.end : CrossAxisAlignment.start,
//                           children: [
//                             Text(
//                               message.sender,
//                               style: TextStyle(
//                                 fontSize: 12,
//                                 fontWeight: FontWeight.bold,
//                                 color: isSentByUser ? Colors.blue[800] : Colors.grey[800],
//                               ),
//                             ),
//                             SizedBox(height: 4),
//                             Text(
//                               message.content,
//                               style: TextStyle(fontSize: 14),
//                             ),
//                             SizedBox(height: 4),
//                             Text(
//                               _formatTimestamp(message.timestamp),
//                               style: TextStyle(fontSize: 10, color: Colors.grey[600]),
//                             ),
//                           ],
//                         ),
//                       ),
//                     );
//                   },
//                 ),
//               ),
//               SizedBox(height: 20),
//               Row(
//                 children: [
//                   Expanded(
//                     child: TextField(
//                       controller: _messageController,
//                       decoration: InputDecoration(
//                         hintText: 'Type a message...',
//                         border: OutlineInputBorder(
//                           borderRadius: BorderRadius.circular(10),
//                         ),
//                         filled: true,
//                         fillColor: Colors.grey[100],
//                       ),
//                     ),
//                   ),
//                   SizedBox(width: 10),
//                   ElevatedButton(
//                     onPressed: _sendMessage,
//                     style: ElevatedButton.styleFrom(
//                       backgroundColor: Colors.blue,
//                       foregroundColor: Colors.white,
//                       shape: RoundedRectangleBorder(
//                         borderRadius: BorderRadius.circular(10),
//                       ),
//                     ),
//                     child: Text('Send'),
//                   ),
//                 ],
//               ),
//             ],
//           ),
//         ),
//       ),
//     );
//   }
// }