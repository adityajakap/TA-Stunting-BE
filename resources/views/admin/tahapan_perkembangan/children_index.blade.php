@extends('layouts.app')

@section('title', 'Daftar Anak - Perkembangan')

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

    .search-form {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .form-control {
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
        font-size: 0.95rem;
        min-width: 300px;
    }
</style>

<div class="card-wrapper">
    {{-- Header Judul dan Search --}}
    <div class="main-header" style="margin-top: 0; margin-bottom: 1.5rem;">
        <div style="display:flex; align-items:center; gap:0.5rem;">
            <x-back-button />
            <h1 class="main-title">Daftar Tahapan Anak</h1>
        </div>
        <div class="action-buttons">
            <form method="GET" class="search-form">
                <input type="text" name="search" class="form-control" placeholder="Cari nama anak..." value="{{ request('search') }}">
                <button class="btn-primary-custom" type="submit">Cari</button>
            </form>
        </div>
    </div>

    {{-- Tabel dalam Card --}}
    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Anak</th>
                        <th>NIK</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $u->nama_anak }}</td>
                            <td>{{ $u->nik_anak }}</td>
                            <td>
                                <a href="{{ route('admin.perkembangan.children.show', $u->id) }}" class="btn-primary-custom">Lihat Perkembangan</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data anak.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
