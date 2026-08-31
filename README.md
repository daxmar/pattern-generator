# Generator Pola — Katalog Project

Kumpulan halaman web statis untuk menghasilkan **pola unik** (kombinasi warna, bentuk, posisi) secara acak. Setiap project = satu generator pola mandiri yang dirender lewat Canvas API di browser, lalu bisa diekspor sebagai gambar JPG.

Saat ini berisi **50 project pola** (Chevron, Polkadot, Batik Kawung, Houndstooth, Aztec, Celtic Knot, dan lainnya), dikelompokkan di katalog `index.php` menjadi Project I–V.

---

## 🚀 Cara menjalankan

Project ini berbasis **HTML + JS statis** — tanpa build tool, tanpa npm, tanpa server khusus.

1. **Lewat USBWebServer (disarankan):**
   - Folder ini sudah berada di web root USBWebServer: `D:\NUSABIT\usbwebserver\root\pattern-generator\`
   - Start USBWebServer, lalu buka: `http://localhost/pattern-generator/index.php`
   - Katalog menampilkan semua project; klik salah satu untuk membuka generator.

2. **Langsung dari browser (cara cepat):**
   - Buka file `project-1/index.html` (atau project lain) langsung di browser. Semua project statis, jadi berjalan tanpa server.
   - Catatan: `index.php` (katalog) butuh PHP, jadi hanya jalan lewat server. Halaman project (*.html) jalan di mana saja.

---

## 🧭 Struktur folder

```
pattern-generator/
├── index.php                 ← katalog (kelompokkan project per 10: I, II, III…)
├── project-1/ … project-50/  ← setiap project = satu generator pola mandiri
│   └── index.html            ← halaman lengkap (canvas, JS, tombol simpan)
├── hasil/                    ← folder tujuan file JPG hasil ekspor
├── petunjuk_ai.md            ← panduan internal (Bahasa Indonesia)
├── AGENTS.md                 ← panduan untuk AI/asisten coding
└── .opencode/skills/         ← skill AI untuk mempercepat pembuatan pola
```

Setiap project berdiri sendiri dan tidak saling bergantung. Menambah/menghapus satu project tidak memengaruhi yang lain.

---

## 🎨 Cara membuat project baru

Tambahkan project baru berarti menambah satu folder `project-N/` (N = nomor berikutnya, mis. `project-51/`) berisi `index.html` yang merender pola.

### Langkah cepat (disarankan — pakai template yang sudah jadi)

1. **Salin template**:
   ```
   .opencode/skills/pola-cepat/template.html  →  project-51/index.html
   ```
   Template ini sudah berisi semua bagian penting yang sama antar project (terverifikasi dari project 1–50): canvas 4000×4000, PRNG ber-seed, export JPG 300 DPI, tombol Generate/Seed/Simpan.

2. **Ganti tanda pengenal** di file salinan:
   - `<title>` dan `<h1>` → `Project 51 — Generator Pola <NAMA>`
   - `aztec` (prefix nama file) → prefix baru, mis. `pola-baru`

3. **Isi bagian pola** — ubah fungsi `gambarMotif(ctx, warna, ukuran, bentuk)`:
   - Gambar motif **relatif ke origin (0,0)** — pemanggil sudah melakukan `ctx.translate(x, y)`.
   - Terima param `bentuk` (`'solid'`, `'outline'`, `'ganda'`) untuk 3 ragam tampilan.
   - Contoh isi (ganti dengan motif Anda):
     ```js
     function gambarMotif(ctx, warna, ukuran, bentuk) {
       ctx.beginPath();
       ctx.arc(0, 0, ukuran * 0.3, 0, Math.PI * 2);
       ctx.fillStyle = warna;
       ctx.fill();
     }
     ```

4. **Sesuaikan palet** (opsional) di array `PALET`.

### Yang TIDAK boleh diubah
Bagian file mulai dari komentar:
```
// ===== BAGIAN DI BAWAH INI JANGAN DIEDIT DARI TEMPLATE =====
```
Bagian ini berisi `setJfifDensity` (penyisipan metadata 300 DPI), `simpanJpg`, wiring tombol, dan atribut `canvas width="4000" height="4000"`. Mengubahnya berisiko merusak spesifikasi output.

---

## ⚙️ Cara kerja generator

- **PRNG ber-seed (mulberry32)** → pola bisa direproduksi. Masukkan seed, klik "Terapkan Seed", dan pola yang sama muncul lagi.
- **Tiling penuh layar** → motif diulang menutupi seluruh 4000×4000 (bukan hanya pojok). Perhitungan: `cols = Math.ceil((S + margin*2)/ukuran) + 2` (begitu juga `rows`).
- **3 ragam bentuk** (`bentuk`): solid / outline / ganda — dipilih acak tiap generate.

---

## 💾 Spesifikasi output (wajib)

- Format **JPG**
- Ukuran **4000×4000 px**
- **300 DPI** — penting: `canvas.toBlob()`/`toDataURL()` **tidak** menyimpan metadata DPI. Template sudah menyisipkannya via fungsi `setJfifDensity` saat ekspor, jadi jangan ganti dengan `toBlob()` polos.
- Nama file unik: `<prefix>-<seed>-<timestamp>.jpg`, disimpan ke folder **`hasil/`**.

### Cara menyimpan
- **Chrome/Edge modern**: klik **Simpan JPG** → pilih folder tujuan (bisa langsung ke `hasil/`).
- **Browser lain**: file terunduh, pindahkan manual ke `hasil/`.

---

## 🔍 Verifikasi output

Setelah menghasilkan JPG, cek dimensi + DPI lewat PowerShell:

```powershell
Add-Type -AssemblyName System.Drawing
$img = [System.Drawing.Image]::FromFile("hasil\<nama-file>.jpg")
"$($img.Width)x$($img.Height) px @ $([int]$img.HorizontalResolution)x$([int]$img.VerticalResolution) DPI"
$img.Dispose()
```

Harus muncul `4000x4000 px @ 300x300 DPI`.

---

## 🛠 Verifikasi kode cepat (saat pengembangan)

CEK sintaks JS tiap project (perlu Node.js):

```powershell
# ekstrak script lalu cek sintaks
$s = [regex]::Match((Get-Content -Raw project-51\index.html),'(?s)<script>(.*)</script>').Groups[1].Value
Set-Content -Path "$env:TEMP\c.js" -Value $s
node --check "$env:TEMP\c.js"   # harus tanpa error
```

---

## 🤖 Bantuan AI (opsional)

Pengerjaan project ini dibantu AI lewat **skill** di `.opencode/skills/`:

- `pola-baru` — alur umum membuat satu project.
- `pola-cepat` — template + checklist + verifikasi cepat (untuk mempercepat banyak project).
- `ekspor-jpg-300dpi` — menyisipkan metadata 300 DPI pada JPG.
- `verifikasi-output` — memindai semua JPG di `hasil/` (cek 4000×4000 & 300 DPI).

Skill ini dipakai asisten coding (mis. opencode). Sebagai pengembang, Anda cukup mengikuti langkah di bagian **"Cara membuat project baru"** di atas — template & skill tinggal mempermudah.

---

## 📝 Catatan
- Tidak ada test otomatis, lint, maupun CI di repo ini. Verifikasi dilakukan dengan membuka halaman di browser + memeriksa JPG di `hasil/` (lihat bagian verifikasi di atas).
- Folder `hasil/` dibuat otomatis sesuai kebutuhan saat menyimpan.
