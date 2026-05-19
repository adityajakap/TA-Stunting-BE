# Cara Menjalankan Proyek (Docker)

Aplikasi ini terdiri dari dua proyek Laravel terpisah:
- **TA-Stunting** — Backend (Laravel API + Database)
- **TA-Stunting-FE** — Frontend (Laravel Blade yang mengkonsumsi API)

---

## Urutan Menjalankan

### 1. Jalankan Backend + DB terlebih dahulu

```bash
cd TA-Stunting

# Salin .env.docker menjadi .env untuk container
cp .env.docker .env

# Build dan jalankan
docker compose up -d --build

# Tunggu container naik (~30 detik), lalu jalankan setup
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
docker compose exec app php artisan storage:link
docker compose exec app php artisan config:cache
```

Backend API akan berjalan di: **http://localhost:8001/api**

---

### 2. Jalankan Frontend setelah Backend siap

```bash
cd TA-Stunting-FE

# Salin .env.docker menjadi .env
cp .env.docker .env

# Generate APP_KEY
# (karena FE tidak punya DB, generate secara manual)
docker compose up -d --build
docker compose exec app_fe composer install
docker compose exec app_fe php artisan key:generate
docker compose exec app_fe php artisan config:cache
```

Frontend akan berjalan di: **http://localhost:8000**

---

## Arsitektur Docker

```
Docker Network: stunting_network
├── BE docker-compose.yml
│   ├── app       (PHP-FPM Laravel API) — port 9000
│   ├── web       (Nginx)              — port 8001:80
│   └── db        (MySQL 8.0)          — port 33060:3306
│
└── FE docker-compose.yml (menggunakan network yang sama)
    ├── app_fe    (PHP-FPM Laravel FE) — port 9000
    └── web_fe    (Nginx)             — port 8000:80
```

Karena FE dan BE berada di `stunting_network` yang sama, request dari FE ke BE
menggunakan hostname **`web`** (nama service Nginx BE di dalam Docker), sehingga
tidak ada masalah CORS karena request dilakukan server-side (PHP HTTP Client).

---

## Environment Variables Penting

| Variable | Deskripsi | Default |
|----------|-----------|---------|
| `API_BASE_URL` | URL API Backend (dari FE) | `http://web/api` |
| `FRONTEND_URL` | URL Frontend (untuk CORS config BE) | `http://localhost:8000` |
| `DB_HOST` | Host database (di dalam Docker: `db`) | `db` |

---

## Alur Autentikasi

1. User membuka `http://localhost:8000/login` (FE)
2. FE mengirim `POST http://web/api/login` ke BE (server-side)
3. BE memvalidasi dan mengembalikan **Sanctum Bearer Token**
4. FE menyimpan token di **PHP Session**
5. Semua request selanjutnya dari FE ke BE menggunakan token tersebut via `Authorization: Bearer {token}`
