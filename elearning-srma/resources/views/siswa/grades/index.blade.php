@extends('layouts.siswa')

@section('title', 'Nilai Saya')
@section('icon', 'fas fa-star')

@section('content')
    <div style="margin-bottom: 30px;">
        <h1 class="page-title">
            <i class="fas fa-star"></i>
            Nilai Saya
        </h1>
        <p class="page-description">Lihat nilai dan feedback dari guru</p>
    </div>

    @if($grades->count() > 0)
        <div class="card">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Kelas</th>
                            <th>Tugas/Ujian</th>
                            <th>Nilai</th>
                            <th>Tanggal Penilaian</th>
                            <th>Feedback</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grades as $grade)
                            @php
                                $assignment = $grade->assignment;
                                $score = $grade->score;
                                $scoreColor = $score >= 80 ? '#28a745' : ($score >= 70 ? '#ffc107' : '#dc3545');
                            @endphp
                            <tr>
                                <td style="font-weight: 600;">{{ $assignment->eClass->name }}</td>
                                <td>{{ $assignment->title }}</td>
                                <td>
                                    <span style="background: {{ $scoreColor }}; color: white; padding: 4px 12px; border-radius: 4px; font-size: 14px; font-weight: 600;">
                                        {{ $score }}
                                    </span>
                                </td>
                                <td>{{ $grade->graded_at ? $grade->graded_at->format('d M Y') : '-' }}</td>
                                <td style="color: #666; font-size: 13px;">
                                    {{ $grade->feedback ? substr($grade->feedback, 0, 50) . '...' : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div style="margin-top: 30px; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <div class="card">
                <div class="card-body" style="text-align: center;">
                    <p style="color: #999; font-size: 14px; margin-bottom: 10px;">Nilai Rata-rata</p>
                    @php
                        $average = $grades->avg('score');
                    @endphp
                    <p style="font-size: 36px; font-weight: 700; color: var(--primary);">
                        {{ number_format($average, 1) }}
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="card-body" style="text-align: center;">
                    <p style="color: #999; font-size: 14px; margin-bottom: 10px;">Total Penilaian</p>
                    <p style="font-size: 36px; font-weight: 700; color: var(--primary);">
                        {{ $grades->count() }}
                    </p>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body" style="text-align: center; padding: 60px 20px;">
                <i class="fas fa-inbox" style="font-size: 64px; color: #ddd; margin-bottom: 20px; display: block;"></i>
                <p style="color: #999; font-size: 16px;">Anda belum memiliki nilai</p>
            </div>
        </div>
    @endif
@endsection
