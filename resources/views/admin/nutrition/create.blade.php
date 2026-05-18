
@extends('layouts.app')

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
        font-family: inherit;
    }

    .form-control:focus {
        border-color: #005f77;
        outline: none;
        box-shadow: 0 0 0 3px rgba(0, 95, 119, 0.1);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 120px;
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

    .file-input-wrapper {
        position: relative;
        display: inline-block;
    }

    .file-input-label {
        display: inline-block;
        padding: 0.75rem 1rem;
        background-color: #f3f4f6;
        color: #374151;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .file-input-label:hover {
        background-color: #e5e7eb;
    }

    input[type="file"] {
        display: none;
    }

    .image-preview {
        margin-top: 1rem;
    }

    .image-preview img {
        max-width: 200px;
        border-radius: 0.5rem;
        margin-top: 0.5rem;
    }
</style>

<div class="main-header">
    <div style="display:flex; align-items:center; gap:0.5rem;">
        <x-back-button :url="route('admin.nutrition.index')" />
        <h1 class="main-title">Tambah Menu</h1>
    </div>
</div>

<div class="card-wrapper">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.nutrition.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Menu</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label for="nutrition" class="form-label">Nutrisi</label>
                    <textarea id="nutrition" name="nutrition" class="form-control" required>{{ old('nutrition') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="ingredients" class="form-label">Bahan-bahan</label>
                    <textarea id="ingredients" name="ingredients" class="form-control" required>{{ old('ingredients') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="instructions" class="form-label">Cara Membuat</label>
                    <textarea id="instructions" name="instructions" class="form-control" required>{{ old('instructions') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="category" class="form-label">Kategori</label>
                    <select id="category" name="category" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        @foreach(['pagi', 'siang', 'malam', 'snack'] as $kategori)
                            <option value="{{ $kategori }}" {{ old('category') == $kategori ? 'selected' : '' }}>{{ ucfirst($kategori) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Gambar</label>
                    <div class="file-input-wrapper">
                        <label for="image" class="file-input-label">Chose File</label>
                        <input type="file" id="image" name="image" accept="image/*">
                    </div>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
