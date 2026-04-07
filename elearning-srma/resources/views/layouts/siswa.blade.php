<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Beranda') - E-Learning SRMA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #A41E35;
            --secondary: #4A4A4A;
            --light: #f8f9fa;
            --border: #e0e0e0;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
            --info: #17a2b8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: #333;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            width: 260px;
            background: linear-gradient(135deg, var(--primary) 0%, #743D52 100%);
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            padding: 20px 0;
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
            z-index: 1000;
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 2px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }

        .sidebar-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 8px;
        }

        .sidebar-subtitle {
            font-size: 11px;
            opacity: 0.9;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu li {
            margin: 5px 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: rgba(255,255,255,0.15);
            color: white;
            padding-left: 25px;
        }

        .sidebar-menu a i {
            width: 20px;
            margin-right: 12px;
            text-align: center;
        }

        .sidebar-divider {
            margin: 15px 0;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-label {
            padding: 10px 20px;
            font-size: 11px;
            text-transform: uppercase;
            opacity: 0.6;
            font-weight: 600;
            letter-spacing: 1px;
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* TOP BAR */
        .topbar {
            background: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-bottom: 2px solid var(--border);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .topbar-title {
            font-size: 24px;
            font-weight: 600;
            color: var(--secondary);
        }

        .topbar-title i {
            color: var(--primary);
            margin-right: 10px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary) 0%, #743D52 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 18px;
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            color: var(--secondary);
            font-size: 14px;
        }

        .user-role {
            font-size: 12px;
            color: #999;
        }

        /* CONTENT AREA */
        .content-area {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 32px;
            font-weight: 700;
            color: var(--secondary);
            margin-bottom: 10px;
        }

        .page-description {
            color: #999;
            font-size: 14px;
        }

        /* CARDS */
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            margin-bottom: 30px;
            border: 1px solid var(--border);
        }

        .card-header {
            padding: 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--secondary);
        }

        .card-body {
            padding: 20px;
        }

        /* STATS */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            border-left: 4px solid var(--primary);
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }

        .stat-card.classes {
            border-left-color: #27ae60;
        }

        .stat-card.assignments {
            border-left-color: #f39c12;
        }

        .stat-card.submissions {
            border-left-color: #2980b9;
        }

        .stat-card.grades {
            border-left-color: #e74c3c;
        }

        .stat-info h3 {
            font-size: 14px;
            color: #999;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--secondary);
        }

        .stat-icon {
            font-size: 48px;
            opacity: 0.2;
        }

        /* BUTTONS */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: #1e8449;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .btn-sm {
            padding: 8px 12px;
            font-size: 12px;
        }

        /* TABLE */
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: #f8f9fa;
            border-bottom: 2px solid var(--border);
        }

        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: var(--secondary);
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--border);
        }

        tbody tr {
            transition: background-color 0.2s ease;
        }

        tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* ALERTS */
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .topbar {
                padding: 12px 15px;
            }

            .content-area {
                padding: 15px;
            }

            .user-info {
                display: none;
            }
        }

        /* SCROLLBAR */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #743D52;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo SRMA">
                </div>
                <div class="sidebar-subtitle">E-LEARNING SISWA</div>
            </div>

            <ul class="sidebar-menu">
                <li class="sidebar-label">Menu Utama</li>
                <li><a href="{{ route('siswa.dashboard') }}" class="@if(request()->routeIs('siswa.dashboard')) active @endif">
                    <i class="fas fa-home"></i> Beranda
                </a></li>

                <li class="sidebar-divider"></li>
                <li class="sidebar-label">Pembelajaran</li>
                <li><a href="{{ route('siswa.subjects.index') }}" class="@if(request()->routeIs('siswa.subjects.*')) active @endif">
                    <i class="fas fa-book"></i> Mata Pelajaran
                </a></li>
                <li><a href="{{ route('siswa.schedule.index') }}" class="@if(request()->routeIs('siswa.schedule.*')) active @endif">
                    <i class="fas fa-calendar-alt"></i> Jadwal Pelajaran
                </a></li>
                <li><a href="{{ route('siswa.assignments.index') }}" class="@if(request()->routeIs('siswa.assignments.*')) active @endif">
                    <i class="fas fa-tasks"></i> Tugas
                </a></li>
                <li><a href="{{ route('siswa.quizzes.index') }}" class="@if(request()->routeIs('siswa.quizzes.*')) active @endif">
                    <i class="fas fa-question-circle"></i> Quiz / Ujian
                </a></li>

                <li class="sidebar-divider"></li>
                <li class="sidebar-label">Akun</li>
                <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </a></li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <div class="main-content">
            <!-- TOP BAR -->
            <div class="topbar">
                <div class="topbar-left">
                    <div class="topbar-title">
                        <i class="@yield('icon', 'fas fa-home')"></i>
                        @yield('title', 'Dashboard')
                    </div>
                </div>
                <div class="topbar-right">
                    <div class="user-info">
                        <div class="user-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
                        <div class="user-details">
                            <div class="user-name">{{ auth()->user()->name }}</div>
                            <div class="user-role">👨‍🎓 Siswa</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CONTENT -->
            <div class="content-area">
                @if (session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
