# SIRKULA — Langkah Eksekusi Enhancement UI/UX

Dokumen ini dibuat untuk dieksekusi berurutan. Kerjakan **satu fase per sesi**, centang `[ ]` saat selesai. Setiap fase punya estimasi waktu dan cek "selesai kalau".

**Total estimasi:** sekitar 10–12 jam kerja (bisa dipecah 9 sesi kecil).
**Aturan emas:** jangan lompat fase. Fase 1–3 adalah fondasi; fase 4–7 tinggal memanen hasilnya.

---

## 0. Hasil Audit (kenapa tampilan terasa tidak proporsional)

Angka ini diukur langsung dari `resources/views` (graphify + grep):

| Temuan | Angka | Dampak |
| :--- | :--- | :--- |
| Warna hex hardcode `[#xxxxxx]` | ±1.400 kemunculan, `#002547` saja 433× | Ganti warna = edit puluhan file |
| `text-xs` (12px) dipakai sebagai ukuran badan teks | 508 baris vs `text-sm` 98 baris, `text-base` 25 baris | Teks kekecilan, mata cepat lelah. `DESIGN.md` sendiri menetapkan body 16px |
| `uppercase` + tracking lebar | 262 baris | Label berteriak, hirarki visual datar |
| Blok `<style>` font + link Google Fonts duplikat | 11 file, masing-masing menyalin | Font di-request berulang, sulit diubah |
| `tailwind.config.js` | Hanya `Figtree`, tanpa token warna/ukuran | Design system `Sirkula Material/DESIGN.md` tidak terpakai di kode |
| `focus-visible` | 0 kemunculan; `focus:ring-0` 22× | Pengguna keyboard kehilangan indikator fokus |
| `<img>` tanpa `alt` | 17 | Gagal aksesibilitas |
| `loading="lazy"` | 2 | Halaman katalog berat |
| `prefers-reduced-motion` | 1 | Animasi tidak menghormati preferensi pengguna |
| Radius campur | `rounded-lg` 162, `xl` 87, `md` 61, `2xl` 48 | Sudut tidak konsisten, `DESIGN.md` minta 1rem/2rem |
| Warna teks redup `#73777f` | 132× | Kontras ±4.5:1 di putih (batas), gagal di latar `#f1f4f3` |
| File terbesar | `admin/orders/index.blade.php` 1.179 baris | Susah dirawat, padat, semua tab dalam satu file |

**Akar masalahnya satu:** design system sudah ditulis, tetapi tidak pernah dijadikan token di Tailwind. Maka perbaikan terbaik adalah **jadikan token dulu, baru rapikan halaman**.

---

## Prinsip Proporsional (acuan semua fase)

Tetapkan ini sekali, pakai di mana-mana.

1. **Grid 8px.** Jarak hanya 4, 8, 12, 16, 24, 32, 48.
2. **Skala teks (storefront):** badan 15–16px, sekunder 14px, meta/badge 12px, judul halaman 28–32px, judul section 20–24px.
3. **Skala teks (admin):** badan 14px, tabel 14px, meta 12px. Admin boleh lebih padat, tapi tidak di bawah 14px untuk data yang dibaca.
4. **Radius:** kontrol (input, tombol) `rounded-xl`, kartu `rounded-2xl`, chip/badge `rounded-full`. Hapus `rounded-md` dan `rounded-sm` dari komponen utama.
5. **Label uppercase hanya untuk:** eyebrow section dan badge status. Bukan untuk tombol biasa, nama menu, atau paragraf.
6. **Kontras:** teks badan minimal 4.5:1. Teks redup pakai `#5f636b` (bukan `#73777f`).
7. **Satu aksi utama per layar** (tombol navy penuh). Sisanya outline atau teks.
8. **Lebar baca:** paragraf maksimal `max-w-prose` (±65 karakter).

---

## Fase 1 — Token Design System di Tailwind (Selesai ✓)

Ini fase terpenting. Tanpa ini fase lain jadi kerja ganda.

**File:** `tailwind.config.js`, `resources/css/app.css`

1. [x] Jalankan `npm run build`. Pastikan lolos dulu.
2. [x] Di `tailwind.config.js`, isi `theme.extend`:
   - `colors`: `navy` (`#002547`, 600 `#000f23`), `emerald` (`#006c47`, soft `#85f9bf`), `ink` (`#181c1c`), `muted` (`#5f636b`), `line` (`#e0e3e2`), `line-strong` (`#c3c6cf`), `canvas` (`#f4f6f5`), `surface` (`#f7faf9`), `surface-low` (`#f1f4f3`), `danger` (`#ba1a1a`), `gold` (`#c9a840`).
   - `fontFamily`: `headline: ['Plus Jakarta Sans', ...]`, `sans/body: ['Inter', ...]`.
   - `fontSize`: `meta: ['12px','16px']`, `small: ['14px','20px']`, `body: ['16px','24px']`.
   - `boxShadow`: `card: '0 1px 2px rgba(0,37,71,.04), 0 4px 16px rgba(0,37,71,.06)'`, `float: '0 12px 40px rgba(0,37,71,.16)'`.
3. [x] Di `resources/css/app.css`, tambahkan `@layer base` dan `@layer components`:
   - base: `:focus-visible { outline: 2px solid #006c47; outline-offset: 2px; }`, `[x-cloak]{display:none!important}`, `prefers-reduced-motion`.
   - components: `.btn`, `.btn-primary`, `.btn-outline`, `.btn-ghost`, `.input`, `.card`, `.badge`, `.eyebrow`, `.table-admin`.
4. [x] Buat partial `resources/views/partials/head-fonts.blade.php`.
5. [x] Ganti 11 blok Google Fonts dengan `@include('partials.head-fonts')`.

**Selesai kalau:** `npm run build` hijau, `grep -r "fonts.googleapis" resources/views` hanya muncul di `head-fonts.blade.php`, halaman `/login` terlihat sama seperti sebelumnya.

---

## Fase 2 — Migrasi Warna Hex ke Token (±1 jam, sebagian otomatis)

Tujuan: hilangkan ±1.400 hex hardcode. Pakai find-and-replace per file, **satu file satu commit**.

| Lama | Baru |
| :--- | :--- |
| `[#002547]` | `navy` |
| `[#000f23]` | `navy-600` |
| `[#006c47]` | `emerald` |
| `[#85f9bf]` | `emerald-soft` |
| `[#181c1c]` | `ink` |
| `[#73777f]` | `muted` (otomatis jadi `#5f636b`, kontras naik) |
| `[#43474e]` | `ink/80` |
| `[#e0e3e2]` | `line` |
| `[#dadada]` | `line` |
| `[#c3c6cf]` | `line-strong` |
| `[#f1f4f3]` | `surface-low` |
| `[#f7faf9]` | `surface` |
| `[#f4f6f5]` | `canvas` |

Contoh: `bg-[#002547]` → `bg-navy`, `text-[#73777f]` → `text-muted`, `border-[#e0e3e2]` → `border-line`, `bg-[#85f9bf]/30` → `bg-emerald-soft/30`.

1. [ ] Buat skrip sekali pakai `scripts/migrate-colors.ps1` yang menjalankan tabel di atas dengan regex `(bg|text|border|ring|from|to|via|divide|placeholder)-\[#hex\]` → `$1-token`. Jalankan dulu pada `auth/` (file kecil) untuk uji.
2. [ ] Jalankan berurutan: `auth/*`, `layouts/*`, `partials/*`, `products/*`, `cart/*`, `checkout/*`, `orders/*`, `wishlist/*`, `dashboard.blade.php`, `welcome.blade.php`, terakhir `admin/orders/index.blade.php`.
3. [ ] Setelah tiap kelompok: `npm run build`, buka halamannya, cek tidak ada warna yang berubah.
4. [ ] Hex sisa yang unik (misal `#3e3e3a`, `#dadada` khusus Uniqlo-style) biarkan dulu, catat di bagian "Sisa" bawah file ini.

**Selesai kalau:** `grep -rE "\[#[0-9a-fA-F]{6}\]" resources/views` turun di bawah 100 kemunculan dan tampilan identik secara visual.

---

## Fase 3 — Tipografi & Skala Ukuran (±1,5 jam)

Tujuan: teks nyaman dibaca tanpa membuat layout meledak.

1. [ ] **Aturan konversi** (cek manual per halaman, jangan buta-ganti):
   - Paragraf, deskripsi, isi tabel, label form: `text-xs` → `text-sm` (14px).
   - Isi kartu produk (nama, harga): nama `text-sm font-medium`, harga `text-base font-semibold`.
   - Tetap `text-xs`: badge, timestamp, helper text, hitungan kecil.
   - Hapus semua `text-[10px]` dan `text-[11px]` (12 + 3 kemunculan). Minimal 12px.
2. [ ] **Kurangi uppercase.** Biarkan uppercase hanya pada `.eyebrow` dan `.badge`. Hapus `uppercase tracking-wider` dari tombol dan item menu; ganti `font-semibold` biasa. Targetnya 262 → di bawah 80 baris.
3. [ ] **Hirarki judul seragam:**
   - `h1`: `font-headline text-3xl font-extrabold tracking-tight` (storefront), `text-2xl` (admin).
   - `h2`: `font-headline text-xl font-bold`.
   - `h3`: `font-headline text-base font-bold`.
4. [ ] **Line-height:** badan `leading-relaxed` (1.625) untuk paragraf panjang, `leading-snug` untuk judul.
5. [ ] **Lebar baca:** tambahkan `max-w-prose` pada deskripsi produk dan teks kebijakan.

**Selesai kalau:** halaman `/` dan `/products/{slug}` terbaca nyaman dari jarak 60 cm di layar laptop tanpa zoom; tidak ada teks di bawah 12px.

---

## Fase 4 — Komponen Seragam: Tombol, Input, Kartu, Badge (±1,5 jam)

Pakai class dari Fase 1, hapus rangkaian utility panjang yang berulang.

1. [ ] **Tombol.** Ganti pola `h-12 rounded-lg bg-navy text-white …` jadi `btn btn-primary`. Sekunder `btn btn-outline`. Hanya satu `btn-primary` per area.
2. [ ] **Input.** Semua `<input>`/`<select>`/`<textarea>` memakai `.input`: latar `surface-low`, border `line-strong`, `focus:border-emerald focus:ring-2 focus:ring-emerald/20`. **Hapus `focus:ring-0` (22 tempat)**; itu yang mematikan indikator fokus.
3. [ ] **Kartu.** `.card` = `bg-white border border-line rounded-2xl shadow-card`. Hapus bayangan berat selain pada modal (`shadow-float`).
4. [ ] **Badge status** (satu sumber kebenaran, dipakai storefront & admin): `pending` kuning, `processing` hijau muda, `completed` navy solid, `cancelled` merah muda. Buat Blade component `components/status-badge.blade.php` (`<x-status-badge :status="$order->status" />`). Ganti tiga blok `@if($order->status===…)` di `admin/orders/index`, `orders/index`, `checkout/success`.
5. [ ] **Empty state seragam:** ikon, satu kalimat, satu tombol aksi. Terapkan di keranjang kosong, wishlist kosong, pesanan kosong, filter admin kosong.
6. [ ] **Skeleton/placeholder gambar:** kotak `bg-surface-low` dengan `aspect-[3/4]` agar layout tidak melompat saat gambar memuat.

**Selesai kalau:** `grep -c "focus:ring-0" -r resources/views` = 0, dan tombol utama terlihat identik di login, keranjang, checkout, dan admin.

---

## Fase 5 — Halaman Global / Storefront (±2 jam)

**File:** `products/index`, `products/show`, `cart/index`, `checkout/index`, `checkout/success`, `wishlist/index`, `partials/mega-menu`, `partials/mobile-drawer`, `welcome`.

1. [ ] **Header (`products/index`).** Tinggi 64px tetap. Jarak ikon seragam 8px, area tap minimal 40×40. Nama pengguna dipotong `truncate`. Tombol Keluar pindah ke dropdown akun agar header tidak sesak di layar 1024–1280px.
2. [ ] **Grid produk.** `grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-x-4 gap-y-8`. Rasio gambar tetap `aspect-[3/4]`, `object-cover`, `loading="lazy"` kecuali 4 gambar pertama (`fetchpriority="high"`).
3. [ ] **Kartu produk.** Urutan visual: gambar → kategori (eyebrow kecil) → nama (`text-sm`, 2 baris maks `line-clamp-2`) → harga (`text-base font-semibold`). Hilangkan border tebal, cukup `hover:shadow-card`.
4. [ ] **Detail produk (`show`).** Dua kolom 7/5 di desktop. Panel beli `sticky top-24`. Pemilih ukuran berupa tombol 44px dengan status stok jelas (coret + `aria-disabled` untuk habis). Deskripsi `max-w-prose`.
5. [ ] **Keranjang & checkout.** Ringkasan pesanan di kolom kanan `sticky`. Satu tombol utama "Bayar/Checkout". Tampilkan progres gratis ongkir sebagai bar tipis (ambang dari `storeConfig`).
6. [ ] **Auth (`login`, `register`).** Layout dua kolom sudah ada. Pastikan tombol Google tinggi 44px, pembatas "atau", pesan galat memakai `text-danger` + ikon, bukan hanya warna.
7. [ ] **Mobile.** Cek lebar 360px: tidak ada scroll horizontal, tombol utama penuh lebar, mega-menu diganti drawer (sudah ada, rapikan jarak).
8. [ ] **Semua `<img>`** wajib `alt` bermakna (nama produk) atau `alt=""` jika dekoratif (17 tempat saat ini).

**Selesai kalau:** Lighthouse mobile halaman `/` skor Accessibility ≥ 95 dan tidak ada scroll horizontal di 360px.

---

## Fase 6 — Area User / Member (±1 jam)

**File:** `dashboard.blade.php` (805 baris), `orders/index`, `profile/*`.

1. [ ] **Hub keanggotaan.** Maksimal 1 baris kartu ringkasan di atas (Barcode, Kupon, Pesanan terakhir). Sisanya daftar tautan dengan tinggi baris 48px.
2. [ ] **Daftar pesanan.** Gunakan `<x-status-badge>`, tanggal `text-xs text-muted`, total `font-semibold`. Aksi "Lihat detail" sebagai satu tautan, bukan tiga tombol.
3. [ ] **Profil.** Tampilkan avatar Google (kolom `avatar`) bila ada, plus label "Masuk dengan Google". Sembunyikan form "Ubah kata sandi" untuk akun Google-only (`firebase_uid` terisi) atau beri catatan jelas.
4. [ ] **Konsistensi dengan storefront.** Header member memakai komponen header yang sama (jangan header kedua yang berbeda).

**Selesai kalau:** alur Login Google → `/member` → lihat pesanan terasa satu keluarga visual dengan toko.

---

## Fase 7 — Panel Admin (±2,5 jam)

**File utama:** `resources/views/admin/orders/index.blade.php` (1.179 baris, 112 `text-xs`).

1. [ ] **Pecah file monolit** menjadi partial per tab (ponytail: pindahkan saja, jangan tulis ulang):
   `admin/partials/tab-dashboard`, `tab-orders`, `tab-products`, `tab-categories`, `tab-users`, `tab-settings`, plus `print-slip`. File induk tinggal rangka + `@include`. Jalankan `php vendor/phpunit/phpunit/phpunit` setelah pemindahan.
2. [ ] **Skala admin.** Isi tabel `text-sm`, header tabel `text-xs font-semibold text-muted` (bukan uppercase tebal). Tinggi baris 56px, `px-4 py-3`. Header tabel `sticky top-16`.
3. [ ] **Sidebar.** Lebar tetap 256px di `lg+`. Di bawah `lg` ubah jadi baris tab horizontal yang bisa digeser (`overflow-x-auto`) supaya tidak memakan separuh layar HP.
4. [ ] **Kartu KPI.** Empat kartu sejajar, angka `font-headline text-2xl`, label `text-xs text-muted`, tanpa ikon dekoratif tambahan.
5. [ ] **Ganti `confirm()` bawaan browser** dengan satu modal Alpine bersama (`x-data="confirmDialog"`) berisi judul, konsekuensi, tombol "Batal" dan "Hapus" (merah). Terapkan pada hapus pesanan, produk, varian, kategori, pengguna.
6. [ ] **Toast.** Pesan sukses/galat jadi toast di pojok kanan atas, hilang otomatis 4 detik, tetap bisa ditutup manual dan memakai `role="status"`.
7. [ ] **Form inline.** Edit produk dan edit pengguna dibuka sebagai panel dengan label di atas input, grup tombol "Simpan" (utama) dan "Batal" (ghost) rata kanan.
8. [ ] **Pengaturan.** Kelompokkan jadi kartu bertema: Toko, Rekening, Kupon, Firebase. Kartu "Cloud Migration Readiness" tampil di paling bawah dengan indikator status berwarna + teks ("Terhubung", "Menunggu API Key").
9. [ ] **Print slip.** Jaga aturan `@media print` yang sudah ada; uji cetak satu pesanan dengan alamat multi-baris.

**Selesai kalau:** `admin/orders/index.blade.php` di bawah 150 baris, semua tab tetap berfungsi (tes 10/10 hijau), dan tidak ada `confirm(` tersisa di view admin.

---

## Fase 8 — Aksesibilitas, Performa, Gerak (±45 menit)

1. [ ] Cek keyboard: Tab melewati header → menu → konten → footer, fokus selalu terlihat (outline emerald).
2. [ ] Semua tombol ikon punya `aria-label` (saat ini hanya 7). Targetkan seluruh tombol ikon di header, drawer, wishlist, keranjang.
3. [ ] Modal & drawer: fokus masuk ke modal saat buka, `Esc` menutup, `aria-modal="true"`.
4. [ ] Animasi hanya `transition` ≤ 200ms, sudah dibungkus `prefers-reduced-motion` dari Fase 1.
5. [ ] Gambar: `loading="lazy"`, `width`/`height` atau `aspect-*` tetap. Pertimbangkan WebP untuk gambar baru.
6. [ ] Jalankan Lighthouse di `/`, `/products/{slug}`, `/login`, `/admin`. Catat skor sebelum dan sesudah di bagian "Hasil" bawah.

**Selesai kalau:** Lighthouse Accessibility ≥ 95 di 4 halaman, Performance mobile ≥ 80 di `/`.

---

## Fase 9 — Verifikasi & Sinkronisasi Graph (±20 menit)

1. [ ] `npm run build`
2. [ ] `php artisan view:clear` lalu `php artisan view:cache` (deteksi Blade rusak)
3. [ ] `php vendor/phpunit/phpunit/phpunit` (semua hijau)
4. [ ] Uji manual 6 alur: Beranda → produk → keranjang → checkout → sukses; Login Google; Login admin; ubah status pesanan; cetak slip; ubah pengaturan.
5. [ ] `graphify update .`
6. [ ] `python -m graphify export obsidian --dir "D:\Software\ObsidianVaults\nuevaweb"`

---

## Urutan Sesi yang Disarankan (ringkas)

| Sesi | Fase | Waktu |
| :--- | :--- | :--- |
| 1 | 1 | 45 menit |
| 2 | 2 | 1 jam |
| 3 | 3 | 1,5 jam |
| 4 | 4 | 1,5 jam |
| 5 | 5 | 2 jam |
| 6 | 6 | 1 jam |
| 7–8 | 7 | 2,5 jam |
| 9 | 8 + 9 | 1 jam |

Mulai dari **Fase 1, langkah 1**: jalankan `npm run build` dan pastikan hijau.

---

## Yang Sengaja Tidak Dikerjakan (supaya tidak melebar)

- Mode gelap (dark mode): belum ada kebutuhan, menggandakan beban uji warna.
- Library UI baru (shadcn, DaisyUI, dsb.): token Tailwind + class komponen sudah cukup.
- Ganti font: Inter dan Plus Jakarta Sans sesuai `DESIGN.md`, tidak perlu diubah.
- Redesain logo atau branding: di luar cakupan UI/UX tata letak.

## Sisa & Catatan (isi saat mengerjakan)

- Hex unik yang belum dipetakan: _(catat di sini)_
- Skor Lighthouse sebelum: _(isi)_ — sesudah: _(isi)_
- Halaman yang masih bermasalah: _(isi)_
