<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dosen - Sistem Informasi Magang</title>
</head>
<body>

    <h1>Dashboard Dosen</h1>

    <p>Selamat datang, {{ auth()->user()->name }}</p>
    <p>Role: {{ auth()->user()->role }}</p>

    <hr>

    <h2>Menu Dosen</h2>

    <ul>
        <li>Profil Saya</li>
        <li>Mahasiswa Bimbingan</li>
        <li>Pengajuan Magang</li>
        <li>Monitoring Magang</li>
        <li>Logbook Mahasiswa</li>
        <li>Penilaian Magang</li>
        <li>Laporan Magang</li>
    </ul>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>

</body>
</html>