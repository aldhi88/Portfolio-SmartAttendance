<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Hapus Akun - Smart ITD</title>
  <link rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        crossorigin="anonymous">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="card shadow-sm">
    <div class="card-body">
      <h1 class="h4 mb-3">Permintaan Penghapusan Akun</h1>
      <p>
        Aplikasi <strong>Smart ITD</strong> digunakan oleh karyawan internal
        <strong>PT Altekno Indonesia</strong> untuk keperluan validasi kehadiran kerja.
      </p>

      <p>
        Akun pengguna dibuat dan dikelola oleh tim HRD atau IT internal.
        Pengguna tidak dapat menghapus akun secara mandiri melalui aplikasi.
        Jika Anda ingin meminta penghapusan akun dan data terkait,
        silakan hubungi administrator dengan informasi berikut:
      </p>

      <ul>
        <li>Email: <a href="mailto:support@altekno.id">support@altekno.id</a></li>
        <li>Subjek: <em>Permintaan Hapus Akun Smart ITD</em></li>
        <li>Sertakan ID karyawan dan alasan permintaan penghapusan.</li>
      </ul>

      <p>
        Setelah diverifikasi, data terkait akun Anda akan dihapus sesuai kebijakan retensi
        internal perusahaan dan hukum yang berlaku.
      </p>

      <hr>
      <p class="text-muted small mb-0">
        Terakhir diperbarui: {{ now()->format('d M Y') }}
      </p>
    </div>
  </div>
</div>
</body>
</html>
