<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #4e73df;
            --secondary: #858796;
            --success: #1cc88a;
            --info: #36b9cc;
            --warning: #f6c23e;
            --danger: #e74a3b;
            --light: #f8f9fc;
            --dark: #5a5c69;
        }
        
        body {
            background-color: #f8f9fc;
            color: var(--dark);
        }
        
        .sidebar {
            background: linear-gradient(180deg, var(--primary) 0%, #224abe 100%);
            min-height: 100vh;
            color: white;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 5px;
            border-radius: 5px;
            padding: 10px 15px;
        }
        
        .sidebar .nav-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
        }
        
        .topbar {
            background-color: white;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            height: 4.375rem;
        }
        
        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        }
        
        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            font-weight: 600;
        }
        
        .stat-card {
            border-left: 0.25rem solid;
        }
        
        .stat-card.primary {
            border-left-color: var(--primary);
        }
        
        .stat-card.success {
            border-left-color: var(--success);
        }
        
        .stat-card.info {
            border-left-color: var(--info);
        }
        
        .stat-card.warning {
            border-left-color: var(--warning);
        }
        
        .user-profile-img {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .activity-item:not(:last-child) {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-0">
                <div class="px-3 py-4">
                    <div class="text-center mb-4">
                        <h4>Admin Panel</h4>
                    </div>
                    <hr class="mt-0 mb-4" style="border-color: rgba(255,255,255,0.1)">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">
                                <i class="bi bi-speedometer2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-people"></i>
                                Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-file-earmark-text"></i>
                                Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-gear"></i>
                                Settings
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 ms-auto p-0">
                <!-- Topbar -->
                <nav class="navbar navbar-expand topbar mb-4 static-top shadow">
                    <div class="container-fluid">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        
                        <!-- Topbar Navbar -->
                        <ul class="navbar-nav ms-auto">
                            <!-- Nav Item - User Information -->
                            <li class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">Welcome, Admin</span>
                                    <img class="user-profile-img" src="https://ui-avatars.com/api/?name=Admin&background=4e73df&color=fff" alt="Profile">
                                </a>
                                <!-- Dropdown - User Information -->
                                <div class="dropdown-menu dropdown-menu-end shadow">
                                    <a class="dropdown-item" href="#">
                                        <i class="bi bi-person mr-2"></i>
                                        Profile
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right mr-2"></i>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>

                <!-- Begin Page Content -->
                <div class="container-fluid px-4">
                    <!-- Welcome Row -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card shadow">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <h2 class="card-title text-primary">Welcome back, Admin!</h2>
                                            <p class="card-text">You're logged in as <strong>Administrator</strong>. Here's an overview of your system.</p>
                                            <p class="text-muted"><small>Last login: First time login</small></p>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <img src="https://img.icons8.com/color/96/000000/admin-settings-male.png" alt="Admin" class="img-fluid" style="max-height: 100px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Row -->
                    <div class="row">
                        <!-- Total Users -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stat-card primary h-100">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Users</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">1,234</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="bi bi-people fs-2 text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Active Projects -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stat-card success h-100">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Active Projects</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">24</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="bi bi-folder fs-2 text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tasks Completed -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stat-card info h-100">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Tasks Completed</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">156</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="bi bi-check-circle fs-2 text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Requests -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stat-card warning h-100">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Pending Requests</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">8</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="bi bi-clock-history fs-2 text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="row">
                        <div class="col-lg-12 mb-4">
                            <div class="card shadow">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold">Recent Activity</h6>
                                </div>
                                <div class="card-body">
                                    <div class="activity-list">
                                        <div class="activity-item">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <i class="bi bi-check-circle-fill text-success fs-4"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-1">System updated to v2.1</h6>
                                                    <p class="mb-0 text-muted small">The system has been successfully updated to the latest version.</p>
                                                    <small class="text-muted">Today, 10:45 AM</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="activity-item">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <i class="bi bi-person-plus-fill text-primary fs-4"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-1">New user registered</h6>
                                                    <p class="mb-0 text-muted small">John Doe has created an account.</p>
                                                    <small class="text-muted">Yesterday, 3:30 PM</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="activity-item">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <i class="bi bi-file-earmark-text-fill text-info fs-4"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-1">Monthly report generated</h6>
                                                    <p class="mb-0 text-muted small">June system report has been created.</p>
                                                    <small class="text-muted">Jun 30, 2023</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>