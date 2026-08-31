---
name: pola-cepat
description: Use to speed up creating a new pattern-generator page. Provides the proven HTML+JS template (canvas 4000x4000, 300 DPI JFIF injection, seed PRNG, save to hasil/), a catalog of pattern ideas already used (1-30) so you propose fresh ones, a list of common bugs to avoid, and a fast headless-node verification checklist.
---

# Pola Cepat

Skill percepat untuk membuat satu project generator pola (atau membanyakkannya 16-30 jadi cepat, seperti yang sudah dilakukan). Melengkapi `pola-baru`.

## Template siap-copy
File **`template.html`** di folder skill ini berisi struktur lengkap yang IDENTIK untuk semua project (hasil nyata dari project 1-30). Bagian bawah template (mulai komentar `===== BAGIAN DI BAWAH INI JANGAN DIEDIT =====`) TIDAK BOLEH diubah sama sekali.

Cara pakai:
1. `Copy-Item template.html project-N/index.html` (folder project-N sudah dibuat).
2. Ganti placeholder di template:
   - `__N__` → nomor project (mis. 16)
   - `__NAMA__` → nama pola (mis. Fish Scale) — dipakai di `<title>` & `<h1>`
   - `__PREFIX__` → prefix nama file (mis. `scale`), muncul 2× (di `render` info & `simpanJpg`)
   - Bagian `function render()` : isi logika pola baru (ganti `const bentuk`, `ukuran`, loop tiling, dan label)
   - Bagian `function __BENTUK__()` dan `PALET`: ganti sesuai pola.
3. Jangan pernah mengubah: `S`, `DPI`, helper PRNG, `setJfifDensity`, `simpanJpg`, `dirHandle`, wiring tombol, `canvas width="4000" height="4000"`.

Semua project yang sudah ada (1-30) mengikuti struktur ini → kalau mau lihat contoh jadi, buka `project-16/index.html` (paling bersih) atau `project-30/index.html`.

## Referensi pola (sudah dipakai project 1-30 — untuk MENAWARKAN ide BARU)
Minta pengguna pilih pola populer yang BELUM ada di daftar ini, atau kombinasikan baru:

| # | Pola | # | Pola |
|---|------|---|------|
| 1 | Chevron | 16 | Fish Scale |
| 2 | Polkadot | 17 | Brickwork |
| 3 | Memphis | 18 | Feather Plume |
| 4 | Honeycomb | 19 | Diamond Chevron |
| 5 | Zigzag | 20 | Mosaic Tessellation |
| 6 | Batik Kawung | 21 | Sunburst |
| 7 | Girih Bintang (islamic star) | 22 | Woven Lattice |
| 8 | Triangle | 23 | Polka Ring |
| 9 | Argyle/Plaid | 24 | Broken Plaid |
| 10 | Mandala Konsentris | 25 | Spiral |
| 11 | Halftone Dotted | 26 | Starry Constellation |
| 12 | Pinstripe Wavy | 27 | Houndstooth |
| 13 | Kaleidoscope | 28 | Calla Bloom |
| 14 | Confetti | 29 | Wave Bar |
| 15 | Moroccan Quatrefoil | 30 | Dazzle |

Ide pola populer lain yang belum dipakai (bisa ditawarkan): Aztec, Damask, Ikat, Paisley, Stripe Geometri, Pixel/Blocky, Ornamen Bunga, Kotak-Kotak Gingham, Tribal, Retro 70-an Sunburst, Polar Bear/kartun, karya batik Parang/Ceplok, dll.

## Bug umum yang sering muncul (CEK SEBELUM DITERIMA)
1. **Motif menggambar di origin (0,0) tapi tidak di-translate** → semua motif numpuk di pojok. Solusi: `ctx.save(); ctx.translate(x,y); ...gambar pakai koordinat relatif origin...; ctx.restore();`.
2. **Tiling tidak menutup layar** (hanya pojok kiri atas, atau kosong separuh). Solusi: `margin` jangan 0, dan `cols = Math.ceil((S + margin*2)/jarak) + 2` (begitu juga rows). Cek rentang acak TERBESAR juga tertutup.
3. **Variabel/fungsi mati (unused)** — mis. deklarasi di-copy tapi tidak dipakai. Buang semuanya. (Gaya project: tanpa komentar berlebihan, tanpa fitur spekulatif.)
4. **Kurung tidak seimbang** setelah menyunting block `if/else`. Selalu `node --check`.
5. **Mengubah bagian bawah template** (DPI/simpan) tidak sengaja. Jangan.
6. **Nama file tidak unik / tidak masuk `hasil/`**. Selalu `<prefix>-<seed>-<timestamp>.jpg`.

## Verifikasi cepat (headless Node) — JANGAN hanya buka browser
Selain `node --check`, jalankan smoke test render (menangkap error runtime yang tidak terlihat di node --check). Salin skrip berikut ke file `v.js` (temp), ganti `project-N`:

```js
const fs = require('fs');
const html = fs.readFileSync('D:\\NUSABIT\\usbwebserver\\root\\pattern-generator\\project-N\\index.html', 'utf8');
const script = html.match(/<script>([\s\S]*?)<\/script>/)[1];
const ctx = {
  fillStyle:'', strokeStyle:'', lineWidth:0, lineJoin:'', lineCap:'', globalAlpha:1,
  setTransform(){}, fillRect(){}, strokeRect(){}, save(){}, restore(){}, beginPath(){}, moveTo(){}, lineTo(){},
  quadraticCurveTo(){}, bezierCurveTo(){}, closePath(){}, translate(){}, rotate(){}, scale(){}, clip(){},
  createLinearGradient(){ return { addColorStop(){} }; }, createRadialGradient(){ return { addColorStop(){} }; },
  stroke(){}, fill(){}, arc(){}, rect(){}, ellipse(){}, setLineDash(){}
};
global.kanvas = { getContext: () => ctx };
global.document = { getElementById: (id) => (id === 'kanvas' ? global.kanvas : { value:'', textContent:'' }) };
eval(script);
for (const s of [1, 42, 777, 12345, 888888]) render(s);
console.log('RENDER OK');
```
`node v.js` → harus "RENDER OK". Kalau error "X is not a function" pada stub, itu berarti project memakai metode Canvas asli yang belum ada di stub — SELIDIKI dahulu, itu biasanya API valid (mis. `strokeRect`, `quadraticCurveTo`); lengkapi stub jangan ubah project.

Urutan verifikasi cepat (loop boleh):
1. `node --check` (JS valid)
2. smoke test render di atas (runtime OK, 5 seed)
3. `index.php` sudah otomatis menampilkan project baru (katalog dikelompokkan per 10).

## Membanyakkan project sekaligus
Untuk membuat banyak project sekaligus (mis. 16-30), strategi tercepat yang terbukti:
- Buat dulu 1 project lengkap yang benar sebagai template nyata (mis. project-16), verifikasi penuh.
- Delegasikan ke beberapa sub-agent paralel, SEMUA membaca template yang sama & aturan wajib (canvas 4000, DPi JFIF, tiling penuh, simpanJpg identik). Beri tiap sub-agent 2 project agar fokus.
- Setelah sub-agent selesai, jangan percaya mentah: verifikasi SEMUA lagi dengan loop node --check + smoke test render. (Pernah terjadi sub-agent mengaku selesai tapi file tidak dibuat / aid returns empty.)
- Setiap project hasil sub-agent itu dibaca sendiri ulang untuk Cek bug umum #1-6 di atas.
