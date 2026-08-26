---
name: pola-baru
description: Use when the user asks to create or generate a new pattern or version ("buat pola", "pola baru", "versi baru", "generator pola", "new pattern"). Full workflow for a pattern generator page that must meet the output spec: JPG 4000x4000px 300 DPI, unique filenames saved into hasil/.
---

# Pola Baru

Alur kerja membuat satu versi generator pola.

## 1. Usulkan ide dulu
Setiap versi mengusulkan **1 pola populer** dengan ragam bentuk populer juga (mis. polkadot, chevron, batik, memphis, zigzag, heksagon). Sampaikan idenya sebelum coding. Jika ada beberapa interpretasi permintaan pengguna, sampaikan semuanya — jangan pilih diam-diam.

## 2. Implementasi
- Halaman statis HTML + JS, render via Canvas API. Tanpa framework / build tool / npm.
- Canvas resolusi penuh **4000x4000px** (atribut width/height canvas, bukan ukuran CSS). Jangan render kecil lalu di-scale.
- Pakai PRNG ber-seed (mis. mulberry32) dan tampilkan seed-nya, agar pola bisa direproduksi saat debug.

## 3. Ekspor
- Simpan JPG ke folder `hasil/` dengan nama file unik, mis. `<nama-pola>-<seed>-<timestamp>.jpg`.
- Selalu ekspor lewat skill `ekspor-jpg-300dpi` supaya metadata 300 DPI benar-benar tertulis di file — `toBlob()` mentah tidak menyimpan DPI.

## 4. Verifikasi
- Buka halaman di browser (file langsung atau localhost USBWebServer), generate beberapa kali.
- Pastikan file muncul di `hasil/`. Validasi dimensi + DPI dari file via PowerShell:

```powershell
Add-Type -AssemblyName System.Drawing
$img = [System.Drawing.Image]::FromFile("hasil\<nama-file>.jpg")
"$($img.Width)x$($img.Height) px @ $([int]$img.HorizontalResolution)x$([int]$img.VerticalResolution) DPI"
$img.Dispose()
```
