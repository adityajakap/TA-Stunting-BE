@extends('layouts.app')

@section('content')

<style>
    /* OVERRIDE agar container bawaan layout jadi full width hanya untuk halaman ini */
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

    .section-title-feature {
        color: #005f77;
        font-weight: 700;
        font-size: 2rem;
    }

    .hero-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 60px 0;
        /* background-color: #f8f9fa; */
    }

    .hero-text {
        max-width: 55%;
    }

    .hero-image {
        width: 45%;
        height: 450px; /* bisa ubah jadi 400px kalau masih kurang besar */
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
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
        justify-content: space-between;
    }

    .feature-box {
        background-color: #00a896;
        color: white;
        flex: 1;
        padding: 30px;
        border-radius: 16px;
        transition: all 0.3s ease-in-out;
        text-align: center;
        min-height: 200px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .feature-box:hover {
        background-color: #008f7f;
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 168, 150, 0.3);
    }

    .feature-box h3 {
        color: #ffffff;
        font-size: 1.5rem;
        font-weight: 600;
        margin: 15px 0 10px 0;
    }

    .feature-box p {
        color: #f1f1f1;
        font-size: 0.95rem;
        line-height: 1.5;
        margin: 0;
    }

    .icon-feature {
        font-size: 3rem;
        color: #ffffff;
        margin-bottom: 15px;
        display: block;
    }


    .menu-grid {
        display: flex;
        justify-content: center;
        gap: 20px;
        padding-bottom: 40px;
        padding-top: 20px;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        flex-wrap: nowrap;
        white-space: nowrap;
    }

    .article-grid {
        display: flex;
        justify-content: center;
        gap: 20px;
        padding-bottom: 40px;
        padding-top: 20px;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
    }

    .menu-block {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 16px;
        text-align: center;
        width: 280px;
        flex: 0 0 auto;
        scroll-snap-align: start;
        transition: transform 0.3s ease;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .menu-block:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .menu-block img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-radius: 12px;
        margin-bottom: 15px;
    }
    
    .article-card {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 16px;
        overflow: hidden;
        min-width: 300px;
        flex: 0 0 auto;
        scroll-snap-align: start;
        transition: transform 0.3s ease;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .article-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .article-card img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 12px;
        margin-bottom: 10px;
    }

    .article-content {
        padding: 20px;
        text-align: left;
    }

    .article-content h6 {
        font-weight: 600;
        margin: 0 0 10px 0;
        color: #333;
        font-size: 1.1rem;
        line-height: 1.4;
    }

    .article-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 20px 20px;
        font-size: 0.9rem;
        color: #6c757d;
    }

    .article-actions .views {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .article-actions .views i {
        color: #005f77;
    }

    .article-actions a {
        background: #005f77;
        color: white;
        border-radius: 8px;
        padding: 8px 15px;
        text-decoration: none;
        font-size: 0.9rem;
        display: inline-block;
        font-weight: 500;
    }

    .article-actions a:hover {
        background: #00485e;
        transform: translateY(-1px);
    }

    .section-header {
        padding: 20px 0;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 12px;
    }

    .section-header h4 {
        margin: 0;
        font-weight: 700;
        font-size: 1.8rem;
        color: #005f77;
    }

    .section-header .btn-arrow {
        background: white;
        border: 2px solid #00a896;
        color: #00a896;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .section-header .btn-arrow:hover {
        background: #00a896;
        color: white;
        transform: scale(1.1);
    }
    
    .btn btn-primary {
        background-color: #005f77;
        color: white;
        border-radius: 8px;
        padding: 10px 20px;
        text-decoration: none;
    }

    .feature-box-link {
    flex: 1;
    text-decoration: none;
    transition: transform 0.2s ease-in-out;
}

    .feature-box-link:hover {
        transform: translateY(-6px);
    }

    .carousel-control-prev,
    .carousel-control-next {
        width: 5%;
        top: 40%;
        bottom: auto;
        opacity: 1;
        transition: 0.3s;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-color: rgba(0, 0, 0, 0.7);
        background-size: 60% 60%;
        border-radius: 50%;
        padding: 10px;
    }

    .carousel-control-prev:hover,
    .carousel-control-next:hover {
        background-color: transparent;
        opacity: 0.8;
        transform: scale(1.1);
    }

    #articleCarousel .carousel-control-prev,
    #articleCarousel .carousel-control-next {
        width: 5%;
        top: 40%;
        bottom: auto;
        opacity: 1;
        transition: 0.3s;
    }

    #articleCarousel .carousel-control-prev-icon,
    #articleCarousel .carousel-control-next-icon {
        background-color: rgba(0, 0, 0, 0.7);
        background-size: 60% 60%;
        border-radius: 50%;
        padding: 10px;
    }

    #articleCarousel .carousel-control-prev:hover,
    #articleCarousel .carousel-control-next:hover {
        background-color: transparent;
        opacity: 0.8;
        transform: scale(1.1);
    }

    .carousel-control-prev,
    .carousel-control-next {
        z-index: 10;
    }

    .carousel {
        position: relative;
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

    .feature-box h3,
    .feature-box p {
        color: white;
    }

    .carousel-control-prev,
    .carousel-control-next {
        width: 5%;
        top: 40%;
        bottom: auto;
        opacity: 1;
        transition: 0.3s;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-color: rgba(0, 0, 0, 0.7);
        background-size: 60% 60%;
        border-radius: 50%;
        padding: 10px;
    }

    .carousel-control-prev:hover,
    .carousel-control-next:hover {
        background-color: transparent;
        opacity: 0.8;
        transform: scale(1.1);
    }

    #articleCarousel .carousel-control-prev,
    #articleCarousel .carousel-control-next {
        width: 5%;
        top: 40%;
        bottom: auto;
        opacity: 1;
        transition: 0.3s;
    }

    #articleCarousel .carousel-control-prev-icon,
    #articleCarousel .carousel-control-next-icon {
        background-color: rgba(0, 0, 0, 0.7);
        background-size: 60% 60%;
        border-radius: 50%;
        padding: 10px;
    }

    #articleCarousel .carousel-control-prev:hover,
    #articleCarousel .carousel-control-next:hover {
        background-color: transparent;
        opacity: 0.8;
        transform: scale(1.1);
    }

    .carousel-control-prev,
    .carousel-control-next {
        z-index: 10;
    }

    .carousel {
    position: relative;
}


</style>

<div class="section-wrapper">

    {{-- HERO SECTION --}}
    <div class="hero-section">
        <div class="hero-text">
            <h2 class="section-title">Pantau Tumbuh Kembang, Cegah Stunting Sejak Dini!</h2>
            <p>Pantau dan deteksi tumbuh kembang anak Anda secara berkala, serta dapatkan rekomendasi menu bergizi yang disesuaikan dengan kebutuhan hariannya untuk mendukung pertumbuhan yang optimal.</p>
            <div class="mt-3">
                <a href="{{ route('orangtua.detections.create') }}" class="btn btn-primary" style="background-color: #005f77; border: none;">Deteksi Stunting</a>
                <a href="{{ route('orangtua.tahapan_perkembangan.index') }}" class="btn btn-primary" style="background-color: #005f77; border: none;">Monitoring Anak</a>
            </div>
        </div>
        <div class="hero-image">
            <img src="{{ asset('images/logo.png') }}" alt="Ilustrasi Dashboard" style="width: 100%; height: 100%; object-fit: contain;">
        </div>
    </div>

    {{-- FITUR UTAMA --}}
    <div class="feature-grid">
        <a href="{{ route('orangtua.detections.create') }}" class="feature-box-link">
            <div class="feature-box">
                <i class="fas fa-chart-line icon-feature"></i>
                <h3>Deteksi</h3>
                <p>Deteksi stunting pada anak dengan menggunakan metode yang tepat.</p>
            </div>
        </a>
        <a href="{{ route('orangtua.nutritionUs.index') }}" class="feature-box-link">
            <div class="feature-box">
                <i class="fas fa-leaf icon-feature"></i>
                <h3>Menu</h3>
                <p>Dapatkan rekomendasi menu bergizi untuk tumbuh kembang anak.</p>
            </div>
        </a>
        <a href="{{ route('bmi') }}" class="feature-box-link">
            <div class="feature-box">
                <i class="fas fa-calculator icon-feature"></i>
                <h3>BMI</h3>
                <p>Hitung status gizi anak secara cepat dan mudah berdasarkan tinggi dan berat badan.</p>
            </div>
        </a>
    </div>



    {{-- TODAY MENU --}}
    <div class="section-header">
        <h4 class="section-title-feature mb-0">Today Menu's</h4>
    </div>

    <div class="menu-grid">
        @php
            $menuItems = collect(['pagi', 'siang', 'malam', 'snack'])->map(function($waktu) use ($menus) {
                return $menus[$waktu] ?? null;
            })->filter()->take(3);
        @endphp
        @foreach ($menuItems as $menu)
            <div class="menu-block">
                <img src="{{ $menu->image ? asset('storage/' . $menu->image) : asset('default-image.png') }}" alt="Menu">
                <h6 style="font-weight: 600; margin: 10px 0 5px 0;">{{ $menu->name }}</h6>
                <small style="color: #6c757d; text-transform: capitalize;">{{ $menu->category }}</small>
                <div style="margin-top: 15px;">
                    <a href="{{ route('orangtua.nutritionUs.show', $menu->id) }}" 
                    style="background:#005f77; color:white; border-radius:8px; padding:10px 20px; text-decoration:none; font-size:0.9rem; display:inline-block; font-weight: 500;">
                        Lihat Menu
                    </a>
                </div>
            </div>
        @endforeach
    </div>


    {{-- ARTICLES --}}
    <div class="section-header">
        <h4 class="section-title-feature mb-0">Articles</h4>
    </div>

    <div class="article-grid">
        @foreach ($artikels->take(3) as $artikel)
            <div class="article-card">
                <img src="{{ $artikel->image ? asset('storage/' . $artikel->image) : asset('default-image.png') }}" alt="Artikel">
                <div class="article-content">
                    <h6>{{ Str::limit($artikel->title, 60) }}</h6>
                </div>
                <div class="article-actions">
                    <div class="views">
                        <i class="fas fa-eye"></i>
                        <span>{{ $artikel->views ?? 100 }}</span>
                    </div>
                    <a href="{{ route('orangtua.artikel.show', $artikel->id) }}">Read All</a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
