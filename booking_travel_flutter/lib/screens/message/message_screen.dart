// import 'package:flutter/material.dart';
// import '../message/message_detail_screen.dart';

// class MessageScreen extends StatefulWidget {
//   @override
//   _MessageScreenState createState() => _MessageScreenState();
// }

// class _MessageScreenState extends State<MessageScreen> {
//   final List<MessageThread> allThreads = [
//     MessageThread(
//       id: '1',
//       sender: 'Travel Agent - Paris',
//       lastMessage: 'Your flight gate has changed to B12.',
//       timestamp: DateTime.now().subtract(Duration(hours: 6)),
//       messages: [
//         Message(
//           id: '1',
//           sender: 'Travel Agent',
//           content: 'Hello! I hope you\'re excited about your upcoming trip to Paris. I wanted to reach out with some important updates.',
//           timestamp: DateTime.now().subtract(Duration(days: 1)),
//           isSentByUser: false,
//         ),
//         Message(
//           id: '2',
//           sender: 'You',
//           content: 'Thanks! Can you confirm the pickup time?',
//           timestamp: DateTime.now().subtract(Duration(hours: 12)),
//           isSentByUser: true,
//         ),
//         Message(
//           id: '3',
//           sender: 'Travel Agent',
//           content: 'Your flight gate has changed to B12.',
//           timestamp: DateTime.now().subtract(Duration(hours: 6)),
//           isSentByUser: false,
//         ),
//       ],
//     ),
//     MessageThread(
//       id: '2',
//       sender: 'Hotel Manager - Pan Pacific',
//       lastMessage: 'Your reservation for 2 nights is confirmed.',
//       timestamp: DateTime.now().subtract(Duration(days: 1)),
//       messages: [
//         Message(
//           id: '4',
//           sender: 'Hotel Manager',
//           content: 'Your reservation for 2 nights is confirmed.',
//           timestamp: DateTime.now().subtract(Duration(days: 1)),
//           isSentByUser: false,
//         ),
//         Message(
//           id: '5',
//           sender: 'You',
//           content: 'Can I get a room with a view?',
//           timestamp: DateTime.now().subtract(Duration(hours: 18)),
//           isSentByUser: true,
//         ),
//       ],
//     ),
//     MessageThread(
//       id: '3',
//       sender: 'Customer Support',
//       lastMessage: 'How can we assist you with your booking?',
//       timestamp: DateTime.now().subtract(Duration(days: 2)),
//       messages: [
//         Message(
//           id: '6',
//           sender: 'Customer Support',
//           content: 'How can we assist you with your booking?',
//           timestamp: DateTime.now().subtract(Duration(days: 2)),
//           isSentByUser: false,
//         ),
//       ],
//     ),
//   ];

//   List<MessageThread> filteredThreads = [];
//   final TextEditingController _controller = TextEditingController();

//   @override
//   void initState() {
//     super.initState();
//     filteredThreads = allThreads;
//   }

//   void _filterThreads(String query) {
//     final results = allThreads.where((thread) =>
//         thread.sender.toLowerCase().contains(query.toLowerCase()) ||
//         thread.lastMessage.toLowerCase().contains(query.toLowerCase())).toList();
//     setState(() {
//       filteredThreads = results;
//     });
//   }

//   void _performSearch() {
//     _filterThreads(_controller.text);
//   }

//   @override
//   Widget build(BuildContext context) {
//     return Scaffold(
//       appBar: AppBar(
//         title: Text('Messages'),
//         backgroundColor: Colors.blue,
//       ),
//       body: Padding(
//         padding: const EdgeInsets.all(16),
//         child: Column(
//           children: [
//             Row(
//               children: [
//                 Expanded(
//                   child: TextField(
//                     controller: _controller,
//                     onChanged: _filterThreads,
//                     decoration: InputDecoration(
//                       hintText: 'Search messages...',
//                       prefixIcon: Icon(Icons.search),
//                       border: OutlineInputBorder(
//                         borderRadius: BorderRadius.circular(10),
//                       ),
//                     ),
//                   ),
//                 ),
//                 SizedBox(width: 10),
//                 ElevatedButton(
//                   onPressed: _performSearch,
//                   style: ElevatedButton.styleFrom(
//                     backgroundColor: Colors.blue,
//                     foregroundColor: Colors.white,
//                     shape: RoundedRectangleBorder(
//                       borderRadius: BorderRadius.circular(10),
//                     ),
//                   ),
//                   child: Text('Search'),
//                 ),
//               ],
//             ),
//             SizedBox(height: 20),
//             Expanded(
//               child: filteredThreads.isEmpty
//                   ? Center(child: Text('No messages found'))
//                   : ListView.builder(
//                       itemCount: filteredThreads.length,
//                       itemBuilder: (context, index) {
//                         final thread = filteredThreads[index];
//                         return Card(
//                           margin: EdgeInsets.symmetric(vertical: 8),
//                           shape: RoundedRectangleBorder(
//                               borderRadius: BorderRadius.circular(10)),
//                           child: ListTile(
//                             leading: Icon(Icons.message, color: Colors.blue),
//                             title: Text(thread.sender),
//                             subtitle: Text(
//                               thread.lastMessage,
//                               maxLines: 1,
//                               overflow: TextOverflow.ellipsis,
//                             ),
//                             trailing: Column(
//                               mainAxisAlignment: MainAxisAlignment.center,
//                               children: [
//                                 Text(
//                                   _formatTimestamp(thread.timestamp),
//                                   style: TextStyle(fontSize: 12, color: Colors.grey),
//                                 ),
//                               ],
//                             ),
//                             onTap: () {
//                               Navigator.push(
//                                 context,
//                                 MaterialPageRoute(
//                                   builder: (context) => MessageScreen(
//                                     thread: thread,
//                                   ),
//                                 ),
//                               );
//                             },
//                           ),
//                         );
//                       },
//                     ),
//             ),
//           ],
//         ),
//       ),
//     );
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
// }

// class MessageThread {
//   final String id;
//   final String sender;
//   final String lastMessage;
//   final DateTime timestamp;
//   final List<Message> messages;

//   MessageThread({
//     required this.id,
//     required this.sender,
//     required this.lastMessage,
//     required this.timestamp,
//     required this.messages,
//   });
// }

// class Message {
//   final String id;
//   final String sender;
//   final String content;
//   final DateTime timestamp;
//   final bool isSentByUser;

//   Message({
//     required this.id,
//     required this.sender,
//     required this.content,
//     required this.timestamp,
//     required this.isSentByUser,
//   });
// }