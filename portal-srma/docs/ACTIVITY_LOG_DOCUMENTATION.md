# 📚 Dokumentasi Activity Log Management System
## Portal Sekolah SRMA 25 Lamongan

### Daftar Isi
1. [Arsitektur Sistem](#1-arsitektur-sistem)
2. [Komponen Utama](#2-komponen-utama)
3. [Alur Data](#3-alur-data)
4. [Fitur Keamanan](#4-fitur-keamanan)
5. [Retensi Data](#5-retensi-data)
6. [Panduan Penggunaan](#6-panduan-penggunaan)
7. [Konfigurasi Scheduler](#7-konfigurasi-scheduler)

---

## 1. Arsitektur Sistem

### 1.1 Diagram Arsitektur

```
┌─────────────────────────────────────────────────────────────────────────┐
│                          ACTIVITY LOG SYSTEM                            │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                          │
│  ┌──────────────┐    ┌────────────────────┐    ┌──────────────────┐    │
│  │   MODELS     │───▶│ ActivityLogObserver │───▶│ ActivityLogService│    │
│  │ (News, etc.) │    │   (Auto Logging)    │    │ (Business Logic) │    │
│  └──────────────┘    └────────────────────┘    └────────┬─────────┘    │
│                                                          │              │
│                                                          ▼              │
│                                              ┌──────────────────────┐   │
│                                              │   ActivityLog Model  │   │
│                                              │    (Database Table)  │   │
│                                              └──────────┬───────────┘   │
│                                                         │               │
│  ┌────────────────────────────────────────────────────┼────────────┐   │
│  │                      OUTPUT LAYER                   │            │   │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐ │            │   │
│  │  │  Web View   │  │ Export CSV  │  │Export Excel │ │            │   │
│  │  │  (Admin)    │  │             │  │             │ │            │   │
│  │  └─────────────┘  └─────────────┘  └─────────────┘ │            │   │
│  └────────────────────────────────────────────────────┴────────────┘   │
│                                                                          │
│  ┌────────────────────────────────────────────────────────────────┐    │
│  │                    MAINTENANCE LAYER                            │    │
│  │  ┌────────────────────────┐    ┌─────────────────────────────┐ │    │
│  │  │ CleanupActivityLogs    │◀───│  Laravel Scheduler (Daily)  │ │    │
│  │  │ (Console Command)      │    │  @ 02:00 AM                 │ │    │
│  │  └────────────────────────┘    └─────────────────────────────┘ │    │
│  └────────────────────────────────────────────────────────────────┘    │
│                                                                          │
└─────────────────────────────────────────────────────────────────────────┘
```

### 1.2 Design Pattern yang Digunakan

| Pattern | Implementasi | Tujuan |
|---------|--------------|--------|
| **Observer Pattern** | `ActivityLogObserver` | Auto-logging tanpa mengubah model |
| **Service Layer** | `ActivityLogService` | Pemisahan business logic |
| **Repository Pattern** | `ActivityLog Model` | Data access abstraction |
| **Single Responsibility** | Setiap class 1 fungsi | Maintainability |

---

## 2. Komponen Utama

### 2.1 ActivityLogService (`app/Services/ActivityLogService.php`)

Service class untuk mengelola pencatatan log aktivitas secara terpusat.

**Fitur:**
- `logCreate()` - Mencatat pembuatan data baru
- `logUpdate()` - Mencatat perubahan data dengan old/new values
- `logDelete()` - Mencatat penghapusan data
- `logLogin()` - Mencatat aktivitas login
- `logLogout()` - Mencatat aktivitas logout
- `deleteOldLogs()` - Menghapus log lama (retensi)
- `getStatistics()` - Mendapatkan statistik aktivitas

**Keamanan:**
- Filter otomatis untuk kolom sensitif (password, token)
- Hanya mencatat perubahan yang relevan

### 2.2 ActivityLogObserver (`app/Observers/ActivityLogObserver.php`)

Observer untuk auto-logging aktivitas CRUD pada model.

**Model yang Di-observe:**
- News (Berita)
- Announcement (Pengumuman)
- Agenda
- Gallery (Galeri)
- Banner
- Profile (Profil Sekolah)
- Teacher (Guru)
- Staff (Tenaga Kependidikan)
- Facility (Fasilitas)
- StudentData (Data Siswa)
- StudentDistribution (Persebaran Siswa)
- Setting (Pengaturan)

### 2.3 ActivityLog Model (`app/Models/ActivityLog.php`)

Model untuk tabel `activity_logs` dengan fitur:

**Scopes:**
- `action($action)` - Filter berdasarkan aksi
- `forModel($class)` - Filter berdasarkan model
- `byUser($userId)` - Filter berdasarkan user
- `dateRange($from, $to)` - Filter rentang tanggal
- `today()`, `thisWeek()`, `thisMonth()` - Filter waktu

**Accessors:**
- `action_label` - Label aksi yang readable
- `model_name` - Nama model dalam Bahasa Indonesia
- `action_color` - Warna badge untuk UI

### 2.4 CleanupActivityLogs Command (`app/Console/Commands/CleanupActivityLogs.php`)

Console command untuk membersihkan log lama.

```bash
# Penggunaan
php artisan logs:cleanup              # Default: hapus log > 6 bulan
php artisan logs:cleanup --months=12  # Hapus log > 12 bulan
php artisan logs:cleanup --dry-run    # Preview tanpa hapus
```

---

## 3. Alur Data

### 3.1 Alur Logging Create

```
User Create Data → Model::create() → Observer::created()
                                          │
                                          ▼
                          ActivityLogService::logCreate()
                                          │
                                          ▼
                              ActivityLog::create()
                                          │
                                          ▼
                              Database: activity_logs
```

### 3.2 Alur Logging Update

```
User Update Data → Model::update() → Observer::updating() [simpan old values]
                                          │
                                          ▼
                                    Observer::updated()
                                          │
                                          ▼
                          ActivityLogService::logUpdate()
                                          │
                                          ▼
                    [Filter changed columns & sensitive data]
                                          │
                                          ▼
                              ActivityLog::create()
```

### 3.3 Alur Export

```
Admin Request Export → ActivityLogController::export()
                              │
                              ▼
                    Apply Filters (date, action, model)
                              │
                              ▼
                    Generate CSV/Excel Stream
                              │
                              ▼
                    Log Export Activity
                              │
                              ▼
                    Download File
```

---

## 4. Fitur Keamanan

### 4.1 Kontrol Akses

| Fitur | Admin | User Biasa |
|-------|-------|------------|
| Lihat Log | ✅ | ❌ |
| Filter Log | ✅ | ❌ |
| Export Log | ✅ | ❌ |
| Edit Log | ❌ | ❌ |
| Hapus Manual | ❌ | ❌ |

### 4.2 Data Sensitif

Kolom yang **TIDAK** dicatat:
- `password`
- `remember_token`
- `two_factor_secret`
- `two_factor_recovery_codes`

### 4.3 Integritas Data

- Log **tidak dapat diedit** setelah dibuat
- Log **tidak dapat dihapus manual** via UI
- Penghapusan hanya via **Scheduler otomatis**
- Setiap log memiliki `ip_address` dan `user_agent`

---

## 5. Retensi Data

### 5.1 Kebijakan Retensi

| Periode | Aksi |
|---------|------|
| 0-6 bulan | Data disimpan |
| > 6 bulan | Dihapus otomatis |

### 5.2 Scheduler Configuration

```php
// routes/console.php
Schedule::command('logs:cleanup --months=6')
    ->daily()
    ->at('02:00')
    ->withoutOverlapping()
    ->onOneServer();
```

### 5.3 Justifikasi Retensi 6 Bulan

1. **Compliance**: Memenuhi standar audit trail minimal
2. **Performance**: Mencegah tabel membengkak
3. **Storage**: Menghemat ruang penyimpanan
4. **Relevance**: Data > 6 bulan jarang diperlukan

---

## 6. Panduan Penggunaan

### 6.1 Melihat Log Aktivitas

1. Login ke Admin Panel
2. Klik menu **Log Aktivitas**
3. Gunakan filter untuk mempersempit hasil:
   - Cari Deskripsi
   - Aksi (Create/Update/Delete/Login)
   - Tipe Model
   - Rentang Tanggal

### 6.2 Export Log

1. Atur filter sesuai kebutuhan
2. Klik tombol **Export CSV** atau **Export Excel**
3. File akan otomatis terdownload
4. Export akan tercatat sebagai aktivitas baru

### 6.3 Membaca Data Log

| Kolom | Keterangan |
|-------|------------|
| Waktu | Tanggal dan jam aktivitas |
| User | Admin yang melakukan |
| Aksi | Create/Update/Delete/Login |
| Tipe | Jenis data yang diubah |
| Deskripsi | Detail aktivitas |

---

## 7. Konfigurasi Scheduler

### 7.1 Setup Cron Job (Production)

Tambahkan ke crontab server:

```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

### 7.2 Mengubah Periode Retensi

Edit file `routes/console.php`:

```php
// Ubah --months=6 menjadi nilai yang diinginkan
Schedule::command('logs:cleanup --months=12')  // 12 bulan
```

### 7.3 Manual Cleanup (Development)

```bash
# Preview
php artisan logs:cleanup --dry-run

# Execute
php artisan logs:cleanup --months=6
```

---

## Lampiran: Struktur Tabel

```sql
CREATE TABLE `activity_logs` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `user_id` bigint unsigned DEFAULT NULL,
    `action` varchar(50) NOT NULL,
    `model_type` varchar(255) DEFAULT NULL,
    `model_id` bigint unsigned DEFAULT NULL,
    `description` text,
    `old_values` json DEFAULT NULL,
    `new_values` json DEFAULT NULL,
    `ip_address` varchar(45) DEFAULT NULL,
    `user_agent` text,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `activity_logs_user_id_foreign` (`user_id`),
    KEY `activity_logs_action_index` (`action`),
    KEY `activity_logs_model_type_index` (`model_type`),
    KEY `activity_logs_created_at_index` (`created_at`)
);
```

---

**Dokumen ini dibuat untuk keperluan dokumentasi teknis dan laporan akademik.**

*Portal Sekolah SRMA 25 Lamongan - 2024/2025*
