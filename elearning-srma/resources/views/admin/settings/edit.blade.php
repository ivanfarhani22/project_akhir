@extends('layouts.admin')
@section('title', 'Pengaturan Sistem')
@section('icon', 'cog')

@section('content')

<style>

.settings-wrapper { padding: 0 1rem 2rem; max-width: 1200px; margin: 0 auto; }
.settings-header { margin-bottom: 2rem; }
.settings-header .label { font-size: 11px; color: #888; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; display: block; }
.settings-header h1 { font-size: 24px; font-weight: 700; color: #1a1a2e; display: flex; align-items: center; gap: 12px; margin: 0 0 8px; }
.settings-header h1 .icon { width: 40px; height: 40px; background: linear-gradient(135deg, #fee2e2, #fecaca); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #dc2626; font-size: 18px; flex-shrink: 0; }
.settings-header p { color: #6b7280; font-size: 13.5px; margin: 0; }

.alert { border-radius: 12px; padding: 14px 16px; margin-bottom: 1.5rem; font-size: 13px; display: flex; align-items: flex-start; gap: 12px; border-left: 4px solid; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
.alert i { flex-shrink: 0; margin-top: 2px; }
.alert-danger  { background: #fef2f2; color: #991b1b; border-color: #ef4444; }
.alert-success { background: #f0fdf4; color: #166534; border-color: #22c55e; }
.alert-warning { background: #fffbeb; color: #92400e; border-color: #f59e0b; }
.alert ul { margin: 6px 0 0 16px; padding: 0; }

.card { background: #fff; border: 1px solid #e5e7eb; border-radius: 16px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.06); margin-bottom: 1.5rem; transition: all 0.3s ease; }
.card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
.card-header { padding: 1.25rem 1.5rem; background: linear-gradient(135deg, #fafafa, #f5f5f5); border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; gap: 14px; }
.card-header .icon { width: 38px; height: 38px; border-radius: 12px; background: linear-gradient(135deg, #fee2e2, #fecaca); display: flex; align-items: center; justify-content: center; color: #dc2626; font-size: 16px; flex-shrink: 0; }
.card-header h5 { margin: 0; font-size: 15px; font-weight: 700; color: #111827; }
.card-header p { margin: 4px 0 0; font-size: 12px; color: #9ca3af; }
.card-body { padding: 1.75rem; }

.section-title { font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: #9ca3af; margin: 0 0 1.25rem; padding-bottom: 10px; border-bottom: 2px solid #f0f0f0; }

.form-group { margin-bottom: 1.5rem; }
.form-row { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem 2rem; margin-bottom: 1.5rem; }
.form-item { display: flex; flex-direction: column; }
.form-item label { font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 8px; }
.form-item .help-text { font-size: 12px; color: #9ca3af; margin-top: 6px; }
.form-control, .form-select { border: 1.5px solid #d1d5db; border-radius: 10px; padding: 10px 14px; font-size: 14px; color: #111827; background: #fff; width: 100%; transition: all 0.2s ease; font-weight: 500; }
.form-control::placeholder { color: #d1d5db; }
.form-control:focus, .form-select:focus { border-color: #dc2626; box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1); outline: none; background: #fafbff; }

.switch-box { display: flex; align-items: center; justify-content: space-between; padding: 16px 18px; background: linear-gradient(135deg, #f9fafb, #f5f5f5); border: 1.5px solid #e5e7eb; border-radius: 12px; gap: 12px; }
.switch-box-label { flex: 1; }
.switch-box-label h4 { font-size: 14px; font-weight: 600; color: #111827; margin: 0 0 4px; }
.switch-box-label p { font-size: 12px; color: #9ca3af; margin: 0; }
.checkbox-toggle { width: 50px; height: 28px; cursor: pointer; border: 2px solid #d1d5db; background: #e5e7eb; border-radius: 20px; appearance: none; -webkit-appearance: none; transition: all 0.3s; position: relative; }
.checkbox-toggle:checked { background-color: #dc2626; border-color: #991b1b; }
.checkbox-toggle::before { content: ''; position: absolute; width: 20px; height: 20px; border-radius: 50%; background: white; top: 2px; left: 2px; transition: all 0.3s; box-shadow: 0 1px 3px rgba(0,0,0,0.2); }
.checkbox-toggle:checked::before { transform: translateX(22px); }
.checkbox-toggle:focus { box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.15); }

.action-buttons { display: flex; align-items: center; gap: 12px; padding-top: 1.5rem; border-top: 1.5px solid #e5e7eb; margin-top: 1rem; flex-wrap: wrap; }
.btn { padding: 11px 24px; border-radius: 10px; font-size: 13.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; cursor: pointer; border: none; transition: all 0.2s ease; text-decoration: none; }
.btn-save   { background: linear-gradient(135deg, #dc2626, #991b1b); color: #fff; box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3); margin-left: auto; }
.btn-save:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(220, 38, 38, 0.4); }
.btn-cancel { background: #fff; border: 1.5px solid #d1d5db; color: #374151; }
.btn-cancel:hover { background: #f9fafb; border-color: #9ca3af; }
.btn-reset  { background: #fff; border: 1.5px solid #fbbf24; color: #d97706; }
.btn-reset:hover { background: #fffbeb; border-color: #f59e0b; }

.banner-section { margin-top: 2rem; }
.banner-list { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 16px; margin-top: 1.5rem; }
.banner-item { position: relative; border: 1.5px solid #e5e7eb; border-radius: 12px; overflow: hidden; height: 200px; background: linear-gradient(135deg, #f3f4f6, #e5e7eb); cursor: pointer; transition: all 0.3s ease; }
.banner-item:hover { transform: translateY(-4px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); border-color: #dc2626; }
.banner-item.inactive { opacity: 0.5; }
.banner-item img { width: 100%; height: 100%; object-fit: cover; }
.banner-badge { position: absolute; top: 8px; left: 8px; font-size: 10px; font-weight: 700; padding: 3px 8px; border-radius: 20px; }
.badge-active   { background: #10b981; color: white; }
.badge-inactive { background: #6b7280; color: white; }
.banner-actions { position: absolute; inset: 0; background: rgba(0,0,0,0); display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.3s; }
.banner-item:hover .banner-actions { background: rgba(0,0,0,0.6); }
.banner-btn { padding: 8px 12px; font-size: 12px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600; transition: all 0.2s; }
.btn-delete { background: #ef4444; color: white; }
.btn-delete:hover { background: #dc2626; transform: scale(1.05); }
.btn-toggle { background: #10b981; color: white; }
.btn-toggle:hover { background: #059669; transform: scale(1.05); }
.btn-toggle.active { background: #6b7280; }
.btn-toggle.active:hover { background: #4b5563; }

.upload-area { border: 2.5px dashed #d1d5db; border-radius: 12px; padding: 40px 24px; text-align: center; cursor: pointer; transition: all 0.3s ease; background: linear-gradient(135deg, #f9fafb, #f5f5f5); display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 200px; margin-top: 1.5rem; }
.upload-area:hover { border-color: #dc2626; background: linear-gradient(135deg, #fef2f2, #fee2e2); }
.upload-icon { font-size: 48px; color: #dc2626; margin-bottom: 12px; transition: transform 0.3s; animation: float 3s ease-in-out infinite; }
@keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }
.upload-area:hover .upload-icon { transform: scale(1.1); }
.upload-text { font-size: 15px; font-weight: 700; color: #1a1a2e; margin-bottom: 6px; }
.upload-hint { font-size: 13px; color: #9ca3af; margin: 0; }

.info-box { font-size: 12px; color: #6b7280; padding: 10px 12px; background: #f9fafb; border-radius: 8px; border-left: 3px solid #dc2626; margin-bottom: 1rem; }
.info-box strong { color: #374151; }
.message-box { padding: 14px 16px; background: #fef2f2; border: 1.5px solid #fecaca; border-radius: 10px; color: #991b1b; font-size: 12.5px; margin-top: 1.5rem; }
.message-box i { margin-right: 8px; }

/* DARK MODE */
body.dark-mode .settings-header h1 { color: #e0e0e0; }
body.dark-mode .settings-header p  { color: #9ca3af; }
body.dark-mode .settings-header .label { color: #9ca3af; }
body.dark-mode .card { background: #2d2d2d; border-color: #404040; }
body.dark-mode .card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.3); }
body.dark-mode .card-header { background: linear-gradient(135deg, #262626, #1f1f1f); border-color: #404040; }
body.dark-mode .card-header h5 { color: #e0e0e0; }
body.dark-mode .card-header p   { color: #9ca3af; }
body.dark-mode .section-title   { color: #9ca3af; border-color: #404040; }
body.dark-mode .form-item label { color: #d1d5db; }
body.dark-mode .form-control, body.dark-mode .form-select { background: #3d3d3d; color: #e0e0e0; border-color: #404040; }
body.dark-mode .form-control:focus, body.dark-mode .form-select:focus { background: #454545; border-color: #c9354a; box-shadow: 0 0 0 3px rgba(201, 53, 74, 0.1); }
body.dark-mode .switch-box { background: linear-gradient(135deg, #2d2d2d, #262626); border-color: #404040; }
body.dark-mode .switch-box-label h4 { color: #e0e0e0; }
body.dark-mode .action-buttons  { border-color: #404040; }
body.dark-mode .btn-cancel { background: #3d3d3d; border-color: #505050; color: #d1d5db; }
body.dark-mode .banner-item { background: linear-gradient(135deg, #3d3d3d, #353535); border-color: #404040; }
body.dark-mode .upload-area { border-color: #404040; background: linear-gradient(135deg, #2d2d2d, #262626); }
body.dark-mode .upload-area:hover { background: linear-gradient(135deg, #3d2d2d, #352626); border-color: #c9354a; }
body.dark-mode .upload-icon { color: #f87171; }
body.dark-mode .upload-text { color: #e0e0e0; }
body.dark-mode .info-box { background: #3d3d3d; color: #d1d5db; border-left-color: #f87171; }
body.dark-mode .info-box strong { color: #e0e0e0; }
body.dark-mode .message-box { background: #3d2d2d; border-color: #5a3d3d; color: #fca5a5; }

/* FONT SIZE */
body.font-small  { font-size: 14px; }
body.font-normal { font-size: 16px; }
body.font-large  { font-size: 18px; }

@media (max-width: 768px) {
    .form-row { grid-template-columns: 1fr; gap: 1rem; }
    .btn-save { margin-left: 0; margin-top: 8px; }
    .banner-list { grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); }
}
@media (max-width: 576px) {
    .settings-wrapper { padding: 0 0.75rem 1.5rem; }
    .settings-header h1 { font-size: 18px; }
    .card-body { padding: 1.25rem; }
    .switch-box { flex-direction: column; align-items: flex-start; }
    .banner-item { height: 160px; }
    .upload-area { min-height: 160px; padding: 24px 16px; }
    .action-buttons { flex-direction: column; }
    .btn { width: 100%; justify-content: center; }
    .btn-save { margin-left: 0; }
}
@media (max-width: 480px) {
    .settings-header h1 { font-size: 16px; }
    .form-row { gap: 0.75rem; }
    .upload-icon { font-size: 36px; }
}
</style>

<div class="settings-wrapper">
    <div class="settings-header">
        <span class="label">Konfigurasi</span>
        <h1>
            <span class="icon"><i class="fas fa-cog"></i></span>
            Pengaturan Sistem
        </h1>
        <p>Kelola konfigurasi dan pengaturan aplikasi E-Learning</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-circle-exclamation"></i>
            <div>
                <strong>Terjadi Kesalahan!</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            <i class="fas fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-warning">
            <i class="fas fa-triangle-exclamation"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- AKADEMIK CARD -->
    <div class="card">
        <div class="card-header">
            <div class="icon"><i class="fas fa-book"></i></div>
            <div>
                <h5>Informasi Akademik</h5>
                <p>Atur nama sekolah, tahun akademik, semester, dan preferensi tampilan</p>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf

                <div class="section-title">Data Sekolah</div>
                <div class="form-row">
                    <div class="form-item">
                        <label for="school_name">Nama Sekolah</label>
                        <input 
                            type="text" 
                            id="school_name"
                            name="school_name" 
                            class="form-control @error('school_name') is-invalid @enderror"
                            value="{{ old('school_name', $themeSettings['school_name']) }}" 
                            placeholder="Masukkan nama sekolah"
                            required>
                        <span class="help-text">Ditampilkan di seluruh halaman aplikasi</span>
                    </div>

                    <div class="form-item">
                        <label for="academic_year">Tahun Akademik</label>
                        <input 
                            type="number" 
                            id="academic_year"
                            name="academic_year" 
                            class="form-control @error('academic_year') is-invalid @enderror"
                            value="{{ old('academic_year', $themeSettings['academic_year']) }}" 
                            min="2000" 
                            max="2100"
                            required>
                        <span class="help-text">Contoh: 2024 → tampil sebagai 2024/2025</span>
                    </div>

                    <div class="form-item">
                        <label for="semester">Semester Aktif</label>
                        <select 
                            id="semester"
                            name="semester" 
                            class="form-select @error('semester') is-invalid @enderror"
                            required>
                            <option value="1" {{ old('semester', $themeSettings['semester']) == '1' ? 'selected' : '' }}>Semester 1 (Ganjil)</option>
                            <option value="2" {{ old('semester', $themeSettings['semester']) == '2' ? 'selected' : '' }}>Semester 2 (Genap)</option>
                        </select>
                        <span class="help-text">Semester yang sedang berjalan</span>
                    </div>

                    <div class="form-item">
                        <label for="font_size">Ukuran Font</label>
                        <select 
                            id="font_size"
                            name="font_size" 
                            class="form-select @error('font_size') is-invalid @enderror"
                            required>
                            <option value="small"  {{ old('font_size', $themeSettings['font_size']) === 'small'  ? 'selected' : '' }}>Kecil (14px)</option>
                            <option value="normal" {{ old('font_size', $themeSettings['font_size']) === 'normal' ? 'selected' : '' }}>Normal (16px)</option>
                            <option value="large"  {{ old('font_size', $themeSettings['font_size']) === 'large'  ? 'selected' : '' }}>Besar (18px)</option>
                        </select>
                        <span class="help-text">Ukuran font untuk seluruh aplikasi</span>
                    </div>
                </div>

                <div class="action-buttons">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-cancel">
                        <i class="fas fa-xmark"></i> Batal
                    </a>
                    <button type="button" class="btn btn-reset" onclick="confirmReset()">
                        <i class="fas fa-rotate-left"></i> Reset Default
                    </button>
                    <button type="submit" class="btn btn-save">
                        <i class="fas fa-floppy-disk"></i> Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- BANNER CARD -->
    <div class="card">
        <div class="card-header">
            <div class="icon"><i class="fas fa-images"></i></div>
            <div>
                <h5>Banner Halaman Login</h5>
                <p>Kelola gambar carousel untuk halaman login (Maksimal: 5 gambar)</p>
            </div>
        </div>
        <div class="card-body">
            @if ($banners->count() > 0)
                <div class="section-title">Banner Tersimpan ({{ $banners->count() }}/5)</div>
                <div class="banner-list">
                    @foreach ($banners as $banner)
                        <div class="banner-item {{ !$banner->is_active ? 'inactive' : '' }}">
                            <img src="{{ asset($banner->image_path) }}" alt="Banner {{ $loop->iteration }}">
                            <span class="banner-badge {{ $banner->is_active ? 'badge-active' : 'badge-inactive' }}">
                                {{ $banner->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                            <div class="banner-actions">
                                <form method="POST" action="{{ route('admin.banners.delete', $banner->id) }}" style="margin: 0;" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete(event, 'Banner')" class="banner-btn btn-delete" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.banners.toggle', $banner->id) }}" style="margin: 0;">
                                    @csrf
                                    @method('PATCH')
                                    <button 
                                        type="submit" 
                                        class="banner-btn btn-toggle {{ $banner->is_active ? 'active' : '' }}" 
                                        title="{{ $banner->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <i class="fas fa-{{ $banner->is_active ? 'eye-slash' : 'eye' }}"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color: #9ca3af; font-size: 13px; text-align: center; padding: 1rem 0;">
                    Belum ada banner. Upload banner pertama Anda di bawah.
                </p>
            @endif

            @if ($remainingSlots > 0)
                <div class="banner-section">
                    <div class="section-title">Upload Banner Baru</div>
                    <div class="info-box">
                        <strong>Slot tersisa:</strong> {{ $remainingSlots }}/5 &nbsp;|&nbsp;
                        <strong>Format:</strong> JPG, PNG, GIF &nbsp;|&nbsp;
                        <strong>Ukuran max:</strong> 5MB per file
                    </div>
                    {{-- Form upload banner TERPISAH dari form settings --}}
                    <form 
                        id="banner-upload-form"
                        method="POST" 
                        action="{{ route('admin.settings.update') }}" 
                        enctype="multipart/form-data">
                        @csrf
                        <input 
                            type="file" 
                            id="banner-input"
                            name="login_banners[]" 
                            accept="image/jpeg,image/png,image/gif" 
                            multiple 
                            style="display:none;"
                            onchange="handleBannerUpload(this)">
                    </form>
                    <div class="upload-area" onclick="document.getElementById('banner-input').click();">
                        <div class="upload-icon"><i class="fas fa-cloud-arrow-up"></i></div>
                        <div class="upload-text">Klik atau drag gambar ke sini</div>
                        <div class="upload-hint">JPG, PNG, GIF | Max 5MB per file | Sisa {{ $remainingSlots }} slot</div>
                    </div>
                </div>
            @else
                <div class="message-box">
                    <i class="fas fa-circle-info"></i>
                    Batas maksimal banner sudah tercapai (5/5). Hapus beberapa banner terlebih dahulu sebelum upload yang baru.
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// ─── Terapkan font size dari server saat halaman load ─────────────
(function() {
    const fontSize = '{{ $themeSettings['font_size'] }}';

    document.body.classList.remove('font-small', 'font-normal', 'font-large');
    document.body.classList.add('font-' + fontSize);
})();

// ─── Preview real-time font size saat select diubah ──────────────────────────
document.getElementById('font_size').addEventListener('change', function() {
    document.body.classList.remove('font-small', 'font-normal', 'font-large');
    document.body.classList.add('font-' + this.value);
});

// ─── Upload banner: validasi sebelum submit ───────────────────────────────────
function handleBannerUpload(input) {
    const maxSlots = {{ $remainingSlots }};
    const maxSize  = 5 * 1024 * 1024; // 5MB
    const files    = Array.from(input.files);

    if (files.length === 0) return;

    if (files.length > maxSlots) {
        alert(`Hanya bisa upload maksimal ${maxSlots} file lagi. Anda memilih ${files.length} file.`);
        input.value = '';
        return;
    }

    const oversized = files.filter(f => f.size > maxSize);
    if (oversized.length > 0) {
        const names = oversized.map(f => f.name).join(', ');
        alert(`File berikut melebihi batas 5MB:\n${names}`);
        input.value = '';
        return;
    }

    const invalidType = files.filter(f => !['image/jpeg', 'image/png', 'image/gif'].includes(f.type));
    if (invalidType.length > 0) {
        alert('Hanya file JPG, PNG, dan GIF yang diperbolehkan.');
        input.value = '';
        return;
    }

    // Semua valid → submit form
    document.getElementById('banner-upload-form').submit();
}

// ─── Konfirmasi reset ─────────────────────────────────────────────────────────
function confirmReset() {
    showConfirmation('Reset semua pengaturan ke nilai default?\nTindakan ini tidak dapat dibatalkan.', 'Konfirmasi Reset', function() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('admin.settings.reset') }}';
        form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
        document.body.appendChild(form);
        form.submit();
    });
}

// ─── Auto-hide alert setelah 5 detik ─────────────────────────────────────────
setTimeout(function() {
    document.querySelectorAll('.alert').forEach(function(el) {
        el.style.transition = 'opacity 0.5s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 500);
    });
}, 5000);

function confirmDelete(event, name) {
    event.preventDefault();
    const form = event.target.closest('form');
    showConfirmation(`Yakin ingin menghapus ${name}?`, 'Konfirmasi Hapus', function() {
        form.submit();
    });
}
</script>

@endsection