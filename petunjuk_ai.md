##petunjuk_ai.md

ini adalah sebuah project untuk membuat halaman halaman website yang akan menghasilkan pola unik, pola acak yang keren, kombinasi warna keren dari perpaduan bentuk warna dan posisi, format output adalah jpg, format nama unik tempatkan file di hasil, 4000x4000px dengan 300dpi, setiap version kamu akan memikirkan 1 pola unik yang populer dengan beragam bentuk populer juga.

Panduan perilaku untuk mengurangi kesalahan coding LLM yang umum terjadi. Gabungkan dengan instruksi spesifik proyek sesuai kebutuhan.

Kompromi (Tradeoff): Panduan ini lebih mengutamakan kehati-hatian daripada kecepatan. Untuk tugas-tugas yang sepele, gunakan penilaian Anda sendiri.

1. Berpikir Sebelum Menulis Kode (Coding)
Jangan berasumsi. Jangan menyembunyikan kebingungan. Sampaikan berbagai pertimbangan (tradeoff).

Sebelum mengimplementasikan:

Nyatakan asumsi Anda secara eksplisit. Jika ragu, bertanyalah.

Jika ada beberapa interpretasi, sampaikan semuanya - jangan memilih sendiri secara diam-diam.

Jika ada pendekatan yang lebih sederhana, sampaikan. Berikan penolakan/sanggahan jika memang diperlukan.

Jika ada yang tidak jelas, berhenti. Sebutkan apa yang membingungkan. Bertanyalah.

2. Utamakan Kesederhanaan
Kode seminimal mungkin untuk menyelesaikan masalah. Jangan membuat sesuatu yang spekulatif.

Tidak ada fitur tambahan di luar apa yang diminta.

Tidak ada abstraksi untuk kode yang hanya digunakan sekali.

Tidak ada "fleksibilitas" atau "konfigurasi" yang tidak diminta.

Tidak ada error handling (penanganan kesalahan) untuk skenario yang mustahil terjadi.

Jika Anda menulis 200 baris kode padahal bisa diselesaikan dengan 50 baris, tulis ulang.

Tanyakan pada diri Anda: "Apakah seorang senior engineer akan menganggap ini terlalu rumit?" Jika ya, sederhanakan.

3. Perubahan yang Presisi (Surgical Changes)
Ubah hanya yang benar-benar perlu diubah. Bersihkan hanya kekacauan yang Anda buat sendiri.

Saat mengedit kode yang sudah ada:

Jangan "memperbaiki" kode, komentar, atau format di sekitarnya.

Jangan melakukan refactor pada sesuatu yang tidak rusak.

Sesuaikan dengan gaya penulisan (style) yang sudah ada, meskipun Anda mungkin memiliki preferensi gaya yang berbeda.

Jika Anda melihat dead code (kode yang tidak terpakai) yang tidak terkait, sebutkan saja - jangan dihapus.

Ketika perubahan Anda menyisakan kode yang tidak lagi terpakai (orphans):

Hapus import / variabel / fungsi yang menjadi tidak terpakai KARENA perubahan Anda.

Jangan menghapus dead code yang sudah ada sebelumnya kecuali jika diminta.

Ujiannya: Setiap baris yang diubah harus bisa ditelusuri secara langsung ke permintaan pengguna.

4. Eksekusi Berbasis Tujuan
Tentukan kriteria keberhasilan. Lakukan perulangan (loop) sampai terverifikasi.

Ubah tugas menjadi tujuan yang bisa diverifikasi:

"Tambahkan validasi" → "Tulis test untuk input yang tidak valid, lalu buat test tersebut berhasil (pass)"

"Perbaiki bug" → "Tulis test yang mereproduksi bug tersebut, lalu buat test tersebut berhasil (pass)"

"Refactor X" → "Pastikan test berhasil (pass) sebelum dan sesudahnya"

Untuk tugas dengan banyak langkah, sampaikan rencana singkat:

1. [Langkah] → verifikasi: [pengecekan]
2. [Langkah] → verifikasi: [pengecekan]
3. [Langkah] → verifikasi: [pengecekan]
Kriteria keberhasilan yang kuat memungkinkan Anda melakukan proses iterasi secara mandiri. Kriteria yang lemah ("pokoknya bisa jalan") membutuhkan klarifikasi terus-menerus.

Panduan ini berfungsi dengan baik jika: ada lebih sedikit perubahan yang tidak perlu pada diff, lebih sedikit penulisan ulang akibat terlalu rumit, dan pertanyaan klarifikasi muncul sebelum implementasi, bukan setelah terjadi kesalahan.