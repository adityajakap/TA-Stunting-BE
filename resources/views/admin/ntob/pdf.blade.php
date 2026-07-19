<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan NTOB</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.5;
            margin: 15px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #005f77;
            padding-bottom: 12px;
        }
        .header h1 {
            color: #005f77;
            margin: 0 0 5px 0;
            font-size: 22px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .stats-wrapper {
            width: 100%;
            margin-bottom: 20px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            overflow: hidden;
            background-color: #fff;
        }
        .stats-header {
            background-color: #005f77;
            color: #fff;
            padding: 8px 15px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #e5e7eb;
            padding: 8px 6px;
            font-size: 10px;
            text-align: center;
        }
        table.data-table th {
            background-color: #005f77;
            color: #ffffff;
            font-weight: 600;
        }
        table.data-table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan NTOB Posyandu</h1>
        <p>Bulan: {{ $bulanNama }} {{ $year }}</p>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d M Y H:i') }}</p>
    </div>

    <div class="stats-wrapper">
        <div class="stats-header">RINGKASAN LAPORAN</div>
        <div style="padding: 15px;">
            <div style="color: #8b5cf6; font-weight: bold; font-size: 10px; margin-bottom: 8px; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px;">DATA NTOB</div>
            <table style="width: 100%; font-size: 10px; border: none; margin-bottom: 10px;">
                <tr>
                    <td style="padding: 4px 0; border: none;">Naik (N)</td>
                    <td style="text-align: right; font-weight: bold; color: #15803d; border: none;">{{ $nValue }}</td>
                </tr>
                <tr>
                    <td style="padding: 4px 0; border: none;">Turun / Tetap (T)</td>
                    <td style="text-align: right; font-weight: bold; color: #b91c1c; border: none;">{{ $tValue }}</td>
                </tr>
                <tr>
                    <td style="padding: 4px 0; border: none;">Tidak Ditimbang (O)</td>
                    <td style="text-align: right; font-weight: bold; color: #f59e0b; border: none;">{{ $oValue }}</td>
                </tr>
                <tr>
                    <td style="padding: 4px 0; border: none;">Baru Pertama (B)</td>
                    <td style="text-align: right; font-weight: bold; color: #0369a1; border: none;">{{ $bValue }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="stats-wrapper">
        <div class="stats-header">Data Anak Ditimbang</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Umur (bln)</th>
                    <th>Jenis kelamin</th>
                    <th>Berat (kg)</th>
                    <th>Z-Score</th>
                    <th>Status</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($monthDetections as $d)
                <tr>
                    <td>{{ $d['child']['nama_lengkap_anak'] ?? '-' }}</td>
                    <td>{{ $d['umur'] ?? '-' }}</td>
                    <td>{{ ($d['jenis_kelamin'] ?? '') == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    <td>{{ $d['berat_badan'] ?? '-' }}</td>
                    <td>{{ $d['z_score'] ?? '-' }}</td>
                    <td>{{ ($d['status'] ?? '') == 'Tinggi' ? 'Normal' : ($d['status'] ?? '-') }}</td>
                    <td>{{ \Carbon\Carbon::parse($d['created_at'])->format('d/m/Y') }}</td>
                </tr>
                @endforeach
                @if(count($monthDetections) == 0)
                <tr>
                    <td colspan="7">Belum ada data anak.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

</body>
</html>
