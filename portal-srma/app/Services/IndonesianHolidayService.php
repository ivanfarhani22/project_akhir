<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class IndonesianHolidayService
{
    /**
     * API URL untuk mengambil data hari libur Indonesia
     */
    protected $apiUrl = 'https://api-harilibur.vercel.app/api';

    /**
     * Ambil data hari libur untuk tahun tertentu
     *
     * @param int $year
     * @return array
     */
    public function getHolidays(int $year): array
    {
        // Cache selama 1 bulan karena data hari libur jarang berubah
        return Cache::remember("indonesian_holidays_{$year}", 60 * 60 * 24 * 30, function () use ($year) {
            try {
                $response = Http::timeout(10)->get("{$this->apiUrl}?year={$year}");
                
                if ($response->successful()) {
                    $data = $response->json();
                    return $this->formatHolidays($data);
                }
            } catch (\Exception $e) {
                // Log error jika diperlukan
                \Log::warning("Failed to fetch Indonesian holidays: " . $e->getMessage());
            }
            
            // Return array kosong jika gagal
            return [];
        });
    }

    /**
     * Format data hari libur dari API
     *
     * @param array $data
     * @return array
     */
    protected function formatHolidays(array $data): array
    {
        $holidays = [];
        
        foreach ($data as $holiday) {
            if (isset($holiday['holiday_date']) && isset($holiday['holiday_name'])) {
                $date = $holiday['holiday_date'];
                $holidays[$date] = [
                    'name' => $holiday['holiday_name'],
                    'is_national_holiday' => $holiday['is_national_holiday'] ?? true,
                ];
            }
        }
        
        return $holidays;
    }

    /**
     * Cek apakah tanggal tertentu adalah hari libur
     *
     * @param Carbon $date
     * @return array|null
     */
    public function getHolidayInfo(Carbon $date): ?array
    {
        $holidays = $this->getHolidays($date->year);
        $dateString = $date->format('Y-m-d');
        
        return $holidays[$dateString] ?? null;
    }

    /**
     * Cek apakah tanggal adalah hari Minggu
     *
     * @param Carbon $date
     * @return bool
     */
    public function isSunday(Carbon $date): bool
    {
        return $date->dayOfWeek === Carbon::SUNDAY;
    }

    /**
     * Ambil semua hari libur dan hari Minggu untuk satu bulan
     *
     * @param int $month
     * @param int $year
     * @return array
     */
    public function getMonthHolidays(int $month, int $year): array
    {
        $holidays = $this->getHolidays($year);
        $monthHolidays = [];
        
        $startOfMonth = Carbon::create($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        
        // Loop through each day of the month
        for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay()) {
            $dateString = $date->format('Y-m-d');
            $day = $date->day;
            
            $holidayInfo = [
                'is_sunday' => $date->dayOfWeek === Carbon::SUNDAY,
                'is_holiday' => false,
                'holiday_name' => null,
            ];
            
            // Check if it's a national holiday
            if (isset($holidays[$dateString])) {
                $holidayInfo['is_holiday'] = true;
                $holidayInfo['holiday_name'] = $holidays[$dateString]['name'];
            }
            
            // Only add to array if it's a special day
            if ($holidayInfo['is_sunday'] || $holidayInfo['is_holiday']) {
                $monthHolidays[$day] = $holidayInfo;
            }
        }
        
        return $monthHolidays;
    }
}
