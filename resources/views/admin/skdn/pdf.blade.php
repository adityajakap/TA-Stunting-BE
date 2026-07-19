<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan SKDN</title>
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
        .chart-box {
            padding: 15px;
            text-align: center;
            display: inline-block;
            width: 100%;
        }
        .bar-container {
            display: inline-block;
            vertical-align: bottom;
            width: 40px;
            margin: 0 10px;
            text-align: center;
        }
        .bar {
            width: 100%;
            border-top-left-radius: 4px;
            border-top-right-radius: 4px;
        }
        .bar-label {
            margin-top: 5px;
            font-weight: bold;
        }
        .bar-val {
            margin-bottom: 5px;
            font-size: 10px;
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
        <h1>Laporan SKDN Posyandu</h1>
        <p>Bulan: {{ $bulanNama }} {{ $year }}</p>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d M Y H:i') }}</p>
    </div>

    @php
        $maxVal = max($sValue, $kValue, $dValue, $nValue);
        if ($maxVal == 0) $maxVal = 1; // avoid division by zero
    @endphp

    <div class="stats-wrapper">
        <!-- Ringkasan Laporan SKDN -->
        <div class="stats-header">RINGKASAN LAPORAN</div>
        <div style="padding: 15px;">
            <div style="color: #005f77; font-weight: bold; font-size: 10px; margin-bottom: 8px; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px;">DATA SKDN</div>
            <table style="width: 100%; font-size: 10px; border: none; margin-bottom: 10px;">
                <tr>
                    <td style="padding: 4px 0; border: none;">Balita di Wilayah (S)</td>
                    <td style="text-align: right; font-weight: bold; color: #111827; border: none;">{{ $sValue }}</td>
                </tr>
                <tr>
                    <td style="padding: 4px 0; border: none;">Balita Terdaftar (K)</td>
                    <td style="text-align: right; font-weight: bold; color: #111827; border: none;">{{ $kValue }}</td>
                </tr>
                <tr>
                    <td style="padding: 4px 0; border: none;">Datang Ditimbang (D)</td>
                    <td style="text-align: right; font-weight: bold; color: #111827; border: none;">{{ $dValue }}</td>
                </tr>
                <tr>
                    <td style="padding: 4px 0; border: none;">Berat Badan Naik (N)</td>
                    <td style="text-align: right; font-weight: bold; color: #111827; border: none;">{{ $nValue }}</td>
                </tr>
            </table>
        </div>

        <div class="stats-header">Grafik Data SKDN</div>
        <div class="chart-box">
            <!-- S -->
            <div class="bar-container">
                <div class="bar-val">{{ $sValue }}</div>
                <div class="bar" style="height: {{ ($sValue / $maxVal) * 150 }}px; background-color: #ef4444;"></div>
                <div class="bar-label">S</div>
            </div>
            <!-- K -->
            <div class="bar-container">
                <div class="bar-val">{{ $kValue }}</div>
                <div class="bar" style="height: {{ ($kValue / $maxVal) * 150 }}px; background-color: #eab308;"></div>
                <div class="bar-label">K</div>
            </div>
            <!-- D -->
            <div class="bar-container">
                <div class="bar-val">{{ $dValue }}</div>
                <div class="bar" style="height: {{ ($dValue / $maxVal) * 150 }}px; background-color: #3b82f6;"></div>
                <div class="bar-label">D</div>
            </div>
            <!-- N -->
            <div class="bar-container">
                <div class="bar-val">{{ $nValue }}</div>
                <div class="bar" style="height: {{ ($nValue / $maxVal) * 150 }}px; background-color: #22c55e;"></div>
                <div class="bar-label">N</div>
            </div>
        </div>
        
        <div style="background-color: #f9fafb; border-top: 1px solid #e5e7eb; padding: 12px 15px;">
            <table style="width: 100%; font-size: 10px; border-collapse: collapse; border: none;">
                <tr>
                    <td style="width: 33.33%; text-align: center; border: none; border-right: 1px dashed #cbd5e1;">
                        <div style="font-size: 15px; font-weight: bold; color: #111827;">{{ ($sValue > 0) ? round(($kValue / $sValue) * 100, 1) : 0 }}%</div>
                        <div style="color: #6b7280;">K/S = Cakupan balita yang memiliki KMS</div>
                    </td>
                    <td style="width: 33.33%; text-align: center; border: none; border-right: 1px dashed #cbd5e1;">
                        <div style="font-size: 15px; font-weight: bold; color: #111827;">{{ ($sValue > 0) ? round(($dValue / $sValue) * 100, 1) : 0 }}%</div>
                        <div style="color: #6b7280;">D/S = Cakupan balita yang ditimbang</div>
                    </td>
                    <td style="width: 33.33%; text-align: center; border: none;">
                        <div style="font-size: 15px; font-weight: bold; color: #22c55e;">{{ ($dValue > 0) ? round(($nValue / $dValue) * 100, 1) : 0 }}%</div>
                        <div style="color: #6b7280;">N/D = Cakupan balita yang timbangannya naik</div>
                    </td>
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
