<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Katalog Pola</title>
<style>
  body { font-family: system-ui, sans-serif; background: #0e1015; color: #eee; margin: 0; padding: 32px 24px; }
  h1 { font-size: 1.5rem; font-weight: 700; margin: 0 0 8px; }
  .sub { color: #7a8290; margin: 0 0 28px; font-size: .9rem; }
  nav { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 36px; }
  nav a { color: #9aa3ad; text-decoration: none; border: 1px solid #2a2d34; border-radius: 20px; padding: 6px 14px; font-size: .85rem; transition: background .15s, color .15s; }
  nav a:hover { background: #242830; color: #fff; }
  .grup { margin-bottom: 40px; }
  .grup-hdr { display: flex; align-items: baseline; gap: 12px; margin: 0 0 4px; }
  .grup-hdr h2 { font-size: 1.15rem; font-weight: 700; margin: 0; }
  .grup-hdr .rentang { font-size: .85rem; color: #6b7280; font-weight: 500; }
  .sekat { border: 0; border-top: 1px solid #23262e; margin: 12px 0 20px; }
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
<?php
$dir = __DIR__;
$proyek = glob("$dir/project-*/index.html");
sort($proyek);

$daftar = [];
foreach ($proyek as $html) {
    $folder = basename(dirname($html));
    $nomor = (int) preg_replace('/[^0-9]/', '', $folder);
    $judul = $folder;
    if (preg_match('/<title>(.*?)<\/title>/i', file_get_contents($html), $m)) {
        $judul = preg_replace('/^Project \d+ — /', '', trim($m[1]));
    }
    $daftar[] = ['nomor' => $nomor, 'folder' => $folder, 'judul' => $judul];
}

if (empty($daftar)) {
    echo '<p class="kosong">Belum ada project.</p>';
} else {
    // Urutkan numerik (1,2,3,...,10) bukan alfabet (1,10,2)
    usort($daftar, function ($a, $b) { return $a['nomor'] - $b['nomor']; });

    // Kelompokkan menurut rentang 10: Project I (1-10), II (11-20), III (21-30), dst.
    $romawi = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X',
               'XI', 'XII', 'XIII', 'XIV', 'XV'];
    $grup = [];
    foreach ($daftar as $d) {
        $key = (int) ceil($d['nomor'] / 10);
        $grup[$key][] = $d;
    }
    ksort($grup);
    $grupKeys = array_keys($grup);

    // Navigasi cepat antar grup
    echo '<nav>';
    foreach ($grupKeys as $key) {
        $label = isset($romawi[$key]) ? $romawi[$key] : (string) $key;
        echo '<a href="#grup-' . $key . '">Project ' . $label . '</a>';
    }
    echo '</nav>' . "\n";

    foreach ($grupKeys as $key) {
        $label = isset($romawi[$key]) ? $romawi[$key] : (string) $key;
        $min = ($key - 1) * 10 + 1;
        $max = $key * 10;
        echo '<section class="grup" id="grup-' . $key . '">' . "\n";
        echo '<div class="grup-hdr"><h2>Project ' . $label . '</h2>'
           . '<span class="rentang">(' . $min . '&ndash;' . $max . ')</span></div>' . "\n";
        echo '<hr class="sekat">' . "\n";
        echo '<div class="grid">' . "\n";
        foreach ($grup[$key] as $d) {
            echo '<a class="kartu" href="' . $d['folder'] . '/">'
               . '<div class="nomor">Project ' . $d['nomor'] . '</div>'
               . '<div class="judul">' . htmlspecialchars($d['judul']) . '</div>'
               . '</a>' . "\n";
        }
        echo '</div>' . "\n";
        echo '</section>' . "\n";
    }
}
?>
</body>
</html>
