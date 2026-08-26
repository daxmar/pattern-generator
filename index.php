<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Katalog Pola</title>
<style>
  body { font-family: system-ui, sans-serif; background: #0e1015; color: #eee; margin: 0; padding: 32px 24px; }
  h1 { font-size: 1.4rem; font-weight: 700; margin: 0 0 8px; }
  .sub { color: #7a8290; margin: 0 0 32px; font-size: .9rem; }
  .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 16px; }
  .kartu { background: #1a1d24; border-radius: 10px; padding: 24px 20px; text-decoration: none; color: #eee; transition: background .15s, transform .15s; border: 1px solid #2a2d34; }
  .kartu:hover { background: #242830; transform: translateY(-2px); }
  .kartu .nomor { font-size: .75rem; color: #5a6270; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 8px; }
  .kartu .judul { font-size: 1.1rem; font-weight: 600; }
  .kosong { color: #5a6270; font-size: .9rem; margin-top: 24px; }
</style>
</head>
<body>
<h1>Katalog Generator Pola</h1>
<p class="sub">Klik untuk membuka generator</p>
<div class="grid">
<?php
$dir = __DIR__;
$proyek = glob("$dir/project-*/index.html");
sort($proyek);

foreach ($proyek as $html) {
    $folder = basename(dirname($html));
    $judul = $folder;
    if (preg_match('/<title>(.*?)<\/title>/i', file_get_contents($html), $m)) {
        $judul = preg_replace('/^Project \d+ — /', '', trim($m[1]));
    }
    $nomor = preg_replace('/[^0-9]/', '', $folder);
    echo '<a class="kartu" href="' . $folder . '/">'
       . '<div class="nomor">Project ' . $nomor . '</div>'
       . '<div class="judul">' . htmlspecialchars($judul) . '</div>'
       . '</a>' . "\n";
}

if (empty($proyek)) {
    echo '<p class="kosong">Belum ada project.</p>';
}
?>
</div>
</body>
</html>
