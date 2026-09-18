<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mahasiswa - Sistem Informasi Magang</title>
</head>
<body>

    <h1>Dashboard Mahasiswa</h1>

    <p>Selamat datang, {{ auth()->user()->name }}</p>
    <p>Role: {{ auth()->user()->role }}</p>

    <hr>

    <h2>Menu Mahasiswa</h2>

    <ul>
        <li>Profil Saya</li>
        <li>Pengajuan Magang</li>
        <li>Tempat Magang</li>
        <li>Jadwal Magang</li>
        <li>Logbook</li>
        <li>Laporan Magang</li>
    </ul>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>

</body>
</html>