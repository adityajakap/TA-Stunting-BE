@extends('layouts.app')

@section('content')
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #e5e7eb;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 900px;
        margin: 2rem auto;
        padding: 1.5rem;
        background-color: #ffffff;
        border-radius: 1rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        position: relative;
    }

    .judul-artikel {
        font-size: 2rem;
        font-weight: bold;
        color: #1f2937;
        margin-bottom: 1rem;
        text-align: center;
        word-break: break-word;
    }

    .artikel-meta {
        font-size: 0.9rem;
        color: #6b7280;
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .badge {
        display: inline-block;
        background-color: #d1fae5;
        color: #065f46;
        font-size: 0.75rem;
        padding: 0.2rem 0.5rem;
        margin: 0.2rem 0.25rem 0 0;
        border-radius: 0.5rem;
    }

    .artikel-image {
        width: 100%;
        max-height: 350px;
        object-fit: cover;
        border-radius: 0.75rem;
        margin-bottom: 2rem;
    }

    .content {
        color: #374151;
        line-height: 1.8;
        font-size: 1rem;
        white-space: pre-line;
        word-break: break-word;
    }

    .btn-back {
        position: fixed;
        bottom: 30px;
        right: 30px;
        padding: 0.6rem 1.2rem;
        background-color: #005f77;
        color: white;
        border: none;
        border-radius: 0.5rem;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        transition: background-color 0.3s ease;
        z-index: 1000;
    }

    .btn-back:hover {
        background-color: #014f66;
    }
</style>

<div class="container">
    <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:1.5rem;">
        <x-back-button :url="route('admin.artikel.index')" />
        <h1 class="judul-artikel" style="text-align:left; margin:0;">{{ $artikel->title }}</h1>
    </div>

    <div class="artikel-meta">
        @foreach ($artikel->kategoris as $kategori)
            <span class="badge">#{{ $kategori->name }}</span>
        @endforeach
        <div style="margin-top: 0.5rem;">👁 {{ $artikel->views ?? 0 }} views</div>
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
@endsection
