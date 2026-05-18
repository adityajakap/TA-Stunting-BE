@extends('layouts.app')

@section('title', 'Tambah Deteksi Stunting (Admin)')

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
        border: 1px solid #e5e7eb;
    }

    .card-body {
        padding: 2rem;
    }

    .form-label {
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
        display: block;
        font-size: 0.95rem;
    }

    .form-control {
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        width: 100%;
        background-color: #fff;
        transition: border-color 0.3s ease;
    }

    .form-control:focus {
        border-color: #005f77;
        outline: none;
        box-shadow: 0 0 0 3px rgba(0, 95, 119, 0.1);
    }

    .mb-3 {
        margin-bottom: 1.5rem;
    }

    .button-group {
        display: flex;
        gap: 0.75rem;
        margin-top: 2rem;
    }

    .btn {
        padding: 0.6rem 1.2rem;
        border: none;
        border-radius: 0.5rem;
        font-weight: 600;
        cursor: pointer;
        font-size: 1rem;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-primary {
        background-color: #005f77;
        color: white;
    }

    .btn-primary:hover {
        background-color: #014f66;
    }

    .btn-secondary {
        background-color: #6b7280;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #4b5563;
    }

    .alert {
        padding: 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .alert-danger {
        background-color: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
</style>

<div class="main-header">
    <div style="display:flex; align-items:center; gap:0.5rem;">
        <x-back-button :url="route('admin.detections.index')" />
        <h1 class="main-title">Form Deteksi</h1>
    </div>
</div>

<div class="card-wrapper">
    <div class="card">
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('admin.detections.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="user_id" class="form-label">Pilih Anak (Orangtua)</label>
                    <select name="user_id" id="user_id" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->nama_anak }} ({{ $u->nik_anak }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="umur" class="form-label">Umur (Bulan)</label>
                    <input type="number" name="umur" id="umur" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="berat_badan" class="form-label">Berat Badan (kg)</label>
                    <input type="number" step="0.1" name="berat_badan" id="berat_badan" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="tinggi_badan" class="form-label">Tinggi Badan (cm)</label>
                    <input type="number" step="0.1" name="tinggi_badan" id="tinggi_badan" class="form-control" required>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-primary">Tambah Deteksi</button>
                    <button type="button" class="btn btn-secondary" onclick="window.history.back()">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
