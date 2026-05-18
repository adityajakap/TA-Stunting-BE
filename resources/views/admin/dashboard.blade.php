@extends('layouts.app')

@section('content')

<style>
    .container.mt-4 {
        max-width: 100% !important;
        width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .section-wrapper {
        padding-inline: 5%;
    }

    .section-title {
        color: #005f77;
        font-weight: 700;
        font-size: 3rem;
    }

    .hero-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 60px 0;
    }

    .hero-text {
        max-width: 55%;
    }

    .hero-image {
        width: 45%;
        height: 450px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .hero-image img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        display: block;
    }

    .feature-grid {
        display: flex;
        gap: 20px;
        padding: 40px 0;
    }

    .feature-box-link {
        flex: 1;
        text-decoration: none;
        transition: transform 0.2s ease-in-out;
    }

    .feature-box-link:hover {
        transform: translateY(-6px);
    }

    .feature-box {
        background-color: #005f77;
        color: white;
        padding: 20px;
        border-radius: 12px;
        height: 100%;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
        display: block;
    }

    .feature-box:hover {
        background-color: #00485e;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }

    .icon-feature {
        font-size: 2rem;
        color: #ffffff;
        margin-bottom: 10px;
        display: block;
    }

    .feature-box h3,
    .feature-box p {
        color: white;
    }
</style>

<div class="section-wrapper">

    {{-- HERO SECTION --}}
    <div class="hero-section">
        <div class="hero-text">
            <h2 class="section-title">Selamat Datang di Dashboard Admin</h2>
            <p>Kelola data stunting, perkembangan anak, serta artikel dan rekomendasi nutrisi dengan antarmuka yang mudah digunakan dan informatif.</p>
        </div>
        <div class="hero-image">
            <img src="{{ asset('images/logo.png') }}" alt="Dashboard Admin">
        </div>
    </div>

    {{-- FITUR UTAMA ADMIN --}}
    <div class="feature-grid">
        <a href="{{ route('admin.detections.index') }}" class="feature-box-link">
            <div class="feature-box">
                <i class="fas fa-search icon-feature"></i>
                <h3>Deteksi</h3>
                <p>Periksa hasil deteksi stunting dari akun orang tua.</p>
            </div>
        </a>
        <a href="{{ route('admin.nutrition.index') }}" class="feature-box-link">
            <div class="feature-box">
                <i class="fas fa-utensils icon-feature"></i>
                <h3>Menu</h3>
                <p>Atur dan kelola rekomendasi menu bergizi untuk anak-anak.</p>
            </div>
        </a>
        <a href="{{ route('admin.tahapan_perkembangan.index') }}" class="feature-box-link">
            <div class="feature-box">
                <i class="fas fa-child icon-feature"></i>
                <h3>Perkembangan</h3>
                <p>Kelola data tahapan perkembangan anak dari berbagai usia.</p>
            </div>
        </a>
        <a href="{{ route('admin.artikel.index') }}" class="feature-box-link">
            <div class="feature-box">
                <i class="fas fa-newspaper icon-feature"></i>
                <h3>Artikel</h3>
                <p>Tambahkan, ubah, dan hapus artikel edukatif seputar stunting.</p>
            </div>
        </a>
    </div>
</div>
@endsection
