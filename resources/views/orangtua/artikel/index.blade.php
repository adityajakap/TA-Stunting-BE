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

    .btn-icon-mini:hover {
        background-color: #014f66;
    }

    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.4);
        z-index: 999;
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background: white;
        padding: 2rem;
        border-radius: 1rem;
        max-width: 600px;
        width: 90%;
    }

    .modal-content h2 {
        margin-bottom: 1rem;
        color: #005f77;
    }

    .card-wrapper {
        max-width: 1280px;
        margin: 0 auto;
        padding-bottom: 2rem;
    }

    .card-container {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        padding: 0 2rem;
    }

    @media (max-width: 1024px) {
        .card-container {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .card-container {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .card-container {
            grid-template-columns: 1fr;
        }
    }

    .card {
        background-color: #ffffff;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        display: flex;
        flex-direction: column;
    }

    .article-image {
        width: 100%;
        height: 180px;
        object-fit: cover;
        background-color: #f3f4f6;
    }

    .card-body {
        padding: 1rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        flex-grow: 1;
    }

    .card-title {
        font-weight: bold;
        color: #1f2937;
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }

    .badge {
        display: inline-block;
        background-color: #d1fae5;
        color: #065f46;
        font-size: 0.75rem;
        padding: 0.2rem 0.5rem;
        margin: 0.2rem 0.2rem 0 0;
        border-radius: 0.5rem;
    }

    .card-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 0.75rem;
    }

    .view-count {
        font-size: 0.9rem;
        color: #6b7280;
    }

    .btn {
        background-color: #005f77;
        color: white;
        padding: 0.4rem 0.8rem;
        border: none;
        border-radius: 0.5rem;
        font-size: 0.8rem;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .btn:hover {
        background-color: #014f66;
    }

    .empty-message {
        text-align: center;
        font-size: 1rem;
        color: #6b7280;
        margin: 3rem 0;
    }

</style>

{{-- Header --}}
<div class="main-header">
    <div style="display:flex; align-items:center; gap:0.5rem;">
        <x-back-button />
        <h1 class="main-title">Artikel untuk Anda</h1>
    </div>
    
</div>

{{-- kategori filter removed --}}

{{-- Search Modal --}}
<div id="searchModal" class="modal-overlay">
    <div class="modal-content">
        <h2>Cari Artikel</h2>
        <form method="GET" action="{{ route('orangtua.artikel.index') }}">
            <input type="text" name="search" placeholder="Masukkan kata kunci..." value="{{ request('search') }}"
                   style="padding: 0.5rem 1rem; width: 100%; border-radius: 0.5rem; border: 1px solid #ccc; margin-bottom: 1rem;">
            <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                <button type="submit" class="btn">Cari</button>
                <button type="button" class="btn" style="background-color: #ef4444;" onclick="toggleSearch()">Tutup</button>
            </div>
        </form>
    </div>
</div>

{{-- Artikel --}}
<div class="card-wrapper">
    <div class="card-container">
        @forelse ($artikels as $artikel)
            <div class="card">
                <img src="{{ $artikel->image ? asset('storage/' . $artikel->image) : asset('default-image.png') }}"
                     alt="Gambar Artikel" class="article-image">
                <div class="card-body">
                    <div class="card-title">{{ Str::limit($artikel->title, 60) }}</div>
                    {{-- kategori badges removed --}}
                    <div class="card-actions">
                        <div class="view-count">👁 {{ $artikel->views ?? 0 }}</div>
                        <a href="{{ route('orangtua.artikel.show', $artikel->id) }}" class="btn">Read All</a>
                    </div>
                </div>
            </div>
        @empty
            <p class="empty-message">Tidak ada artikel ditemukan.</p>
        @endforelse
    </div>
</div>

{{-- Kembali ke Semua Artikel --}}
@if(request('search'))
    <a href="{{ route('orangtua.artikel.index') }}" 
       class="btn" 
       style="position: fixed; bottom: 30px; right: 30px; padding: 0.6rem 1.5rem; font-size: 0.85rem; z-index: 1000;">
        ← Kembali ke Semua Artikel
    </a>
@endif

<script>
    function toggleFilter() {
        const modal = document.getElementById('filterModal');
        modal.style.display = modal.style.display === 'flex' ? 'none' : 'flex';
    }

    function toggleSearch() {
        const modal = document.getElementById('searchModal');
        modal.style.display = modal.style.display === 'flex' ? 'none' : 'flex';
    }
</script>

{{-- Font Awesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

{{-- Pagination --}}
<div class="d-flex justify-content-center mt-4">
    {{ $artikels->links('pagination::bootstrap-5') }}
</div>
@endsection
