<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Perkembangan Anak</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 0; padding: 0; }
        .header p { margin: 5px 0 0 0; color: #555; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 4px; }
        .info-table .label { font-weight: bold; width: 120px; }
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .data-table th, .data-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .data-table th { background-color: #f4f4f4; }
        .category-header { background-color: #e2e8f0; font-weight: bold; }
        .text-center { text-align: center; }
        .footer { position: fixed; bottom: -20px; left: 0px; right: 0px; height: 40px; font-size: 10px; text-align: center; color: #777; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN EVALUASI PERKEMBANGAN ANAK</h2>
        <p>Sistem Informasi Pencegahan Stunting Terpadu</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Nama Anak</td>
            <td>: {{ $child->nama_lengkap_anak }}</td>
            <td class="label">Jenis Kelamin</td>
            <td>: {{ $child->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Lahir</td>
            <td>: {{ \Carbon\Carbon::parse($child->tanggal_lahir)->translatedFormat('d F Y') }}</td>
            <td class="label">Tanggal Cetak</td>
            <td>: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="45%">Tahapan (Milestone)</th>
                <th width="20%">Target Rentang Umur</th>
                <th width="15%">Tanggal Capai</th>
                <th width="15%">Status Evaluasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groupedData as $kategori => $items)
                <tr class="category-header">
                    <td colspan="5">{{ strtoupper($kategori) }}</td>
                </tr>
                @foreach($items as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->tahapan->tugas_perkembangan ?? 'Tugas Perkembangan' }}</td>
                        <td>{{ $item->tahapan->umur_minimal_bulan }} - {{ $item->tahapan->umur_maksimal_bulan }} bulan</td>
                        <td class="text-center">
                            @if($item->achieved_data)
                                {{ \Carbon\Carbon::parse($item->achieved_data->tanggal_pencapaian)->format('d/m/Y') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            {{ $item->status_detail['label'] ?? '-' }}
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak otomatis oleh Sistem pada {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}
    </div>
</body>
</html>
