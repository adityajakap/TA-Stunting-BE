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

    .badge {
        display: inline-block;
        background: #e0f2fe;
        color: #0284c7;
        border-radius: 0.5rem;
        padding: 0.2rem 0.7rem;
        font-size: 0.85rem;
        margin-right: 0.3rem;
        margin-bottom: 0.2rem;
    }
</style>

{{-- HEADER --}}
<div class="main-header">
    <div style="display:flex; align-items:center; gap:0.5rem;">
        <x-back-button />
        <h1 class="main-title">Rekomendasi Nutrisi</h1>
    </div>
    <div class="action-buttons" style="display: flex; gap: 0.5rem;">
        <x-button-icon icon="fas fa-filter" title="Filter" onclick="document.getElementById('filterModal').classList.remove('hidden')" />
        <x-button-icon icon="fas fa-search" title="Cari" onclick="document.getElementById('search-modal').classList.remove('hidden')" />
    </div>
</div>

{{-- FILTER MODAL --}}
<div id="filterModal" class="modal-overlay hidden fixed inset-0 bg-black bg-opacity-10 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md">
        <form method="GET" action="{{ route('orangtua.nutritionUs.index') }}">
            <div class="mb-4 font-semibold text-sky-900">Kategori</div>
            <div class="flex flex-wrap gap-x-4 gap-y-2 mb-6">
                @foreach($kategoris as $kategori)
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="kategori[]" value="{{ $kategori->id }}"
                            class="form-checkbox accent-sky-600"
                            {{ in_array($kategori->id, (array) $kategoriIds) ? 'checked' : '' }}>
                        <span class="ml-2 text-sky-800">{{ $kategori->name }}</span>
                    </label>
                @endforeach
            </div>
            <div class="flex justify-end gap-2">
                <button type="submit" class="px-4 py-2 rounded text-white" style="background-color: #005f77;">Terapkan</button>
                <a href="{{ route('orangtua.nutritionUs.index') }}" class="px-4 py-2 rounded text-white" style="background-color: #005f77;">Reset</a>
                <button type="button" onclick="document.getElementById('filterModal').classList.add('hidden')" class="px-4 py-2 rounded text-white" style="background-color: #005f77;">Tutup</button>
            </div>
        </form>
    </div>
</div>

{{-- SEARCH MODAL --}}
<div id="search-modal" class="modal-overlay hidden fixed inset-0 bg-black bg-opacity-10 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md">
        <form method="GET" action="{{ route('orangtua.nutritionUs.index') }}">
            <div class="mb-4 font-semibold text-sky-900">Cari Menu</div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Masukkan kata kunci menu..." class="w-full mb-6 px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-sky-600">
            <div class="flex justify-end gap-2">
                <button type="submit" class="px-4 py-2 rounded text-white" style="background-color: #005f77;">Cari</button>
                <a href="{{ route('orangtua.nutritionUs.index') }}" class="px-4 py-2 rounded text-white" style="background-color: #005f77;">Reset</a>
                <button type="button" onclick="document.getElementById('search-modal').classList.add('hidden')" class="px-4 py-2 rounded text-white" style="background-color: #005f77;">Tutup</button>
            </div>
        </form>
    </div>
</div>

{{-- CARD VIEW --}}
<div class="card-wrapper">
    <div class="card-container">
        @forelse ($menus as $menu)
            <div class="card">
                <img src="{{ $menu->image ? asset('storage/' . $menu->image) : asset('default-image.png') }}"
                     alt="Gambar Menu" class="article-image" style="width: 100%; height: 180px; object-fit: cover; background-color: #f3f4f6;">
                <div class="card-body" style="padding: 1rem; display: flex; flex-direction: column; justify-content: space-between; flex-grow: 1;">
                    <div class="card-title" style="font-weight: bold; color: #1f2937; font-size: 1.1rem; margin-bottom: 0.5rem;">
                        {{ $menu->name }}
                    </div>
                    <div style="margin-bottom: 0.5rem;">
                        <span class="badge">{{ ucfirst($menu->category) }}</span>
                    </div>
                    <x-button :href="route('orangtua.nutritionUs.show', $menu->id)" class="w-full text-center">
    Lihat Menu
</x-button>
                </div>
            </div>
        @empty
            <p style="text-align: center; color: #6b7280;">Belum ada menu yang tersedia.</p>
        @endforelse
    </div>
</div>

{{-- Font Awesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection
