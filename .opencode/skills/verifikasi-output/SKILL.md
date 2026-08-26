---
name: verifikasi-output
description: Use when verifying or validating output JPG files in hasil/ or any output folder ("verifikasi", "cek hasil", "validasi", "batch check", "semua file"). Scans all JPGs and checks dimensions 4000x4000, DPI 300x300, and reports mismatches.
---

# Verifikasi Output

Cek semua file JPG di folder `hasil/` (atau folder lain) sekaligus.

## PowerShell — satu file

```powershell
Add-Type -AssemblyName System.Drawing
$img = [System.Drawing.Image]::FromFile("hasil\<nama>.jpg")
"$($img.Width)x$($img.Height) px @ $([int]$img.HorizontalResolution)x$([int]$img.VerticalResolution) DPI · $([math]::Round((Get-Item "hasil\<nama>.jpg").Length/1MB,2)) MB"
$img.Dispose()
```

## PowerShell — semua file di folder

```powershell
Add-Type -AssemblyName System.Drawing
$folder = "hasil"
Get-ChildItem "$folder\*.jpg" | Sort-Object Name | ForEach-Object {
  $img = [System.Drawing.Image]::FromFile($_.FullName)
  $ok = $img.Width -eq 4000 -and $img.Height -eq 4000 -and [int]$img.HorizontalResolution -eq 300
  $status = if ($ok) { "OK" } else { "GAGAL" }
  Write-Output "$status | $($_.Name) | $($img.Width)x$($img.Height) | $([int]$img.HorizontalResolution)x$([int]$img.VerticalResolution) DPI | $([math]::Round($_.Length/1MB,2)) MB"
  $img.Dispose()
}
```

Output yang benar: semua baris `OK | ... | 4000x4000 | 300x300 DPI`.

## Kriteria kegagalan umum
- Dimensi bukan 4000x4000 → canvas tidak di-set ke resolusi penuh
- DPI bukan 300x300 → `setJfifDensity` tidak dipanggil atau applicaton/octet-stream terjadi
- Ukuran file < 0.5 MB → kualitas JPEG terlalu rendah atau pola kosong
- Ukuran file > 15 MB → kualitas terlalu tinggi atau pola terlalu detail
