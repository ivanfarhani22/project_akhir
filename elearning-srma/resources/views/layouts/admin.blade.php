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
            --red:        #C41E3A;
            --red-dark:   #9B1630;
            --red-deeper: #6E0F22;
            --sidebar-bg: #0F0A0D;
            --sidebar-hover:  rgba(196,30,58,.15);
            --sidebar-active: rgba(196,30,58,.25);
            --sidebar-border: rgba(255,255,255,.07);
            --body-bg:   #F4F5F8;
            --card-bg:   #ffffff;
            --border:    #E5E7EB;
            --text-1:    #1A1A2E;
            --text-2:    #6B7280;
            --font: 'Plus Jakarta Sans', sans-serif;
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: var(--font);
            background: var(--body-bg);
            color: var(--text-1);
            margin: 0;
        }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(196,30,58,.4); border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--red); }

        /* ══════════════ SIDEBAR ══════════════ */
        #sidebar {
            position: fixed;
            top: 0; left: 0;
            width: 240px;
            height: 100vh;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            border-right: 1px solid var(--sidebar-border);
            z-index: 50;
            transition: transform .28s cubic-bezier(.4,0,.2,1);
        }

        /* header */
        .sb-header {
            padding: 22px 18px 18px;
            border-bottom: 1px solid var(--sidebar-border);
            display: flex;
            align-items: center;
            gap: 11px;
            flex-shrink: 0;
        }
        .logo-mark {
            width: 38px; height: 38px;
            border-radius: 10px;
            background: #ffffff;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            overflow: hidden;
            position: relative;
        }
        .logo-mark img { width: 100%; height: 100%; object-fit: cover; border-radius: 10px; }
        .logo-mark::after {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(135deg,rgba(255,255,255,.18) 0%,transparent 60%);
            pointer-events: none;
        }
        .brand-title { color: #fff; font-size: 13px; font-weight: 700; line-height: 1.2; letter-spacing: .01em; }
        .brand-sub   { color: rgba(255,255,255,.35); font-size: 10px; font-weight: 600; letter-spacing: .08em; text-transform: uppercase; margin-top: 1px; }

        /* nav */
        .sb-body { flex: 1; padding: 8px 0 12px; }

        .sb-section {
            padding: 14px 20px 5px;
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: rgba(255,255,255,.25);
        }

        .sb-link {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 8.5px 10px 8.5px 14px;
            margin: 1px 10px;
            border-radius: 9px;
            color: rgba(255,255,255,.52);
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: background .15s, color .15s;
            position: relative;
        }
        .sb-link .sb-icon { width: 16px; height: 16px; flex-shrink: 0; opacity: .8; }
        .sb-link:hover { background: var(--sidebar-hover); color: rgba(255,255,255,.88); }

        .sb-link.active {
            background: var(--sidebar-active);
            color: #fff;
            border: 1px solid rgba(196,30,58,.38);
        }
        .sb-link.active .sb-icon { color: var(--red); opacity: 1; }
        .sb-link.active::before {
            content: '';
            position: absolute;
            left: -10px; top: 50%;
            transform: translateY(-50%);
            width: 3px; height: 18px;
            background: var(--red);
            border-radius: 0 3px 3px 0;
        }

        .sb-badge {
            margin-left: auto;
            background: var(--red);
            color: #fff;
            font-size: 10px; font-weight: 700;
            padding: 1px 7px;
            border-radius: 20px;
            line-height: 1.7;
        }

        .sb-divider {
            height: 1px;
            background: var(--sidebar-border);
            margin: 8px 20px;
        }

        /* footer */
        .sb-footer {
            flex-shrink: 0;
            padding: 10px;
            border-top: 1px solid var(--sidebar-border);
        }
        .sb-link.logout:hover { background: rgba(220,30,50,.22); color: #ff8080; }

        /* ══════════════ TOPBAR ══════════════ */
        #topbar {
            position: sticky;
            top: 0; z-index: 40;
            height: 60px;
            background: var(--card-bg);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 26px;
            gap: 16px;
        }
        .topbar-left { display: flex; align-items: center; gap: 12px; }

        /* hamburger */
        #sb-toggle {
            display: none;
            align-items: center; justify-content: center;
            width: 36px; height: 36px;
            border-radius: 9px;
            border: 1px solid var(--border);
            background: transparent;
            cursor: pointer;
            color: var(--text-2);
            transition: background .15s;
        }
        #sb-toggle:hover { background: #F3F4F6; }

        .page-title { font-size: 17px; font-weight: 700; color: var(--text-1); letter-spacing: -.015em; }
        .breadcrumb { font-size: 11.5px; color: var(--text-2); margin-top: 1px; }
        .breadcrumb .bc-active { color: var(--red); font-weight: 600; }

        .topbar-right { display: flex; align-items: center; gap: 7px; }

        .icon-btn {
            width: 36px; height: 36px;
            border-radius: 9px;
            border: 1px solid var(--border);
            background: #fff;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            color: var(--text-2);
            font-size: 14px;
            transition: background .14s, border-color .14s;
            position: relative;
        }
        .icon-btn:hover { background: #F9FAFB; color: var(--text-1); }
        .notif-dot {
            position: absolute; top: 6px; right: 6px;
            width: 7px; height: 7px;
            background: var(--red); border-radius: 50%;
            border: 1.5px solid #fff;
        }

        .user-chip {
            display: flex; align-items: center; gap: 8px;
            padding: 4px 12px 4px 5px;
            border: 1px solid var(--border);
            border-radius: 100px;
            background: #fff;
            cursor: pointer;
            transition: border-color .14s;
            text-decoration: none;
        }
        .user-chip:hover { border-color: #C0C4CC; }
        .uc-avatar {
            width: 28px; height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--red), var(--red-deeper));
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 12px; font-weight: 700;
            flex-shrink: 0;
        }
        .uc-name { font-size: 13px; font-weight: 600; color: var(--text-1); line-height: 1.2; }
        .uc-role { font-size: 10.5px; color: var(--text-2); }

        /* ══════════════ LAYOUT ══════════════ */
        .page-wrap { display: flex; min-height: 100vh; }
        .main-col { margin-left: 240px; flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
        .content-area { flex: 1; padding: 26px; }

        /* ══════════════ ALERTS ══════════════ */
        .alert {
            display: flex; align-items: flex-start; gap: 10px;
            padding: 13px 16px;
            border-radius: 11px;
            margin-bottom: 20px;
            font-size: 13.5px;
            font-weight: 500;
        }
        .alert.alert-success {
            background: rgba(16,185,129,.08);
            border: 1px solid rgba(16,185,129,.22);
            color: #065F46;
        }
        .alert.alert-error {
            background: rgba(196,30,58,.07);
            border: 1px solid rgba(196,30,58,.22);
            color: #7F1D2A;
        }
        .alert i { margin-top: 1px; flex-shrink: 0; }

        /* ══════════════ RESPONSIVE ══════════════ */
        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.open { transform: translateX(0); box-shadow: 0 0 40px rgba(0,0,0,.5); }
            .main-col { margin-left: 0; }
            #sb-toggle { display: flex; }
            .content-area { padding: 16px; }
            .uc-name, .uc-role { display: none; }
        }
    </style>

    @stack('styles')
</head>
<body>

<div class="page-wrap">

    <!-- ════════ SIDEBAR ════════ -->
    <aside id="sidebar">
        <div class="sb-header">
            <div class="logo-mark">
                <img src="{{ asset('images/logo.png') }}" alt="Logo SRMA">
            </div>
            <div>
                <div class="brand-title">E-Learning SRMA</div>
                <div class="brand-sub">Admin Panel</div>
            </div>
        </div>

        <nav class="sb-body">
            <div class="sb-section">Main Menu</div>

            <a href="{{ route('admin.dashboard') }}"
               class="sb-link @if(request()->routeIs('admin.dashboard')) active @endif">
                <svg class="sb-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/>
                    <rect x="14" y="14" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('admin.users.index') }}"
               class="sb-link @if(request()->routeIs('admin.users.*')) active @endif">
                <svg class="sb-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                Kelola Pengguna
            </a>

            <a href="{{ route('admin.classes.index') }}"
               class="sb-link @if(request()->routeIs('admin.classes.*')) active @endif">
                <svg class="sb-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
                Kelola Kelas
            </a>

            <a href="{{ route('admin.subjects.index') }}"
               class="sb-link @if(request()->routeIs('admin.subjects.*')) active @endif">
                <svg class="sb-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                </svg>
                Mata Pelajaran
            </a>

            <div class="sb-divider"></div>
            <div class="sb-section">Konten</div>

            <a href="{{ route('admin.materials.index') }}"
               class="sb-link @if(request()->routeIs('admin.materials.*')) active @endif">
                <svg class="sb-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
                Materi
            </a>

            <a href="{{ route('admin.assignments.index') }}"
               class="sb-link @if(request()->routeIs('admin.assignments.*')) active @endif">
                <svg class="sb-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="9 11 12 14 22 4"/>
                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                </svg>
                Tugas
            </a>

            <div class="sb-divider"></div>
            <div class="sb-section">Akademik</div>

            <a href="{{ route('admin.grades.index') }}"
               class="sb-link @if(request()->routeIs('admin.grades.*')) active @endif">
                <svg class="sb-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                </svg>
                Nilai
            </a>

            <a href="{{ route('admin.attendance.index') }}"
               class="sb-link @if(request()->routeIs('admin.attendance.*')) active @endif">
                <svg class="sb-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 11l3 3L22 4"/>
                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                </svg>
                Presensi
            </a>

            <div class="sb-divider"></div>
            <div class="sb-section">Sistem</div>

            <a href="{{ route('admin.settings.edit') }}"
               class="sb-link @if(request()->routeIs('admin.settings.*')) active @endif">
                <svg class="sb-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                </svg>
                Pengaturan
            </a>
        </nav>

        <div class="sb-footer">
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="sb-link logout">
                <svg class="sb-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                Keluar
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" hidden>@csrf</form>
        </div>
    </aside>
    <!-- ════════ /SIDEBAR ════════ -->

    <!-- ════════ MAIN ════════ -->
    <div class="main-col">

        <!-- TOPBAR -->
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
                <div class="icon-btn" title="Pencarian">
                    <i class="fas fa-search" style="font-size:13px;"></i>
                </div>
                <div class="icon-btn" title="Notifikasi">
                    <i class="fas fa-bell" style="font-size:13px;"></i>
                    <span class="notif-dot"></span>
                </div>
                <div class="user-chip">
                    <div class="uc-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
                    <div>
                        <div class="uc-name">{{ auth()->user()->name }}</div>
                        <div class="uc-role">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</div>
                    </div>
                </div>
            </div>
        </header>

        <!-- CONTENT -->
        <main class="content-area">

            @if ($errors->any())
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <strong>Terjadi kesalahan:</strong>
                        <ul style="margin: 6px 0 0 16px; list-style: disc;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @yield('content')

        </main>

    </div>
    <!-- ════════ /MAIN ════════ -->

</div><!-- /page-wrap -->

@stack('scripts')
<script>
(function () {
    /* Dark mode */
    fetch('/api/settings/dark-mode', { headers: { Accept: 'application/json' } })
        .then(r => r.json())
        .then(d => {
            if (d.dark_mode === 'true') document.documentElement.classList.add('dark');
        })
        .catch(() => {
            if (localStorage.getItem('darkMode') === 'true')
                document.documentElement.classList.add('dark');
        });

    /* Sidebar toggle */
    const toggle  = document.getElementById('sb-toggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.createElement('div');
    Object.assign(overlay.style, {
        position:'fixed', inset:'0', background:'rgba(0,0,0,.45)',
        zIndex:'49', display:'none', backdropFilter:'blur(2px)'
    });
    document.body.appendChild(overlay);

    function openSidebar() {
        sidebar.classList.add('open');
        overlay.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.style.display = 'none';
        document.body.style.overflow = '';
    }

    if (toggle) toggle.addEventListener('click', () =>
        sidebar.classList.contains('open') ? closeSidebar() : openSidebar());

    overlay.addEventListener('click', closeSidebar);

    sidebar.querySelectorAll('.sb-link').forEach(a =>
        a.addEventListener('click', () => { if (window.innerWidth <= 768) closeSidebar(); }));

    window.addEventListener('resize', () => { if (window.innerWidth > 768) closeSidebar(); });
})();
</script>
</body>
</html>