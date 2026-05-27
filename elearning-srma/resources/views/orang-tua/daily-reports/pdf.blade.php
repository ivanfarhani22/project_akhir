<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Harian</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111827; }
        .header { margin-bottom: 14px; }
        .title { font-size: 16px; font-weight: 700; margin: 0 0 4px; }
        .meta { font-size: 12px; color: #374151; margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #E5E7EB; padding: 8px; vertical-align: top; }
        th { background: #F9FAFB; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: .04em; color: #374151; }
        .muted { color: #6B7280; }
    </style>
</head>
<body>
    <div class="header">
        <p class="title">Laporan Harian</p>
        <p class="meta">Nama: <strong>{{ $student?->name ?? '-' }}</strong></p>
        <p class="meta">Tanggal: <strong>{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</strong></p>
    </div>

    @if(($rows ?? collect())->count() === 0)
        <p class="muted">Belum ada data untuk tanggal ini.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th style="width: 6%">No</th>
                    <th style="width: 29%">Kegiatan</th>
                    <th style="width: 15%">Presensi</th>
                    <th style="width: 10%">Nilai</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $r)
                    <tr>
                        <td>{{ $r['no'] }}</td>
                        <td>{{ $r['kegiatan'] }}</td>
                        <td>{{ $r['presensi'] ?? '-' }}</td>
                        <td>{{ $r['nilai'] ?? '-' }}</td>
                        <td>{{ $r['catatan'] ?: '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
