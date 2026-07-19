# Dokumentasi Testing API via Postman (Lengkap + Penjelasan)

Dokumen ini berisi panduan, penjelasan fungsi, dan format *payload* (body) untuk melakukan testing seluruh API secara lokal menggunakan Postman di lingkungan **Docker**.

> **Base URL:** `http://localhost:8001/api`
> **Header Wajib:** `Accept: application/json`

---

## 🟢 1. Public Routes (Tanpa Token)

### Register Orangtua
- **Fungsi:** Mendaftarkan akun baru ke dalam sistem. Secara default, akun yang mendaftar melalui rute ini akan diberikan role `orangtua`.
- **Endpoint:** `POST /register`
- **Body:**
```json
{
  "nama_lengkap": "Budi Santoso",
  "username": "budisantoso",
  "password": "password123",
  "password_confirmation": "password123"
}
```

### Login
- **Fungsi:** Melakukan autentikasi dengan username dan password untuk mendapatkan **Bearer Token**. Token ini bagaikan "kunci" yang wajib digunakan untuk mengakses rute yang terproteksi (Protected Routes).
- **Endpoint:** `POST /login`
- **Body:**
```json
{
  "username": "budisantoso",
  "password": "password123"
}
```

### Master Data Tahapan Perkembangan
- **Fungsi:** Mengambil master data (daftar lengkap) tahapan perkembangan anak berdasarkan bulan. Biasanya digunakan oleh Frontend untuk menampilkan pilihan dropdown di form.
- **Endpoint:** `GET /tahapan-master`

---

## 🟡 2. Auth & User Profile (Butuh Token)

### Get Current User
- **Fungsi:** Mengambil detail data profil akun (orangtua/admin) yang saat ini sedang login.
- **Endpoint:** `GET /me`

### Logout
- **Fungsi:** Menghapus token akses dari database agar user keluar dengan aman (sesi berakhir).
- **Endpoint:** `POST /logout`

---

## 🔵 3. Manajemen Data Anak (Orangtua)
*Catatan: Ganti `{child}` dengan ID anak, misal `1`. Sistem otomatis akan mencocokkan anak dengan akun orang tua yang sedang login.*

### Get All Children
- **Fungsi:** Menampilkan daftar seluruh anak yang sudah didaftarkan oleh akun orang tua saat ini.
- **Endpoint:** `GET /children`

### Tambah Anak
- **Fungsi:** Mendaftarkan profil anak baru ke dalam akun orang tua.
- **Endpoint:** `POST /children`
- **Body:**
```json
{
  "nama_lengkap_anak": "Budi Junior",
  "tanggal_lahir": "2023-05-15",
  "nik_anak": "1234567890123456"
}
```

### Get Detail Anak
- **Fungsi:** Menampilkan profil lengkap untuk satu anak tertentu berdasarkan ID.
- **Endpoint:** `GET /children/{child}`

### Update Anak
- **Fungsi:** Mengubah atau memperbarui data profil anak (seperti nama, NIK, atau tanggal lahir).
- **Endpoint:** `PUT /children/{child}`
- **Body:**
```json
{
  "nama_lengkap_anak": "Budi Junior Updated",
  "tanggal_lahir": "2023-05-16"
}
```

### Delete Anak
- **Fungsi:** Menghapus profil anak dari sistem beserta seluruh riwayat terkait (deteksi, BMI, perkembangan).
- **Endpoint:** `DELETE /children/{child}`

---

## 🟠 4. Deteksi Stunting (Per Anak)

### Get Riwayat Deteksi
- **Fungsi:** Menampilkan seluruh riwayat pengecekan stunting yang pernah dilakukan untuk anak tersebut.
- **Endpoint:** `GET /children/{child}/detections`

### Tambah Deteksi Baru (Cek Stunting)
- **Fungsi:** Memasukkan data tinggi, berat badan, dan umur anak. Sistem backend akan **otomatis menghitung nilai Z-Score** berdasarkan tabel standar WHO (di folder storage) dan menentukan status anak apakah 'Normal' atau 'Stunting'.
- **Endpoint:** `POST /children/{child}/detections`
- **Body:**
```json
{
  "umur": 12,
  "jenis_kelamin": "L",
  "berat_badan": 8.5,
  "tinggi_badan": 75.0
}
```
*(Catatan: `jenis_kelamin` hanya menerima `L` atau `P`)*

### Hapus Riwayat Deteksi
- **Fungsi:** Menghapus salah satu data hasil pengecekan stunting anak di masa lalu.
- **Endpoint:** `DELETE /children/{child}/detections/{id}`

---

## 🟣 5. Tahapan Perkembangan (Per Anak)

### Get Progress Perkembangan
- **Fungsi:** Menampilkan daftar tahapan perkembangan (milestone) dan status apakah anak tersebut sudah berhasil mencapainya atau belum.
- **Endpoint:** `GET /children/{child}/perkembangan`
- **Query Params (Opsional):** `?kategori=Motorik` (Untuk memfilter berdasarkan kategori tertentu).

### Input Pencapaian Baru
- **Fungsi:** Mencatat kapan (tanggal) anak berhasil melakukan suatu milestone perkembangan, beserta catatan opsional dari orang tua.
- **Endpoint:** `POST /children/{child}/perkembangan`
- **Body:**
```json
{
  "tahapan_perkembangan_id": 1,
  "tanggal_pencapaian": "2024-06-25",
  "catatan": "Anak sudah bisa tengkurap sendiri dengan lancar"
}
```

### Update Pencapaian
- **Fungsi:** Mengubah tanggal pencapaian atau catatan untuk suatu milestone yang sudah pernah diinput sebelumnya.
- **Endpoint:** `PUT /children/{child}/perkembangan/{id}`
- **Body:**
```json
{
  "tanggal_pencapaian": "2024-06-26",
  "catatan": "Update catatan baru"
}
```

### Hapus Pencapaian
- **Fungsi:** Membatalkan/menghapus catatan pencapaian, sehingga status milestone kembali menjadi 'Belum Dicapai'.
- **Endpoint:** `DELETE /children/{child}/perkembangan/{id}`

---

## 🟤 6. Kalkulasi BMI (Per Anak)

### Get Riwayat BMI
- **Fungsi:** Menampilkan riwayat perhitungan Body Mass Index (BMI) dari waktu ke waktu. Endpoint ini juga otomatis menghitung **estimasi dan saran kebutuhan kalori harian** anak berdasarkan data BMI terakhirnya.
- **Endpoint:** `GET /children/{child}/bmi`

### Hitung & Tambah BMI
- **Fungsi:** Menghitung skor BMI dan status gizi anak (Underweight, Normal, Overweight, Obese) berdasarkan input tinggi, berat, usia, dan tingkat aktivitas fisik, lalu menyimpannya sebagai riwayat.
- **Endpoint:** `POST /children/{child}/bmi`
- **Body:**
```json
{
  "berat": 10.5,
  "tinggi": 80,
  "usia": 2,
  "gender": "pria",
  "activity_level": "moderately_active"
}
```
*(Catatan: `gender` menerima `pria`/`wanita`. `activity_level` menerima: `sedentary`, `lightly_active`, `moderately_active`, `very_active`, `extra_active`)*

### Hapus Riwayat BMI
- **Fungsi:** Menghapus salah satu data catatan BMI.
- **Endpoint:** `DELETE /children/{child}/bmi/{id}`

---

## 🟢 7. Dashboard & Edukasi (Orangtua)

### Get Dashboard Orangtua
- **Fungsi:** Endpoint utama untuk halaman beranda Frontend. Otomatis menampilkan rekomendasi menu nutrisi (pagi, siang, malam, snack) yang diputar berdasarkan jadwal/hari, serta daftar artikel edukasi terbaru.
- **Endpoint:** `GET /dashboard/orangtua`

### Artikel & Nutrisi (Read Only)
- **Fungsi:** Menampilkan daftar lengkap atau detail spesifik dari edukasi nutrisi dan artikel yang dibuat oleh admin.
- `GET /nutrition`
- `GET /nutrition/{id}`
- `GET /artikel`
- `GET /artikel/{id}`

---

## 🔴 8. Admin Routes (Khusus Akun Role Admin)

**Prefix URL:** `http://localhost:8001/api/admin/...`

- **Fungsi Umum:** Rute ini digunakan untuk Dashboard Panel Admin yang bisa memantau keseluruhan data dari seluruh pengguna aplikasi.

- **Dashboard:** `GET /admin/dashboard` (Statistik keseluruhan)
- **Semua Data Anak:** `GET /admin/children` (Seluruh anak dari semua user)
- **Semua Data Deteksi:** `GET /admin/detections` (Semua riwayat stunting)

### Admin Tambah Deteksi Stunting
- **Fungsi:** Memungkinkan admin (misal petugas puskesmas) untuk menginputkan data deteksi stunting langsung ke anak tertentu berdasarkan `child_id`.
- **Endpoint:** `POST /admin/detections`
- **Body:**
```json
{
  "child_id": 1,
  "umur": 24,
  "jenis_kelamin": "P",
  "berat_badan": 10.0,
  "tinggi_badan": 85.0
}
```

### Admin Lihat & Input Perkembangan Anak Tertentu
- **Fungsi:** Memungkinkan admin melihat dan menginputkan milestone secara paksa/bantuan untuk anak dari user mana pun.
- **Lihat Progress Anak:** `GET /admin/children/{child}/perkembangan`
- **Input Progress:** `POST /admin/children/{child}/perkembangan`
*(Body sama dengan orangtua)*

### Manajemen Artikel & Nutrisi (Resource)
- **Fungsi:** API penuh (Create, Read, Update, Delete) untuk Admin dalam mengelola daftar Artikel Edukasi, Kategori Artikel, dan Master Data Rekomendasi Nutrisi.
- Admin dapat mengakses endpoint `GET`, `POST`, `GET {id}`, `PUT {id}`, `DELETE {id}` untuk:
  - `/admin/nutrition`
  - `/admin/artikel`
  - `/admin/kategori`
