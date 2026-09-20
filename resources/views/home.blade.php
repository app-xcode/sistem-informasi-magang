<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Informasi Magang untuk mengelola pengajuan, monitoring, logbook, laporan, dan penilaian magang.">
    <title>Sistem Informasi Magang</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white font-sans text-slate-900 antialiased">

    <header class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/90 backdrop-blur">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-5 sm:px-6 lg:px-8">
            <a href="{{ route('landing') }}" class="flex items-center gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-900 text-white">
                    <i class="fa-solid fa-graduation-cap text-sm"></i>
                </span>
                <div>
                    <div class="text-sm font-bold leading-tight text-slate-950">Sistem Informasi Magang</div>
                    <div class="text-[11px] text-slate-500">Platform Pengelolaan Magang</div>
                </div>
            </a>

            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">
                <i class="fa-solid fa-right-to-bracket text-xs"></i>
                Masuk
            </a>
        </div>
    </header>

    <main>
        <section class="relative overflow-hidden border-b border-slate-200 bg-slate-50">
            <div class="absolute -right-32 -top-32 h-80 w-80 rounded-full bg-sky-100/70 blur-3xl"></div>
            <div class="absolute -bottom-40 -left-32 h-96 w-96 rounded-full bg-emerald-100/50 blur-3xl"></div>

            <div class="relative mx-auto grid min-h-[620px] max-w-7xl items-center gap-12 px-5 py-20 sm:px-6 lg:grid-cols-2 lg:px-8 lg:py-24">
                <div>
                    <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-600 shadow-sm">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        Sistem Pengelolaan Magang Terintegrasi
                    </div>

                    <h1 class="max-w-2xl text-4xl font-extrabold tracking-tight text-slate-950 sm:text-5xl lg:text-6xl">
                        Kelola kegiatan magang
                        <span class="text-sky-600">lebih terstruktur.</span>
                    </h1>

                    <p class="mt-6 max-w-xl text-base leading-7 text-slate-600 sm:text-lg">
                        Satu platform untuk mengelola pengajuan magang, bimbingan, logbook,
                        laporan, monitoring, hingga penilaian secara lebih mudah dan terorganisir.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                            Mulai Sekarang
                        </a>
                        <a href="#fitur" class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            <i class="fa-solid fa-layer-group text-xs"></i>
                            Lihat Fitur
                        </a>
                    </div>

                    <div class="mt-8 flex flex-wrap gap-x-6 gap-y-3 text-sm text-slate-500">
                        <span><i class="fa-solid fa-check mr-2 text-emerald-600"></i>Berbasis peran</span>
                        <span><i class="fa-solid fa-check mr-2 text-emerald-600"></i>Monitoring terpusat</span>
                        <span><i class="fa-solid fa-check mr-2 text-emerald-600"></i>Data terdokumentasi</span>
                    </div>
                </div>

                <div class="relative">
                    <div class="rounded-2xl border border-slate-200 bg-white p-3 shadow-2xl shadow-slate-200/70">
                        <div class="overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                            <div class="flex items-center justify-between border-b border-slate-200 bg-white px-5 py-4">
                                <div>
                                    <div class="text-xs font-medium text-slate-500">Dashboard</div>
                                    <div class="mt-0.5 text-lg font-bold text-slate-900">Ringkasan Magang</div>
                                </div>
                                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-900 text-white">
                                    <i class="fa-solid fa-chart-line text-sm"></i>
                                </span>
                            </div>

                            <div class="grid gap-3 p-4 sm:grid-cols-2">
                                <div class="rounded-xl border border-slate-200 bg-white p-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-slate-500">Pengajuan</span>
                                        <i class="fa-solid fa-file-circle-check text-sky-500"></i>
                                    </div>
                                    <div class="mt-3 text-2xl font-bold text-slate-900">12</div>
                                    <div class="mt-1 text-xs text-slate-500">Data terkelola</div>
                                </div>
                                <div class="rounded-xl border border-slate-200 bg-white p-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-slate-500">Berlangsung</span>
                                        <i class="fa-solid fa-briefcase text-emerald-500"></i>
                                    </div>
                                    <div class="mt-3 text-2xl font-bold text-slate-900">8</div>
                                    <div class="mt-1 text-xs text-slate-500">Peserta aktif</div>
                                </div>
                            </div>

                            <div class="mx-4 mb-4 rounded-xl border border-slate-200 bg-white p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-semibold text-slate-800">Progress kegiatan</span>
                                    <span class="text-xs font-semibold text-slate-500">78%</span>
                                </div>
                                <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full w-[78%] rounded-full bg-sky-500"></div>
                                </div>
                                <div class="mt-4 space-y-2.5">
                                    <div class="flex items-center gap-3 text-xs">
                                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600"><i class="fa-solid fa-check"></i></span>
                                        <span class="flex-1 text-slate-600">Pengajuan disetujui</span>
                                        <span class="font-semibold text-slate-700">Selesai</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-xs">
                                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-sky-50 text-sky-600"><i class="fa-solid fa-book"></i></span>
                                        <span class="flex-1 text-slate-600">Logbook kegiatan</span>
                                        <span class="font-semibold text-slate-700">Aktif</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-xs">
                                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-50 text-amber-600"><i class="fa-solid fa-file-lines"></i></span>
                                        <span class="flex-1 text-slate-600">Laporan magang</span>
                                        <span class="font-semibold text-slate-700">Proses</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="absolute -bottom-5 -left-5 hidden rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-lg sm:block">
                        <div class="flex items-center gap-3">
                            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                                <i class="fa-solid fa-shield-halved"></i>
                            </span>
                            <div>
                                <div class="text-xs font-bold text-slate-800">Terorganisir</div>
                                <div class="text-[11px] text-slate-500">Alur magang terpantau</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="fitur" class="mx-auto max-w-7xl px-5 py-20 sm:px-6 lg:px-8 lg:py-24">
            <div class="max-w-2xl">
                <p class="text-sm font-bold uppercase tracking-wider text-sky-600">Fitur Utama</p>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">Semua proses dalam satu sistem.</h2>
                <p class="mt-4 text-slate-600">Dirancang untuk membantu mahasiswa, dosen, dan admin mengelola seluruh alur kegiatan magang.</p>
            </div>

            <div class="mt-10 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach ([
                    ['fa-file-signature', 'Pengajuan Magang', 'Ajukan dan pantau proses persetujuan magang secara terstruktur.'],
                    ['fa-user-group', 'Bimbingan Dosen', 'Dosen dapat memantau mahasiswa bimbingan dan aktivitas magang.'],
                    ['fa-book-open', 'Logbook Digital', 'Catat kegiatan magang dan lampirkan bukti kegiatan dengan mudah.'],
                    ['fa-chart-line', 'Monitoring', 'Pantau perkembangan kegiatan magang melalui data yang terpusat.'],
                    ['fa-file-lines', 'Laporan Magang', 'Upload, validasi, dan kelola dokumen laporan dalam satu alur.'],
                    ['fa-star', 'Penilaian', 'Kelola komponen penilaian dan hasil akhir mahasiswa.'],
                ] as [$icon, $title, $description])
                    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-slate-900 text-white">
                            <i class="fa-solid {{ $icon }}"></i>
                        </div>
                        <h3 class="mt-5 font-bold text-slate-900">{{ $title }}</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-500">{{ $description }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="border-y border-slate-200 bg-slate-50">
            <div class="mx-auto max-w-7xl px-5 py-16 sm:px-6 lg:px-8">
                <div class="rounded-2xl bg-slate-900 px-6 py-10 text-center shadow-xl sm:px-10">
                    <p class="text-sm font-semibold text-sky-300">Sistem Informasi Magang</p>
                    <h2 class="mt-3 text-2xl font-bold tracking-tight text-white sm:text-3xl">Mulai kelola kegiatan magang dengan lebih terarah.</h2>
                    <p class="mx-auto mt-3 max-w-2xl text-sm leading-6 text-slate-300">Masuk ke sistem untuk mengakses fitur sesuai peran Anda.</p>
                    <a href="{{ route('login') }}" class="mt-7 inline-flex items-center gap-2 rounded-lg bg-white px-5 py-3 text-sm font-semibold text-slate-900 transition hover:bg-slate-100">
                        <i class="fa-solid fa-right-to-bracket text-xs"></i>
                        Masuk ke Sistem
                    </a>
                </div>
            </div>
        </section>
    </main>

    <footer class="border-t border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-2 px-5 py-6 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
            <span>© {{ date('Y') }} Sistem Informasi Magang</span>
            <span>Platform Pengelolaan Magang</span>
        </div>
    </footer>

</body>
</html>