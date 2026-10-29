# Perumda BPR Majalengka (Bank Majalengka) - Web Portal & Admin Management System

Aplikasi Web Resmi & Sistem Manajemen Dinamis **Perumda BPR Majalengka** berbasis **Laravel 11**, arsitektur database relasional **MySQL** (kompatibel penuh dengan **SQLite** untuk testing lokal), dan antarmuka korporat modern (BCA style) yang responsif dan interaktif.

Aplikasi ini mengintegrasikan seluruh data profil perusahaan secara dinamis:
- **24 Katalog Produk Perbankan**: 10 Tabungan, 2 Deposito Berjangka LPS, 12 Skema Kredit UMKM & Konsumer.
- **12 Jaringan Kantor Kas**: Tersebar di seluruh kecamatan Kabupaten Majalengka lengkap dengan alamat dan kontak operasional.
- **Literasi Keuangan & Berita**: Edukasi finansial, panduan usaha UMKM, dan himbauan keamanan `#AwasModus`.
- **Laporan Publikasi & Tata Kelola (GCG)**: Publikasi transparansi keuangan triwulanan dan kepatuhan regulasi OJK & LPS.
- **Formulir Pengajuan Kredit Online**: Terhubung langsung ke database backend dan panel verifikasi admin.
- **Kalkulator Simulasi Kredit Interaktif**: Menghitung angsuran pokok dan bunga secara presisi sesuai sketsa resmi.
- **Dashboard Admin Terpadu**: CRUD seluruh data, manajemen status pengajuan pinjaman, dan konfigurasi identitas BPR.

---

## ⚡ Prasyarat Sistem & Panduan Setup Native (NO DOCKER)

Aplikasi dirancang untuk berjalan **secara native dan sangat mudah disetup** tanpa Docker pada lingkungan Windows/Linux/macOS dengan **Laragon**, **XAMPP**, atau **Native PHP 8.2+**.

### 1. Kebutuhan Sistem
- **PHP**: Versi `>= 8.2` (PHP 8.2 atau 8.3 direkomendasikan)
- **Ekstensi PHP**: `pdo`, `pdo_mysql` (atau `pdo_sqlite`), `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`
- **Database**: MySQL 8.x / MariaDB 10.x atau SQLite 3
- **Composer**: Versi `2.x`
- **Node.js**: Opsional (hanya jika ingin menjalankan suite pengujian static `verify.js` atau build Tailwind)

---

### 2. Langkah Instalasi Cepat (Native Setup)

#### Langkah 1: Clone / Buka Direktori Proyek
```bash
cd "Bank Majalengka"
```

#### Langkah 2: Install Dependensi PHP via Composer
```bash
composer install
```

#### Langkah 3: Konfigurasi File Environment (`.env`)
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Lalu generate application encryption key:
```bash
php artisan key:generate
```

#### Langkah 4: Konfigurasi Database
Buka file `.env` di teks editor favorit Anda:

**Opsi A: Menggunakan MySQL (Laragon / XAMPP - Default)**
1. Pastikan servis MySQL di Laragon / XAMPP sudah berjalan di port 3306.
2. Buat database baru bernama `bank_majalengka` melalui HeidiSQL / phpMyAdmin.
3. Sesuaikan baris koneksi pada `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=bank_majalengka
   DB_USERNAME=root
   DB_PASSWORD=
   ```

**Opsi B: Menggunakan SQLite (Sangat Praktis tanpa Database Server)**
Jika tidak ingin menginstal/menyalakan MySQL server:
```bash
touch database/database.sqlite
```
Lalu pada `.env`:
```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

#### Langkah 5: Jalankan Migrasi & Database Seeder
Jalankan perintah berikut untuk membuat seluruh tabel dan menyemai (seed) data awal (24 produk, 12 kantor kas, artikel, laporan publikasi, akun admin, serta pengaturan profil):
```bash
php artisan migrate --seed
```

#### Langkah 6: Hubungkan Storage Simbolis (Opsional)
```bash
php artisan storage:link
```

#### Langkah 7: Jalankan Aplikasi
```bash
php artisan serve
```
Akses aplikasi melalui browser:
- **Halaman Utama (Landing Page)**: [http://localhost:8000](http://localhost:8000)
- **Portal Admin**: [http://localhost:8000/admin/login](http://localhost:8000/admin/login)

---

## 🔑 Kredensial Login Admin

Setelah database di-seed, akun administrator default yang dapat langsung digunakan adalah:
- **URL Login**: `http://localhost:8000/admin/login`
- **Email**: `admin@bankmajalengka.co.id`
- **Password**: `password`
- **Hak Akses**: Administrator Penuh

---

## 🖥️ Fitur-Fitur Panel Dashboard Admin

Panel admin dirancang dengan antarmuka yang bersih, responsif, dan mudah digunakan:
1. **Ringkasan Statistik (Dashboard Overview)**
   - Total produk aktif, cabang kantor kas, artikel terbit, dan laporan publikasi.
   - Status antrean pengajuan kredit (Pending, Follow Up, Disetujui, Ditolak) beserta total nominal pinjaman masuk.
   - Tabel 5 pengajuan pinjaman terbaru dengan shortcut langsung ke WhatsApp pemohon.
2. **Manajemen Produk (CRUD Produk)**
   - Tambah, edit, dan hapus katalog produk Tabungan, Deposito, maupun Kredit.
   - Form input dinamis untuk badge produk, benefit, dan berkas persyaratan (disimpan terstruktur dalam format JSON).
3. **Manajemen Kantor Kas (CRUD Cabang)**
   - Pengelolaan 12 lokasi kantor kas di berbagai kecamatan Majalengka.
   - Input nama cabang, alamat lengkap, nomor telepon, dan jam operasional.
4. **Manajemen Edukasi & Artikel (CRUD Artikel)**
   - Publikasi berita dan literasi perbankan untuk nasabah.
   - Pengaturan kategori, ringkasan (excerpt), isi konten lengkap, dan status publikasi.
5. **Laporan Publikasi & GCG (CRUD Dokumen)**
   - Pengelolaan berkas laporan keuangan triwulanan, laporan GCG tahunan, dan audit KAP independen.
6. **Manajemen Pengajuan Pinjaman (Loan Application Inbox)**
   - Melihat detail lengkap pengajuan: NIK 16 digit, Nama, No. WhatsApp, Pekerjaan, Nominal Pinjaman, Tenor, Keterangan Agunan.
   - Pengubahan status pengajuan: `pending` ➔ `contacted` ➔ `approved` / `rejected`.
   - Tombol shortcut untuk langsung menghubungi pemohon via WhatsApp resmi.
7. **Pengaturan BPR (Corporate Settings)**
   - Mengubah informasi identitas bank, slogan, alamat kantor pusat operasional, nomor telepon, WhatsApp, email resmi, teks jaminan LPS, serta disclaimer pengawasan OJK tanpa perlu menyentuh kode.

---

## 🌐 Dokumentasi REST API Publik

Backend menyediakan endpoint API publik dalam format JSON yang bersih:

| Metode | Endpoint | Keterangan |
| :--- | :--- | :--- |
| `GET` | `/api/products` | Mendapatkan semua produk (bisa difilter `?category=tabungan\|deposito\|kredit`) |
| `GET` | `/api/products/{slug}` | Detail produk perbankan berdasarkan slug |
| `GET` | `/api/branches` | Daftar seluruh 12 jaringan kantor kas |
| `GET` | `/api/articles` | Daftar artikel literasi keuangan |
| `GET` | `/api/articles/{slug}` | Detail artikel lengkap |
| `GET` | `/api/publication-reports` | Daftar laporan keuangan publikasi dan GCG |
| `GET` | `/api/settings` | Pengaturan informasi profil & kontak resmi BPR |
| `GET` | `/api/search?q={keyword}` | Pencarian global produk, artikel, dan kantor kas |
| `POST` | `/api/pengajuan-kredit` | Pengiriman pengajuan pinjaman online dari website |

---

## 🧪 Pengujian Otomatis & Verifikasi

Proyek ini telah dilengkapi dengan rangkaian pengujian berlapis:

### 1. Backend PHPUnit Feature & Unit Tests (27 Tests, 90 Assertions)
Jalankan seluruh pengujian backend Laravel:
```bash
php artisan test
```
Cakupan test meliputi:
- `AdminAuthTest`: Alur login admin, validasi email/password, proteksi session, logout, dan redirection tamu.
- `AdminCrudTest`: CRUD produk, kantor kas, artikel, update status pengajuan pinjaman, dan update pengaturan instansi.
- `PublicApiTest`: Integritas data API publik (produk, kategori, kantor kas, artikel, laporan publikasi, dan submit pengajuan kredit).
- `HomeLandingTest`: Uji respon halaman utama, integrasi dinamis data katalog, cabang, artikel, laporan, dan pengaturan.

### 2. Frontend Comprehensive Verification Suite (106 Checks)
Jalankan pengujian integritas frontend & visual schema:
```bash
node test/verify.js
```
Hasil: **106 passed, 0 failed**.

---

## 📁 Struktur Direktori Utama

```
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/             # Controller Admin Dashboard (Auth, Produk, Cabang, Artikel, Laporan, Kredit, Setting)
│   │   ├── Api/               # PublicApiController (REST API JSON)
│   │   └── HomeController.php # Landing page controller
│   └── Models/                # Product, Branch, Article, PublicationReport, LoanApplication, Setting, User
├── database/
│   ├── migrations/            # 7 berkas migrasi database
│   └── seeders/               # DatabaseSeeder, ProductSeeder, BranchSeeder, ArticleSeeder, dll.
├── resources/
│   └── views/
│       ├── admin/             # Blade template panel admin (auth, dashboard, products, branches, articles, dll.)
│       └── landing.blade.php  # Template responsif portal utama Bank Majalengka
├── routes/
│   ├── web.php                # Rute web landing page & admin dashboard
│   └── api.php                # Rute API publik
├── test/
│   └── verify.js              # Comprehensive frontend verification suite
└── tests/
    └── Feature/               # PHPUnit feature test suite
```

---

## 🏛️ Kepatuhan & Regulasi
Perumda BPR Majalengka berizin dan diawasi oleh **Otoritas Jasa Keuangan (OJK)** dan Bank Indonesia, serta merupakan peserta penjaminan **Lembaga Penjamin Simpanan (LPS)** hingga Rp 2.000.000.000,- (Dua Miliar Rupiah) per nasabah.
