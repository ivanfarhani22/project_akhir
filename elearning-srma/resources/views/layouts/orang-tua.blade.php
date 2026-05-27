<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — E-Learning SRMA</title>

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#561020">

    @vite(['resources/css/app.css','resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

        /* Sidebar */
        #sidebar {
            position: fixed; top: 0; left: 0;
            width: 240px; height: 100vh;
            background: var(--sidebar-bg);
            display: flex; flex-direction: column;
            overflow-y: auto; overflow-x: hidden;
            border-right: 1px solid var(--sidebar-border);
            z-index: 50;
            transition: width .28s cubic-bezier(.4,0,.2,1), transform .28s cubic-bezier(.4,0,.2,1);
        }
        #sidebar.compact { width: 64px; }
        #sidebar.open { transform: translateX(0); box-shadow: 0 0 40px rgba(0,0,0,.5); }

        .sb-header {
            height: 60px; padding: 0 12px;
            border-bottom: 1px solid var(--sidebar-border);
            display: flex; align-items: center; gap: 10px;
            flex-shrink: 0;
        }
        .sb-header-content {
            display: flex; align-items: center; gap: 10px;
            flex: 1; min-width: 0; overflow: hidden;
        }
        .logo-mark {
            width: 36px; height: 36px; border-radius: 9px;
            background: #ffffff;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,.15);
        }
        .logo-mark img { width: 100%; height: 100%; object-fit: cover; border-radius: 9px; }
        .brand-info { display: flex; flex-direction: column; gap: 2px; min-width: 0; overflow: hidden; }
        .brand-title { color: #fff; font-size: 12.5px; font-weight: 700; white-space: nowrap; }
        .brand-sub   { color: rgba(255,255,255,.45); font-size: 9.5px; font-weight: 500; letter-spacing: .06em; text-transform: uppercase; white-space: nowrap; }

        .sb-collapse-btn {
            width: 28px; height: 28px; border-radius: 7px;
            background: rgba(196,30,58,.15);
            border: 1px solid rgba(196,30,58,.3);
            color: rgba(255,255,255,.7);
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; flex-shrink: 0;
            transition: background .2s, color .2s;
        }
        .sb-collapse-btn:hover {
            background: rgba(196,30,58,.28);
            color: #fff;
            border-color: rgba(196,30,58,.5);
        }

        #sidebar.compact .sb-header {
            flex-direction: column; height: auto;
            padding: 12px 0 8px; gap: 8px;
            justify-content: center; align-items: center;
        }
        #sidebar.compact .sb-header-content { flex: 0; }
        #sidebar.compact .brand-info { display: none; }
        #sidebar.compact .sb-collapse-btn { display: flex; width: 40px; height: 26px; }

        .sb-body { flex: 1; padding: 6px 0 10px; }
        .sb-section {
            padding: 12px 16px 4px;
            font-size: 9px; font-weight: 700;
            letter-spacing: .1em; text-transform: uppercase;
            color: rgba(255,255,255,.25); white-space: nowrap;
        }
        #sidebar.compact .sb-section { display: none; }

        .sb-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px 9px 14px;
            margin: 1px 8px; border-radius: 8px;
            color: rgba(255,255,255,.6);
            font-size: 12.5px; font-weight: 500;
            text-decoration: none;
            transition: background .18s, color .18s;
            position: relative; white-space: nowrap; overflow: hidden;
        }
        .sb-link .sb-icon {
            width: 16px; height: 16px;
            flex-shrink: 0; opacity: .7;
            transition: opacity .18s;
        }
        .sb-link-text { flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; }
        .sb-link:hover { background: var(--sidebar-hover); color: rgba(255,255,255,.95); }
        .sb-link:hover .sb-icon { opacity: 1; }
        .sb-link.active {
            background: var(--sidebar-active); color: #fff;
            border: 1px solid rgba(196,30,58,.35);
        }
        .sb-link.active .sb-icon { color: var(--red); opacity: 1; }
        .sb-link.active::before {
            content: '';
            position: absolute; left: 0; top: 50%;
            transform: translateY(-50%);
            width: 3px; height: 18px;
            background: linear-gradient(180deg, var(--red), var(--red-dark));
            border-radius: 0 3px 3px 0;
        }

        #sidebar.compact .sb-link {
            justify-content: center; padding: 10px 0;
            margin: 1px 6px; gap: 0;
            width: calc(100% - 12px);
        }
        #sidebar.compact .sb-link-text { display: none; }
        #sidebar.compact .sb-link.active::before { display: none; }

        .sb-divider { height: 1px; background: var(--sidebar-border); margin: 6px 16px; }
        #sidebar.compact .sb-divider { margin: 6px 8px; }

        .sb-footer { flex-shrink: 0; padding: 8px; border-top: 1px solid var(--sidebar-border); }
        .sb-link.logout:hover { background: rgba(220,30,50,.22); color: #ff8080; }

        /* Layout */
        .main-col { margin-left: 240px; min-height: 100vh; display: flex; flex-direction: column; transition: margin-left .28s cubic-bezier(.4,0,.2,1); }
        #sidebar.compact ~ .main-col { margin-left: 64px; }

        /* Topbar */
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

        .user-chip {
            display: flex; align-items: center; gap: 8px;
            padding: 4px 12px 4px 5px; border: 1px solid var(--border);
            border-radius: 100px; background: #fff; cursor: pointer;
        }
        .uc-avatar {
            width: 28px; height: 28px; border-radius: 50%;
            background: linear-gradient(135deg, var(--red), var(--red-deeper));
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 12px; font-weight: 700; flex-shrink: 0;
        }
        .uc-name { font-size: 13px; font-weight: 600; color: var(--text-1); line-height: 1.2; }
        .uc-role { font-size: 10.5px; color: var(--text-2); }

        /* Profile Card */
        #profile-card {
            position: fixed; top: 70px; right: 26px; width: 320px;
            background: white; border: 1px solid var(--border); border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.12); z-index: 1200; display: none;
            animation: slideIn 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        #profile-card.active { display: block; }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .profile-header { background: linear-gradient(135deg, var(--red), var(--red-dark)); padding: 20px; text-align: center; border-radius: 11px 11px 0 0; }
        .profile-avatar {
            width: 60px; height: 60px; border-radius: 50%; background: white; color: var(--red);
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; font-weight: 700; margin: 0 auto 12px;
        }
        .profile-name  { color: white; font-size: 16px; font-weight: 700; }
        .profile-email { color: rgba(255,255,255,.75); font-size: 12px; margin-top: 4px; word-break: break-all; }
        .profile-role  { display: inline-block; background: rgba(255,255,255,.2); color: white; font-size: 11px; font-weight: 600; padding: 4px 12px; border-radius: 20px; margin-top: 8px; }
        .profile-body  { padding: 16px; border-bottom: 1px solid var(--border); }
        .profile-item  { display: flex; align-items: center; gap: 10px; padding: 10px 0; font-size: 13px; color: var(--text-2); }
        .profile-item i { color: var(--red); width: 16px; flex-shrink: 0; }
        .profile-item-value { color: var(--text-1); font-weight: 600; }
        .profile-footer { padding: 12px; display: flex; gap: 8px; }
        .profile-btn {
            flex: 1; padding: 10px 12px; border: 1px solid var(--border); border-radius: 8px;
            background: white; color: var(--text-1); font-size: 12px; font-weight: 600;
            cursor: pointer; transition: background 0.15s, border-color 0.15s;
            text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 6px;
        }
        .profile-btn:hover { background: #F9FAFB; border-color: #D1D5DB; }
        .profile-btn.logout { background: #FEE2E2; color: #991B1B; border-color: #FECACA; }
        .profile-btn.logout:hover { background: #FCA5A5; }

        .content-area { padding: 18px; }

        @media (max-width: 1024px) {
            #sidebar { transform: translateX(-100%); position: fixed; }
            .main-col { margin-left: 0; }
            #sb-toggle { display: flex; }
            #profile-card { right: 14px; width: calc(100vw - 28px); max-width: 360px; }
        }
    </style>
</head>
<body>
<div class="page-wrap">
    <aside id="sidebar">
        <div class="sb-header">
            <div class="sb-header-content">
                <div class="logo-mark"><img src="{{ asset('images/logo.png') }}" alt="Logo SRMA"></div>
                <div class="brand-info">
                    <div class="brand-title">E‑Learning SRMA</div>
                    <div class="brand-sub">Portal Orang Tua</div>
                </div>
            </div>
            <button class="sb-collapse-btn" type="button" onclick="toggleSidebarCompact()" title="Toggle sidebar">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <nav class="sb-body">
            <div class="sb-section">Menu</div>
            <a href="{{ route('orang-tua.dashboard') }}" class="sb-link @if(request()->routeIs('orang-tua.dashboard')) active @endif">
                <i class="fas fa-home sb-icon"></i>
                <span class="sb-link-text">Dashboard</span>
            </a>
            <a href="{{ route('orang-tua.daily-reports.index') }}" class="sb-link @if(request()->routeIs('orang-tua.daily-reports.*')) active @endif">
                <i class="fas fa-clipboard-list sb-icon"></i>
                <span class="sb-link-text">Laporan Harian</span>
            </a>

            <div class="sb-divider"></div>
        </nav>

        <div class="sb-footer">
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="sb-link logout">
                <i class="fas fa-right-from-bracket sb-icon"></i>
                <span class="sb-link-text">Keluar</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" hidden>@csrf</form>
        </div>
    </aside>

    <div class="main-col">
        <header id="topbar">
            <div class="topbar-left">
                <button id="sb-toggle" aria-label="Toggle sidebar"><i class="fas fa-bars"></i></button>
                <div>
                    <div class="page-title">@yield('title', 'Dashboard')</div>
                    <div class="breadcrumb">Monitoring aktivitas & laporan harian anak</div>
                </div>
            </div>
            <div class="user-chip" onclick="toggleProfileCard()" style="cursor: pointer;">
                <div class="uc-avatar">{{ substr(auth()->user()->name,0,1) }}</div>
                <div>
                    <div class="uc-name">{{ auth()->user()->name }}</div>
                    <div class="uc-role">Orang Tua</div>
                </div>
                <i class="fas fa-chevron-down" style="font-size:10px;color:var(--text-2);"></i>
            </div>

            <div id="profile-card">
                <div class="profile-header">
                    <div class="profile-avatar">{{ substr(auth()->user()->name,0,1) }}</div>
                    <div class="profile-name">{{ auth()->user()->name }}</div>
                    <div class="profile-email">{{ auth()->user()->email }}</div>
                    <div class="profile-role">Orang Tua</div>
                </div>
                <div class="profile-body">
                    <div class="profile-item"><i class="fas fa-user"></i> <span class="profile-item-value">{{ auth()->user()->name }}</span></div>
                    <div class="profile-item"><i class="fas fa-envelope"></i> <span class="profile-item-value">{{ auth()->user()->email }}</span></div>
                </div>
                <div class="profile-footer">
                    <a href="{{ route('logout') }}" class="profile-btn logout"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-right-from-bracket"></i> Keluar
                    </a>
                </div>
            </div>
        </header>

        <main class="content-area">
            @yield('content')
        </main>
    </div>
</div>

<script>
    function toggleSidebarCompact() {
        const sb = document.getElementById('sidebar');
        sb.classList.toggle('compact');
        try {
            localStorage.setItem('sb_compact_orangtua', sb.classList.contains('compact') ? '1' : '0');
        } catch (e) {}
    }

    // restore
    (function () {
        try {
            const v = localStorage.getItem('sb_compact_orangtua');
            if (v === '1') document.getElementById('sidebar')?.classList.add('compact');
        } catch (e) {}
    })();

    // Mobile sidebar open/close (same behavior as siswa layout)
    (function () {
        const sb = document.getElementById('sidebar');
        const toggle = document.getElementById('sb-toggle');
        if (!sb || !toggle) return;

        const overlay = document.createElement('div');
        Object.assign(overlay.style, { position: 'fixed', inset: '0', background: 'rgba(0,0,0,.45)', zIndex: '49', display: 'none', backdropFilter: 'blur(2px)' });
        document.body.appendChild(overlay);

        function openSb() {
            sb.classList.add('open');
            overlay.style.display = 'block';
        }
        function closeSb() {
            sb.classList.remove('open');
            overlay.style.display = 'none';
        }

        toggle.addEventListener('click', () => {
            if (sb.classList.contains('open')) closeSb();
            else openSb();
        });
        overlay.addEventListener('click', closeSb);

        // close on navigation
        sb.querySelectorAll('a.sb-link').forEach((a) => {
            a.addEventListener('click', () => {
                if (window.matchMedia('(max-width: 1024px)').matches) closeSb();
            });
        });
    })();

    // Profile card toggle + outside click
    window.toggleProfileCard = function () {
        const card = document.getElementById('profile-card');
        if (!card) return;
        card.classList.toggle('active');
    };

    document.addEventListener('click', function (e) {
        const card = document.getElementById('profile-card');
        const chip = document.querySelector('#topbar .user-chip');
        if (!card || !chip) return;
        if (chip.contains(e.target) || card.contains(e.target)) return;
        card.classList.remove('active');
    });
</script>
</body>
</html>
