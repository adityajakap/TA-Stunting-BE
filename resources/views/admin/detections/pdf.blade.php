<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Deteksi Stunting</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #000;
            line-height: 1.3;
            margin: 20px;
        }

        .container {
            max-width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 12px;
            border-bottom: 2px solid #000;
        }

        .header h1 {
            font-size: 15px;
            margin-bottom: 6px;
            font-weight: bold;
            letter-spacing: 0.3px;
        }

        .header p {
            font-size: 9px;
            margin: 1px 0;
        }

        .stats {
            margin: 15px 0;
            font-size: 9px;
            padding: 0 0 12px 0;
            border-bottom: 1px solid #000;
        }

        .stats strong {
            display: block;
            margin-bottom: 6px;
            font-size: 9px;
        }

        .stats-row {
            display: flex;
            gap: 50px;
        }

        .stat-item {
            flex: 1;
        }

        .stat-value {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .stat-label {
            font-size: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            table-layout: fixed;
        }

        thead {
            background-color: #fff;
        }

        thead th {
            padding: 8px 3px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #000;
            font-size: 9px;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        tbody td {
            padding: 7px 3px;
            border: 1px solid #000;
            font-size: 9px;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        tbody tr {
            height: auto;
        }

        .num-col {
            text-align: center;
        }

        .text-col {
            text-align: left;
        }

        .number-col {
            text-align: center;
        }

        .footer {
            margin-top: 20px;
            padding-top: 12px;
            border-top: 2px solid #000;
            font-size: 8px;
            text-align: center;
        }

        .footer p {
            margin: 3px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>LAPORAN DATA DETEKSI STUNTING</h1>
            <p>Tanggal Laporan: {{ now()->format('d F Y') }}</p>
            <p>Waktu: {{ now()->format('H:i') }} WIB</p>
        </div>

        <div class="stats">
            <strong>STATISTIK DATA DETEKSI:</strong>
            <div class="stats-row">
                <div class="stat-item">
                    <div class="stat-value">{{ $semua->count() }}</div>
                    <div class="stat-label">Total Data</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $semua->where('status', 'Stunting')->count() }}</div>
                    <div class="stat-label">Stunting</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $semua->where('status', 'Normal')->count() }}</div>
                    <div class="stat-label">Normal</div>
                </div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 14%;">Nama Anak</th>
                    <th style="width: 8%;">Umur (bln)</th>
                    <th style="width: 6%;">JK</th>
                    <th style="width: 8%;">BB (kg)</th>
                    <th style="width: 8%;">TB (cm)</th>
                    <th style="width: 8%;">Z-Score</th>
                    <th style="width: 12%;">Status</th>
                    <th style="width: 11%;">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($semua as $index => $d)
                    <tr>
                        <td class="num-col">{{ $index + 1 }}</td>
                        <td class="text-col">{{ $d->user->nama_anak ?? '-' }}</td>
                        <td class="number-col">{{ $d->umur }}</td>
                        <td class="number-col">{{ $d->jenis_kelamin }}</td>
                        <td class="number-col">{{ $d->berat_badan }}</td>
                        <td class="number-col">{{ $d->tinggi_badan }}</td>
                        <td class="number-col">{{ $d->z_score }}</td>
                        <td class="number-col">
                            @if ($d->status == 'Stunting')
                                STUNTING
                            @else
                                NORMAL
                            @endif
                        </td>
                        <td class="number-col">{{ $d->created_at->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="number-col" style="padding: 12px 0; text-align: center;">Belum ada data deteksi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            <p>Laporan digenerate dari Sistem Monitoring Stunting</p>
            <p>{{ now()->format('d F Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>
