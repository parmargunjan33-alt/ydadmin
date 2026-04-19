<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'YD Admin Panel')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            overflow-y: auto;
            padding-top: 70px;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar-brand {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: -70px;
            padding-top: 90px;
        }

        .sidebar-brand h3 {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }

        .sidebar-menu li {
            margin: 0;
        }

        .sidebar-menu a {
            display: block;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border-left-color: white;
        }

        .sidebar-menu i {
            width: 20px;
            margin-right: 10px;
        }

        /* Navbar */
        .navbar-main {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 70px;
            background: white;
            border-bottom: 1px solid #e9ecef;
            padding: 0 20px;
            display: flex;
            align-items: center;
            z-index: 999;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .navbar-brand {
            font-size: 20px;
            font-weight: bold;
            color: #667eea;
        }

        .navbar-actions {
            margin-left: auto;
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .navbar-actions a, .navbar-actions button {
            color: #667eea;
            text-decoration: none;
            cursor: pointer;
            border: none;
            background: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .navbar-actions a:hover, .navbar-actions button:hover {
            color: #764ba2;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            margin-top: 70px;
            padding: 30px;
            min-height: calc(100vh - 140px);
        }

        /* Header */
        .page-header {
            background: white;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .page-header h1 {
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
            margin: 0 0 5px 0;
        }

        .page-header p {
            color: #7f8c8d;
            margin: 0;
            font-size: 14px;
        }

        /* Cards and Tables */
        .card {
            background: white;
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .card-header {
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            padding: 15px 20px;
            font-weight: 600;
            color: #2c3e50;
            border-radius: 8px 8px 0 0;
        }

        .card-body {
            padding: 20px;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
            font-weight: 600;
            color: #2c3e50;
            padding: 15px;
        }

        .table tbody td {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-sm {
            padding: 5px 12px;
            font-size: 13px;
        }

        /* Badges */
        .badge {
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-success {
            background-color: #10b981;
        }

        .badge-danger {
            background-color: #ef4444;
        }

        .badge-warning {
            background-color: #f59e0b;
        }

        /* Forms */
        .form-control {
            border: 1px solid #e9ecef;
            border-radius: 6px;
            padding: 10px 15px;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .form-group label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        /* Flash Messages */
        .alert {
            border: none;
            border-radius: 6px;
            padding: 15px 20px;
            margin-bottom: 20px;
            animation: slideIn 0.3s ease;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }

        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Footer */
        .footer {
            background: white;
            border-top: 1px solid #e9ecef;
            padding: 20px;
            text-align: center;
            color: #7f8c8d;
            font-size: 13px;
            margin-left: 280px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 15px;
            }

            .footer {
                margin-left: 0;
            }

            .navbar-main {
                padding: 0 15px;
            }

            .page-header {
                padding: 15px;
            }

            .page-header h1 {
                font-size: 22px;
            }
        }

        /* Action Buttons */
        .btn-edit, .btn-delete {
            display: inline-block;
            padding: 5px 10px;
            margin: 0 3px;
            border-radius: 4px;
            font-size: 12px;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            background: none;
        }

        .btn-edit {
            color: #3498db;
        }

        .btn-edit:hover {
            background-color: #ecf0f1;
        }

        .btn-delete {
            color: #e74c3c;
        }

        .btn-delete:hover {
            background-color: #ffe6e6;
        }

        .btn-secondary {
            background-color: #95a5a6;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #7f8c8d;
        }

        /* Stats Cards */
        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            text-align: center;
        }

        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
            margin: 10px 0;
        }

        .stat-label {
            color: #7f8c8d;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar-main">
        <div class="navbar-brand">
            <i class="bi bi-speedometer2"></i> YD Admin
        </div>
        <div class="navbar-actions">
            <span id="admin-name">Admin</span>
            <form action="{{ route('admin.logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn-link">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <h3>YD Panel</h3>
        </div>
        <ul class="sidebar-menu">
            <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-graph-up"></i> Dashboard
            </a></li>
            <li><a href="{{ route('admin.universities.index') }}" class="{{ request()->routeIs('admin.universities.*') ? 'active' : '' }}">
                <i class="bi bi-building"></i> Universities
            </a></li>
            <li><a href="{{ route('admin.courses.index') }}" class="{{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
                <i class="bi bi-book"></i> Courses
            </a></li>
            <li><a href="{{ route('admin.semesters.index') }}" class="{{ request()->routeIs('admin.semesters.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-event"></i> Semesters
            </a></li>
            <li><a href="{{ route('admin.subjects.index') }}" class="{{ request()->routeIs('admin.subjects.*') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i> Subjects
            </a></li>
            <li><a href="{{ route('admin.pdfs.index') }}" class="{{ request()->routeIs('admin.pdfs.*') ? 'active' : '' }}">
                <i class="bi bi-file-pdf"></i> PDF Management
            </a></li>
            <li><a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Users
            </a></li>
            <li><a href="{{ route('admin.subscriptions.index') }}" class="{{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">
                <i class="bi bi-credit-card"></i> Subscriptions
            </a></li>
            <li><a href="{{ route('admin.config.index') }}" class="{{ request()->routeIs('admin.config.*') ? 'active' : '' }}">
                <i class="bi bi-gear"></i> App Config
            </a></li>
            <li><a href="{{ route('admin.admin-users.index') }}" class="{{ request()->routeIs('admin.admin-users.*') ? 'active' : '' }}">
                <i class="bi bi-shield-check"></i> Admin Users
            </a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Flash Messages -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Validation Error:</strong>
                <ul style="margin-bottom: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i> {{ session('warning') }}
            </div>
        @endif

        <!-- Page Header -->
        @if (View::hasSection('page_title'))
            <div class="page-header">
                <h1>@yield('page_title')</h1>
                @if (View::hasSection('page_subtitle'))
                    <p>@yield('page_subtitle')</p>
                @endif
            </div>
        @endif

        <!-- Page Content -->
        @yield('content')
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2026 YD Backend Admin. All rights reserved.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script>
        // Auto-hide alerts after 5 seconds
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        });

        // Confirm delete action
        function confirmDelete(url) {
            Swal.fire({
                title: 'Delete Item?',
                text: 'Are you sure you want to delete this item? This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#95a5a6',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.location.href = url;
                }
            });
        }
    </script>
</body>
</html>
