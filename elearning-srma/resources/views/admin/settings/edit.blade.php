@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')
@section('icon', 'cog')

@section('content')
    <div style="margin-bottom: 30px;">
        <p style="color: #999; font-size: 14px; margin-bottom: 5px;">Konfigurasi</p>
        <h1 class="page-title">
            <i class="fas fa-sliders-h"></i>
            Pengaturan Sistem
        </h1>
        <p class="page-description">Kelola konfigurasi dan pengaturan aplikasi E-Learning</p>
    </div>

    <!-- Banner Login Setting -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-images" style="color: var(--primary); margin-right: 10px;"></i>
                Banner Halaman Login (Carousel)
            </div>
        </div>
        <div class="card-body">
            <p style="color: #666; margin-bottom: 20px; font-size: 14px;">
                Upload gambar banner yang akan ditampilkan di sebelah kiri halaman login. 
                <br>Rekomendasi ukuran: <strong>600 x 800px</strong>, format JPG/PNG/GIF, maksimal 5MB per banner
                <br><strong>Batas maksimal banner: {{ $bannerCount }}/5 
                @if($remainingSlots > 0)
                    <span style="color: #28a745;">({{ $remainingSlots }} slot tersisa)</span>
                @else
                    <span style="color: #dc2626;">(PENUH)</span>
                @endif
                </strong>
            </p>

            <!-- Keterangan Alur -->
            <div style="background: #e7f3ff; border-left: 4px solid var(--primary); padding: 12px 15px; border-radius: 6px; margin-bottom: 20px;">
                <p style="margin: 0; color: #0066cc; font-size: 13px;">
                    <i class="fas fa-info-circle"></i> <strong>Cara kerja:</strong> Card upload muncul di paling kiri, setiap kali Anda upload gambar, card akan bergeser ke kanan dan digantikan dengan card upload baru. Ketika sudah 5 banner, card upload akan hilang otomatis.
                </p>
            </div>

<!-- Banners + Upload Card Container (Flexbox) -->
<div style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-start;" id="banner-container">

    <!-- Banner Cards: gambar yang sudah diupload, urut dari kiri -->
    @foreach($banners as $banner)
    <div style="flex: 0 0 calc(20% - 12px); min-width: 160px;">
        <div style="border: 1px solid var(--border); border-radius: 12px; overflow: hidden; background: white; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0,0,0,0.08); position: relative; height: 220px;">
            <div style="position: relative; overflow: hidden; height: 150px; background: #f0f0f0;">
                <img src="{{ asset($banner->image_path) }}" alt="Banner" style="width: 100%; height: 100%; object-fit: cover;">

                <!-- Order Badge -->
                <div style="position: absolute; top: 6px; left: 6px; background: rgba(0,0,0,0.6); color: white; padding: 3px 8px; border-radius: 6px; font-size: 11px; font-weight: 700;">
                    #{{ $banner->order }}
                </div>

                <!-- Tombol Hapus (X) pojok kanan atas -->
                <form method="POST" action="{{ route('admin.banners.delete', $banner->id) }}" style="position: absolute; top: 6px; right: 6px; margin: 0;" onsubmit="return confirm('Hapus banner ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background: rgba(220, 38, 38, 0.9); color: white; border: none; border-radius: 50%; width: 28px; height: 28px; cursor: pointer; font-size: 16px; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease;" title="Hapus" onmouseover="this.style.background='rgba(220, 38, 38, 1)'; this.style.transform='scale(1.1)'" onmouseout="this.style.background='rgba(220, 38, 38, 0.9)'; this.style.transform='scale(1)'">
                        <i class="fas fa-times"></i>
                    </button>
                </form>

                <!-- Status Badge -->
                <div style="position: absolute; bottom: 6px; right: 6px;">
                    @if($banner->is_active)
                        <span style="background: #28a745; color: white; padding: 3px 8px; border-radius: 6px; font-size: 10px; font-weight: 700; display: flex; align-items: center; gap: 3px;">
                            <i class="fas fa-check-circle"></i> Aktif
                        </span>
                    @else
                        <span style="background: #6c757d; color: white; padding: 3px 8px; border-radius: 6px; font-size: 10px; font-weight: 700; display: flex; align-items: center; gap: 3px;">
                            <i class="fas fa-times-circle"></i> Off
                        </span>
                    @endif
                </div>
            </div>

            <div style="padding: 10px;">
                <div style="display: flex; gap: 5px;">
                    <!-- Toggle Active -->
                    <form method="POST" action="{{ route('admin.banners.toggle', $banner->id) }}" style="flex: 1;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-sm" style="width: 100%; padding: 5px 6px; font-size: 9px; background: {{ $banner->is_active ? '#ffc107' : '#28a745' }}; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: 600; transition: all 0.3s ease;" title="{{ $banner->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                            {{ $banner->is_active ? '⊘' : '✓' }}
                        </button>
                    </form>
                </div>
                <p style="margin: 6px 0 0 0; color: #999; font-size: 10px; text-align: center;">{{ Carbon\Carbon::parse($banner->created_at)->format('d M Y') }}</p>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Upload Card: selalu nempel di sebelah kanan gambar terakhir -->
    @if($remainingSlots > 0)
    <div style="flex: 0 0 calc(20% - 12px); min-width: 160px;">
        <label style="display: block; font-weight: 600; margin-bottom: 10px; color: var(--secondary); font-size: 13px;">
            Tambah Banner:
        </label>

        <div style="position: relative; border: 2px dashed var(--primary); border-radius: 12px; padding: 30px 15px; text-align: center; background: #fafafa; cursor: pointer; transition: all 0.3s ease; height: 220px; display: flex; flex-direction: column; align-items: center; justify-content: center;" class="dropzone" id="mainDropzone" onmouseover="this.style.borderColor='var(--primary)'; this.style.background='#fff5f5'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(196, 30, 58, 0.15)'" onmouseout="this.style.borderColor='var(--primary)'; this.style.background='#fafafa'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
            <i class="fas fa-cloud-upload-alt" style="font-size: 40px; color: var(--primary); margin-bottom: 12px; display: block;"></i>
            <p style="font-weight: 600; color: var(--secondary); margin-bottom: 5px; font-size: 12px;">Klik/Drag</p>
            <p style="color: #aaa; font-size: 10px; margin: 0; line-height: 1.4;">JPG, PNG, GIF<br>Max 5MB</p>
            <input
                type="file"
                name="login_banners[]"
                class="banner-input"
                accept="image/*"
                style="display: none;"
                id="fileInput"
            >
            <p style="color: #28a745; font-size: 11px; margin-top: 8px; font-weight: 600;" id="filename"></p>
        </div>
    </div>
    @endif

</div>
            <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
                @csrf

                <!-- Action Buttons -->
                <div style="display: flex; gap: 10px; margin-top: 30px;">
                    @if($remainingSlots > 0)
                    <button 
                        type="submit" 
                        class="btn btn-primary"
                        style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; font-weight: 600;"
                    >
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    @endif
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; font-weight: 600;">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Setup drag and drop
        const dropzone = document.getElementById('mainDropzone');
        const fileInput = document.getElementById('fileInput');
        const filename = document.getElementById('filename');

        if (dropzone && fileInput) {
            dropzone.addEventListener('click', () => fileInput.click());

            dropzone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropzone.style.borderColor = 'var(--primary)';
                dropzone.style.background = '#fff5f5';
                dropzone.style.transform = 'translateY(-2px)';
                dropzone.style.boxShadow = '0 4px 12px rgba(196, 30, 58, 0.15)';
            });

            dropzone.addEventListener('dragleave', () => {
                dropzone.style.borderColor = 'var(--primary)';
                dropzone.style.background = '#fafafa';
                dropzone.style.transform = 'translateY(0)';
                dropzone.style.boxShadow = 'none';
            });

            dropzone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropzone.style.borderColor = 'var(--primary)';
                dropzone.style.background = '#fafafa';
                dropzone.style.transform = 'translateY(0)';
                dropzone.style.boxShadow = 'none';
                fileInput.files = e.dataTransfer.files;
                updateFilename();
            });

            fileInput.addEventListener('change', updateFilename);

            function updateFilename() {
                if (fileInput.files.length > 0) {
                    const file = fileInput.files[0];
                    const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
                    filename.textContent = `✓ ${file.name} (${sizeMB}MB)`;
                } else {
                    filename.textContent = '';
                }
            }
        }

        // Responsive handling for mobile
        if (window.innerWidth < 768) {
            const container = document.getElementById('banner-container');
            if (container) {
                // Ubah flex basis untuk mobile (lebih kecil)
                const items = container.querySelectorAll('[style*="flex: 0 0"]');
                items.forEach(item => {
                    item.style.flex = '0 0 calc(50% - 7.5px)';
                });
            }
        }

        window.addEventListener('resize', () => {
            const container = document.getElementById('banner-container');
            if (container) {
                const items = container.querySelectorAll('[style*="flex"]');
                if (window.innerWidth < 768) {
                    items.forEach(item => {
                        item.style.flex = '0 0 calc(50% - 7.5px)';
                    });
                } else if (window.innerWidth < 1200) {
                    items.forEach(item => {
                        item.style.flex = '0 0 calc(33.333% - 10px)';
                    });
                } else {
                    items.forEach(item => {
                        item.style.flex = '0 0 calc(20% - 12px)';
                    });
                }
            }
        });
    </script>
@endsection
