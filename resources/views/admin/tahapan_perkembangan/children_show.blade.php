@extends('layouts.app')

@section('title', 'Perkembangan - ' . $user->nama_anak)

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

    .card-wrapper {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 1rem 2rem;
    }

    .card {
        background-color: #ffffff;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .card-body {
        padding: 0;
    }

    .table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 0;
        background-color: white;
    }

    .table thead th {
        background-color: #f9fafb;
        padding: 0.75rem 1rem;
        font-weight: 600;
        color: #1f2937;
        text-align: left;
        border: 1px solid #e5e7eb;
        font-size: 0.95rem;
    }

    .table tbody tr {
        border-bottom: 1px solid #e5e7eb;
    }

    .table tbody tr:hover {
        background-color: #f9fafb;
    }

    .table tbody td {
        padding: 0.75rem 1rem;
        color: #374151;
        border: 1px solid #e5e7eb;
        font-size: 0.95rem;
    }

    /* Rounded corners for table inside card */
    .table thead th:first-child {
        border-top-left-radius: 0.75rem;
    }

    .table thead th:last-child {
        border-top-right-radius: 0.75rem;
    }

    .table tbody tr:last-child td:first-child {
        border-bottom-left-radius: 0.75rem;
    }

    .table tbody tr:last-child td:last-child {
        border-bottom-right-radius: 0.75rem;
    }

    .text-center {
        text-align: center;
    }

    .badge {
        display: inline-block;
        padding: 0.4rem 0.8rem;
        border-radius: 0.375rem;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: capitalize;
    }

    .bg-success {
        background-color: #10b981;
        color: white;
    }

    .bg-danger {
        background-color: #ef4444;
        color: white;
    }

    .btn-primary-custom {
        background-color: #005f77;
        border: none;
        color: #fff;
        padding: 0.5rem 0.9rem;
        border-radius: 0.375rem;
        text-decoration: none;
        font-size: 0.95rem;
    }

    .btn-primary-custom:hover {
        background-color: #014f66;
    }
</style>

<div class="card-wrapper">
    {{-- Header Judul dan Tombol --}}
    <div class="main-header" style="margin-top: 0; margin-bottom: 1.5rem;">
        <div style="display:flex; align-items:center; gap:0.5rem;">
            <x-back-button :url="route('admin.perkembangan.children.index')" />
            <h1 class="main-title">Perkembangan: {{ $user->nama_anak }}</h1>
        </div>

        <div class="action-buttons">
            <a href="{{ route('admin.perkembangan.children.create', $user->id) }}" class="btn-primary-custom">Tambah Pencapaian</a>
        </div>
    </div>

    {{-- Tabel dalam Card --}}
    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tahapan</th>
                        <th>Tanggal Pencapaian</th>
                        <th>Status</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ optional($d->tahapanPerkembangan)->nama_tahapan ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($d->tanggal_pencapaian)->format('d M Y') }}</td>
                            <td>
                                <span class="badge {{ $d->status == 'tercapai' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $d->status == 'tercapai' ? 'Tercapai' : 'Belum Tercapai' }}
                                </span>
                            </td>
                            <td>{{ $d->catatan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada pencapaian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
