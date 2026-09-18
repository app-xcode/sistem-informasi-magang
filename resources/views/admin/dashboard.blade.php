<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sistem Informasi Magang</title>
</head>
<body>

    <h1>Dashboard Admin</h1>

    <p>Selamat datang, {{ auth()->user()->name }}</p>
    <p>Role: {{ auth()->user()->role }}</p>

    <hr>

    <h2>Menu Admin</h2>

    <ul>
        <li>Kelola Mahasiswa</li>
        <li>Kelola Dosen</li>
        <li>Kelola Perusahaan</li>
        <li>Kelola Pengajuan Magang</li>
        <li>Kelola Data Magang</li>
        <li>Laporan</li>
    </ul>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>

</body>
</html>