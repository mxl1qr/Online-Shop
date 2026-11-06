# SIRKULA Recommerce

Sirkula adalah platform e-commerce fashion sirkular untuk kurasi pakaian tangan pertama, surplus ekspor, dan arsip pilihan. Sistem mengintegrasikan verifikasi fisik pakaian, proses sanitasi medis uap-ozon, dan manajemen inventaris satu item satu stok (*1-of-1*).

Proyek ini dibangun menggunakan Laravel 12, Tailwind CSS, Alpine.js, dan pengujian otomatis PHPUnit.

---

## Akses Web Langsung (Live Demo)

Aplikasi telah dipublikasikan dan dapat diakses langsung tanpa instalasi lokal:

- **Tautan Website:** [https://sirkula1.wasmer.app](https://sirkula1.wasmer.app)
- **Status Server:** Aktif (Wasmer Edge WebAssembly Environment)

---

## Fitur Utama

### 1. Etalase & Pengalaman Belanja Publik
- **Katalog Terkurasi:** Penelusuran produk berdasarkan kategori pakaian, ukuran (S, M, L, XL), dan urutan harga atau rilisan terbaru.
- **Inventaris 1-of-1:** Indikator stok real-time dengan status kelangkaan (*scarcity badge*) dan pencegahan pembelian ganda.
- **Standar SirkulaCare & SirkulaVerify:** Informasi transparan hasil inspeksi fisik 12 titik serta sertifikasi sanitasi uap 100°C dan gas ozon.
- **Kupon Belanja:** Klaim kupon langsung dari etalase dengan mekanisme salin kode instan ke clipboard.
- **Daftar Keinginan (Wishlist):** Penyimpanan item favorit berbasis peramban lokal (*localStorage*).
- **Optimasi Mobile Ekstrem:** Penyesuaian layout hingga viewport terkecil (Mobile S - 320px) dengan benchmark visual retail global.

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

## Tumpukan Teknologi (Tech Stack)

- **Backend:** PHP 8.2+, Laravel 12 (Breeze, Eloquent ORM)
- **Frontend:** Blade Templates, Tailwind CSS 4, Alpine.js 3
- **Asset Bundler:** Vite 6
- **Autentikasi:** Laravel Session Auth & Integrasi Google Sign-In (Firebase SDK)
- **Database:** SQLite (lingkungan pengembangan) / MySQL (lingkungan produksi)
- **Pengujian:** PHPUnit (45 tes otomatis, 126 asersi)

---

## Kebutuhan Sistem

Pastikan komputer Anda telah terpasang:
- PHP >= 8.2 dengan ekstensi `pdo`, `mbstring`, `openssl`, `curl`
- Composer >= 2.0
- Node.js >= 18.0 & npm

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

Secara default, aplikasi menggunakan SQLite. Buat berkas database lokal jika belum ada:
```bash
touch database/database.sqlite
```

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
Untuk mode pengembangan aktif:
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
│   ├── Http/Controllers/
│   │   ├── Admin/             # Controller CMS Administrator (Order, Product, Category, Setting)
│   │   ├── CartController.php # Manajemen sesi keranjang belanja
│   │   ├── CheckoutController.php # Pemrosesan transaksi dan diskon
│   │   ├── OrderController.php    # Riwayat dan pelacakan pesanan
│   │   └── ProductController.php  # Etalase produk dan wishlist
│   └── Models/                # Model Eloquent (Product, Variant, Category, Order, OrderItem)
├── database/
│   ├── migrations/            # Skema tabel database relasional
│   └── seeders/               # Data awal pakaian kurasi, kupon, dan akun
├── resources/
│   ├── css/app.css            # Desain kustom Tailwind CSS
│   ├── js/app.js              # Inisialisasi Alpine.js & logika klien
│   └── views/                 # Blade template antarmuka pengguna
│       ├── admin/             # Panel kelola CMS
│       ├── auth/              # Halaman masuk dan daftar
│       ├── cart/              # Keranjang belanja
│       ├── checkout/          # Form alamat dan pembayaran
│       ├── dashboard.blade.php# Portal member & barcode pelanggan
│       └── products/          # Etalase PLP dan detail produk PDP
├── routes/
│   └── web.php                # Definisi rute publik, member, dan admin
└── tests/                     # Berkas pengujian otomatis fitur
```

---

## Lisensi

Proyek ini dikembangkan di bawah lisensi terbuka [MIT License](LICENSE).
