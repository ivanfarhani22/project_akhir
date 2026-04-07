@extends('layouts.admin')

@section('title', 'Dashboard')
@section('icon', 'chart-line')

@section('content')
    <!-- STATISTICS CARDS -->
    <div class="stats-grid">
        <div class="stat-card users">
            <div class="stat-info">
                <h3>Total Siswa</h3>
                <div class="stat-number">{{ \App\Models\User::where('role', 'siswa')->count() }}</div>
            </div>
            <i class="fas fa-users stat-icon"></i>
        </div>

        <div class="stat-card classes">
            <div class="stat-info">
                <h3>Total Guru</h3>
                <div class="stat-number">{{ \App\Models\User::where('role', 'guru')->count() }}</div>
            </div>
            <i class="fas fa-chalkboard stat-icon"></i>
        </div>

        <div class="stat-card subjects">
            <div class="stat-info">
                <h3>Total Kelas</h3>
                <div class="stat-number">{{ \App\Models\EClass::count() }}</div>
            </div>
            <i class="fas fa-book stat-icon"></i>
        </div>

        <div class="stat-card activities">
            <div class="stat-info">
                <h3>Total Materi</h3>
                <div class="stat-number">{{ \App\Models\Material::count() }}</div>
            </div>
            <i class="fas fa-file-alt stat-icon"></i>
        </div>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="page-header" style="margin-top: 30px; margin-bottom: 20px;">
        <h2 style="font-size: 20px; font-weight: 600; color: var(--secondary); margin-bottom: 15px;">
            <i class="fas fa-lightning-bolt" style="color: var(--primary); margin-right: 10px;"></i>
            Aksi Cepat
        </h2>
    </div>

    <div class="stats-grid">
        <a href="{{ route('admin.users.create') }}" style="text-decoration: none; color: inherit;">
            <div class="card" style="text-align: center; padding: 30px; cursor: pointer;">
                <div style="font-size: 48px; margin-bottom: 15px; color: var(--primary);">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h3 style="font-weight: 600; margin-bottom: 8px; color: var(--secondary);">Tambah Pengguna</h3>
                <p style="font-size: 14px; color: #999;">Buat akun guru atau siswa baru</p>
            </div>
        </a>

        <a href="{{ route('admin.classes.create') }}" style="text-decoration: none; color: inherit;">
            <div class="card" style="text-align: center; padding: 30px; cursor: pointer;">
                <div style="font-size: 48px; margin-bottom: 15px; color: #28a745;">
                    <i class="fas fa-plus-square"></i>
                </div>
                <h3 style="font-weight: 600; margin-bottom: 8px; color: var(--secondary);">Tambah Kelas</h3>
                <p style="font-size: 14px; color: #999;">Buat kelas baru dengan guru</p>
            </div>
        </a>

        <a href="{{ route('admin.subjects.create') }}" style="text-decoration: none; color: inherit;">
            <div class="card" style="text-align: center; padding: 30px; cursor: pointer;">
                <div style="font-size: 48px; margin-bottom: 15px; color: #ffc107;">
                    <i class="fas fa-bookmark"></i>
                </div>
                <h3 style="font-weight: 600; margin-bottom: 8px; color: var(--secondary);">Tambah Mata Pelajaran</h3>
                <p style="font-size: 14px; color: #999;">Tambahkan mata pelajaran baru</p>
            </div>
        </a>

        <a href="{{ route('admin.settings.edit') }}" style="text-decoration: none; color: inherit;">
            <div class="card" style="text-align: center; padding: 30px; cursor: pointer;">
                <div style="font-size: 48px; margin-bottom: 15px; color: #17a2b8;">
                    <i class="fas fa-sliders-h"></i>
                </div>
                <h3 style="font-weight: 600; margin-bottom: 8px; color: var(--secondary);">Pengaturan</h3>
                <p style="font-size: 14px; color: #999;">Atur banner dan sistem</p>
            </div>
        </a>
    </div>

    <!-- RECENT ACTIVITY -->
    <div class="page-header" style="margin-top: 40px; margin-bottom: 20px;">
        <h2 style="font-size: 20px; font-weight: 600; color: var(--secondary); margin-bottom: 15px;">
            <i class="fas fa-history" style="color: var(--primary); margin-right: 10px;"></i>
            Aktivitas Terbaru
        </h2>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="width: 20%;">Pengguna</th>
                        <th style="width: 15%;">Aksi</th>
                        <th style="width: 35%;">Deskripsi</th>
                        <th style="width: 15%;">IP Address</th>
                        <th style="width: 15%;">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(\App\Models\ActivityLog::with('user')->orderBy('timestamp', 'desc')->take(15)->get() as $log)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div class="user-avatar" style="width: 32px; height: 32px; font-size: 12px;">
                                        {{ substr($log->user->name, 0, 1) }}
                                    </div>
                                    <span>{{ $log->user->name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-primary">{{ $log->action }}</span>
                            </td>
                            <td>{{ Str::limit($log->description, 50) }}</td>
                            <td>
                                <code style="background: #f5f5f5; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                                    {{ $log->ip_address }}
                                </code>
                            </td>
                            <td style="font-size: 13px; color: #999;">
                                {{ \Carbon\Carbon::parse($log->timestamp)->diffForHumans() }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: #999; padding: 40px 20px;">
                                <i class="fas fa-inbox" style="font-size: 32px; margin-bottom: 10px; display: block; opacity: 0.3;"></i>
                                Tidak ada aktivitas
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- SYSTEM INFO -->
    <div style="margin-top: 40px; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
        <div class="card" style="padding: 20px;">
            <h3 style="font-weight: 600; color: var(--secondary); margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-chart-bar" style="color: var(--primary);"></i>
                Statistik Pengguna
            </h3>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--border);">
                    <span style="color: #666;">Admin:</span>
                    <strong>{{ \App\Models\User::where('role', 'admin_elearning')->count() }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--border);">
                    <span style="color: #666;">Guru:</span>
                    <strong>{{ \App\Models\User::where('role', 'guru')->count() }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 8px 0;">
                    <span style="color: #666;">Siswa:</span>
                    <strong>{{ \App\Models\User::where('role', 'siswa')->count() }}</strong>
                </div>
            </div>
        </div>

        <div class="card" style="padding: 20px;">
            <h3 style="font-weight: 600; color: var(--secondary); margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-book-open" style="color: #28a745;"></i>
                Statistik Pembelajaran
            </h3>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--border);">
                    <span style="color: #666;">Kelas:</span>
                    <strong>{{ \App\Models\EClass::count() }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--border);">
                    <span style="color: #666;">Mata Pelajaran:</span>
                    <strong>{{ \App\Models\Subject::count() }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 8px 0;">
                    <span style="color: #666;">Tugas:</span>
                    <strong>{{ \App\Models\Assignment::count() }}</strong>
                </div>
            </div>
        </div>

        <div class="card" style="padding: 20px;">
            <h3 style="font-weight: 600; color: var(--secondary); margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-clock" style="color: #17a2b8;"></i>
                Informasi Sistem
            </h3>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--border);">
                    <span style="color: #666;">Versi:</span>
                    <strong>1.0.0</strong>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--border);">
                    <span style="color: #666;">Environment:</span>
                    <strong>{{ app()->environment() }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 8px 0;">
                    <span style="color: #666;">Database:</span>
                    <strong>MySQL 8.0+</strong>
                </div>
            </div>
        </div>
    </div>
@endsection
