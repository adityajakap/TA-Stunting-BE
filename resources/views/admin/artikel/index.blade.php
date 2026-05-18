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

    /* Custom Confirmation Modal */
    .confirm-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        justify-content: center;
        align-items: center;
    }

    .confirm-modal.active {
        display: flex;
    }

    .confirm-modal-content {
        background: white;
        padding: 2rem;
        border-radius: 1rem;
        max-width: 400px;
        width: 90%;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .confirm-modal-icon {
        font-size: 3rem;
        text-align: center;
        margin-bottom: 1rem;
    }

    .confirm-modal-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        text-align: center;
        margin-bottom: 0.5rem;
    }

    .confirm-modal-message {
        color: #6b7280;
        text-align: center;
        margin-bottom: 1.5rem;
        font-size: 0.95rem;
    }

    .confirm-modal-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }

    .confirm-btn, .cancel-btn {
        padding: 0.7rem 1.5rem;
        border: none;
        border-radius: 0.5rem;
        font-weight: 600;
        cursor: pointer;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .confirm-btn {
        background-color: #ef4444;
        color: white;
    }

    .confirm-btn:hover {
        background-color: #dc2626;
        transform: translateY(-2px);
    }

    .cancel-btn {
        background-color: #e5e7eb;
        color: #374151;
    }

    .cancel-btn:hover {
        background-color: #d1d5db;
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

    .btn-icon-action {
        font-size: 1rem;
        padding: 0.2rem 0.4rem;
        border-radius: 0.375rem;
    }

    /* Small pill buttons used for edit/delete actions to match requested design */
    .btn-small {
        background-color: #f3f4f6; /* light gray */
        color: #0f172a;
        padding: .25rem .6rem;
        border-radius: .5rem;
        font-size: 0.85rem;
        text-decoration: none;
        border: none;
        display: inline-block;
    }

    .btn-small:hover { background-color: #e8eef0; }

    .btn-delete-small {
        background-color: #fff1f2; /* soft pink */
        color: #b91c1c; /* red */
        padding: .25rem .6rem;
        border-radius: .5rem;
        font-size: 0.85rem;
        border: none;
        display: inline-block;
    }

    .btn-delete-small:hover { background-color: #ffe6e9; }

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
        <h1 class="main-title">Manajemen Artikel</h1>
    </div>
</div>

<a href="{{ route('admin.artikel.create') }}"
   class="btn"
   style="display: block; width: 100%; text-align: center; font-size: 0.9rem; margin-bottom: 1.5rem;">
    + New Article
</a>


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
                        <div class="flex gap-2">
                            <a href="{{ route('admin.artikel.edit', $artikel->id) }}" class="btn-small" title="Edit">Edit</a>
                            <button type="button" class="btn-delete-small" onclick="showDeleteConfirm('{{ route('admin.artikel.destroy', $artikel->id) }}')">Hapus</button>
                        </div>
                    </div>
                    <a href="{{ route('admin.artikel.show', $artikel->id) }}" class="btn mt-2 w-full text-center">Read All</a>
                </div>
            </div>
        @empty
            <p class="empty-message">Belum ada artikel yang tersedia.</p>
        @endforelse

        {{-- no kategori filtering --}}
    </div>
</div>

{{-- Kembali --}}
@if(request('search'))
    <a href="{{ route('admin.artikel.index') }}" 
       class="btn" 
       style="position: fixed; bottom: 30px; right: 30px; padding: 0.6rem 1.5rem; font-size: 0.85rem; z-index: 1000;">
        ← Kembali ke Semua Artikel
    </a>
@endif

{{-- Pagination --}}
<div class="d-flex justify-content-center mt-4">
    {{ $artikels->links('pagination::bootstrap-5') }}
</div>

<script>
    function toggleFilter() {
        const modal = document.getElementById('filterModal');
        modal.style.display = modal.style.display === 'flex' ? 'none' : 'flex';
    }

    function toggleSearch() {
        const modal = document.getElementById('searchModal');
        modal.style.display = modal.style.display === 'flex' ? 'none' : 'flex';
    }

    let deleteUrl = '';

    function showDeleteConfirm(url) {
        deleteUrl = url;
        const modal = document.getElementById('confirmModal');
        modal.classList.add('active');
    }

    function closeDeleteConfirm() {
        const modal = document.getElementById('confirmModal');
        modal.classList.remove('active');
        deleteUrl = '';
    }

    function confirmDelete() {
        if (deleteUrl) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = deleteUrl;
            form.innerHTML = '@csrf @method('DELETE')';
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('confirmModal');
        if (event.target === modal) {
            closeDeleteConfirm();
        }
    });
</script>

<!-- Delete Confirmation Modal -->
<div id="confirmModal" class="confirm-modal">
    <div class="confirm-modal-content">
        <div class="confirm-modal-icon">⚠️</div>
        <div class="confirm-modal-title">Hapus Artikel?</div>
        <div class="confirm-modal-message">Anda yakin ingin menghapus artikel ini? Tindakan ini tidak dapat dibatalkan.</div>
        <div class="confirm-modal-buttons">
            <button type="button" class="cancel-btn" onclick="closeDeleteConfirm()">Batal</button>
            <button type="button" class="confirm-btn" onclick="confirmDelete()">Hapus</button>
        </div>
    </div>
</div>

{{-- Font Awesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@endsection
