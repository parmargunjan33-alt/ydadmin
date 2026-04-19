<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - YD Backend</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h3 {
            font-size: 20px;
            margin: 0;
            font-weight: 700;
        }

        .sidebar-header p {
            font-size: 12px;
            opacity: 0.8;
            margin: 5px 0 0 0;
        }

        .sidebar-nav {
            padding: 20px 0;
        }

        .sidebar-nav .nav-section {
            margin-bottom: 0;
        }

        .sidebar-nav .section-title {
            padding: 12px 20px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.7;
            font-weight: 600;
            margin-top: 15px;
        }

        .sidebar-nav .nav-link {
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            font-size: 14px;
        }

        .sidebar-nav .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border-left-color: white;
        }

        .sidebar-nav .nav-link.active {
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
            border-left-color: white;
            font-weight: 600;
        }

        .sidebar-nav .nav-link i {
            width: 20px;
            margin-right: 12px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* Navbar */
        .navbar {
            background: white;
            border-bottom: 1px solid #e0e0e0;
            padding: 0 30px;
            height: 70px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .navbar-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100%;
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .navbar-title h1 {
            font-size: 22px;
            font-weight: 600;
            color: #333;
            margin: 0;
        }

        .navbar-title p {
            font-size: 12px;
            color: #999;
            margin: 0;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .admin-info {
            text-align: right;
        }

        .admin-info .admin-name {
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }

        .admin-info .admin-role {
            font-size: 12px;
            color: #999;
        }

        .admin-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
        }

        .logout-btn {
            padding: 8px 16px;
            background-color: #f5f7fa;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }

        .logout-btn:hover {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
        }

        /* Page Content */
        .page-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }

        .page-description {
            font-size: 14px;
            color: #999;
        }

        .page-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background-color: #e0e0e0;
            border: none;
            color: #333;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #d0d0d0;
        }

        .btn-success {
            background-color: #28a745;
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-small {
            padding: 6px 12px;
            font-size: 12px;
        }

        /* Cards & Content */
        .card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .card-header {
            background-color: #f5f7fa;
            border-bottom: 1px solid #e0e0e0;
            padding: 15px 20px;
            font-weight: 600;
        }

        .card-body {
            padding: 20px;
        }

        /* Tables */
        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: #f5f7fa;
            border-bottom: 2px solid #e0e0e0;
            font-weight: 600;
            color: #333;
            padding: 15px;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background-color: #f9f9f9;
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            color: #666;
        }

        .table-actions {
            display: flex;
            gap: 8px;
        }

        .table-actions a,
        .table-actions button {
            padding: 6px 12px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s ease;
        }

        .table-actions .btn-edit {
            background-color: #007bff;
            color: white;
        }

        .table-actions .btn-edit:hover {
            background-color: #0056b3;
        }

        .table-actions .btn-delete {
            background-color: #dc3545;
            color: white;
        }

        .table-actions .btn-delete:hover {
            background-color: #c82333;
        }

        .table-actions .btn-view {
            background-color: #17a2b8;
            color: white;
        }

        .table-actions .btn-view:hover {
            background-color: #138496;
        }

        /* Forms */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 10px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-row.full {
            grid-template-columns: 1fr;
        }

        /* Status Badges */
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }

        .badge-info {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        /* Alerts */
        .alert {
            border-radius: 8px;
            border: 1px solid transparent;
            margin-bottom: 20px;
            padding: 15px 20px;
        }

        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }

        .alert-warning {
            background-color: #fff3cd;
            border-color: #ffeaa7;
            color: #856404;
        }

        .alert-info {
            background-color: #d1ecf1;
            border-color: #bee5eb;
            color: #0c5460;
        }

        /* Stat Cards */
        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            text-align: center;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            font-size: 32px;
            margin-bottom: 10px;
            height: 60px;
            width: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: auto;
            margin-right: auto;
        }

        .stat-icon.blue {
            background-color: #e3f2fd;
            color: #2196f3;
        }

        .stat-icon.green {
            background-color: #e8f5e9;
            color: #4caf50;
        }

        .stat-icon.orange {
            background-color: #fff3e0;
            color: #ff9800;
        }

        .stat-icon.red {
            background-color: #ffebee;
            color: #f44336;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            margin: 10px 0 5px 0;
        }

        .stat-label {
            font-size: 13px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Pagination */
        .pagination {
            display: flex;
            gap: 5px;
            margin-top: 20px;
            justify-content: center;
        }

        .pagination a,
        .pagination span {
            padding: 8px 12px;
            border-radius: 4px;
            border: 1px solid #e0e0e0;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: #333;
        }

        .pagination a:hover {
            background-color: #f5f7fa;
            border-color: #667eea;
        }

        .pagination .active span {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: #667eea;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: all 0.3s ease;
                width: 100%;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .page-content {
                padding: 20px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .table {
                font-size: 12px;
            }

            .table thead th,
            .table tbody td {
                padding: 10px;
            }

            .page-title {
                font-size: 22px;
            }

            .navbar {
                padding: 0 15px;
            }
        }
    </style>
    @yield('extra_css')
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h3><i class="bi bi-gear"></i> YD Admin</h3>
                <p>Management System</p>
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('admin.dashboard') }}"
                    class="nav-link @if (request()->route()->getName() == 'admin.dashboard') active @endif">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>

                <div class="section-title">MANAGE CONTENT</div>

                <a href="{{ route('admin.universities.index') }}"
                    class="nav-link @if (strpos(request()->route()->getName(), 'universities') !== false) active @endif">
                    <i class="bi bi-building"></i>
                    <span>Universities</span>
                </a>

                <a href="{{ route('admin.courses.index') }}"
                    class="nav-link @if (strpos(request()->route()->getName(), 'courses') !== false) active @endif">
                    <i class="bi bi-book"></i>
                    <span>Courses</span>
                </a>

                <a href="{{ route('admin.semesters.index') }}"
                    class="nav-link @if (strpos(request()->route()->getName(), 'semesters') !== false) active @endif">
                    <i class="bi bi-calendar-range"></i>
                    <span>Semesters</span>
                </a>

                <a href="{{ route('admin.subjects.index') }}"
                    class="nav-link @if (strpos(request()->route()->getName(), 'subjects') !== false) active @endif">
                    <i class="bi bi-file-text"></i>
                    <span>Subjects</span>
                </a>

                <div class="section-title">MANAGE FILES & USERS</div>

                <a href="{{ route('admin.users.index') }}"
                    class="nav-link @if (strpos(request()->route()->getName(), 'users') !== false) active @endif">
                    <i class="bi bi-people"></i>
                    <span>Users</span>
                </a>

                <a href="{{ route('admin.subscriptions.index') }}"
                    class="nav-link @if (strpos(request()->route()->getName(), 'subscriptions') !== false) active @endif">
                    <i class="bi bi-credit-card"></i>
                    <span>Subscriptions</span>
                </a>

                <div class="section-title">SYSTEM</div>

                <a href="{{ route('admin.config.index') }}"
                    class="nav-link @if (strpos(request()->route()->getName(), 'config') !== false) active @endif">
                    <i class="bi bi-sliders"></i>
                    <span>App Config</span>
                </a>

                <a href="{{ route('admin.admin-users.index') }}"
                    class="nav-link @if (strpos(request()->route()->getName(), 'admin-users') !== false) active @endif">
                    <i class="bi bi-shield-lock"></i>
                    <span>Admin Users</span>
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Navbar -->
            <nav class="navbar">
                <div class="navbar-content">
                    <div class="navbar-left">
                        <div class="navbar-title">
                            <h1>@yield('page_title', 'Dashboard')</h1>
                            <p>@yield('page_subtitle', 'Welcome to YD Admin Panel')</p>
                        </div>
                    </div>

                    <div class="navbar-right">
                        <div class="admin-profile">
                            <div class="admin-info">
                                <div class="admin-name">{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</div>
                                <div class="admin-role">Administrator</div>
                            </div>
                            <div class="admin-avatar">
                                {{ strtoupper(substr(Auth::guard('admin')->user()->name ?? 'A', 0, 1)) }}
                            </div>
                            <form method="POST" action="{{ route('admin.logout') }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="logout-btn">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="page-content">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle"></i>
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <script>
        // Delete confirmation
        function confirmDelete(name = 'this item') {
            return Swal.fire({
                title: 'Are you sure?',
                text: `You won't be able to revert this! "${name}" will be deleted permanently.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                return result.isConfirmed;
            });
        }

        // Dismiss alerts after 5 seconds
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                let bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    </script>

    @yield('extra_js')
</body>

</html>
