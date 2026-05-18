@extends('layouts.app')

@section('title', 'Edit Pencapaian')

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
        color: #1e5a6e;
        font-size: 1.75rem;
        margin: 0;
        font-weight: 600;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .card-wrapper { max-width: 1280px; margin: 0 auto; padding-bottom: 2rem; padding: 0 1rem 2rem; }

    .card { background-color: #ffffff; border-radius: 1rem; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); display: flex; flex-direction: column; }
    .card-body { padding: 2rem; display: flex; flex-direction: column; justify-content: space-between; flex-grow: 1; }

    .form-label {
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control, .form-select {
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        width: 100%;
        background-color: #fff;
    }

    .form-control:focus, .form-select:focus {
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
        justify-content: flex-end;
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

    .btn-secondary {
        background-color: #e5e7eb;
        color: #374151;
        border: 1px solid #d1d5db;
    }

    .btn-secondary:hover {
        background-color: #d1d5db;
    }
</style>

<div class="main-header">
    <div style="display:flex; align-items:center; gap:0.5rem;">
        <x-back-button :url="route('orangtua.tahapan_perkembangan.index')" />
        <h1 class="main-title">Edit Pencapaian</h1>
    </div>
    <div class="action-buttons"></div>
</div>

<div class="card-wrapper">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('orangtua.tahapan_perkembangan.update', $tahapanPerkembanganData->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="tahapan_perkembangan_id" class="form-label">Pilih Tahapan Perkembangan</label>
                    <select name="tahapan_perkembangan_id" id="tahapan_perkembangan_id" class="form-select" required>
                        @foreach($tahapanPerkembangan as $tahapan)
                            <option value="{{ $tahapan->id }}" {{ $tahapan->id == $tahapanPerkembanganData->tahapan_perkembangan_id ? 'selected' : '' }}>
                                {{ $tahapan->nama_tahapan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="tanggal_pencapaian" class="form-label">Tanggal Pencapaian</label>
                    <input type="date" name="tanggal_pencapaian" id="tanggal_pencapaian" class="form-control"
                        value="{{ old('tanggal_pencapaian', $tahapanPerkembanganData->tanggal_pencapaian) }}" required max="{{ now()->toDateString() }}">
                </div>

                <div class="mb-3">
                    <label for="catatan" class="form-label">Catatan</label>
                    <textarea name="catatan" id="catatan" class="form-control" rows="3">{{ old('catatan', $tahapanPerkembanganData->catatan) }}</textarea>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection