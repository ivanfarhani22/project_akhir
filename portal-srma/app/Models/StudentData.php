<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentData extends Model
{
    use HasFactory;

    protected $table = 'student_data';

    protected $fillable = [
        'academic_year',
        'class_name',
        'male_count',
        'female_count',
        'study_groups',
    ];

    public function getTotalStudentsAttribute()
    {
        return $this->male_count + $this->female_count;
    }

    public function scopeCurrentYear($query)
    {
        $currentYear = date('Y');
        $month = date('n');
        
        // Jika bulan >= 7 (Juli), tahun ajaran adalah tahun ini/tahun depan
        // Jika bulan < 7, tahun ajaran adalah tahun lalu/tahun ini
        if ($month >= 7) {
            $academicYear = $currentYear . '/' . ($currentYear + 1);
        } else {
            $academicYear = ($currentYear - 1) . '/' . $currentYear;
        }
        
        return $query->where('academic_year', $academicYear);
    }

    public static function getSummary($academicYear = null)
    {
        $query = self::query();
        
        if ($academicYear) {
            $query->where('academic_year', $academicYear);
        } else {
            $query->currentYear();
        }
        
        $data = $query->get();
        
        return [
            'male' => $data->sum('male_count'),
            'female' => $data->sum('female_count'),
            'total' => $data->sum('male_count') + $data->sum('female_count'),
            'groups' => $data->sum('study_groups'),
            'by_class' => $data,
        ];
    }
}
