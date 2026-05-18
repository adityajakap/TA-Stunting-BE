
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
        color: #1e5a6e;
        font-size: 1.75rem;
        margin: 0;
        font-weight: 600;
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
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 2rem;
    }

    .detail-content {
        display: flex;
        gap: 3rem;
        align-items: flex-start;
    }

    .detail-image {
        flex: 0 0 300px;
        min-width: 300px;
    }

    .detail-image img {
        width: 100%;
        height: 300px;
        object-fit: cover;
        border-radius: 1rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .detail-info {
        flex: 1;
    }

    .menu-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .menu-category {
        color: #6b7280;
        font-size: 1rem;
        margin-bottom: 2rem;
        text-transform: capitalize;
    }

    .info-section {
        margin-bottom: 2rem;
    }

    .info-section h3 {
        color: #1f2937;
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
    }

    .info-section p {
        color: #555;
        line-height: 1.7;
        margin: 0;
        white-space: pre-line;
    }

    .info-section ul {
        margin: 0;
        padding-left: 1.5rem;
    }

    .info-section li {
        color: #555;
        line-height: 1.7;
        margin-bottom: 0.5rem;
    }

    @media (max-width: 768px) {
        .detail-content {
            flex-direction: column;
        }

        .detail-image {
            flex: 1;
            min-width: 100%;
        }
    }
</style>

<div class="main-header">
    <div style="display:flex; align-items:center; gap:0.5rem;">
        <x-back-button :url="route('orangtua.nutritionUs.index')" />
        <h1 class="main-title">Detail Menu</h1>
    </div>
    <div class="action-buttons"></div>
</div>

<div class="card-wrapper">
    <div class="card">
        <div class="detail-content">
            <div class="detail-image">
                @if ($menu->image)
                    <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}">
                @else
                    <img src="{{ asset('default-image.png') }}" alt="{{ $menu->name }}">
                @endif
            </div>

            <div class="detail-info">
                <h1 class="menu-title">{{ $menu->name }}</h1>
                <p class="menu-category">{{ ucfirst($menu->category) }}</p>

                <div class="info-section">
                    <h3>Nutrisi :</h3>
                    <p>{{ $menu->nutrition ?? 'Informasi nutrisi tidak tersedia' }}</p>
                </div>

                <div class="info-section">
                    <h3>Bahan :</h3>
                    <p>{{ $menu->ingredients ?? 'Informasi bahan tidak tersedia' }}</p>
                </div>

                <div class="info-section">
                    <h3>Cara Buat :</h3>
                    <p>{{ $menu->instructions ?? 'Informasi cara membuat tidak tersedia' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection