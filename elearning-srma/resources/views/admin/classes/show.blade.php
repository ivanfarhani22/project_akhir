@extends('layouts.admin')
@section('title', 'Detail Kelas')
@section('icon', 'fas fa-chalkboard')

@php
    $allStudents       = \App\Models\User::where('role', 'siswa')->get();
    $enrolledIds       = $class->students->pluck('id')->toArray();
    $availableStudents = $allStudents->whereNotIn('id', $enrolledIds)->values();
    $dayTranslate      = ['monday'=>'Senin','tuesday'=>'Selasa','wednesday'=>'Rabu',
                          'thursday'=>'Kamis','friday'=>'Jumat','saturday'=>'Sabtu','sunday'=>'Minggu'];
@endphp

@section('content')
<nav class="breadcrumb">
    <a href="{{ route('admin.classes.index') }}"><i class="fas fa-chalkboard"></i> Kelola Kelas</a>
    <span class="sep">/</span>
    <span class="current">{{ $class->name }}</span>
</nav>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fas fa-chalkboard"></i> {{ $class->name }}</h1>
        <p class="page-description">Kelola mata pelajaran, guru, dan siswa dalam kelas</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.classes.edit', $class) }}" class="btn btn-secondary">
            <i class="fas fa-edit"></i> Edit Kelas
        </a>
        <form method="POST" action="{{ route('admin.classes.destroy', $class) }}"
              onsubmit="return confirm('Yakin ingin menghapus kelas ini?')">
            @csrf @method('DELETE')
            <button class="btn btn-danger"><i class="fas fa-trash"></i> Hapus Kelas</button>
        </form>
    </div>
</div>

<!-- Info Cards -->
<div class="info-grid">
    <div class="card">
        <div class="card-header"><div class="card-title"><i class="fas fa-info-circle"></i> Deskripsi</div></div>
        <div class="card-body"><p class="text-muted">{{ $class->description ?? '—' }}</p></div>
    </div>

    <div class="card">
        <div class="card-header"><div class="card-title"><i class="fas fa-calendar"></i> Jadwal</div></div>
        <div class="card-body">
            @if ($class->day_of_week || $class->start_time || $class->end_time || $class->room)
                <p class="text-muted">
                    <strong>Hari:</strong> {{ $class->day_of_week ? ucfirst($class->day_of_week) : '—' }}<br>
                    <strong>Waktu:</strong>
                    @if ($class->start_time && $class->end_time)
                        {{ \Carbon\Carbon::createFromFormat('H:i', $class->start_time)->format('H:i') }} –
                        {{ \Carbon\Carbon::createFromFormat('H:i', $class->end_time)->format('H:i') }}
                    @else —
                    @endif
                    <br>
                    <strong>Ruangan:</strong> {{ $class->room ?? '—' }}
                </p>
            @else
                <p class="text-muted">Belum ada jadwal yang diatur</p>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header"><div class="card-title"><i class="fas fa-chart-bar"></i> Statistik</div></div>
        <div class="card-body">
            <p class="text-muted">
                📖 Mata Pelajaran: <strong class="text-primary">{{ $class->classSubjects->count() }}</strong><br>
                👥 Siswa: <strong class="text-success">{{ $class->students->count() }}</strong>
            </p>
        </div>
    </div>
</div>

<!-- Mata Pelajaran -->
<div class="card mb-30">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-book"></i> Mata Pelajaran
            <span class="badge badge-primary">{{ $class->classSubjects->count() }}</span>
        </div>
        <a href="{{ route('admin.class-subjects.create', $class) }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah
        </a>
    </div>
    <div class="card-body">
        @if ($class->classSubjects->isEmpty())
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>Belum ada mata pelajaran di kelas ini</p>
                <a href="{{ route('admin.class-subjects.create', $class) }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Mata Pelajaran
                </a>
            </div>
        @else
            <div class="subject-grid">
                @foreach ($class->classSubjects as $cs)
                    <div class="subject-card">
                        <div class="subject-card__header">
                            <h4>📖 {{ $cs->subject->name }}</h4>
                            <p class="text-muted text-sm">Kode: {{ $cs->subject->code }}</p>
                        </div>
                        <div class="subject-card__teacher">
                            <span class="label">Guru Pengajar</span>
                            <p>👤 {{ $cs->teacher->name }}</p>
                        </div>
                        @if ($cs->description)
                            <p class="text-muted text-sm">{{ Str::limit($cs->description, 60) }}</p>
                        @endif
                        <div class="subject-card__actions">
                            <a href="{{ route('admin.class-subjects.edit', [$class, $cs]) }}"
                               class="btn btn-secondary btn-sm flex-1">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form method="POST"
                                  action="{{ route('admin.class-subjects.destroy', [$class, $cs]) }}"
                                  class="flex-1" onsubmit="return confirm('Hapus mata pelajaran ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm w-full">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<!-- Jadwal Pelajaran -->
<div class="card mb-30">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-calendar-alt"></i> Jadwal Pelajaran
            <span class="badge badge-warning">{{ $class->schedules->count() }}</span>
        </div>
        @if ($class->classSubjects->isNotEmpty())
            <a href="{{ route('admin.schedules.create', $class) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Jadwal
            </a>
        @endif
    </div>
    <div class="card-body">
        @if ($class->schedules->isEmpty())
            <div class="empty-state">
                <i class="fas fa-calendar"></i>
                <p>Belum ada jadwal pelajaran</p>
                @if ($class->classSubjects->isNotEmpty())
                    <a href="{{ route('admin.schedules.create', $class) }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Buat Jadwal
                    </a>
                @else
                    <p class="text-muted text-sm">Tambahkan mata pelajaran terlebih dahulu</p>
                @endif
            </div>
        @else
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Hari</th><th>Waktu</th><th>Mata Pelajaran</th>
                            <th>Guru</th><th>Ruangan</th><th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($class->schedules as $schedule)
                            <tr>
                                <td class="fw-500">{{ $dayTranslate[$schedule->day_of_week] ?? $schedule->day_of_week }}</td>
                                <td><strong>{{ $schedule->start_time }} – {{ $schedule->end_time }}</strong></td>
                                <td>{{ $schedule->classSubject->subject->name }}</td>
                                <td>{{ $schedule->classSubject->teacher->name }}</td>
                                <td>{{ $schedule->room ?? '—' }}</td>
                                <td class="text-center">
                                    <div class="action-group">
                                        <a href="{{ route('admin.schedules.edit', [$class, $schedule]) }}"
                                           class="btn btn-secondary btn-xs"><i class="fas fa-edit"></i></a>
                                        <form method="POST"
                                              action="{{ route('admin.schedules.destroy', [$class, $schedule]) }}"
                                              onsubmit="return confirm('Hapus jadwal ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-xs"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<!-- Daftar Siswa -->
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-users"></i> Daftar Siswa
            <span class="badge badge-success">{{ $class->students->count() }}</span>
        </div>
        <button type="button" onclick="StudentModal.open()" class="btn btn-primary btn-sm">
            <i class="fas fa-user-plus"></i> Tambah Siswa
        </button>
    </div>
    <div class="card-body">
        @if ($class->students->isEmpty())
            <div class="empty-state">
                <i class="fas fa-users"></i>
                <p>Belum ada siswa di kelas ini</p>
                <button type="button" onclick="StudentModal.open()" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Tambah Siswa
                </button>
            </div>
        @else
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr><th>No</th><th>Nama Siswa</th><th>Email</th><th class="text-center">Aksi</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($class->students as $i => $student)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td class="fw-500">{{ $student->name }}</td>
                                <td class="text-muted">{{ $student->email }}</td>
                                <td class="text-center">
                                    <form method="POST"
                                          action="{{ route('admin.classes.students.destroy', [$class, $student]) }}"
                                          onsubmit="return confirm('Yakin ingin menghapus siswa ini dari kelas?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-xs"><i class="fas fa-trash"></i> Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<!-- Modal Tambah Siswa -->
<div id="addStudentModal" class="modal-backdrop" hidden aria-modal="true" role="dialog">
    <div class="modal-box">
        <div class="modal-header">
            <h2><i class="fas fa-user-plus"></i> Tambah Siswa ke {{ $class->name }}</h2>
            <button type="button" onclick="StudentModal.close()" class="btn-close" aria-label="Tutup">✕</button>
        </div>

        <form method="POST" action="{{ route('admin.classes.students.store', $class) }}" id="addStudentForm" onsubmit="disableSubmitButton()">
            @csrf
            @if ($availableStudents->isEmpty())
                <p class="text-muted text-center p-20">
                    <i class="fas fa-check-circle"></i> Semua siswa sudah terdaftar di kelas ini.
                </p>
            @else
                <input type="text" id="studentSearch" placeholder="🔍 Cari nama atau email..."
                       class="input-search" autocomplete="off">

                <div class="student-list-wrap">
                    <div id="studentList" class="student-list"></div>
                    <p id="noResults" class="empty-search" hidden>
                        <i class="fas fa-search"></i><br>Tidak ada siswa yang cocok
                    </p>
                </div>

                <div id="pagination" class="pagination-bar">
                    <span id="pageInfo" class="page-info">Halaman 1 dari 1</span>
                    <div class="page-btns">
                        <button type="button" id="prevBtn" onclick="StudentModal.prev()" class="btn btn-outline btn-xs">
                            <i class="fas fa-chevron-left"></i> Sebelumnya
                        </button>
                        <button type="button" id="nextBtn" onclick="StudentModal.next()" class="btn btn-outline btn-xs">
                            Selanjutnya <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <p class="selected-info">
                    <i class="fas fa-info-circle"></i>
                    Dipilih: <strong id="selectedCount">0</strong> dari
                    <strong>{{ $availableStudents->count() }}</strong> siswa
                </p>
            @endif

            <div class="modal-footer">
                <button type="button" onclick="StudentModal.close()" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </button>
                @if ($availableStudents->isNotEmpty())
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Tambahkan Siswa</button>
                @endif
            </div>
        </form>
    </div>
</div>

<script>
// Prevent double form submission
function disableSubmitButton() {
    const submitBtn = document.querySelector('#addStudentForm button[type="submit"]');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
    }
}

const StudentModal = (() => {
    const STUDENTS = @json($availableStudents->map(fn($s) => ['id'=>$s->id,'name'=>$s->name,'email'=>$s->email])->values()->toArray());
    const PER_PAGE = 8;
    
    let filteredStudents = [...STUDENTS];
    let currentPage = 1;
    let selectedIds = new Set();

    const getElement = (id) => document.getElementById(id);

    function renderStudents() {
        const listEl = getElement('studentList');
        if (!listEl) return;

        const totalPages = Math.max(1, Math.ceil(filteredStudents.length / PER_PAGE));
        currentPage = Math.min(currentPage, totalPages);

        if (filteredStudents.length === 0) {
            listEl.innerHTML = '';
            const noResults = getElement('noResults');
            if (noResults) noResults.hidden = false;
            const pagination = getElement('pagination');
            if (pagination) pagination.hidden = true;
            return;
        }

        const noResults = getElement('noResults');
        if (noResults) noResults.hidden = true;
        
        const pagination = getElement('pagination');
        if (pagination) pagination.hidden = filteredStudents.length <= PER_PAGE;

        const start = (currentPage - 1) * PER_PAGE;
        const end = start + PER_PAGE;
        const pageStudents = filteredStudents.slice(start, end);

        listEl.innerHTML = pageStudents.map(student => `
            <label class="student-item ${selectedIds.has(student.id) ? 'is-checked' : ''}">
                <input type="checkbox" name="student_ids[]" value="${student.id}"
                       ${selectedIds.has(student.id) ? 'checked' : ''}
                       onchange="StudentModal.toggleStudent(${student.id}, this.checked)">
                <div class="student-item__info">
                    <p class="student-item__name">${student.name}</p>
                    <p class="student-item__email">${student.email}</p>
                </div>
            </label>
        `).join('');

        const pageInfo = getElement('pageInfo');
        if (pageInfo) pageInfo.textContent = `Halaman ${currentPage} dari ${totalPages}`;

        const prevBtn = getElement('prevBtn');
        if (prevBtn) prevBtn.disabled = currentPage <= 1;

        const nextBtn = getElement('nextBtn');
        if (nextBtn) nextBtn.disabled = currentPage >= totalPages;

        updateSelectedCount();
    }

    function updateSelectedCount() {
        const counter = getElement('selectedCount');
        if (counter) counter.textContent = selectedIds.size;
    }

    function openModal() {
        const modal = getElement('addStudentModal');
        if (modal) {
            modal.hidden = false;
            document.body.style.overflow = 'hidden';
        }
        const search = getElement('studentSearch');
        if (search) search.value = '';
        filteredStudents = [...STUDENTS];
        currentPage = 1;
        renderStudents();
        if (search) search.focus();
    }

    function closeModal() {
        const modal = getElement('addStudentModal');
        if (modal) modal.hidden = true;
        document.body.style.overflow = '';
    }

    function toggleStudent(id, checked) {
        if (checked) {
            selectedIds.add(id);
        } else {
            selectedIds.delete(id);
        }
        const checkbox = document.querySelector(`input[value="${id}"]`);
        if (checkbox) {
            checkbox.closest('label')?.classList.toggle('is-checked', checked);
        }
        updateSelectedCount();
    }

    function prevPage() {
        if (currentPage > 1) {
            currentPage--;
            renderStudents();
        }
    }

    function nextPage() {
        const totalPages = Math.ceil(filteredStudents.length / PER_PAGE);
        if (currentPage < totalPages) {
            currentPage++;
            renderStudents();
        }
    }

    let searchTimeout;
    function onSearch(query) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const q = query.toLowerCase().trim();
            if (q) {
                filteredStudents = STUDENTS.filter(s =>
                    s.name.toLowerCase().includes(q) ||
                    s.email.toLowerCase().includes(q)
                );
            } else {
                filteredStudents = [...STUDENTS];
            }
            currentPage = 1;
            renderStudents();
        }, 200);
    }

    document.addEventListener('DOMContentLoaded', () => {
        const search = getElement('studentSearch');
        if (search) {
            search.addEventListener('input', (e) => onSearch(e.target.value));
        }

        const modal = getElement('addStudentModal');
        if (modal) {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) closeModal();
            });
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !getElement('addStudentModal').hidden) {
                closeModal();
            }
        });
    });

    return {
        open: openModal,
        close: closeModal,
        toggleStudent,
        prev: prevPage,
        next: nextPage
    };
})();
</script>

<style>
    /* ========== UTILITIES ========== */
    .mb-30 { margin-bottom: 30px; }
    .text-muted { color: #666; }
    .text-sm { font-size: 12px; }
    .text-center { text-align: center; }
    .fw-500 { font-weight: 500; }
    .flex-1 { flex: 1; }
    .w-full { width: 100%; }
    .p-20 { padding: 20px; }

    /* ========== BREADCRUMB ========== */
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        font-size: 14px;
    }
    .breadcrumb a {
        color: var(--primary);
        text-decoration: none;
    }
    .breadcrumb a:hover {
        text-decoration: underline;
    }
    .breadcrumb .sep {
        color: #bbb;
    }
    .breadcrumb .current {
        color: var(--secondary);
        font-weight: 600;
    }

    /* ========== PAGE HEADER ========== */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 15px;
    }
    .header-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    /* ========== ALERTS ========== */
    .alert {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
    }
    .alert-success {
        background: #eff9ef;
        border: 1px solid #c3e6c3;
        color: #276727;
    }

    /* ========== GRIDS ========== */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    .subject-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 16px;
    }

    /* ========== BADGES ========== */
    .badge {
        border-radius: 20px;
        padding: 2px 10px;
        font-size: 12px;
        font-weight: 600;
        color: #fff;
        margin-left: 8px;
    }
    .badge-primary { background: var(--primary); }
    .badge-success { background: var(--success); }
    .badge-warning { background: var(--warning); }

    /* ========== BUTTONS ========== */
    .btn-sm { font-size: 13px; padding: 7px 14px; }
    .btn-xs { font-size: 11px; padding: 5px 10px; }
    .btn-outline {
        background: #fff;
        border: 1px solid var(--border);
        color: var(--secondary);
    }
    .btn-outline:disabled {
        opacity: 0.45;
        cursor: not-allowed;
    }

    /* ========== EMPTY STATE ========== */
    .empty-state {
        text-align: center;
        padding: 50px 20px;
        color: #aaa;
    }
    .empty-state i {
        font-size: 34px;
        margin-bottom: 14px;
        display: block;
    }
    .empty-state p {
        margin: 0 0 16px;
    }

    /* ========== SUBJECT CARDS ========== */
    .subject-card {
        border: 1.5px solid var(--border);
        border-radius: 10px;
        padding: 16px;
        transition: box-shadow 0.2s;
    }
    .subject-card:hover {
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.09);
    }
    .subject-card__header {
        padding-bottom: 12px;
        border-bottom: 1.5px solid var(--border);
        margin-bottom: 12px;
    }
    .subject-card__header h4 {
        margin: 0 0 4px;
        font-size: 15px;
        color: var(--secondary);
    }
    .subject-card__teacher {
        margin-bottom: 10px;
    }
    .subject-card__teacher .label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        color: #aaa;
    }
    .subject-card__teacher p {
        margin: 5px 0 0;
        font-size: 14px;
        font-weight: 600;
        color: var(--secondary);
    }
    .subject-card__actions {
        display: flex;
        gap: 8px;
        padding-top: 12px;
        border-top: 1px solid var(--border);
    }

    /* ========== TABLE ========== */
    .table-wrapper {
        overflow-x: auto;
    }
    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    .data-table th {
        padding: 12px;
        text-align: left;
        font-weight: 600;
        color: var(--secondary);
        background: #f8f9fa;
        border-bottom: 2px solid var(--border);
    }
    .data-table td {
        padding: 12px;
        color: #555;
        border-bottom: 1px solid var(--border);
    }
    .data-table tr:last-child td {
        border-bottom: none;
    }
    .action-group {
        display: flex;
        gap: 6px;
        justify-content: center;
    }

    /* ========== MODAL ========== */
    .modal-backdrop[hidden] {
        display: none;
    }
    .modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.48);
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }
    .modal-box {
        background: #fff;
        border-radius: 14px;
        width: 100%;
        max-width: 580px;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 12px 48px rgba(0, 0, 0, 0.2);
        overflow: hidden;
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 24px 16px;
        border-bottom: 1.5px solid var(--border);
        flex-shrink: 0;
    }
    .modal-header h2 {
        margin: 0;
        font-size: 18px;
        color: var(--secondary);
    }
    .btn-close {
        background: none;
        border: none;
        font-size: 22px;
        cursor: pointer;
        color: #aaa;
        line-height: 1;
        padding: 0 4px;
        transition: color 0.15s;
    }
    .btn-close:hover {
        color: var(--secondary);
    }

    /* ========== MODAL FORM ========== */
    #addStudentForm {
        display: flex;
        flex-direction: column;
        flex: 1;
        overflow: hidden;
        padding: 20px 24px;
        gap: 14px;
    }
    .input-search {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        font-size: 13px;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        flex-shrink: 0;
        box-sizing: border-box;
    }
    .input-search:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
    }
    .student-list-wrap {
        flex: 1;
        overflow-y: auto;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        background: #fafafa;
        min-height: 0;
    }
    .student-list {
        padding: 10px;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .student-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 14px;
        border-radius: 7px;
        cursor: pointer;
        background: #fff;
        border: 1.5px solid transparent;
        transition: background 0.15s, border-color 0.15s;
    }
    .student-item:hover {
        background: #f3f4f6;
    }
    .student-item.is-checked {
        background: #eef2ff;
        border-color: var(--primary);
    }
    .student-item input[type="checkbox"] {
        width: 17px;
        height: 17px;
        flex-shrink: 0;
        cursor: pointer;
        accent-color: var(--primary);
    }
    .student-item__info {
        flex: 1;
        min-width: 0;
    }
    .student-item__name {
        margin: 0;
        font-weight: 600;
        font-size: 14px;
        color: var(--secondary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .student-item__email {
        margin: 2px 0 0;
        font-size: 12px;
        color: #999;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .empty-search {
        text-align: center;
        padding: 40px 20px;
        color: #bbb;
        font-size: 13px;
        margin: 0;
    }
    .empty-search i {
        font-size: 28px;
        margin-bottom: 10px;
        display: block;
    }

    /* ========== PAGINATION ========== */
    .pagination-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-shrink: 0;
        padding: 10px 14px;
        background: #fff;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        font-size: 12px;
    }
    .page-info {
        color: #666;
    }
    .page-btns {
        display: flex;
        gap: 8px;
    }

    /* ========== SELECTED INFO ========== */
    .selected-info {
        font-size: 12px;
        color: #1565c0;
        background: #e3f2fd;
        border-radius: 6px;
        padding: 10px 14px;
        margin: 0;
        flex-shrink: 0;
    }

    /* ========== MODAL FOOTER ========== */
    .modal-footer {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        padding-top: 4px;
        flex-shrink: 0;
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 600px) {
        .page-header {
            flex-direction: column;
        }
        .modal-box {
            max-height: 95vh;
            border-radius: 10px 10px 0 0;
            align-self: flex-end;
        }
        .modal-backdrop {
            align-items: flex-end;
            padding: 0;
        }
        .modal-header h2 {
            font-size: 16px;
        }
        .subject-grid,
        .info-grid {
            grid-template-columns: 1fr;
        }
        .pagination-bar {
            flex-direction: column;
            gap: 8px;
        }
    }
</style>

@endsection