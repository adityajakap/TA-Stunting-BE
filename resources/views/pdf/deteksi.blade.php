<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Deteksi Stunting (KMS)</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 0; padding: 0; }
        .header p { margin: 5px 0 0 0; color: #555; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 4px; }
        .info-table .label { font-weight: bold; width: 120px; }
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .data-table th, .data-table td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        .data-table th { background-color: #f4f4f4; font-weight: bold; }
        .status-normal { color: green; font-weight: bold; }
        .status-stunting { color: red; font-weight: bold; }
        .footer { position: fixed; bottom: -20px; left: 0px; right: 0px; height: 40px; font-size: 10px; text-align: center; color: #777; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN DETEKSI STUNTING (KMS)</h2>
        <p>Sistem Informasi Pencegahan Stunting Terpadu</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Nama Anak</td>
            <td colspan="3">: {{ $child->nama_lengkap_anak }}</td>
        </tr>
        <tr>
            <td class="label">Jenis Kelamin</td>
            <td>: {{ $child->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}</td>
            <td class="label">Tanggal Cetak</td>
            <td>: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Lahir</td>
            <td>: {{ \Carbon\Carbon::parse($child->tanggal_lahir)->translatedFormat('d F Y') }}</td>
            <td class="label">Dicetak Oleh</td>
            <td>: {{ auth()->user()->name ?? 'Sistem' }}</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">Bulan Ke-</th>
                <th width="20%">Tanggal Ukur</th>
                <th width="15%">Berat (kg)</th>
                <th width="15%">Tinggi (cm)</th>
                <th width="15%">Z-Score (WHO)</th>
                <th width="15%">Status Gizi</th>
                <th width="15%">Petugas</th>
            </tr>
        </thead>
        <tbody>
            @forelse($detections as $det)
                <tr>
                    <td>{{ $det->umur }}</td>
                    <td>{{ \Carbon\Carbon::parse($det->created_at)->format('d/m/Y') }}</td>
                    <td>{{ $det->berat_badan }}</td>
                    <td>{{ $det->tinggi_badan }}</td>
                    <td>{{ number_format($det->z_score, 2) }}</td>
                    <td class="{{ strtolower($det->status) == 'normal' ? 'status-normal' : 'status-stunting' }}">
                        {{ $det->status }}
                    </td>
                    <td>{{ ucfirst($det->added_by ?? '-') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Belum ada riwayat pengukuran stunting untuk anak ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak otomatis oleh Sistem pada {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}
    </div>
</body>
</html>
