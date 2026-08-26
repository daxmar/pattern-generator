---
name: ekspor-jpg-300dpi
description: Use ONLY when exporting or saving pattern output as JPG, or when 300 DPI correctness matters ("export jpg", "simpan hasil", "unduh jpg", "300 dpi"). Patches JPEG JFIF density metadata so downloaded files truly report 300 DPI — canvas.toBlob()/toDataURL() alone silently drop DPI metadata.
---

# Ekspor JPG 300 DPI

Spesifikasi project mewajibkan JPG 4000x4000px **300 DPI**. Browser (`canvas.toBlob()` / `toDataURL()`) menulis JFIF dengan density default, bukan 300 — file tetap sah sebagai JPG tapi gagal jika DPI divalidasi dari file. Solusinya: patch byte segmen APP0 JFIP setelah `toBlob()`.

## Kode siap pakai

```js
// Ekspor canvas -> unduh JPG dengan metadata 300 DPI
function unduhJpg300Dpi(canvas, filename, dpi = 300, kualitas = 0.92) {
  canvas.toBlob(async (blob) => {
    const bytes = new Uint8Array(await blob.arrayBuffer());
    const url = URL.createObjectURL(
      new Blob([setJfifDensity(bytes, dpi)], { type: 'image/jpeg' })
    );
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    a.click();
    setTimeout(() => URL.revokeObjectURL(url), 1000);
  }, 'image/jpeg', kualitas);
}

// Set Xdensity/Ydensity pada segmen APP0 JFIF; sisipkan APP0 bila belum ada.
function setJfifDensity(bytes, dpi) {
  if (bytes[0] !== 0xff || bytes[1] !== 0xd8) throw new Error('bukan JPEG');
  let i = 2;
  while (i + 3 < bytes.length && bytes[i] === 0xff) {
    const marker = bytes[i + 1];
    if (marker === 0xe0) { // APP0: units=i+11, Xdensity=i+12..13, Ydensity=i+14..15
      const out = bytes.slice();
      out[i + 11] = 1; // units = dot per inch
      out[i + 12] = (dpi >> 8) & 0xff;
      out[i + 13] = dpi & 0xff;
      out[i + 14] = (dpi >> 8) & 0xff;
      out[i + 15] = dpi & 0xff;
      return out;
    }
    if (marker === 0xda) break; // mulai data scan — berhenti
    i += 2 + ((bytes[i + 2] << 8) | bytes[i + 3]); // loncat ke segmen berikutnya
  }
  // Tidak ada APP0: sisipkan satu setelah SOI
  const app0 = Uint8Array.of(
    0xff, 0xe0, 0x00, 0x10, 0x4a, 0x46, 0x49, 0x46, 0x00, // FFE0, len=16, "JFIF\0"
    0x01, 0x02,             // versi 1.2
    0x01,                   // units = dot per inch
    (dpi >> 8) & 0xff, dpi & 0xff,
    (dpi >> 8) & 0xff, dpi & 0xff,
    0x00, 0x00              // tanpa thumbnail
  );
  const out = new Uint8Array(app0.length + bytes.length - 2);
  out.set(bytes.subarray(0, 2), 0);
  out.set(app0, 2);
  out.set(bytes.subarray(2), 2 + app0.length);
  return out;
}
```

## Verifikasi

Validasi dari file yang sudah tersimpan, bukan dari angka di kode:

```powershell
Add-Type -AssemblyName System.Drawing
$img = [System.Drawing.Image]::FromFile("hasil\<nama-file>.jpg")
"$($img.Width)x$($img.Height) px @ $([int]$img.HorizontalResolution)x$([int]$img.VerticalResolution) DPI"
$img.Dispose()
```

Output yang benar: `4000x4000 px @ 300x300 DPI`.
