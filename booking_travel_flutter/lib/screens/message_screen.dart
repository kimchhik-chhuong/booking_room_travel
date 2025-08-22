import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

class MessageScreen extends StatelessWidget {
  final List<Map<String, dynamic>> hotelChats = [
    {
      'hotelName': 'Hotel Paradise',
      'lastMessage': 'Your booking is confirmed',
      'time': '9:01 PM',
      'avatar': 'https://via.placeholder.com/150',
    },
    {
      'hotelName': 'Seaside Resort',
      'lastMessage': 'New message from guest',
      'time': '8:42 PM',
      'avatar': 'https://via.placeholder.com/150',
    },
    {
      'hotelName': 'Mountain View Hotel',
      'lastMessage': 'Room availability update',
      'time': '6:28 PM',
      'avatar': 'https://via.placeholder.com/150',
    },
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Hotel Messages'),
        backgroundColor: Colors.blue,
        foregroundColor: Colors.white,
      ),
      backgroundColor: Colors.white,
      body: ListView.builder(
        itemCount: hotelChats.length,
        itemBuilder: (context, index) {
          final chat = hotelChats[index];
          return ListTile(
            leading: CircleAvatar(
              radius: 25,
              backgroundImage: NetworkImage(chat['avatar']),
            ),
            title: Text(
              chat['hotelName'],
              style: const TextStyle(
                color: Colors.black,
                fontWeight: FontWeight.bold,
              ),
            ),
            subtitle: Text(
              chat['lastMessage'],
              style: const TextStyle(color: Colors.grey),
            ),
            trailing: Text(
              chat['time'],
              style: const TextStyle(color: Colors.grey, fontSize: 12),
            ),
            onTap: () {
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (_) => ChatScreen(
                    hotelName: chat['hotelName'],
                    avatarUrl: chat['avatar'],
                  ),
                ),
              );
            },
          );
        },
      ),
    );
  }
}

class ChatScreen extends StatefulWidget {
  final String hotelName;
  final String avatarUrl;

  ChatScreen({required this.hotelName, required this.avatarUrl});

  @override
  _ChatScreenState createState() => _ChatScreenState();
}

class _ChatScreenState extends State<ChatScreen> {
  final List<Map<String, String>> messages = [
    {'text': 'Hello! How can I help you?', 'sender': 'hotel', 'time': '10:24 AM'},
    {'text': 'I want to check room availability.', 'sender': 'user', 'time': '10:25 AM'},
  ];
  final TextEditingController _controller = TextEditingController();
  String? userName;

  @override
  void initState() {
    super.initState();
    _fetchUserName();
  }

  void _fetchUserName() async {
    // Simulate fetching the logged-in user's name (replace with your auth logic)
    // Example: Using SharedPreferences or FirebaseAuth
    userName = 'LoggedInUser'; // Placeholder, update with real data
    setState(() {});
  }

  Future<void> sendMessage() async {
    if (_controller.text.trim().isEmpty || userName == null) return;
    final now = TimeOfDay.now();
    final time = '${now.hour}:${now.minute} ${now.period == DayPeriod.pm ? 'PM' : 'AM'}';
    setState(() {
      messages.add({'text': _controller.text, 'sender': 'user', 'time': time});
    });
    _controller.clear();

    // Send message to backend
    final url = Uri.parse('https://your-laravel-api.com/api/messages');
    try {
      final response = await http.post(
        url,
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({
          'hotelId': widget.hotelName, // Match with admin's conversation ID
          'userName': userName,
          'message': _controller.text,
          'time': time,
        }),
      );

      if (response.statusCode == 200) {
        // Handle success
        print('Message sent successfully');
      } else {
        // Handle error
        print('Failed to send message: ${response.body}');
      }
    } catch (e) {
      print('Error sending message: $e');
    }

    // Simulate a reply from the hotel
    Future.delayed(const Duration(seconds: 2), () {
      if (mounted) {
        setState(() {
          messages.add({'text': 'Thank you! I’ll check and get back to you.', 'sender': 'hotel', 'time': time});
        });
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Row(
          children: [
            CircleAvatar(backgroundImage: NetworkImage(widget.avatarUrl)),
            const SizedBox(width: 8),
            Text(widget.hotelName),
          ],
        ),
      ),
      body: Column(
        children: [
          Expanded(
            child: ListView.builder(
              padding: const EdgeInsets.all(8),
              itemCount: messages.length,
              itemBuilder: (context, index) {
                final message = messages[index];
                final isUser = message['sender'] == 'user';
                return Align(
                  alignment: isUser ? Alignment.centerRight : Alignment.centerLeft,
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                    margin: const EdgeInsets.symmetric(vertical: 4),
                    decoration: BoxDecoration(
                      color: isUser ? Colors.blue : Colors.grey[300],
                      borderRadius: BorderRadius.circular(8),
                    ),
                    child: Column(
                      crossAxisAlignment: isUser ? CrossAxisAlignment.end : CrossAxisAlignment.start,
                      children: [
                        if (isUser && userName != null) Text(userName!, style: TextStyle(color: Colors.white70, fontSize: 12)),
                        Text(
                          message['text']!,
                          style: TextStyle(
                            color: isUser ? Colors.white : Colors.black,
                          ),
                        ),
                        Text(
                          message['time']!,
                          style: TextStyle(
                            color: isUser ? Colors.white70 : Colors.black54,
                            fontSize: 10,
                          ),
                        ),
                      ],
                    ),
                  ),
                );
              },
            ),
          ),
          SafeArea(
            child: Row(
              children: [
                Expanded(
                  child: TextField(
                    controller: _controller,
                    decoration: const InputDecoration(
                      hintText: 'Type a message...',
                      border: OutlineInputBorder(),
                    ),
                  ),
                ),
                IconButton(
                  icon: const Icon(Icons.send, color: Colors.blue),
                  onPressed: sendMessage,
                )
              ],
            ),
          ),
        ],
      ),
    );
  }
}