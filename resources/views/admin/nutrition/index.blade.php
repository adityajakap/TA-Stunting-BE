@extends('layouts.app')

@section('content')

<style>
    /* Reuse same styles as admin artikel for consistent admin look */
    .main-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 1280px;
        margin: 2rem auto 1rem;
        padding: 0 1rem;
    }

    .main-title { color: #005f77; font-size: 2rem; margin: 0; }
    .action-buttons { display: flex; gap: 0.5rem; }
    .btn-icon-mini { background-color: #005f77; color: white; padding: 0.3rem 0.6rem; border: none; border-radius: 0.375rem; font-size: 0.75rem; cursor: pointer; }
    .btn-icon-mini:hover { background-color: #014f66; }

    .card-wrapper { max-width: 1280px; margin: 0 auto; padding-bottom: 2rem; }
    .card-container { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; padding: 0 2rem; }
    @media (max-width: 1024px) { .card-container { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 768px) { .card-container { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 480px) { .card-container { grid-template-columns: 1fr; } }

    .card { background-color: #ffffff; border-radius: 1rem; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); display: flex; flex-direction: column; }
    .article-image { width: 100%; height: 180px; object-fit: cover; background-color: #f3f4f6; }
    .card-body { padding: 1rem; display: flex; flex-direction: column; justify-content: space-between; flex-grow: 1; }
    .card-title { font-weight: bold; color: #1f2937; font-size: 1.1rem; margin-bottom: 0.5rem; }
    .btn { background-color: #005f77; color: white; padding: 0.4rem 0.8rem; border: none; border-radius: 0.5rem; font-size: 0.8rem; text-decoration: none; transition: background-color 0.3s ease; }
    .btn:hover { background-color: #014f66; }
    .empty-message { text-align: center; font-size: 1rem; color: #6b7280; margin: 3rem 0; }

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
</style>

{{-- Header --}}
<div class="main-header">
    <div style="display:flex; align-items:center; gap:0.5rem;">
        <x-back-button />
        <h1 class="main-title">Daftar Menu</h1>
    </div>
</div>

{{-- New Menu button --}}
<a href="{{ route('admin.nutrition.create') }}" class="btn" style="display:block; width:100%; text-align:center; font-size:0.9rem; margin-bottom:1.5rem;">+ Menu</a>

{{-- Search Modal --}}
<div id="searchModal" class="modal-overlay" style="display:none; position: fixed; inset:0; background: rgba(0,0,0,0.4); z-index:999; justify-content:center; align-items:center;">
    <div class="modal-content" style="background:white; padding:2rem; border-radius:1rem; max-width:600px; width:90%;">
        <h2>Cari Menu</h2>
        <form method="GET" action="{{ route('admin.nutrition.index') }}">
            <input type="text" name="search" placeholder="Masukkan kata kunci..." value="{{ request('search') }}" style="padding:0.5rem 1rem; width:100%; border-radius:0.5rem; border:1px solid #ccc; margin-bottom:1rem;">
            <div style="display:flex; justify-content:flex-end; gap:0.5rem;">
                <button type="submit" class="btn">Cari</button>
                <button type="button" class="btn" style="background-color:#ef4444;" onclick="toggleSearch()">Tutup</button>
            </div>
        </form>
    </div>
</div>

{{-- Card grid --}}
<div class="card-wrapper">
    <div class="card-container">
        @forelse ($menus as $menu)
            <div class="card">
                @if ($menu->image)
                    <img src="{{ asset('storage/' . $menu->image) }}" alt="Gambar Menu" class="article-image">
                @endif
                <div class="card-body">
                    <div>
                        <div class="card-title">{{ $menu->name }}</div>
                        <div class="text-muted mb-2">{{ ucfirst($menu->category) }}</div>
                        <p style="margin-bottom:.5rem;"><strong>Nutrisi:</strong> {{ Str::limit($menu->nutrition, 140) }}</p>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">👁 {{ $menu->views ?? 0 }}</div>
                        <div style="display:flex; gap:.5rem;">
                            <a href="{{ route('admin.nutrition.edit', $menu->id) }}" class="btn btn-sm" style="background:#f3f4f6; color:#0f172a; padding:.25rem .5rem;">Edit</a>
                            <button type="button" class="btn btn-sm" style="background:#ffeef0; color:#b91c1c; padding:.25rem .5rem; border:none; cursor:pointer;" onclick="showDeleteConfirm('{{ route('admin.nutrition.destroy', $menu->id) }}')">Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="empty-message">Belum ada menu yang tersedia.</p>
        @endforelse
    </div>
</div>

{{-- Pagination --}}
<div class="d-flex justify-content-center mt-4">
    {{ $menus->links('pagination::bootstrap-5') }}
</div>

<script>
    function toggleSearch() {
        const modal = document.getElementById('searchModal');
        modal.style.display = modal.style.display === 'flex' || modal.style.display === 'block' ? 'none' : 'flex';
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
        <div class="confirm-modal-title">Hapus Menu?</div>
        <div class="confirm-modal-message">Anda yakin ingin menghapus menu ini? Tindakan ini tidak dapat dibatalkan.</div>
        <div class="confirm-modal-buttons">
            <button type="button" class="cancel-btn" onclick="closeDeleteConfirm()">Batal</button>
            <button type="button" class="confirm-btn" onclick="confirmDelete()">Hapus</button>
        </div>
    </div>
</div>

{{-- Font Awesome (if not already included) --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@endsection