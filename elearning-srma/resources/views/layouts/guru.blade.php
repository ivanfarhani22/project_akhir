<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — E-Learning SRMA</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --red:        #A41E35;
            --red-dark:   #7D1627;
            --red-deeper: #561020;
            --sidebar-bg: #0D0A0B;
            --sidebar-hover:  rgba(164,30,53,.18);
            --sidebar-active: rgba(164,30,53,.28);
            --sidebar-border: rgba(255,255,255,.07);
            --body-bg:   #F4F5F8;
            --card-bg:   #ffffff;
            --border:    #E5E7EB;
            --text-1:    #1A1A2E;
            --text-2:    #6B7280;
            --font: 'Plus Jakarta Sans', sans-serif;
        }

        html.dark {
            --sidebar-bg: #0D0A0B;
            --sidebar-hover:  rgba(164,30,53,.18);
            --sidebar-active: rgba(164,30,53,.28);
            --sidebar-border: rgba(255,255,255,.07);
            --body-bg:   #0F0F12;
            --card-bg:   #1A1A1E;
            --border:    #2D2D33;
            --text-1:    #F3F4F6;
            --text-2:    #9CA3AF;
        }

        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: var(--font); background: var(--body-bg); color: var(--text-1); margin: 0; }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(164,30,53,.4); border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--red); }

        /* ══ SIDEBAR ══ */
        #sidebar {
            position: fixed; top: 0; left: 0;
            width: 240px; height: 100vh;
            background: var(--sidebar-bg);
            display: flex; flex-direction: column;
            overflow-y: auto;
            overflow-x: hidden;
            border-right: 1px solid var(--sidebar-border);
            z-index: 50;
            transition: width .28s cubic-bezier(.4,0,.2,1);
        }
        #sidebar.compact { width: 64px; }

        /* ── header ── */
        .sb-header {
            height: 60px;
            padding: 0 12px;
            border-bottom: 1px solid var(--sidebar-border);
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }
        .sb-header-content {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
            min-width: 0;
            overflow: hidden;
        }
        .logo-mark {
            width: 36px; height: 36px;
            border-radius: 9px;
            background: #ffffff;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,.15);
        }
        .logo-mark img { width: 100%; height: 100%; object-fit: cover; border-radius: 9px; }
        .brand-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
            min-width: 0;
            overflow: hidden;
        }
        .brand-title { color: #fff; font-size: 12.5px; font-weight: 700; white-space: nowrap; }
        .brand-sub   { color: rgba(255,255,255,.45); font-size: 9.5px; font-weight: 500; letter-spacing: .06em; text-transform: uppercase; white-space: nowrap; }

        .sb-collapse-btn {
            width: 28px; height: 28px;
            border-radius: 7px;
            background: rgba(196,30,58,.15);
            border: 1px solid rgba(196,30,58,.3);
            color: rgba(255,255,255,.7);
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: 11px;
            flex-shrink: 0;
            transition: background .2s, color .2s, transform .2s;
        }
        .sb-collapse-btn:hover {
            background: rgba(196,30,58,.28);
            color: #fff;
            border-color: rgba(196,30,58,.5);
        }

        /* compact header: centre logo only */
        #sidebar.compact .sb-header { padding: 0; justify-content: center; gap: 0; }
        #sidebar.compact .sb-header-content { flex: 0; }
        #sidebar.compact .brand-info { display: none; }

        /* Compact: tombol pindah ke bawah header, full-width, ikon berubah arah */
        #sidebar.compact .sb-header {
            flex-direction: column;
            height: auto;
            padding: 12px 0 8px;
            gap: 8px;
            justify-content: center;
            align-items: center;
        }
        #sidebar.compact .sb-collapse-btn {
            display: flex;
            width: 40px;
            height: 28px;
        }

        /* ── nav ── */
        .sb-body { flex: 1; padding: 6px 0 10px; }

        .sb-section {
            padding: 12px 16px 4px;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: rgba(255,255,255,.25);
            white-space: nowrap;
        }
        #sidebar.compact .sb-section { display: none; }

        .sb-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px 9px 14px;
            margin: 1px 8px;
            border-radius: 8px;
            color: rgba(255,255,255,.6);
            font-size: 12.5px;
            font-weight: 500;
            text-decoration: none;
            transition: background .18s, color .18s;
            position: relative;
            white-space: nowrap;
            overflow: hidden;
        }
        .sb-link-text { flex: 1; min-width: 0; overflow: hidden; }
        .sb-link .sb-icon {
            width: 16px; height: 16px;
            flex-shrink: 0;
            opacity: .7;
            transition: opacity .18s;
        }
        .sb-link:hover { background: var(--sidebar-hover); color: rgba(255,255,255,.95); }
        .sb-link:hover .sb-icon { opacity: 1; }

        .sb-link.active {
            background: var(--sidebar-active);
            color: #fff;
            border: 1px solid rgba(196,30,58,.35);
        }
        .sb-link.active .sb-icon { color: var(--red); opacity: 1; }
        .sb-link.active::before {
            content: '';
            position: absolute;
            left: 0; top: 50%;
            transform: translateY(-50%);
            width: 3px; height: 18px;
            background: linear-gradient(180deg, var(--red), var(--red-dark));
            border-radius: 0 3px 3px 0;
        }

        /* compact links: perfectly centred icon */
        #sidebar.compact .sb-link {
            justify-content: center;
            padding: 10px 0;
            margin: 1px 6px;
            gap: 0;
            width: calc(100% - 12px);
        }
        #sidebar.compact .sb-link-text { display: none; }
        #sidebar.compact .sb-link.active::before { display: none; }

        .sb-badge {
            background: var(--red); color: #fff;
            font-size: 10px; font-weight: 700;
            padding: 1px 7px; border-radius: 20px; line-height: 1.7;
            flex-shrink: 0;
        }
        #sidebar.compact .sb-badge { display: none; }

        .sb-divider {
            height: 1px;
            background: var(--sidebar-border);
            margin: 6px 16px;
        }
        #sidebar.compact .sb-divider { margin: 6px 8px; }

        /* ── footer ── */
        .sb-footer {
            flex-shrink: 0;
            padding: 8px;
            border-top: 1px solid var(--sidebar-border);
        }
        .sb-link.logout:hover { background: rgba(220,30,50,.22); color: #ff8080; }

        /* ══ TOPBAR ══ */
        #topbar {
            position: sticky; top: 0; z-index: 40; height: 60px;
            background: var(--card-bg); border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 26px; gap: 16px;
        }
        .topbar-left { display: flex; align-items: center; gap: 12px; }
        #sb-toggle {
            display: none; align-items: center; justify-content: center;
            width: 36px; height: 36px; border-radius: 9px;
            border: 1px solid var(--border); background: transparent;
            cursor: pointer; color: var(--text-2); transition: background .15s;
        }
        #sb-toggle:hover { background: #F3F4F6; }
        .page-title { font-size: 17px; font-weight: 700; color: var(--text-1); letter-spacing: -.015em; }
        .breadcrumb { font-size: 11.5px; color: var(--text-2); margin-top: 1px; }
        .breadcrumb .bc-active { color: var(--red); font-weight: 600; }
        .topbar-right { display: flex; align-items: center; gap: 7px; }
        .icon-btn {
            width: 36px; height: 36px; border-radius: 9px;
            border: 1px solid var(--border); background: #fff;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: var(--text-2); font-size: 14px;
            transition: background .14s; position: relative;
        }
        .icon-btn:hover { background: #F9FAFB; color: var(--text-1); }
        .notif-dot {
            position: absolute; top: 6px; right: 6px;
            width: 7px; height: 7px; background: var(--red); border-radius: 50%; border: 1.5px solid #fff;
        }
        .user-chip {
            display: flex; align-items: center; gap: 8px;
            padding: 4px 12px 4px 5px;
            border: 1px solid var(--border); border-radius: 100px; background: #fff; cursor: pointer;
            transition: border-color .14s;
        }
        .user-chip:hover { border-color: #C0C4CC; }
        .uc-avatar {
            width: 28px; height: 28px; border-radius: 50%;
            background: linear-gradient(135deg, var(--red), var(--red-deeper));
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 12px; font-weight: 700; flex-shrink: 0;
        }
        .uc-name { font-size: 13px; font-weight: 600; color: var(--text-1); line-height: 1.2; }
        .uc-role { font-size: 10.5px; color: var(--text-2); }

        /* ══ PROFILE CARD ══ */
        #profile-card {
            position: fixed; top: 70px; right: 26px;
            width: 320px; background: white;
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.12);
            z-index: 1200;
            display: none;
            animation: slideIn 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        #profile-card.active { display: block; }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .profile-header {
            background: linear-gradient(135deg, var(--red), var(--red-dark));
            padding: 20px;
            text-align: center;
            border-radius: 11px 11px 0 0;
        }
        .profile-avatar {
            width: 60px; height: 60px;
            border-radius: 50%;
            background: white;
            color: var(--red);
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; font-weight: 700;
            margin: 0 auto 12px;
        }
        .profile-name { color: white; font-size: 16px; font-weight: 700; }
        .profile-email { color: rgba(255,255,255,.75); font-size: 12px; margin-top: 4px; word-break: break-all; }
        .profile-role {
            display: inline-block;
            background: rgba(255,255,255,.2);
            color: white;
            font-size: 11px; font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
            margin-top: 8px;
        }
        .profile-body {
            padding: 16px;
            border-bottom: 1px solid var(--border);
        }
        .profile-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 0;
            font-size: 13px;
            color: var(--text-2);
        }
        .profile-item i { color: var(--red); width: 16px; flex-shrink: 0; }
        .profile-item-value { color: var(--text-1); font-weight: 600; }
        .profile-footer {
            padding: 12px;
            display: flex; gap: 8px;
        }
        .profile-btn {
            flex: 1;
            padding: 10px 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: white;
            color: var(--text-1);
            font-size: 12px; font-weight: 600;
            cursor: pointer;
            transition: background 0.15s, border-color 0.15s;
            text-decoration: none;
            display: flex; align-items: center; justify-content: center;
            gap: 6px;
        }
        .profile-btn:hover { background: #F9FAFB; border-color: #D1D5DB; }
        .profile-btn.logout {
            background: #FEE2E2; color: #991B1B;
            border-color: #FECACA;
        }
        .profile-btn.logout:hover { background: #FCA5A5; }

        /* ══ LAYOUT ══ */
        .page-wrap { display: flex; min-height: 100vh; }
        .main-col  { margin-left: 240px; flex: 1; display: flex; flex-direction: column; min-height: 100vh; transition: margin-left .28s cubic-bezier(.4,0,.2,1); }
        .main-col.compact { margin-left: 64px; }
        .content-area { flex: 1; padding: 26px; }

        /* ══ RESPONSIVE ══ */
        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); width: 240px !important; }
            #sidebar.open { transform: translateX(0); box-shadow: 0 0 40px rgba(0,0,0,.5); }
            .main-col, .main-col.compact { margin-left: 0 !important; }
            #sb-toggle { display: flex; }
            .content-area { padding: 16px; }
            .uc-name, .uc-role { display: none; }
            .sb-collapse-btn { display: none; }
        }
    </style>

    @stack('styles')
</head>
<body>

<div class="page-wrap">

    <!-- SIDEBAR -->
    <aside id="sidebar">
        <div class="sb-header">
            <div class="sb-header-content">
                <div class="logo-mark">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo SRMA">
                </div>
                <div class="brand-info">
                    <div class="brand-title">E-Learning SRMA</div>
                    <div class="brand-sub">Portal Guru</div>
                </div>
            </div>
            <button class="sb-collapse-btn" id="sb-collapse-btn" title="Collapse sidebar" onclick="toggleSidebarCollapse()">
                <i class="fas fa-chevron-left" id="sb-collapse-icon"></i>
            </button>
        </div>

        <nav class="sb-body">
            <div class="sb-section">Menu Utama</div>

            <a href="{{ route('guru.dashboard') }}"
               class="sb-link @if(request()->routeIs('guru.dashboard')) active @endif">
                <svg class="sb-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/>
                    <rect x="14" y="14" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/>
                </svg>
                <span class="sb-link-text">Dashboard</span>
            </a>

            <div class="sb-divider"></div>
            <div class="sb-section">Pembelajaran</div>

            <a href="{{ route('guru.classes.index') }}"
               class="sb-link @if(request()->routeIs('guru.classes.*')) active @endif">
                <svg class="sb-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
                <span class="sb-link-text">Kelas Saya</span>
            </a>

            <a href="{{ route('guru.materials.index') }}"
               class="sb-link @if(request()->routeIs('guru.materials.*')) active @endif">
                <svg class="sb-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
                <span class="sb-link-text">Materi</span>
            </a>

            <a href="{{ route('guru.assignments.index') }}"
               class="sb-link @if(request()->routeIs('guru.assignments.*')) active @endif">
                <svg class="sb-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="9 11 12 14 22 4"/>
                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                </svg>
                <span class="sb-link-text">Tugas & Soal</span>
            </a>

            <a href="{{ route('guru.grades.index') }}"
               class="sb-link @if(request()->routeIs('guru.grades.*')) active @endif">
                <svg class="sb-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                </svg>
                <span class="sb-link-text">Nilai</span>
            </a>

            <a href="{{ route('guru.attendance.index') }}"
               class="sb-link @if(request()->routeIs('guru.attendance.*')) active @endif">
                <svg class="sb-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 11l3 3L22 4"/>
                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                </svg>
                <span class="sb-link-text">Presensi</span>
            </a>
        </nav>

        <div class="sb-footer">
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); showConfirmation('Apakah Anda yakin ingin keluar?', 'Konfirmasi Logout', function() { document.getElementById('logout-form').submit(); });"
               class="sb-link logout">
                <svg class="sb-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                <span class="sb-link-text">Keluar</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" hidden>@csrf</form>
        </div>
    </aside>

    <!-- MAIN -->
    <div class="main-col">

        <header id="topbar">
            <div class="topbar-left">
                <button id="sb-toggle" aria-label="Toggle sidebar">
                    <i class="fas fa-bars" style="font-size:15px;"></i>
                </button>
                <div>
                    <div class="page-title">@yield('title', 'Dashboard')</div>
                    <div class="breadcrumb">
                        E-Learning SRMA &rsaquo;
                        <span class="bc-active">@yield('title', 'Dashboard')</span>
                    </div>
                </div>
            </div>
            <div class="topbar-right">
                <!-- Notification Button -->
                <div style="position: relative;">
                    <button class="icon-btn" id="notif-toggle" title="Notifikasi" onclick="toggleNotifications()">
                        <i class="fas fa-bell" style="font-size:13px;"></i>
                        <span class="notif-dot" id="notif-badge" style="display: none;"></span>
                    </button>
                    
                    <!-- Notification Dropdown -->
                    <div id="notif-dropdown" style="display: none; position: absolute; top: 45px; right: 0; width: 320px; background: white; border: 1px solid #E5E7EB; border-radius: 11px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); z-index: 1000;">
                        <div style="padding: 12px 16px; border-bottom: 1px solid #E5E7EB; display: flex; justify-content: space-between; align-items: center;">
                            <strong style="font-size: 14px; color: var(--text-1);">Notifikasi</strong>
                            <button onclick="clearNotifications()" style="background: none; border: none; color: var(--text-2); cursor: pointer; font-size: 12px;">Hapus Semua</button>
                        </div>
                        <div id="notif-list" style="max-height: 400px; overflow-y: auto;">
                            <div style="padding: 20px; text-align: center; color: var(--text-2); font-size: 13px;">
                                <i class="fas fa-inbox" style="display: block; font-size: 24px; margin-bottom: 8px; opacity: 0.5;"></i>
                                Tidak ada notifikasi baru
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="user-chip" onclick="toggleProfileCard()" style="cursor: pointer;">
                    <div class="uc-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
                    <div>
                        <div class="uc-name">{{ auth()->user()->name }}</div>
                        <div class="uc-role">Guru</div>
                    </div>
                </div>

                <!-- Profile Card -->
                <div id="profile-card">
                    <div class="profile-header">
                        <div class="profile-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
                        <div class="profile-name">{{ auth()->user()->name }}</div>
                        <div class="profile-email">{{ auth()->user()->email }}</div>
                        <div class="profile-role">
                            <i class="fas fa-chalkboard-user"></i> Guru
                        </div>
                    </div>
                    <div class="profile-body">
                        <div class="profile-item">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <div style="font-size: 11px; color: var(--text-2);">Email</div>
                                <div class="profile-item-value" style="word-break: break-all;">{{ auth()->user()->email }}</div>
                            </div>
                        </div>
                        <div class="profile-item">
                            <i class="fas fa-calendar"></i>
                            <div>
                                <div style="font-size: 11px; color: var(--text-2);">Bergabung</div>
                                <div class="profile-item-value">{{ auth()->user()->created_at->format('d M Y') }}</div>
                            </div>
                        </div>
                        @if(auth()->user()->email_verified_at)
                            <div class="profile-item">
                                <i class="fas fa-check-circle"></i>
                                <div>
                                    <div style="font-size: 11px; color: var(--text-2);">Status Email</div>
                                    <div class="profile-item-value">Terverifikasi</div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="profile-footer">
                        <a href="{{ route('guru.dashboard') }}" class="profile-btn">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}" style="display: none;" id="logout-form-profile">@csrf</form>
                        <button type="button" onclick="showConfirmation('Apakah Anda yakin ingin keluar?', 'Konfirmasi Logout', function() { document.getElementById('logout-form-profile').submit(); })" class="profile-btn logout">
                            <i class="fas fa-sign-out-alt"></i> Keluar
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <main class="content-area">

            @if (session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        showPopup('success', "{{ session('success') }}", 'Berhasil');
                    });
                </script>
            @endif

            @if (session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        showPopup('error', "{{ session('error') }}", 'Terjadi Kesalahan');
                    });
                </script>
            @endif

            @yield('content')

        </main>

    </div>

</div>

@stack('scripts')
@include('components.popup')
<script>
    // Override browser alert dengan popup
    window.alert = function(message) {
        showPopup('info', message, 'Pemberitahuan');
    };
</script>
<script>
(function () {
    const toggle  = document.getElementById('sb-toggle');
    const sidebar = document.getElementById('sidebar');
    const mainCol = document.querySelector('.main-col');
    const overlay = document.createElement('div');
    Object.assign(overlay.style, {
        position:'fixed', inset:'0', background:'rgba(0,0,0,.45)',
        zIndex:'49', display:'none', backdropFilter:'blur(2px)'
    });
    document.body.appendChild(overlay);

    function openSidebar()  { sidebar.classList.add('open');    overlay.style.display = 'block'; document.body.style.overflow = 'hidden'; }
    function closeSidebar() { sidebar.classList.remove('open'); overlay.style.display = 'none';  document.body.style.overflow = ''; }

    if (toggle) toggle.addEventListener('click', () =>
        sidebar.classList.contains('open') ? closeSidebar() : openSidebar());
    overlay.addEventListener('click', closeSidebar);
    sidebar.querySelectorAll('.sb-link').forEach(a =>
        a.addEventListener('click', () => { if (window.innerWidth <= 768) closeSidebar(); }));
    window.addEventListener('resize', () => { if (window.innerWidth > 768) closeSidebar(); });

    /* Sidebar Collapse Toggle */
    function updateCollapseBtn(isCompact) {
        const icon = document.getElementById('sb-collapse-icon');
        const btn  = document.getElementById('sb-collapse-btn');
        if (isCompact) {
            icon.className = 'fas fa-chevron-right';
            btn.title = 'Expand sidebar';
            btn.setAttribute('aria-label', 'Expand sidebar navigation');
            btn.setAttribute('aria-expanded', 'false');
        } else {
            icon.className = 'fas fa-chevron-left';
            btn.title = 'Collapse sidebar';
            btn.setAttribute('aria-label', 'Collapse sidebar navigation');
            btn.setAttribute('aria-expanded', 'true');
        }
    }

    window.toggleSidebarCollapse = function() {
        const isCompact = sidebar.classList.toggle('compact');
        mainCol.classList.toggle('compact');
        localStorage.setItem('sidebar_compact', isCompact);
        updateCollapseBtn(isCompact);
    };

    // Load sidebar preference on page load
    document.addEventListener('DOMContentLoaded', () => {
        const isCompact = localStorage.getItem('sidebar_compact') === 'true';
        if (isCompact) {
            sidebar.classList.add('compact');
            mainCol.classList.add('compact');
        }
        updateCollapseBtn(isCompact);
    });

    /* Toggle Profile Card */
    window.toggleProfileCard = function() {
        const card = document.getElementById('profile-card');
        card.classList.toggle('active');
    };

    window.closeProfileCard = function() {
        const card = document.getElementById('profile-card');
        card.classList.remove('active');
    };

    /* Notification Functions */
    window.toggleNotifications = function() {
        const dropdown = document.getElementById('notif-dropdown');
        dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        if (dropdown.style.display === 'block') {
            loadNotifications();
        }
    };

    window.loadNotifications = function() {
        fetch('/api/notifications', {
            headers: { 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            const notifList = document.getElementById('notif-list');
            const badge = document.getElementById('notif-badge');
            
            if (data.notifications && data.notifications.length > 0) {
                const html = data.notifications.map(notif => `
                    <div style="padding: 12px 16px; border-bottom: 1px solid #F3F4F6; cursor: pointer; transition: background 0.15s;" 
                         onclick="${notif.url ? `window.location.href='${notif.url}'` : `markNotificationRead('${notif.id}')`}" 
                         onmouseover="this.style.background = '#F9FAFB'" 
                         onmouseout="this.style.background = 'transparent'">
                        <div style="display: flex; gap: 10px;">
                            <i class="${notif.icon}" style="color: var(--red); flex-shrink: 0; margin-top: 2px;"></i>
                            <div style="flex: 1; min-width: 0;">
                                <div style="font-size: 13px; font-weight: 600; color: var(--text-1); word-break: break-word;">${notif.title}</div>
                                <div style="font-size: 12px; color: var(--text-2); margin-top: 2px;">${notif.message}</div>
                                <div style="font-size: 11px; color: #9CA3AF; margin-top: 4px;">${notif.time}</div>
                            </div>
                            ${notif.unread ? '<div style="width: 8px; height: 8px; background: var(--red); border-radius: 50%; flex-shrink: 0; margin-top: 6px;"></div>' : ''}
                        </div>
                    </div>
                `).join('');
                notifList.innerHTML = html;
                badge.style.display = data.unread_count > 0 ? 'block' : 'none';
            } else {
                notifList.innerHTML = `
                    <div style="padding: 20px; text-align: center; color: var(--text-2); font-size: 13px;">
                        <i class="fas fa-inbox" style="display: block; font-size: 24px; margin-bottom: 8px; opacity: 0.5;"></i>
                        Tidak ada notifikasi baru
                    </div>
                `;
                badge.style.display = 'none';
            }
        })
        .catch(err => console.error('Notification error:', err));
    };

    window.markNotificationRead = function(notificationId) {
        fetch(`/api/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: { 'Accept': 'application/json' }
        })
        .then(() => loadNotifications())
        .catch(err => console.error('Mark read error:', err));
    };

    window.clearNotifications = function() {
        // No popup confirmation; clear immediately
        fetch('/api/notifications/clear', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }
        })
        .then(async (res) => {
            if (!res.ok) {
                const text = await res.text();
                throw new Error(text || 'Failed to clear notifications');
            }
            const dd = document.getElementById('notif-dropdown');
            if