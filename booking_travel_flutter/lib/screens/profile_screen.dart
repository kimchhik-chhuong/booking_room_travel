import 'dart:io';
import 'dart:typed_data';
import 'dart:convert';

import 'package:flutter/foundation.dart' show kIsWeb;
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:shared_preferences/shared_preferences.dart';

/// Fake user data (frontend only)
Map<String, dynamic> fakeUser = {
  "name": "John Doe",
  "email": "johndoe@example.com",
  "profile_image_url": "https://via.placeholder.com/150",
  "created_at": DateTime.now().toIso8601String(),
};

class ProfileScreen extends StatefulWidget {
  const ProfileScreen({super.key});

  @override
  State<ProfileScreen> createState() => _ProfileScreenState();
}

class _ProfileScreenState extends State<ProfileScreen> {
  Map<String, dynamic>? _currentUser;
  Map<String, dynamic>? _originalUser;
  bool _hasChanges = false;

  File? _profileImageFile; // Mobile
  Uint8List? _profileImageBytes; // Web
  String? _profileImageFileName;

  final ImagePicker _picker = ImagePicker();

  @override
  void initState() {
    super.initState();
    _loadUserData();
  }

  Future<void> _loadUserData() async {
    final prefs = await SharedPreferences.getInstance();
    final userData = prefs.getString('user');

    if (userData != null) {
      setState(() {
        _currentUser = Map<String, dynamic>.from(jsonDecode(userData));
        _originalUser = Map.from(_currentUser!);
        _hasChanges = false;
      });
    } else {
      setState(() {
        _currentUser = Map.from(fakeUser);
        _originalUser = Map.from(fakeUser);
        _hasChanges = false;
      });
    }
  }

  void _checkForChanges() {
    setState(() {
      _hasChanges = _originalUser.toString() != _currentUser.toString() ||
          _profileImageFile != null ||
          _profileImageBytes != null;
    });
  }

  // --- Convert image to base64 ---
  String? _imageToBase64() {
    if (_profileImageBytes != null) {
      return base64Encode(_profileImageBytes!); // Web
    } else if (_profileImageFile != null) {
      return base64Encode(_profileImageFile!.readAsBytesSync()); // Mobile
    }
    return null;
  }

  // --- Get image provider (from memory, file, base64, or url) ---
  ImageProvider _getProfileImage() {
    if (_profileImageBytes != null) {
      return MemoryImage(_profileImageBytes!);
    } else if (_profileImageFile != null) {
      return FileImage(_profileImageFile!);
    } else if (_currentUser?['profile_image_base64'] != null) {
      return MemoryImage(base64Decode(_currentUser!['profile_image_base64']));
    } else {
      return NetworkImage(
        _currentUser?['profile_image_url'] ?? 'https://via.placeholder.com/150',
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    if (_currentUser == null) {
      return const Scaffold(
        body: Center(child: Text("No user found")),
      );
    }

    return Scaffold(
      appBar: AppBar(
        title: const Text("My Profile"),
        backgroundColor: Colors.blueAccent,
        actions: [
          IconButton(
            onPressed: () => _showLogoutDialog(context),
            icon: const Icon(Icons.logout),
          ),
        ],
      ),
      body: SingleChildScrollView(
        physics: const AlwaysScrollableScrollPhysics(),
        child: Column(
          children: [
            const SizedBox(height: 24),
            _buildProfileSection(),
            const SizedBox(height: 24),
            _buildInfoCards(),
            const SizedBox(height: 24),
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: ElevatedButton(
                onPressed: _hasChanges ? _saveChanges : null,
                style: ElevatedButton.styleFrom(
                  minimumSize: const Size(double.infinity, 52),
                  backgroundColor:
                      _hasChanges ? Colors.green : Colors.grey.shade400,
                  shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(16)),
                ),
                child: const Text(
                  "Save Changes",
                  style: TextStyle(fontSize: 17, fontWeight: FontWeight.bold),
                ),
              ),
            ),
            const SizedBox(height: 32),
          ],
        ),
      ),
    );
  }

  Widget _buildProfileSection() {
    return Column(
      children: [
        Stack(
          children: [
            CircleAvatar(
              radius: 64,
              backgroundColor: Colors.grey[200],
              child: ClipOval(
                child: Image(
                  image: _getProfileImage(),
                  width: 128,
                  height: 128,
                  fit: BoxFit.cover,
                ),
              ),
            ),
            Positioned(
              bottom: 0,
              right: 4,
              child: GestureDetector(
                onTap: () => _pickImageDialog(context),
                child: Container(
                  decoration: BoxDecoration(
                    color: Colors.white,
                    shape: BoxShape.circle,
                    border: Border.all(color: Colors.blueAccent, width: 2),
                  ),
                  padding: const EdgeInsets.all(6),
                  child: const Icon(Icons.camera_alt,
                      color: Colors.blueAccent, size: 20),
                ),
              ),
            ),
          ],
        ),
        const SizedBox(height: 12),
        GestureDetector(
          onTap: () => _showEditNameDialog(context),
          child: Text(
            _currentUser!['name'] ?? "Unknown User",
            style: const TextStyle(
                fontSize: 24,
                fontWeight: FontWeight.bold,
                color: Colors.black87),
          ),
        ),
        const SizedBox(height: 4),
        Text(
          _currentUser!['email'] ?? "No email",
          style: const TextStyle(color: Colors.black54, fontSize: 16),
        ),
      ],
    );
  }

  Widget _buildInfoCards() {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16),
      child: Column(
        children: [
          _buildInfoCard(
            icon: Icons.person,
            label: "Full Name",
            value: _currentUser!['name'] ?? "Not set",
            onTap: () => _showEditNameDialog(context),
          ),
          _buildInfoCard(
            icon: Icons.email,
            label: "Email",
            value: _currentUser!['email'] ?? "Not set",
            onTap: () => _showEditEmailDialog(context),
          ),
          if (_currentUser!['created_at'] != null)
            _buildInfoCard(
              icon: Icons.calendar_today,
              label: "Member Since",
              value: _formatDate(_currentUser!['created_at']),
            ),
        ],
      ),
    );
  }

  Widget _buildInfoCard({
    required IconData icon,
    required String label,
    required String value,
    VoidCallback? onTap,
  }) {
    return Card(
      margin: const EdgeInsets.only(bottom: 14),
      elevation: 3,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
      child: ListTile(
        contentPadding: const EdgeInsets.symmetric(horizontal: 20, vertical: 8),
        leading: Icon(icon, color: Colors.blueAccent, size: 28),
        title: Text(label,
            style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 16)),
        subtitle: Text(value,
            style: const TextStyle(fontSize: 15, color: Colors.black87)),
        trailing: onTap != null
            ? const Icon(Icons.edit, size: 22, color: Colors.blueAccent)
            : null,
        onTap: onTap,
      ),
    );
  }

  String _formatDate(String dateString) {
    try {
      final date = DateTime.parse(dateString);
      return "${date.day}/${date.month}/${date.year}";
    } catch (e) {
      return dateString;
    }
  }

  // --- Image Picker ---
  void _pickImageDialog(BuildContext context) {
    showDialog(
      context: context,
      builder: (_) => AlertDialog(
        title: const Text("Update Profile Photo"),
        content: const Text("Choose an option to update your profile photo."),
        actions: [
          TextButton(
            onPressed: () {
              _pickImage(ImageSource.gallery);
              Navigator.pop(context);
            },
            child: const Text("Select from Gallery"),
          ),
          TextButton(
            onPressed: () {
              _pickImage(ImageSource.camera);
              Navigator.pop(context);
            },
            child: const Text("Take a Photo"),
          ),
          TextButton(
              onPressed: () => Navigator.pop(context),
              child: const Text("Cancel")),
        ],
      ),
    );
  }

  Future<void> _pickImage(ImageSource source) async {
    final picked = await _picker.pickImage(source: source, maxWidth: 600);
    if (picked != null) {
      if (kIsWeb) {
        final bytes = await picked.readAsBytes();
        setState(() {
          _profileImageBytes = bytes;
          _profileImageFileName = picked.name;
          _checkForChanges();
        });
      } else {
        setState(() {
          _profileImageFile = File(picked.path);
          _checkForChanges();
        });
      }
    }
  }

  void _showEditNameDialog(BuildContext context) {
    final controller = TextEditingController(text: _currentUser!['name'] ?? '');
    showDialog(
      context: context,
      builder: (_) => AlertDialog(
        title: const Text("Edit Name"),
        content: TextField(controller: controller),
        actions: [
          TextButton(
              onPressed: () => Navigator.pop(context),
              child: const Text("Cancel")),
          TextButton(
            onPressed: () {
              setState(() {
                _currentUser!['name'] = controller.text;
                _checkForChanges();
              });
              Navigator.pop(context);
            },
            child: const Text("Save"),
          ),
        ],
      ),
    );
  }

  void _showEditEmailDialog(BuildContext context) {
    final controller = TextEditingController(text: _currentUser!['email'] ?? '');
    showDialog(
      context: context,
      builder: (_) => AlertDialog(
        title: const Text("Edit Email"),
        content: TextField(
          controller: controller,
          keyboardType: TextInputType.emailAddress,
        ),
        actions: [
          TextButton(
              onPressed: () => Navigator.pop(context),
              child: const Text("Cancel")),
          TextButton(
            onPressed: () {
              setState(() {
                _currentUser!['email'] = controller.text;
                _checkForChanges();
              });
              Navigator.pop(context);
            },
            child: const Text("Save"),
          ),
        ],
      ),
    );
  }

  void _showLogoutDialog(BuildContext context) {
    showDialog(
      context: context,
      builder: (_) => AlertDialog(
        title: const Text("Logout"),
        content: const Text("Are you sure you want to logout?"),
        actions: [
          TextButton(
              onPressed: () => Navigator.pop(context),
              child: const Text("Cancel")),
          TextButton(
            onPressed: () {
              Navigator.pushReplacementNamed(context, '/login');
            },
            child: const Text("Logout"),
          ),
        ],
      ),
    );
  }

  void _saveChanges() async {
    final prefs = await SharedPreferences.getInstance();

    setState(() {
      final base64Image = _imageToBase64();
      if (base64Image != null) {
        _currentUser!['profile_image_base64'] = base64Image;
      }

      _originalUser = Map.from(_currentUser!);
      _profileImageBytes = null;
      _profileImageFile = null;
      _profileImageFileName = null;
      _hasChanges = false;
    });

    await prefs.setString('user', jsonEncode(_currentUser));

    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(
        content: Text('Profile updated (saved locally with image)'),
        backgroundColor: Colors.green,
      ),
    );
  }
}
