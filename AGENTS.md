# AGENTS.md

## Proyek
Halaman web generator pola unik: pola acak dengan kombinasi warna, bentuk, dan posisi yang menarik. Setiap versi harus mengusulkan **1 pola populer** dengan ragam bentuk populer juga.

## Spesifikasi output (wajib)
- Format **JPG**, ukuran **4000x4000px**, **300 DPI**
- Nama file unik; semua hasil disimpan di folder `hasil/`
- Gotcha: `canvas.toBlob()`/`toDataURL()` tidak menyimpan metadata DPI. Jika 300 DPI benar-benar divalidasi dari file, perlu penyisipan metadata JFIF saat ekspor/post-process — jangan diam-diam mengabaikan spesifikasi ini.

## Struktur project
- Setiap pola/project hidup di foldernya sendiri: `project-1/`, `project-2/`, dst. Project baru = folder bernomor berikutnya, jangan mencampur beberapa pola dalam satu folder.
- Tiap project = satu halaman statis mandiri (`index.html`); output tetap masuk `hasil/`.

## Teknologi & menjalankan
- Static HTML + JS, render pola via Canvas API di browser. **Tanpa build tool / npm / framework** — jangan membuat `package.json` atau config build yang tidak diminta.
- Preview: buka file HTML langsung di browser, atau lewat localhost karena repo ini berada di dalam web root USBWebServer (`D:\NUSABIT\usbwebserver\root\`).

## Status repo
- Tidak ada test, lint, maupun CI. Jangan mengarang perintah build/test; verifikasi dengan membuka halaman di browser dan memeriksa output JPG di `hasil/`.
- `.gitattributes` hanya normalisasi LF (`* text=auto`).

## Panduan perilaku
Baca `petunjuk_ai.md` (Bahasa Indonesia) sebelum coding. Poin inti:
- Perubahan presisi: ubah hanya yang diminta pengguna; jangan refactor/perbaiki kode di sekitarnya; ikuti gaya kode yang sudah ada.
- Kesederhanaan dulu: tanpa fitur spekulatif, abstraksi satu-pemakaian, atau error handling untuk skenario mustahil.
- Nyatakan asumsi secara eksplisit sebelum implementasi; jika ada beberapa interpretasi, sampaikan semuanya — jangan memilih diam-diam.

# Response
Berikan respon yang bahagia, mudah dipahami, dan mungkin bisa ditambah emoticon