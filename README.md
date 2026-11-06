# SIRKULA Recommerce

Sirkula adalah platform e-commerce fashion sirkular untuk kurasi pakaian tangan pertama, surplus ekspor, dan arsip pilihan. Sistem mengintegrasikan verifikasi fisik pakaian, proses sanitasi medis uap-ozon, dan manajemen inventaris satu item satu stok (*1-of-1*).

---

## Akses Web Langsung (Live Deployment)

Aplikasi aktif dan dapat diuji langsung tanpa instalasi lokal:

- **Tautan Website:** [https://sirkula1.wasmer.app](https://sirkula1.wasmer.app)
- **Infrastruktur Hosting:** Wasmer Edge WebAssembly (Wasm) Runtime Environment

---

## Tumpukan Teknologi Lengkap (Comprehensive Tech Stack)

Berikut rincian seluruh teknologi, paket, dan pustaka yang digunakan dalam proyek ini:

### 1. Backend & Runtime
- **Bahasa Pemrograman:** PHP 8.3.16 (Kebutuhan versi `^8.3` pada `composer.json`)
- **Kerangka Kerja Utama:** Laravel Framework 13.36.0 (Laravel 13)
- **Pola Arsitektur:** Model-View-Controller (MVC) + Service Layer (`App\Services\FirebaseService`) + Custom Artisan Commands (`app/Console/Commands/MigrateToFirebaseCommand.php`)
- **Autentikasi & Scaffolding:** Laravel Breeze 2.4.2 (Stateful Session Auth, Middleware Proteksi Role)
- **Object-Relational Mapping (ORM):** Laravel Eloquent ORM (Relasi `hasMany`, `belongsTo`, Eager Loading, Database Transactions, Agregasi Stok)
- **Interactive REPL:** Laravel Tinker 3.0.2

### 2. Basis Data & Penyimpanan
- **Basis Data Utama (Aktif):** MySQL 8 / MariaDB (Driver PDO MySQL, skema: `nuevaweb`, port: 3306)
- **Basis Data Alternatif:** SQLite 3 (Driver terintegrasi untuk portabilitas lokal)
- **Driver Sesi:** Database Session (`sessions` table)
- **Driver Antrean (Queue):** Database Queue (`jobs`, `failed_jobs` tables)
- **Driver Cache:** Database Cache (`cache`, `cache_locks` tables)
- **Basis Data Dokumen NoSQL:** Google Cloud Firestore (Sinkronisasi koleksi `products`, `categories`, `orders`, `users`, `settings` via REST API)

### 3. Frontend & Antarmuka Pengguna
- **Mesin Template:** Laravel Blade (Komponen modular, partisi view, layout inheritance, proteksi CSRF)
- **Kerangka Kerja Reaktivitas:** Alpine.js 3.17.4 (State management client, drawer geser mobile, mega-menu hover, selektor interaktif varian warna/ukuran, kontrol kuantitas keranjang, sinkronisasi wishlist via `localStorage`)
- **Kerangka Kerja CSS:** Tailwind CSS 3.4.19 (Sistem token desain kustom: Navy, Emerald, Ink, Canvas, Surface)
- **Plugin Tailwind:** `@tailwindcss/forms 0.5.11` (Normalisasi elemen form formulir)
- **Prapemroses CSS:** PostCSS 8.5.30 & Autoprefixer 10.6.1
- **Asset Bundler & HMR:** Vite 8.3.4 dengan `laravel-vite-plugin 3.2.0`
- **Tipografi:** Google Fonts CDN (Inter untuk body/teks label dan Plus Jakarta Sans untuk judul headline)
- **Ikonografi:** Heroicons v2 (Inline SVG berbasis vektor tanpa dependensi font eksternal)
- **Mesin Barcode:** Generator Vektor SVG Code-128 murni (ID numerik 13 digit keanggotaan pelanggan)

### 4. Keamanan & Layanan Pihak Ketiga
- **Autentikasi Google Sign-In:** Integrasi Google Identity Services & Firebase Authentication Web SDK v11
- **Verifikasi Kriptografi Token:** Verifikasi tanda tangan digital Google ID Token (JWT) di sisi backend menggunakan sertifikat publik Google API JWK (`https://www.googleapis.com/robot/v1/metadata/x509/securetoken@system.gserviceaccount.com`)
- **Pengacakan Sandi (Hashing):** Algoritma Bcrypt dengan 12 putaran kerja (*12 rounds*)
- **Mitigasi Serangan Web:** Perlindungan CSRF otomatis, prepared statements anti SQL Injection, XSS sanitization via Blade escaping, serta pembatasan laju request (*rate limiting throttle:6,1*)

### 5. Pengujian & Kualitas Kode
- **Kerangka Kerja Pengujian:** PHPUnit 12.5.39 (45 pengujian otomatis lolos, 126 asersi pengujian)
- **Pustaka Mocking:** Mockery 1.6.15
- **Generator Data Tiruan:** FakerPHP 1.24.1
- **Pemeriksa Standar Kode:** Laravel Pint 1.32.1 (PHP CS Fixer)
- **Pemantau Log Real-time:** Laravel Pail 1.2.7
- **Diagnostik Pengujian CLI:** Laravel Pao 1.1.5 & NunoMaduro Collision 8.9.5

### 6. Infrastruktur & Arsitektur Pengetahuan
- **Hosting Produksi Edge:** Wasmer Edge WebAssembly (Wasm) Runtime Environment
- **Server Web Lokal:** Laragon / Apache 2.4 / Nginx / PHP CLI Server
- **Graf Pengetahuan Repositori:** Graphify Knowledge Graph (`graph.json`, ekstraksi AST simbol kode, dan sinkronisasi Obsidian Vault)

---

## Fitur Fungsional Sistem

### 1. Etalase & Pengalaman Belanja Publik
- **Katalog Terkurasi:** Penelusuran produk berdasarkan kategori pakaian, ukuran (S, M, L, XL), dan urutan harga atau rilisan terbaru.
- **Inventaris 1-of-1:** Indikator stok real-time dengan status kelangkaan (*scarcity badge*) dan pencegahan pembelian ganda.
- **Standar SirkulaCare & SirkulaVerify:** Informasi transparan hasil inspeksi fisik 12 titik serta sertifikasi sanitasi uap 100°C dan gas ozon.
- **Kupon Belanja:** Klaim kupon langsung dari etalase dengan mekanisme salin kode instan ke clipboard.
- **Daftar Keinginan (Wishlist):** Penyimpanan item favorit berbasis peramban lokal (*localStorage*).
- **Optimasi Mobile:** Penyesuaian tata letak hingga resolusi terkecil (Mobile S - 320px) dengan benchmark visual retail Uniqlo ID.

### 2. Transaksi & Checkout
- **Keranjang Belanja:** Kontrol kuantitas otomatis sesuai batas stok fisik varian.
- **Checkout Multi-Metode:** Kalkulasi subtotal, potongan kupon dinamis, dan kalkulator gratis ongkir otomatis.
- **Pembayaran Transfer Bank:** Verifikasi pesanan untuk metode transfer BCA, Mandiri, dan BRI.
- **Pelacakan Pesanan:** Halaman status pesanan publik dan privat untuk memantau progres sanitasi hingga pengiriman ekspedisi.

### 3. Portal Member Pelanggan
- **Kartu Keanggotaan Digital:** Barcode Code-128 berbasis ID numerik unik pelanggan untuk integrasi toko fisik atau kasir.
- **Riwayat Belanja:** Daftar transaksi lengkap beserta rincian varian produk yang dibeli.
- **Dompet Kupon:** Manajemen voucer promo aktif yang siap pakai saat checkout.
- **Pengaturan Akun:** Pembaruan profil, alamat pengiriman, dan pergantian kata sandi.

### 4. Enterprise Admin CMS
- **Manajemen Pesanan & Logistik:** Pembaruan status transaksi bertahap (*Menunggu Pembayaran*, *Diproses Sanitasi*, *Dikirim*, *Selesai*) dengan log audit.
- **Cetak Surat Jalan & Label Pengiriman:** Format siap cetak (*print-slip*) untuk kemasan paket kurir.
- **Katalog & Stok:** Pengaturan produk, foto lookbook depan dan detail, penambahan varian ukuran/warna, serta restock.
- **Kategori & Kupon:** Penambahan kategori baru dan pembuatan kode promo dengan batas minimal belanja.
- **Pengaturan Toko:** Konfigurasi batas gratis ongkir dan pengumuman banner global.

---

## Kebutuhan Sistem Lokal

Pastikan komputer Anda telah terpasang:
- PHP >= 8.3 dengan ekstensi `pdo_mysql`, `pdo_sqlite`, `mbstring`, `openssl`, `curl`
- Composer >= 2.2
- Node.js >= 18.0 & npm >= 9.0
- Server Database: MySQL 8.0+ / MariaDB 10.4+ (atau SQLite 3)

---

## Panduan Instalasi Lokal

Ikuti langkah berikut untuk menjalankan proyek di komputer lokal:

### 1. Salin Repositori
```bash
git clone https://github.com/mxl1qr/Online-Shop.git
cd Online-Shop
```

### 2. Pasang Dependensi PHP & JavaScript
```bash
composer install
npm install
```

### 3. Konfigurasi Lingkungan (.env)
Salin berkas konfigurasi template:
```bash
cp .env.example .env
```

Buat kunci aplikasi:
```bash
php artisan key:generate
```

Konfigurasi basis data pada `.env`:
- **Opsi A (MySQL - Default):** Buat basis data bernama `nuevaweb` di MySQL lokal Anda. Pastikan `DB_CONNECTION=mysql`, `DB_DATABASE=nuevaweb`, `DB_USERNAME=root`, dan `DB_PASSWORD=` sesuai konfigurasi lokal Anda.
- **Opsi B (SQLite):** Ubah `DB_CONNECTION=sqlite`, lalu buat berkas dengan perintah `touch database/database.sqlite`.

Jalankan migrasi tabel dan data awal (seeder):
```bash
php artisan migrate --seed
```

### 4. Tautkan Direktori Penyimpanan Berkas
Buat tautan simbolik untuk folder foto produk:
```bash
php artisan storage:link
```

### 5. Kompilasi Aset Frontend
Jalankan kompilasi aset menggunakan Vite:
```bash
npm run build
```
Untuk mode pengembangan aktif (*hot reload*):
```bash
npm run dev
```

### 6. Jalankan Server Lokal
```bash
php artisan serve
```
Buka peramban dan akses alamat `http://127.0.0.1:8000`.

---

## Akun Pengguna Uji Coba

Gunakan kredensial akun bawaan seeder berikut untuk masuk dan menguji sistem (berlaku di server lokal maupun live demo):

| Peran Akun | Email | Kata Sandi | Hak Akses |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin@toko.com` | `password` | CMS Admin, Pesanan, Stok, Cetak Resi, Konfigurasi |
| **Member Pelanggan** | `budi@gmail.com` | `password` | Katalog, Keranjang, Checkout, Barcode Member, Riwayat |

---

## Pengujian Otomatis

Proyek ini dilengkapi rangkaian unit test dan feature test untuk memvalidasi alur keranjang, kupon, checkout, kalkulasi stok, dan izin akses admin.

Jalankan perintah berikut:
```bash
php artisan test
```

Hasil verifikasi:
```text
Tests:    45 passed (126 assertions)
Duration: ~3.5s
```

---

## Struktur Direktori Utama

```text
├── app/
│   ├── Console/Commands/      # Perintah Artisan kustom (MigrateToFirebaseCommand)
│   ├── Http/Controllers/
│   │   ├── Admin/             # Controller CMS Administrator (Order, Product, Category, Setting, User)
│   │   ├── Auth/              # Controller Autentikasi Breeze & Firebase Google Sign-In
│   │   ├── CartController.php # Manajemen sesi keranjang belanja
│   │   ├── CheckoutController.php # Pemrosesan transaksi dan diskon
│   │   ├── OrderController.php    # Riwayat dan pelacakan pesanan
│   │   └── ProductController.php  # Etalase produk dan wishlist
│   ├── Models/                # Model Eloquent (Product, Variant, Category, Order, OrderItem, User)
│   └── Services/              # Layanan integrasi eksternal (FirebaseService)
├── database/
│   ├── migrations/            # Skema migrasi tabel database relasional
│   └── seeders/               # Data awal pakaian kurasi, kupon promo, dan akun pengguna
├── resources/
│   ├── css/app.css            # Pengaturan direktif Tailwind CSS kustom
│   ├── js/app.js              # Inisialisasi Alpine.js & logika sisi klien
│   └── views/                 # Template antarmuka Blade
│       ├── admin/             # Panel kelola CMS Administrator
│       ├── auth/              # Formulir autentikasi masuk dan daftar
│       ├── cart/              # Tampilan keranjang belanja
│       ├── checkout/          # Formulir alamat, kalkulasi ongkir, dan pembayaran
│       ├── dashboard.blade.php# Portal member & barcode keanggotaan
│       ├── partials/          # Komponen modular (logo, mega-menu, drawer mobile)
│       └── products/          # Etalase katalog (PLP) dan detail pakaian (PDP)
├── routes/
│   ├── auth.php               # Rute autentikasi Breeze & Firebase callback
│   └── web.php                # Rute publik belanja, member, dan CMS admin
└── tests/                     # Berkas pengujian otomatis PHPUnit Feature & Unit
```

---

## Lisensi

Proyek ini dikembangkan di bawah lisensi terbuka [MIT License](LICENSE).
