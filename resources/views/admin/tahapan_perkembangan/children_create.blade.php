@extends('layouts.app')

@section('title', 'Tambah Pencapaian - ' . $user->nama_anak)

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
    }

    .card-body {
        padding: 2rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        flex-grow: 1;
    }

    .form-label {
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control {
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        width: 100%;
        background-color: #fff;
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
        justify-content: flex-start;
    }

    .btn {
        padding: 0.6rem 1.2rem;
        border: none;
        border-radius: 0.5rem;
        font-weight: 600;
        cursor: pointer;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background-color: #005f77;
        color: white;
    }

    .btn-primary:hover {
        background-color: #014f66;
    }

    .alert {
        background-color: #fee2e2;
        color: #991b1b;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid #ef4444;
    }

    .alert ul {
        margin: 0;
        padding-left: 1.5rem;
    }

    .alert li {
        margin: 0.25rem 0;
    }
</style>

<div class="card-wrapper">
    {{-- Header --}}
    <div class="main-header" style="margin-top: 0; margin-bottom: 1.5rem;">
        <div style="display:flex; align-items:center; gap:0.5rem;">
            <x-back-button :url="route('admin.perkembangan.children.show', $user->id)" />
            <h1 class="main-title">Tambah Pencapaian: {{ $user->nama_anak }}</h1>
        </div>
    </div>

    {{-- Form dalam Card --}}
    <div class="card">
        <div class="card-body">
            @if($errors->any())
                <div class="alert">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.perkembangan.children.store', $user->id) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="tahapan_perkembangan_id" class="form-label">Pilih Tahapan</label>
                    <select name="tahapan_perkembangan_id" id="tahapan_perkembangan_id" class="form-control" required>
                        <option value="">-- Pilih Tahapan --</option>
                        @foreach($tahapanPerkembangan as $t)
                            <option value="{{ $t->id }}" {{ old('tahapan_perkembangan_id') == $t->id ? 'selected' : '' }}>
                                {{ $t->nama_tahapan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="tanggal_pencapaian" class="form-label">Tanggal Pencapaian</label>
                    <input type="date" name="tanggal_pencapaian" id="tanggal_pencapaian" class="form-control" 
                        value="{{ old('tanggal_pencapaian') }}" required max="{{ now()->toDateString() }}">
                </div>

                <div class="mb-3">
                    <label for="catatan" class="form-label">Catatan (opsional)</label>
                    <textarea name="catatan" id="catatan" class="form-control" rows="4">{{ old('catatan') }}</textarea>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
