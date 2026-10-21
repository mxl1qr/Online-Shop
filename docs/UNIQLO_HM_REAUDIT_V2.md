# RE-AUDIT V2: UNIQLO ID & H&M ID vs SIRKULA
*Diukur dengan DevTools, desktop 1366px, 2026-10-21. Temuan dikirim bertahap oleh subagent browser dan disimpan per bagian.*

## Bagian 1. Homepage

### Uniqlo ID (tinggi dokumen 8.948px)
1. Header 64px: logo kotak merah 32px, tab WANITA/PRIA/ANAK/BAYI, search.
2. Hero full-bleed 1351x497px (rasio ~2,7:1), banner "WEEKLY NEWS TIMELESS ESSENTIALS". Teks ada di dalam gambar, tanpa overlay HTML.
3. "Cari berdasarkan kategori": h2 22px/400, lh 26,4px, mulai y=549. Grid ikon foto 6 kolom x 3 baris, ditutup tombol outline "LIHAT SEMUA KATEGORI".
4. Pola berulang: banner editorial 1351x497px + judul + deskripsi + harga merah (Rp399.000, label "Limited Offers") + rail 4 produk. Dipakai untuk Celana Barel Jersey, Fleece, Winter Travel, Jeans, HEATTECH, Bawahan, Workwear, T-Shirt.
5. Footer teks padat: link legal, info layanan, copyright.

Ritme vertikal:
- Banner 497px (y=1587, 3246, 5937, 7553, 8066), disusul rail kartu 294x392px (3:4) mulai y=2116.
- Jarak antar banner ~529px (497 + celah 32px). Blok banner+rail ~1.660px.
- Hanya 1 hero. Tidak ada newsletter besar, tidak ada testimoni.
- Foto lazy-load dengan skeleton abu-abu. 336 `<img>` per halaman.

Tipografi: body 16px (Segoe UI/Helvetica/Arial); heading kategori 22px/400 (bukan bold, hierarki dibangun dari foto); warna hampir semua #000 di atas putih; aksen merah hanya untuk harga limited dan logo.

### H&M ID (tinggi dokumen 6.883px)
- Header 134px (announcement bar + nav + tab), relative, tidak sticky di atas.
- Hero full-bleed 1351x760px (~1,78:1), mulai y=221, gambar + teks overlay.
- Split 2 kolom: gambar 676x843px (y=1040), label "THE NEW UTILITY".
- "THE OUTERWEAR EDIT" dan "MIDNIGHT ORCHESTRA" berdampingan (y~1900).
- Rail produk kartu 460x689px (2:3) di y=2930 dan y=3724.
- Banner FLASH SALE "ALL BELOW RP 299.000" (y~4665): h1 80px/700, lh 96px, HM Slussen Expanded.
- "DISCOVER NEW STYLES EVERYDAY" 18px (y~5134), lalu footer.
- Tipografi: body 13px (HM Slussen/Helvetica); label editorial 15px weight 100 uppercase extended; kontras ekstrem label 15px vs angka sale 80px. Emoji = 0.

### Sirkula (tinggi dokumen 2.590px, 3-3,5x lebih pendek)
- Header sticky 65px.
- Hero 609px (y=98-707), background solid #ebeeed + border, **tanpa gambar**. h1 48px/800, lh 48px (rasio 1,0), ls -1,2px, Plus Jakarta Sans.
- Section kategori/value 173px (y=707).
- "Koleksi Drop Pilihan" h2 24px/700, lh 32px. Grid 2/3/4 kolom, tinggi 1.003px, hanya 8 produk. Judul produk 13px/600.
- 16 emoji di teks halaman, 0 foto produk. Halaman berhenti di y=2.590px.
- Body Montserrat 16px, #181c1c di #f7faf9.
- Catatan: screenshot fullpage Sirkula time-out, visual asli belum dilihat.

### Perbandingan sementara
| Aspek | Uniqlo | H&M | Sirkula |
|---|---|---|---|
| Tinggi halaman | 8.948px | 6.883px | 2.590px |
| Hero | foto 1351x497 | foto 1351x760 | 609px, tanpa foto |
| Banner/section editorial | 8 banner 497px | 2 split + sale 80px | 0 |
| Rail produk | 4 kartu 294x392 per rail, banyak rail | kartu 460x689 | 1 grid 8 produk |
| `<img>` | 336 | banyak | 0 (emoji 16) |
| Newsletter besar | tidak ada | tidak ada | belum dicek |

---

## Bagian 2. Header / Mega-menu / Search (desktop 1366px)

### Uniqlo ID
- Header 64px. Elemen `<header>` `static`, bar logo+tab tampil di atas hero. Ada mask 96px dan overlay announcement 99px di y=64.
- Susunan: logo 75x44; tab WANITA/PRIA/ANAK/BAYI (tinggi 44px, lebar 44-57px, 16px/400, uppercase, `transition: all`) sebagai pemilih gender yang mengganti seluruh homepage; search inline input 382x38 (placeholder "Apa yang Anda cari?"); ikon 44x44 untuk Wishlist, Member, Keranjang, Kategori. Semua ikon punya `aria-label`.
- Announcement berupa kartu notifikasi mengambang dengan tombol Dismiss, bukan bar tipis penuh.
- Menu kategori: klik "Kategori produk" membuka overlay penuh 1366x577 (`dialog__overlay`), body `overflow:hidden`, isi drill-down dengan tombol Kembali/Tutup/Bahasa (44x44).
- Pengganti mega-menu: grid ikon foto kategori 6x3 di homepage.

### H&M ID
- Header total 134px: bar promo "BELANJA FLASH SALE - SEMUA ITEM DI BAWAH RP 299.000" + CTA "BELI SEKARANG"; baris nav ~48px dengan logo 71x32, tab WANITA/PRIA/ANAK-ANAK (tinggi 16px, tap area 48px), 4 ikon 48x48 (search, Masuk, Favorit, Tas Belanja). Background putih solid tanpa border bawah.
- Sticky saat scroll; logo mengecil 71x32 -> 49x32, tab jadi 87x48.
- Mega-menu hover 2 kolom @ ~672px:
  - Kiri (kurasi): NEW ARRIVALS, SEPATU DAN AKSESORIS, ELIE SAAB H&M, THE OUTERWEAR EDIT, DRESS CODE, ONLINE EXCLUSIVE, SPECIAL PRICE.
  - Kanan (kategori): LIHAT SEMUA, BASIC, ATASAN, GAUN, MERCH & GRAPHICS, KEMEJA & BLUS, CARDIGAN & JUMPER, SWEATSHIRT & HOODIES, JACKET & COAT, CELANA, dst.
  - Link tinggi 15px, jarak antar baris 27px (~12px gap), uppercase kecil.

### Sirkula
- Header sticky 65px, `rgba(255,255,255,.95)` + `backdrop-filter: blur(12px)`, border bawah 1px #e0e3e2, tanpa shadow (sudah sesuai DESIGN.md).
- Announcement strip dengan emoji: "100% Terkurasi & Higienis (Sanitized) - Garansi Keaslian SirkulaVerify - Gratis Ongkir ...".
- Nav: 6 link flat (SEMUA, KAOS & T-SHIRT, KEMEJA CASUAL, HOODIE & JAKET, OUTERWEAR & JAKET, CELANA & BAWAHAN), tinggi 64px, lebar 49-126px. Tanpa mega-menu/dropdown. "HOODIE & JAKET" dan "OUTERWEAR & JAKET" tumpang tindih secara semantik.
- Search inline 192x28 ("Cari pakaian..."), tanpa overlay/suggestion.
- Aksi: tombol "MASUK" 68x36 + 1 ikon 36x36. Tanpa wishlist, tanpa badge keranjang terlihat, tanpa link akun terpisah.
- Tap target ikon 36px, di bawah standar 44px.

### Tabel
| Elemen | Uniqlo | H&M | Sirkula |
|---|---|---|---|
| Tinggi header | 64 | 134 (promo+nav) | 65 + strip emoji |
| Pemilih gender | tab 4 | tab 3 | tidak ada |
| Mega-menu | overlay penuh 1366x577 | hover 2 kolom x672px | tidak ada |
| Search | inline 382x38 + overlay | ikon 48x48 -> overlay | inline 192x28 |
| Wishlist di header | ya (44px) | ya, "Favorit" (48px) | tidak |
| Tap target ikon | 44 | 48 | 36 |
| Sticky | logo bar di-scroll | ya, mengecil | ya, blur |
| Announcement | kartu dismissable | bar promo + CTA | strip emoji |

*Catatan: screenshot DevTools time-out berulang; angka diambil dari DOM/computed style.*

---

## Bagian 3. PLP (desktop 1366px)

### Uniqlo ID (/women/tops, tinggi dokumen 6.741px)
- Grid 4 kolom. Kartu 294px lebar x 536px tinggi total; gambar 294x392 (3:4, object-fit cover). Konten mulai x=88 (margin kiri 88px).
- "303 produk". Filter bar: tombol Filter, Ukuran, Warna, Harga (chip/dropdown horizontal di atas grid).
- Tiap kartu punya tombol wishlist ("Tambahkan ke wishlist", 37 tombol per halaman).
- Swatch warna: 224 elemen di 36 kartu (~6 warna per produk) di bawah gambar.
- Nama produk 14px/400, lh 16,8px (tanpa bold). Harga + harga coret + label "Limited Offer mulai ..." + "Dibuat dari Bahan Daur Ulang".
- Tanpa nomor halaman, lazy load saat scroll.

### H&M ID (/ladies/clothing/tops.html, tinggi dokumen 7.093px)
- Toolbar: "URUT BERDASARKAN", "SEMUA FILTER", "HARGA", "UKURAN", "WARNA".
- Grid dan ukuran kartu belum terukur (selector otomatis terkena false-positive dari menu hover).
- Dari DOM: tiap kartu memuat beberapa `<img>` (foto utama + foto kedua untuk hover). **Belum diverifikasi visual.**
- Tombol load-more tidak terdeteksi lewat teks; perlu verifikasi manual.

### Sirkula (tidak ada halaman PLP khusus)
- Kategori di header hanya `?category=...` pada homepage. Tanpa rute /products atau /shop, breadcrumb, judul kategori, atau jumlah produk.
- Tanpa filter, sort, ukuran, warna, harga. Satu-satunya elemen interaktif: input "Cari pakaian...". Wishlist = 0.
- Grid 4 kolom x 283px, gap 56px (baris) x 28px (kolom), total 1.216px. Maksimum 8 produk.
- Kartu 283x377px:
  - Area gambar `aspect-[3/4]`, bg #f4f6f5, isi emoji 36px (opacity .6) + teks kategori 10px uppercase. `<img>` = 0.
  - Pill "GRADE A+ - LIKE NEW" 9px/700 #006c47 pojok kiri atas.
  - Meta "KEMEJA CASUAL - M, L, XL" 11px/400 #73777f.
  - Nama 13px/600 #181c1c. Harga 14px/700 #002547, coret 10px #73777f, "-55%" 9px/700 #006c47. `transition: all`.
- Tidak ada: wishlist, foto kedua saat hover, swatch warna, quick-add, rating, pagination/load-more.
- Teks di bawah batas keterbacaan: 9px (pill/diskon) dan 10px (harga coret/kategori) vs minimum Uniqlo 14px.

### Tabel PLP
| Fitur | Uniqlo | H&M | Sirkula |
|---|---|---|---|
| Halaman PLP sendiri | ya (303 produk) | ya | tidak (homepage + ?category) |
| Kolom desktop | 4 | 4 (belum diukur) | 4 |
| Kartu gambar | foto 294x392 | foto, ~2 per kartu | emoji 36px |
| Filter | Filter/Ukuran/Warna/Harga | Sort/Semua Filter/Harga/Ukuran/Warna | tidak ada |
| Sort | ada | ada | tidak ada |
| Swatch warna | ~6 per kartu | ada | tidak ada |
| Wishlist heart | di setiap kartu | ya | tidak ada |
| Badge | Limited Offer, Daur Ulang | NEW/SALE | Grade A+, -% (9px) |
| Nama produk | 14px/400 | - | 13px/600 |
| Pagination | lazy scroll | - | tidak ada |

---

## Bagian 4. PDP (desktop 1366px)

### Uniqlo ID (T-Shirt Fleece Rajut Kerah Tinggi, tinggi dokumen 7.968px)
- Galeri: grid foto besar 369x492 (3:4) di x=88; foto ke-2 di x=457 (369x492); foto ke-3 dan ke-4 ditumpuk vertikal (y=624, y=1116). 4+ foto besar, **tanpa thumbnail**; pengguna scroll. Strip thumbnail warna 68x68 di sisi kanan (jarak 76px).
- Buy box sticky (`template-base-sticky-container`) di kolom kanan, lebar ~328-474px.
- h1 18px/400, lh 21,6px (kecil dan tenang; hierarki dari harga + foto).
- 1.697 elemen chip/swatch per halaman (termasuk rekomendasi).
- CTA "TAMBAHKAN KE KERANJANG" 204x52, #000, 16px. Qty +/- 52x52. Tombol wishlist 328x32 putih; ikon wishlist 44x44 di tiap rekomendasi.
- Accordion (`button[aria-expanded]`): Toko terdekat dengan stok, Fitur, Detail, Material/Perawatan, Pengiriman/Penukaran/Pengembalian, Produksi.
- Ada Panduan ukuran, Ulasan, rekomendasi item (carousel, kartu 168x168 di y~1.809), cek stok toko.

### H&M ID
- **Tidak terukur** (browser lambat/time-out). Dari homepage/PLP: foto 2:3 besar. Tidak ada klaim angka PDP H&M.

### Sirkula (/products/linen-blend-camp-collar-shirt, tinggi dokumen 2.591px)
- `<img>` = 0. Galeri diganti panel "Sirkula Clean Showcase" dengan emoji; tanpa thumbnail, slider, zoom, foto kedua. Panel `sticky top-24` (96px) di kolom kiri.
- Ada: breadcrumb, "LIKE-NEW CONDITION - QC INSPECTED", "GRADE A+ - LIKE NEW", "SANITASI OZON 100%", "Menghemat ~2.500L Air", 3 indikator kondisi (Warna Pekat 98%, Jahitan & Kancing 100%, Aroma Steril).
- h1 30px/800, lh 36px, y=218 (Uniqlo 18px/400).
- Harga Rp 179.000, coret Rp 393.800, badge "Hemat 55%", "Bebas biaya sanitasi", "Garansi tukar ukuran 3 hari".
- Pemilih warna: tombol teks BROKEN WHITE (134x34) dan TERRACOTTA (119x34), 12px, **tanpa kotak warna**. Ukuran M/L/XL 113x42, aktif #002547, 12px. "Sisa Stok: 6 pcs".
- Qty -/+ 40x40, input 48. CTA "+ TAMBAH KE KERANJANG" 474x52, #002547, teks 12px. Tanpa wishlist.
- Tidak ada: size guide, accordion detail/bahan/perawatan, review/rating, info pengambilan, carousel rekomendasi (hanya grid 4 kartu emoji 289x385 + link 11-12px), sticky bar CTA mobile.
- Emoji di halaman = 9.

### Tabel PDP
| Elemen | Uniqlo | H&M | Sirkula |
|---|---|---|---|
| Foto utama | 4+ foto 369x492 | belum diukur | 0 foto (emoji) |
| Thumbnail/zoom | strip 68x68 + scroll | belum diukur | tidak ada |
| Buy box sticky | ya | belum diukur | tidak (yang sticky panel kiri) |
| h1 | 18px/400 | - | 30px/800 |
| Swatch warna | foto/kotak warna | - | tombol teks 12px |
| Size selector | ya + panduan ukuran | - | M/L/XL 113x42 (+stok) |
| CTA | 204x52 hitam | - | 474x52 #002547 |
| Wishlist | ya | - | tidak ada |
| Accordion detail/bahan/perawatan | 6 accordion | - | tidak ada |
| Review | ya | - | tidak ada |
| Ketersediaan toko | ya | - | tidak relevan (online) |
| Rekomendasi | carousel | - | grid kartu emoji |

**Kekuatan Sirkula di PDP (jangan dibuang):** breadcrumb, grade kondisi + indikator QC, sanitasi, stok, hemat air. Ini diferensiasi nyata yang tidak dimiliki Uniqlo/H&M. Masalahnya di presentasi visual (tanpa foto) dan teks 9-12px.

---

## Bagian 5. Cart / Checkout
*Cakupan terbatas: hanya keranjang kosong yang diukur; tidak ada item ditambah dan tidak ada checkout.*

### Sirkula (/cart kosong, tinggi dokumen 1.093px)
- Halaman penuh, tanpa mini-cart drawer. Header: 1 ikon 36x36 + tombol MASUK. Badge jumlah tidak terkonfirmasi (keranjang kosong, badge memang tersembunyi di kode).
- Empty state: judul, subjudul, emoji sebagai ilustrasi, teks kurasi, 1 CTA "JELAJAHI KOLEKSI PAKAIAN" 279x44. 3 emoji.
- Tidak ada: step indicator di halaman cart, trust badge, rekomendasi produk di empty state, rail "terakhir dilihat".
- Copy "Pakaian pilihan Anda sebelum diproses pengiriman higienis" sudah sesuai brand.
- **Koreksi dari pemeriksaan kode:** halaman checkout dan success sudah memiliki step indicator 3 langkah di header (Keranjang, Pengiriman & Bayar, Selesai) dan badge cart di PLP/PDP. Yang belum ada: step indicator di halaman cart itu sendiri dan trust badge pembayaran.

### Uniqlo ID (/cart)
- Konten dirender klien dan tetap kosong saat dibuka; belum ada data empty state valid.
- Dari PDP: CTA "TAMBAHKAN KE KERANJANG" 204x52 hitam; link keranjang header 44x44.
- Footer: "Gratis ongkir min IDR 699K / Pick Up di Toko" sebagai trust cue.

### H&M ID
- Cart/checkout belum dibuka.

### Kandidat perbaikan Sirkula (dari bukti di atas)
- Mini-cart drawer.
- Step indicator di halaman cart.
- Trust badge (SirkulaVerify, garansi tukar 3 hari, sanitasi, metode bayar).
- Rekomendasi produk di empty state.

*Perbandingan cart/checkout belum cukup data; perlu verifikasi dengan item di keranjang.*

---

## Bagian 6. Tipografi & Microinteraction

Distribusi ukuran font Sirkula (homepage desktop, ~125 elemen teks daun):
| px | 8 | 9 | 10 | 11 | 12 | 13 | 14 | 16 | 18 | 20 | 24 | 36 | 60 |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| jumlah | 1 | 16 | 21 | 29 | 25 | 8 | 10 | 2 | 1 | 4 | 1 | 8 (emoji) | 1 |

**92 elemen (~74%) berukuran <=12px.** Uniqlo: body 16px, nama produk 14px. H&M: body 13px, tapi label editorial 15-18px dan hero sale 80px.

| Aspek | Uniqlo | H&M | Sirkula |
|---|---|---|---|
| Font | Segoe UI/Helvetica/Arial | HM Slussen (proprietary, extended) | Plus Jakarta Sans / Montserrat / Inter |
| Body | 16px #000 | 13px #000 | 16px #181c1c |
| Heading section | 22px/400 lh 26,4 | 15-18px/100 uppercase; hero 80px/700 lh 96 | 24px/700 lh 32 ls -0,6px |
| Hero | gambar berisi teks | h1/overlay di atas foto | h1 48px/800 lh 48 ls -1,2px; mobile 30px |
| Nama produk | 14px/400 lh 16,8 | - | 13px/600 lh 17,9 |
| CTA | 52px, hitam, 16px | area 48px | 42px, 12px/700, ls 0,6px, uppercase |
| Teks terkecil | ~12px (legal) | 12px | 8-9px |

- Microinteraction Sirkula: `transition: color .15s cubic-bezier(.4,0,.2,1), background-color ...`; sebagian elemen kartu `transition: all` (kasar). Tanpa hover ganti foto, tanpa skeleton (`animate-pulse` = false), tanpa newsletter (`input[type=email]` = false).
- Uniqlo: skeleton abu-abu saat lazy load, tombol 44x44 konsisten.
- Ikon: Uniqlo/H&M memakai SVG + foto. Sirkula memakai emoji sebagai ikon dan ilustrasi produk (~16 di homepage, 9 di PDP, 3 di cart).
- Warna: Uniqlo/H&M hitam-putih + aksen merah. Sirkula #002547/#006c47/#f7faf9 tonal, sesuai DESIGN.md dan kontras tinggi; **tidak perlu diubah**.

## Bagian 7. Mobile 390px (emulasi 390x844 @3x)

### Sirkula
- Header 104px (bar logo 65px + baris nav 22px scroll horizontal). Tanpa hamburger/drawer.
- Item nav tinggi 22px (lebar 47-140px): tap target jauh di bawah 44px. 28 elemen interaktif <44px.
- Ikon header 36x36, MASUK 68x36.
- Tanpa bottom nav (`position: fixed` = 0), tanpa sticky CTA di PDP, tanpa search di header mobile.
- Hero 934px (~1,1x viewport) tanpa gambar; h1 30px.
- Grid 2 kolom x 173px, gap 12px. Tinggi dokumen 4.053px. Tidak ada horizontal scroll (baik).

### Uniqlo (/women/tops, 390px)
- Header 56px. Sticky tabs `fr-ec-sticky-tabs` 44px di y=106 (sub-kategori).
- Grid 2 kolom, kartu 195x394 (gambar 195x260, 3:4).
- Filter di drawer full-width dari kanan (844px) dengan "303 Produk"; wishlist 44x44.
- Navigasi lewat drawer fullscreen. Tanpa bottom nav tradisional.

### H&M
- Mobile belum diukur (browser lambat).

---

## Bagian 8. Diagnosis & Prioritas

**Batasan data:** H&M PDP, cart/checkout, dan mobile tidak terukur. Mega-menu hover dan foto hover hanya terdeteksi dari DOM. Semua angka dari computed style/DOM; screenshot sering time-out.

### Diagnosis: kenapa Sirkula terasa "kurang" (urut dampak)
1. **Tidak ada foto.** `<img>` = 0 di homepage, PLP, PDP; kartu berisi emoji 36px di kotak #f4f6f5. Uniqlo 336 `<img>`, H&M kartu 2:3 berfoto. Fashion dijual lewat foto; ini penyebab utama kesan prototipe.
2. **Hero tanpa visual.** 609px teks + emoji di #ebeeed vs Uniqlo 1351x497 foto, H&M 1351x760 foto + overlay.
3. **Halaman terlalu pendek.** 2.590px vs 6.883 (H&M) dan 8.948 (Uniqlo). Hanya 1 grid 8 produk; tanpa banner kategori, rail berulang, editorial, newsletter.
4. **Tidak ada PLP sungguhan.** Tanpa filter, sort, jumlah produk, pagination.
5. **Tidak ada wishlist, badge keranjang jelas, search overlay.** Sirkula 1 ikon 36px + MASUK vs 4 ikon 44-48px.
6. **Tidak ada mega-menu/drawer.** 6 link flat, dua kategori tumpang tindih; mobile tanpa hamburger (strip 22px).
7. **Teks terlalu kecil.** 74% elemen <=12px; pill/diskon 9px; harga coret 10px; CTA 12px.
8. **PDP tanpa galeri,** accordion, size guide, review, sticky CTA mobile.
9. **Emoji sebagai ikon,** mengaburkan identitas brand.
10. **Mobile belum matang:** tanpa bottom nav/sticky CTA/hamburger.
11. **Cart:** tanpa mini-cart drawer, step indicator di cart, trust badge.
12. **USP belum divisualkan:** grade kondisi, sanitasi ozon, QC, SirkulaVerify, hemat air tampil kecil (9-11px) dan tertutup emoji. Ini keunggulan yang tidak dimiliki Uniqlo/H&M.

### Tabel ringkas
| Aspek | Uniqlo | H&M | Sirkula |
|---|---|---|---|
| Tinggi homepage | 8.948 | 6.883 | 2.590 |
| Hero | 1351x497 foto | 1351x760 foto | 609 tanpa foto |
| `<img>` | 336 | banyak | 0 |
| Mega-menu | overlay 1366x577 | hover 2x672px | tidak ada |
| Ikon header | 44px x4 | 48px x4 | 36px x1 |
| Wishlist | kartu + PDP | ya | tidak |
| Filter/sort | ya | ya | tidak |
| PDP galeri | 4+ foto + strip 68 | - | emoji |
| PDP accordion | 6 | - | 0 |
| Teks min | ~12px | 12px | 8-9px |
| Mobile tap nav | 44px | - | 22px |

### Prioritas perbaikan (sesuai DESIGN.md: #002547/#006c47/#f7faf9, Plus Jakarta Sans/Montserrat/Inter, elevasi tonal)

**P0 (dampak terbesar)**
1. Ganti emoji produk dengan foto asli 3:4 (`<img object-cover>`); fallback blok monokrom #f4f6f5 + SVG, bukan emoji. Foto ke-2 untuk hover (`opacity .3s`) dan galeri PDP 4+ foto.
2. Hero foto penuh: desktop >=560px tinggi (target 600-640), mobile ~520px, overlay gradien tonal #002547 (opacity .35-.5), CTA 48-52px.
3. PLP sungguhan (`/koleksi`): chip filter (ukuran, warna, grade, harga) border 1px #c3c6cf, sort, jumlah produk, grid 4/2 kolom, "Muat lebih banyak".
4. Wishlist heart 44x44 di kartu + ikon header (badge #006c47) + halaman /wishlist.
5. Skala teks minimum: pill/diskon >=11px, harga coret >=12px, meta >=12px, CTA >=14px/700, harga 16px. Tidak ada teks 8-9px.

**P1**
6. Header: search ikon -> overlay penuh, ikon SVG 44x44 (akun/wishlist/keranjang) + badge angka; hapus tumpang tindih kategori.
7. Mega-menu hover 2 kolom (Kurasi: Baru, Grade A+, Sale; Kategori) ~672px/kolom, bg #f7faf9, border 1px + tonal.
8. Mobile: hamburger + drawer 100% lebar, bottom nav 56px (Beranda, Koleksi, Wishlist, Keranjang, Akun), sticky "Tambah ke Keranjang" di PDP, tap target >=44px.
9. Homepage berulang ala Uniqlo (banner kategori ~497px + rail 4 kartu), editorial "Cerita Sirkula", newsletter. Target tinggi >=5.500px.
10. PDP: accordion (Detail, Bahan, Perawatan, Pengiriman & Penukaran, Kondisi/QC), size guide modal, thumbnail 68x68 + zoom, swatch warna kotak/lingkaran, buy box sticky, rekomendasi carousel.

**P2**
11. Mini-cart drawer 400px dari kanan, badge, step indicator di cart, trust badge (SirkulaVerify, garansi 3 hari, sanitasi).
12. Skeleton loading (pulse #ebeeed); ganti `transition: all` dengan properti spesifik (150-300ms).
13. Ganti emoji ikon dengan set SVG konsisten (#002547/#006c47).
14. Visualkan USP: strip ikon SVG "Grade A+ - Sanitasi Ozon - SirkulaVerify - Hemat Air" 14px + bar grade kondisi bergaya progress #006c47.
15. Rating/review dan badge Baru/Sale/Limited 11-12px, tonal (#006c47 Baru, #002547 Sale).

*Screenshot full-page Uniqlo: C:/Users/Maliq/.gemini/antigravity-cli/brain/bae31990-2336-4eef-8453-be9555852e1f/.system_generated/steps/26/media_0.jpg*
