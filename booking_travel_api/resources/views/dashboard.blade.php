<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5">
    <h1>Dashboard</h1>
    <p>Welcome, {{ auth()->user()->name }}!</p>
    <p>Your role: {{ auth()->user()->role }}</p>

    <h2>User Management</h2>
    <p>This dashboard section is intended to manage user data for the Flutter app.</p>
    <ul>
        <li>View user list</li>
        <li>Add new users</li>
        <li>Edit existing users</li>
        <li>Delete users</li>
    </ul>

    <h2>API Endpoints</h2>
    <p>The Flutter app interacts with the following API endpoints for user management:</p>
    <ul>
        <li>GET /api/users - List users</li>
        <li>POST /api/users - Create user</li>
        <li>PUT /api/users/{id} - Update user</li>
        <li>DELETE /api/users/{id} - Delete user</li>
    </ul>

    <p>Ensure these endpoints are secured and accessible only to authorized roles.</p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-danger">Logout</button>
    </form>
</div>
</body>
</html>
