# DOKUMEN AUDIT & BENCHMARK TATA LETAK: UNIQLO & H&M vs SIRKULA
*Standar Arsitektur UI/UX E-Commerce Fashion Sirkular (Sirkula Recommerce)*

## 1. Diagnosis Akar Masalah (Root Cause)

### A. Masalah Navbar Asimetris & Ruang Kosong Berlebih
- **Penyebab**: Navbar menggunakan Flexbox `justify-between` dengan bobot elemen yang sangat timpang:
  - Pilar Kiri (Logo + Kategori): **~599px**
  - Pilar Tengah (Search Bar): **~384px**
  - Pilar Kanan (Aksi & Cart): **~167px**
- Karena pilar kiri 4x lebih lebar dari pilar kanan, titik tengah matematis halaman bergeser ke kanan, mendorong menu kategori menempel ke logo di kiri dan menciptakan ruang kosong canggung di tengah.
- **Standar Uniqlo & H&M**: Menggunakan **CSS Grid 3 Pilar Presisi (`grid-cols-[1fr_auto_1fr]`)** setinggi **64px (`h-16`)**.
  - **Pilar Kiri**: Logo Brand (Lebar tetap).
  - **Pilar Tengah**: Navigasi Kategori (Tepat di titik sumbu tengah layar / 50vw).
  - **Pilar Kanan**: Utilitas (Search bar proporsional, Akun, Keranjang).

---

### B. Masalah Tampilan Katalog Produk "Kaku"
- **Penyebab**: Kartu produk sebelumnya membungkus pakaian dalam kotak kaku ber-outline:
  - `border border-[#e0e3e2]` (memenjarakan foto).
  - `p-4` internal padding (mempersempit ruang foto).
  - Tombol kotak abu-abu di dalam kartu produk (terasa seperti widget inventaris/admin gudang).
- **Standar Uniqlo & H&M**: Menerapkan arsitektur **BORDERLESS**:
  - `border: 0px` (Tanpa garis luar keliling).
  - `box-shadow: none` (Tanpa drop shadow).
  - `padding: 0px` (Foto baju full edge-to-edge).
  - Pemisahan antar produk menggunakan **Natural Whitespace Breathing Room** dengan jarak baris vertikal yang lapang: **`gap-y-12 md:gap-y-14` (48px - 56px)**.
  - Seluruh kartu adalah tautan yang dapat diklik (*clickable card*) tanpa tombol kotak tambahan yang mengotori visual.

---

## 2. Spesifikasi Standar Sirkula (Sesuai DESIGN.md)
- **Palette**: Deep Navy (`#002547`), Secondary Emerald (`#006c47`), Surface Canvas (`#f7faf9`), White Card (`#ffffff`).
- **Typography**: Headings: Plus Jakarta Sans, Body: Montserrat, Label & Price: Inter.
- **Rasio Foto**: 3:4 Vertikal Pakaian.
- **Anchor Pricing**: Menampilkan perbandingan estimasi harga mall (strikethrough) vs Harga Sirkula (bold navy).
