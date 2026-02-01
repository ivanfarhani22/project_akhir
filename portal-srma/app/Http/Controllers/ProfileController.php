<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Setting;
use App\Models\Teacher;
use App\Models\Staff;
use App\Models\Facility;
use App\Models\StudentData;
use App\Models\StudentDistribution;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        return redirect()->route('profil.dasar-hukum');
    }

    public function tentang()
    {
        // Redirect ke dasar hukum karena halaman tentang sudah dihapus
        return redirect()->route('profil.dasar-hukum');
    }

    public function dasarHukum()
    {
        $dasarHukum = Profile::getValue('dasar_hukum', '');
        
        return view('public.profile.dasar-hukum', compact('dasarHukum'));
    }

    public function visiMisi()
    {
        $visi = Profile::getValue('visi', '');
        $misi = Profile::getValue('misi', '');
        
        return view('public.profile.visi-misi', compact('visi', 'misi'));
    }

    public function saranaPrasarana()
    {
        $facilities = Facility::active()->ordered()->get();
        
        return view('public.profile.sarana-prasarana', compact('facilities'));
    }

    public function struktur()
    {
        $struktur = Profile::getValue('struktur_organisasi', '');
        $strukturImage = Profile::getValue('struktur_organisasi_image', '');
        
        return view('public.profile.struktur', compact('struktur', 'strukturImage'));
    }

    public function guru()
    {
        $teachers = Teacher::active()->ordered()->get();
        
        return view('public.profile.guru', compact('teachers'));
    }

    public function tenagaKependidikan()
    {
        $staff = Staff::active()->ordered()->get();
        
        return view('public.profile.tenaga-kependidikan', compact('staff'));
    }

    public function dataSiswa(Request $request)
    {
        // Tahun ajaran dimulai dari Juli
        // Jika bulan >= 7 (Juli-Desember): tahun ini / tahun depan
        // Jika bulan < 7 (Januari-Juni): tahun lalu / tahun ini
        $year = date('Y');
        $month = date('n');
        
        if ($month >= 7) {
            $currentYear = $year . '/' . ($year + 1);
        } else {
            $currentYear = ($year - 1) . '/' . $year;
        }
        
        // Ambil semua tahun ajaran yang tersedia
        $academicYears = StudentData::select('academic_year')
            ->distinct()
            ->orderBy('academic_year', 'desc')
            ->pluck('academic_year');
        
        // Filter berdasarkan tahun ajaran (default: tahun aktif)
        $selectedYear = $request->get('academic_year', $currentYear);
        
        // Jika tahun yang dipilih tidak ada di database, gunakan tahun aktif
        if (!$academicYears->contains($selectedYear)) {
            $selectedYear = $currentYear;
        }
        
        $studentData = StudentData::where('academic_year', $selectedYear)->orderBy('class_name')->get();
        $summary = StudentData::getSummary($selectedYear);
        
        return view('public.profile.data-siswa', compact('studentData', 'summary', 'currentYear', 'academicYears', 'selectedYear'));
    }

    public function persebaranSiswa(Request $request)
    {
        // Tahun ajaran dimulai dari Juli
        $year = date('Y');
        $month = date('n');
        
        if ($month >= 7) {
            $currentYear = $year . '/' . ($year + 1);
        } else {
            $currentYear = ($year - 1) . '/' . $year;
        }
        
        // Ambil semua tahun ajaran yang tersedia
        $academicYears = StudentDistribution::select('academic_year')
            ->distinct()
            ->orderBy('academic_year', 'desc')
            ->pluck('academic_year');
        
        // Filter berdasarkan tahun ajaran (default: tahun aktif)
        $selectedYear = $request->get('academic_year', $currentYear);
        
        // Jika tahun yang dipilih tidak ada di database, gunakan tahun aktif
        if (!$academicYears->contains($selectedYear)) {
            $selectedYear = $currentYear;
        }
        
        $distributions = StudentDistribution::where('academic_year', $selectedYear)
            ->orderBy('student_count', 'desc')
            ->get();
        
        return view('public.profile.persebaran-siswa', compact('distributions', 'currentYear', 'academicYears', 'selectedYear'));
    }

    public function dataSekolah()
    {
        $data = [
            'siswa_laki' => Setting::getValue('total_siswa_laki', 40),
            'siswa_perempuan' => Setting::getValue('total_siswa_perempuan', 35),
            'guru' => Setting::getValue('total_guru', 16),
            'kepala_sekolah' => Setting::getValue('total_kepala_sekolah', 1),
            'wali_asrama' => Setting::getValue('total_wali_asrama', 3),
            'wali_asuh' => Setting::getValue('total_wali_asuh', 8),
            'keamanan' => Setting::getValue('total_keamanan', 3),
            'kebersihan' => Setting::getValue('total_kebersihan', 4),
            'juru_masak' => Setting::getValue('total_juru_masak', 5),
            'operator' => Setting::getValue('total_operator', 1),
            'bendahara' => Setting::getValue('total_bendahara', 1),
            'tu' => Setting::getValue('total_tu', 1),
        ];
        
        return view('public.profile.data-sekolah', compact('data'));
    }
}
