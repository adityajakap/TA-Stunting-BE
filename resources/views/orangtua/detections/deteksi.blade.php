{{-- resources/views/namafile.blade.php --}}
@extends('layouts.app')

@section('title', 'Deteksi Stunting')

@section('content')

<style>
    .main-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 1280px;
        margin: 2rem auto 1rem;
        padding: 0 1rem;
    }

    .main-title {
        color: #005f77;
        font-size: 2rem;
        margin: 0;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-icon-mini {
        background-color: #005f77;
        color: white;
        padding: 0.3rem 0.6rem;
        border: none;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        cursor: pointer;
    }

    .btn-icon-mini:hover { background-color: #014f66; }

    .card-wrapper { max-width: 1280px; margin: 0 auto; padding-bottom: 2rem; }

    .card { background-color: #ffffff; border-radius: 1rem; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); display: flex; flex-direction: column; }
    .card-body { padding: 1rem; display: flex; flex-direction: column; justify-content: space-between; flex-grow: 1; }

    .table thead th { background: #f8fafc; }

    .empty-message { text-align: center; font-size: 1rem; color: #6b7280; margin: 3rem 0; }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e5a6e;
        margin-bottom: 1.5rem;
        margin-top: 2rem;
    }

    .table {
        /* Use separate borders so we can apply rounded corners to the table cells */
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
        margin-bottom: 0;
    }

    .table thead th {
        background-color: #f9fafb;
        color: #1f2937;
        font-weight: 600;
        padding: 0.75rem;
        border: 1px solid #e5e7eb;
        text-align: left;
    }

    .table tbody tr {
        border-bottom: 1px solid #e5e7eb;
    }

    .table tbody tr:hover {
        background-color: #f9fafb;
    }

    .table tbody td {
        padding: 0.75rem;
        border: 1px solid #e5e7eb;
        color: #374151;
    }

    /* Rounded corners for the table inside the card */
    .table thead th:first-child { border-top-left-radius: 0.75rem; }
    .table thead th:last-child { border-top-right-radius: 0.75rem; }
    .table tbody tr:last-child td:first-child { border-bottom-left-radius: 0.75rem; }
    .table tbody tr:last-child td:last-child { border-bottom-right-radius: 0.75rem; }
</style>

<div class="main-header">
    <div style="display:flex; align-items:center; gap:0.5rem;">
        <x-back-button />
        <h1 class="main-title">Form Deteksi Stunting</h1>
    </div>
    <div class="action-buttons"></div>
</div>

<div class="card-wrapper">
    <div class="card mb-4">
        <div class="card-body">
            {{-- Notifikasi error --}}
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- Form Deteksi --}}
            <form action="{{ route('orangtua.detections.store') }}" method="POST">
        @csrf
        <input type="hidden" name="nama" value="{{ auth()->user()->nama_anak }}">

        <div class="mb-3">
            <label>Umur (bulan)</label>
            <input type="number" name="umur" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Jenis Kelamin</label>
            <select name="jenis_kelamin" class="form-control" required>
                <option value="">-- Pilih --</option>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Berat Badan (kg)</label>
            <input type="number" step="0.1" name="berat_badan" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Tinggi Badan (cm)</label>
            <input type="number" step="0.1" name="tinggi_badan" class="form-control" required>
        </div>

                <div class="d-grid gap-2 d-md-flex justify-content-end">
                    <x-button>Deteksi</x-button>
                </div>
            </form>
        </div>
    </div>

    {{-- Hasil Deteksi Terbaru --}}
    @isset($hasil)
    <div class="mb-3">
        <h3 class="mb-3 fw-bold">Hasil Deteksi Terbaru</h3>
        <div class="card p-3">
            <p><strong>Nama:</strong> {{ $hasil->nama }}</p>
            <p><strong>Umur:</strong> {{ $hasil->umur }} bulan</p>
            <p><strong>Jenis Kelamin:</strong> {{ $hasil->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
            <p><strong>Berat Badan:</strong> {{ $hasil->berat_badan }} kg</p>
            <p><strong>Tinggi Badan:</strong> {{ $hasil->tinggi_badan }} cm</p>
            <p><strong>Z-Score:</strong> {{ $hasil->z_score }}</p>
            <p><strong>Status:</strong>
                <span class="badge {{ $hasil->status == 'Stunting' ? 'bg-danger' : 'bg-success' }}">
                    {{ $hasil->status == 'Tinggi' ? 'Normal' : $hasil->status }}
                </span>
            </p>
        </div>
    </div>
    @endisset

    {{-- Riwayat Deteksi --}}
    <h2 class="section-title">Riwayat Deteksi</h2>
    <div class="card">
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Umur (bulan)</th>
                        <th>Jenis Kelamin</th>
                        <th>Berat (kg)</th>
                        <th>Tinggi (cm)</th>
                        <th>Z-Score</th>
                        <th>Status</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($semua as $d)
                    <tr>
                        <td>{{ $d->nama }}</td>
                        <td>{{ $d->umur }}</td>
                        <td>{{ $d->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                        <td>{{ $d->berat_badan }}</td>
                        <td>{{ $d->tinggi_badan }}</td>
                        <td>{{ $d->z_score }}</td>
                        <td>
                            <span class="badge {{ $d->status == 'Stunting' ? 'bg-danger' : 'bg-success' }}">
                                {{ $d->status == 'Tinggi' ? 'Normal' : $d->status }}
                            </span>
                        </td>
                        <td>{{ $d->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Belum ada data deteksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
