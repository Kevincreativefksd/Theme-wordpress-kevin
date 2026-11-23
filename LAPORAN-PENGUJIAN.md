# Laporan Pengujian — Zona Ekonomi 1.0.0

**Ringkasan jujur:** lingkungan pengembangan ini tidak memiliki PHP, WordPress, maupun akses jaringan. Karena itu kode PHP **belum pernah dieksekusi** dan skor PageSpeed Insights **belum bisa diukur**. Yang sudah diuji nyata adalah CSS dan JavaScript di Chromium headless, dan PHP diperiksa secara statis.
Gambar referensi Vauban **tidak terlampir** pada permintaan; desain dibangun dari spesifikasi teks.

## Status per area

| Area | Status | Bukti / catatan |
|---|---|---|
| Sintaks PHP | **Belum dijalankan** | Tidak ada binary PHP. Pemeriksaan statis: 44 file, kurung/kurawal/string seimbang, semua file diawali `<?php`, semua file punya guard `ABSPATH`. Wajib dijalankan `php -l` / PHPCS di server Anda. |
| Fungsi tak terdefinisi | Lulus (statis) | 65 fungsi `ze_*` terdefinisi; 0 pemanggilan `ze_*` tanpa definisi; semua callback hook/sanitize terdefinisi. |
| Escaping / sanitasi | Lulus (statis) | Setiap `echo` memakai `esc_*`/`wp_kses*` atau literal; semua setting Customizer punya `sanitize_callback`; kontrol capability `edit_theme_options`; tidak ada query `$wpdb`. Tidak ada form buatan tema, jadi nonce tidak relevan (form pencarian/komentar memakai API inti). |
| Template | Dibuat, **belum dirender di WordPress** | Homepage, blog index, single, page, category, tag/date (via `archive.php`), author, search, 404, attachment, pagination. |
| Responsive | **Lulus (fixture)** | 10 halaman × 5 lebar (1440, 1366, 768, 390, 360) = 50 kombinasi: **0 horizontal overflow**. Satu bug nyata (breadcrumb di 360px) ditemukan dan diperbaiki. |
| Navigasi desktop/mobile | **Lulus (fixture)** | Toggle menu & pencarian, `aria-expanded`, Escape, menu layar penuh + kunci scroll, dropdown submenu, header sticky, scroll horizontal kategori. |
| Tanpa JavaScript | Lulus (fixture) | Menu terlipat di bawah logo, form pencarian terlihat, tanpa overflow. |
| Error JavaScript | Lulus | 0 error konsol/`pageerror`. |
| Reading progress bar | Lulus (fixture) | Bergerak sesuai scroll. |
| Aksesibilitas | Sebagian | Skip link, fokus terlihat, tombol ≥ 44px, label ARIA. Kontras (rasio): teks 18.9, meta 5.3, aksen 6.7, hero 10.6/8.7, footer 10.6; mode gelap 16.5/6.7/8.0 — semua ≥ 4.5. Audit pembaca layar manual belum dilakukan. |
| Gutenberg | Dibuat, **belum diuji dengan konten nyata** | `align-wide`, `responsive-embeds`, `wp-block-styles`, editor.css, gaya tabel/kutipan/daftar/embed/gambar. Fixture memuat tabel lebar, kode panjang, URL panjang, iframe: tanpa overflow. |
| Kompatibilitas Rank Math | Dirancang, **belum diuji dengan plugin** | Tema nol output meta/OG/Twitter/canonical/schema (grep: kosong). Breadcrumb memakai `rank_math_the_breadcrumbs()` bila aktif. |
| Duplicate metadata | Lulus (statis) | Tidak ada `og:`, `twitter:`, `canonical`, `ld+json`, `itemtype`, `name="description"` di kode tema. |
| LiteSpeed Cache | Dirancang, belum diuji | Tidak ada nonce/output per-pengguna di front-end; aset berversi. |
| Dependensi terlarang | Lulus | grep: tidak ada jQuery, Bootstrap, Tailwind, React, font/CDN eksternal, URL eksternal. |
| Pengaturan dashboard | **Belum diuji di Customizer** | Seluruh setting terdaftar dengan default & sanitizer; UI Customizer belum dibuka. |
| PHP warning/notice | **Tidak dapat dipastikan** | Butuh `WP_DEBUG` di instalasi nyata. |

## PageSpeed Insights
**Tidak dijalankan** (tanpa jaringan; tanpa WordPress). Target 90+ **belum terbukti**. Proxy lab pada fixture statis (Chromium, mobile: Slow 4G 1.6 Mbps/150 ms, CPU 4× lebih lambat; desktop tanpa throttle):

| Halaman | LCP mobile | CLS | LCP desktop | CLS |
|---|---|---|---|---|
| Beranda | 816 ms | 0.000 | 148 ms | 0.000 |
| Artikel | 828 ms | 0.000 | 128 ms | 0.000 |
| Arsip | 664 ms | 0.000 | 132 ms | 0.000 |

Bobot aset front-end: CSS+JS ≈ **9 KB gzip** untuk seluruh tema (halaman tertentu memuat lebih sedikit). Ini hanya menunjukkan tema ringan; skor nyata bergantung pada hosting, gambar, plugin, dan caching. Silakan uji di https://pagespeed.web.dev setelah instal dan laporkan hasilnya.

Teknik yang sudah diterapkan: gambar hero/artikel `loading=eager` + `fetchpriority=high`, lainnya `lazy`; `width`/`height` + `srcset`/`sizes` eksplisit; font sistem; JS `defer`; `single.css` dan `progress.js` hanya dimuat di halaman yang memerlukan; emoji script dinonaktifkan; CSS blok inti dimuat per-blok; tanpa permintaan eksternal; query homepage digabung dan dipakai ulang.

## Keputusan dan perbedaan dari spesifikasi
- `tag.php` dan `date.php` tidak dibuat: hierarki WordPress otomatis memakai `archive.php` (isi identik, jadi tidak ada file duplikat).
- `theme.js` dan `toc.js` tidak dibuat: daftar isi dibuat di sisi server (PHP), tanpa JS, dan tidak ada logika yang memerlukan `theme.js`.
- File tambahan yang berfungsi: `comments.php`, `searchform.php`, `sidebar.php`, `inc/customizer-controls.php`, `assets/js/progress.js`, `assets/js/customizer-controls.js`, `assets/css/single.css`, `template-parts/content/listing.php`.
- Warna dasar/aksen hanya ada di bagian *Tampilan*; deskripsi brand dan copyright hanya di *Umum* (tidak digandakan di dua tempat).
- "Pilihan Editor" memakai artikel *sticky*; "Populer" memakai jumlah komentar (opsi tanpa penulisan database).
- `screenshot.png` dirender dari fixture berisi teks contoh, hanya untuk pratinjau tema; tidak ada konten dummy di dalam tema.
- Teks antarmuka bawaan berbahasa Indonesia; `.pot` tersedia (117 string).

## Yang perlu Anda cek setelah instal
1. `WP_DEBUG=true`: aktifkan tema, buka beranda, artikel (layout A dan B), arsip, pencarian, 404; pastikan tidak ada warning/notice.
2. Customizer: ubah tiap pengaturan, simpan, muat ulang.
3. Rank Math: periksa sumber halaman — satu `<title>`, satu meta description, satu canonical, satu set OG/Twitter, satu blok JSON-LD.
4. PageSpeed Insights mobile & desktop pada beranda dan satu artikel.
5. Kirim gambar referensi Vauban jika ingin penyesuaian layout.
