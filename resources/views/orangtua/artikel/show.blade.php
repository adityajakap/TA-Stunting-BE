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
        padding: 2.5rem;
    }

    .artikel-title {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1rem;
        text-align: center;
    }

    .view-count {
        text-align: center;
        font-size: 0.95rem;
        color: #6b7280;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .artikel-image {
        width: 100%;
        max-width: 600px;
        height: auto;
        max-height: 400px;
        object-fit: cover;
        border-radius: 0.75rem;
        margin: 0 auto 2.5rem;
        display: block;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .content {
        color: #374151;
        line-height: 1.8;
        font-size: 1.025rem;
        white-space: pre-line;
        word-break: break-word;
        text-align: justify;
    }

    @media (max-width: 768px) {
        .card {
            padding: 1.5rem;
        }

        .artikel-title {
            font-size: 1.5rem;
        }
    }
</style>

<div class="main-header">
    <div style="display:flex; align-items:center; gap:0.5rem;">
        <x-back-button :url="route('orangtua.artikel.index')" />
        <h1 class="main-title">{{ Str::limit($artikel->title, 50) }}</h1>
    </div>
    <div class="action-buttons"></div>
</div>

<div class="card-wrapper">
    <div class="card">
        <h1 class="artikel-title">{{ $artikel->title }}</h1>

        <div class="view-count">
            👁 {{ $artikel->views ?? 0 }} views
        </div>

        @if ($artikel->image)
            <img src="{{ asset('storage/' . $artikel->image) }}" alt="Gambar Artikel" class="artikel-image">
        @else
            <img src="{{ asset('default-image.png') }}" alt="Gambar Default" class="artikel-image">
        @endif

        <div class="content">
            {!! nl2br(e($artikel->content)) !!}
        </div>
    </div>
</div>

@endsection
