class UserRole {
  final String name;
  final String description;
  final Map<String, bool> defaultPermissions;

  const UserRole({
    required this.name,
    required this.description,
    required this.defaultPermissions,
  });

  // Predefined roles
  static const UserRole user = UserRole(
    name: 'user',
    description: 'Regular user with basic permissions',
    defaultPermissions: {
      'read_content': true,
      'create_content': false,
      'edit_own_content': true,
      'delete_own_content': true,
      'manage_users': false,
      'manage_roles': false,
      'view_analytics': false,
      'access_admin': false,
    },
  );

  static const UserRole editor = UserRole(
    name: 'editor',
    description: 'Editor with content management permissions',
    defaultPermissions: {
      'read_content': true,
      'create_content': true,
      'edit_own_content': true,
      'edit_any_content': true,
      'delete_own_content': true,
      'delete_any_content': false,
      'manage_users': false,
      'manage_roles': false,
      'view_analytics': true,
      'access_admin': true,
    },
  );

  static const UserRole admin = UserRole(
    name: 'admin',
    description: 'Administrator with high-level permissions',
    defaultPermissions: {
      'read_content': true,
      'create_content': true,
      'edit_own_content': true,
      'edit_any_content': true,
      'delete_own_content': true,
      'delete_any_content': true,
      'manage_users': true,
      'manage_roles': false,
      'view_analytics': true,
      'access_admin': true,
    },
  );

  static const UserRole superadmin = UserRole(
    name: 'superadmin',
    description: 'Super administrator with all permissions',
    defaultPermissions: {
      'read_content': true,
      'create_content': true,
      'edit_own_content': true,
      'edit_any_content': true,
      'delete_own_content': true,
      'delete_any_content': true,
      'manage_users': true,
      'manage_roles': true,
      'view_analytics': true,
      'access_admin': true,
      'system_settings': true,
    },
  );

  // Get all available roles
  static List<UserRole> get allRoles => [user, editor, admin, superadmin];

  // Create a role from string
  static UserRole fromString(String roleName) {
    return allRoles.firstWhere(
      (role) => role.name == roleName,
      orElse: () => user, // Default to user role if not found
    );
  }

  // Check if role has a specific permission
  bool hasPermission(String permission) {
    return defaultPermissions[permission] ?? false;
  }

  @override
  bool operator ==(Object other) {
    if (identical(this, other)) return true;
    return other is UserRole && other.name == name;
  }

  @override
  int get hashCode => name.hashCode;
}