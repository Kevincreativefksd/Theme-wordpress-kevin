# Zona Ekonomi — Dokumentasi Singkat

Tema WordPress klasik, gaya Editorial Minimalist. Tanpa framework, tanpa jQuery, tanpa font eksternal, tanpa proses build.
Persyaratan: WordPress 6.3+, PHP 7.4+.

## 1. Instalasi
1. **Appearance → Themes → Add New Theme → Upload Theme**.
2. Pilih `zona-ekonomi-theme.zip` → **Install Now** → **Activate**.
3. Buka **Appearance → Customize → Zona Ekonomi**. Semua pengaturan ada di panel ini.
4. Jika situs sudah punya banyak artikel: jalankan plugin *Regenerate Thumbnails* sekali agar ukuran gambar baru tema (`ze-card`, `ze-wide`, dll.) tersedia.

## 2. Logo dan favicon
- **Customize → Identitas Situs → Logo.** Header, hero, dan footer berwarna hitam, jadi unggah logo versi terang/putih (PNG transparan atau SVG).
- Tanpa logo, tema menampilkan nama situs sebagai teks.
- Lebar logo: **Zona Ekonomi → Header → Lebar logo**.
- Favicon: **Identitas Situs → Ikon Situs** (fitur bawaan WordPress).

## 3. Beranda
Bisa dipakai dengan "Your latest posts" maupun halaman statis (Settings → Reading). Jika memakai halaman statis, pilih juga *Posts page* untuk daftar semua artikel.

| Pengaturan (Zona Ekonomi → Beranda) | Fungsi |
|---|---|
| Sumber artikel unggulan | *Artikel terbaru* atau *Pilih manual* (dropdown 100 artikel terbaru). Jika artikel yang dipilih dihapus/di-draft, tema otomatis memakai artikel terbaru. |
| Jumlah artikel grid terbaru | 3–12. |
| Jumlah kolom | 2/3/4 di desktop (tablet 2, mobile 1). Berlaku juga di halaman arsip. |
| Kategori pada navigasi kategori | Centang kategori yang sudah ada. Kosong = 8 kategori dengan artikel terbanyak. Tema tidak pernah membuat kategori. |
| Kategori sebagai section sendiri | Maks. 4 kategori. |
| Saklar section | Hero, navigasi kategori, artikel dua kolom, terbaru, populer, pilihan editor, per kategori. |

Catatan perilaku:
- **Artikel dua kolom** = artikel terbaru berikutnya setelah hero (tidak duplikat).
- **Pilihan Editor** = artikel yang dicentang *Stick to the top of the blog* di editor (maks. 3). Tanpa artikel sticky, section tidak tampil.
- **Artikel Populer** dihitung dari jumlah komentar (ringan, tanpa menulis ke database). Nonaktif secara default; tampil hanya jika ada artikel berkomentar.
- Halaman 2 dan seterusnya pada mode "latest posts" melanjutkan tepat setelah artikel yang tampil di halaman 1.

## 4. Navigasi
**Appearance → Menus** (atau Customize → Menu), tetapkan lokasi:
- *Navigasi utama* — header (mendukung submenu 1 tingkat).
- *Navigasi sekunder* — strip tipis di bawah header (opsional; hanya tampil jika diisi).
- *Navigasi footer* — kolom "Halaman" di footer.
Kolom "Kategori" di footer otomatis memakai daftar kategori yang sama dengan navigasi kategori.
Header: tampilkan/sembunyikan pencarian, header menempel (sticky), gaya menu mobile (dropdown atau layar penuh).

## 5. Layout artikel
**Zona Ekonomi → Artikel**
- **Layout A (Minimalist):** satu kolom sempit, kutipan (excerpt) tampil jika diisi.
- **Layout B (Editorial):** judul besar, gambar lebar, sidebar opsional (widget area *Sidebar artikel*; jika kosong tema menampilkan artikel terbaru).
- Daftar isi otomatis (H2/H3, minimal 2 heading), artikel terkait, reading progress bar.
- Metadata: penulis, tanggal terbit, tanggal diperbarui, estimasi waktu baca.
- Breadcrumb: memakai Rank Math jika fitur breadcrumb Rank Math aktif; jika tidak, breadcrumb bawaan tema (tanpa schema).

## 6. Warna, mode, tipografi
**Zona Ekonomi → Tampilan: mode, warna, tipografi** (warna dasar dan aksen dikonsolidasi di sini, tidak digandakan di "Umum").
- Mode: Terang / Gelap / Ikuti perangkat pengunjung.
- Warna latar, teks, header+footer+hero, aksen. Warna teks header dipilih otomatis agar kontras.
- Font: sans-serif atau serif sistem (tanpa unduhan font). Ukuran teks dasar 15–20 px.

## 7. Footer dan Umum
- **Umum:** deskripsi brand, teks copyright (`{year}` dan `{site}` diganti otomatis).
- **Footer:** URL media sosial (Facebook, X, Instagram, YouTube, LinkedIn, TikTok) — hanya yang diisi yang tampil.

## 8. SEO (Rank Math)
Tema tidak mencetak meta description, canonical, Open Graph, Twitter Card, atau schema. Semuanya diserahkan ke Rank Math; tema tetap jalan tanpa Rank Math. Tema hanya *membaca* kategori utama dan deskripsi dari Rank Math untuk label dan ringkasan.
- Satu `<h1>` per halaman. Di beranda, `<h1>` bersifat tersembunyi secara visual (nama situs + tagline), judul hero memakai `<h2>`.

## 9. Struktur file
`functions.php` memuat modul di `inc/` (options, setup, enqueue, customizer, template-functions, template-tags, breadcrumbs, performance, compatibility). Template di root, potongan tampilan di `template-parts/`, aset di `assets/` (CSS: main, components, single, responsive, editor; JS: navigation, progress). Prefix fungsi `ze_`, text domain `zona-ekonomi`.
