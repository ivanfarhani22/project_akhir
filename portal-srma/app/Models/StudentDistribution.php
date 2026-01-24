<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentDistribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year',
        'district',
        'student_count',
    ];

    public function scopeCurrentYear($query)
    {
        $currentYear = date('Y');
        $month = date('n');
        
        if ($month >= 7) {
            $academicYear = $currentYear . '/' . ($currentYear + 1);
        } else {
            $academicYear = ($currentYear - 1) . '/' . $currentYear;
        }
        
        return $query->where('academic_year', $academicYear);
    }

    public static function getChartData($academicYear = null)
    {
        $query = self::query();
        
        if ($academicYear) {
            $query->where('academic_year', $academicYear);
        } else {
            $query->currentYear();
        }
        
        $distributions = $query->orderBy('student_count', 'desc')->get();
        
        return [
            'labels' => $distributions->pluck('district')->toArray(),
            'data' => $distributions->pluck('student_count')->toArray(),
        ];
    }
}
