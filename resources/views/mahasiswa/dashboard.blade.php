@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('content')
    <div>
        <p class="text-sm font-medium text-slate-500">Ringkasan Magang</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">Dashboard Mahasiswa</h1>
        <p class="mt-1 text-sm text-slate-500">Pantau status pengajuan, kegiatan, dan dokumen magang kamu.</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-card title="Status Pengajuan" :value="$magang ? str_replace('_', ' ', $magang->status_pengajuan) : 'Belum Mengajukan'" icon="fa-file-circle-check" icon-class="bg-amber-50 text-amber-700" />
        <x-card title="Logbook" :value="$logbookCount" icon="fa-book-open" icon-class="bg-slate-100 text-slate-700" />
        <x-card title="Logbook Disetujui" :value="$logbookStatus['disetujui']" icon="fa-circle-check" icon-class="bg-emerald-50 text-emerald-700" />
        <x-card title="Logbook Menunggu" :value="$logbookStatus['menunggu']" icon="fa-clock" icon-class="bg-sky-50 text-sky-700" />
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm lg:col-span-2">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Profil Mahasiswa</p>
                    <h2 class="mt-1 text-lg font-bold text-slate-950">{{ $mahasiswa?->nama ?? 'Profil belum tersedia' }}</h2>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-100 text-slate-700">
                    <i class="fa-solid fa-user"></i>
                </div>
            </div>

            @if ($mahasiswa)
                <dl class="mt-5 grid gap-4 sm:grid-cols-3">
                    <div>
                        <dt class="text-xs font-medium text-slate-500">NIM</dt>
                        <dd class="mt-1 font-semibold text-slate-900">{{ $mahasiswa->nim }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500">Program Studi</dt>
                        <dd class="mt-1 font-semibold text-slate-900">{{ $mahasiswa->program_studi }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500">Status Magang</dt>
                        <dd class="mt-1"><x-badge :status="$magang?->status_magang ?? 'belum_mengajukan'" /></dd>
                    </div>
                </dl>
            @else
                <div class="mt-5 rounded-lg border border-dashed border-slate-300 p-5 text-sm text-slate-500">
                    Profil mahasiswa belum tersedia untuk akun ini.
                </div>
            @endif
        </div>

        <x-chart
            type="doughnut"
            title="Status Logbook"
            description="Distribusi validasi logbook kamu."
            :labels="['Menunggu', 'Disetujui', 'Ditolak']"
            :data="[$logbookStatus['menunggu'], $logbookStatus['disetujui'], $logbookStatus['ditolak']]"
        />
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <x-card title="Ringkasan Magang">
            @if ($magang)
                <dl class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-medium text-slate-500">Instansi</dt>
                        <dd class="mt-1 font-semibold text-slate-950">{{ $magang->instansi?->nama_instansi ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500">Dosen Pembimbing</dt>
                        <dd class="mt-1 font-semibold text-slate-950">{{ $magang->dosen?->nama ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500">Tanggal Mulai</dt>
                        <dd class="mt-1 font-semibold text-slate-950">{{ $magang->tanggal_mulai?->format('d M Y') ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500">Tanggal Selesai</dt>
                        <dd class="mt-1 font-semibold text-slate-950">{{ $magang->tanggal_selesai?->format('d M Y') ?? '-' }}</dd>
                    </div>
                </dl>
            @else
                <div class="rounded-lg border border-dashed border-slate-300 p-5 text-sm text-slate-500">
                    Belum ada data magang. Silakan ajukan magang melalui menu Pengajuan Magang.
                </div>
            @endif
        </x-card>

        <x-card title="Logbook Terakhir">
            <div class="flex items-start gap-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                    <i class="fa-solid fa-book-open"></i>
                </div>
                <div class="min-w-0">
                    <p class="font-semibold text-slate-900">{{ $logbookTerakhir?->judul_kegiatan ?? 'Belum ada logbook' }}</p>
                    @if ($logbookTerakhir)
                        <p class="mt-1 text-sm text-slate-500">{{ $logbookTerakhir->tanggal?->format('d M Y') }}</p>
                        <div class="mt-3"><x-badge :status="$logbookTerakhir->status_validasi" /></div>
                    @endif
                </div>
            </div>
        </x-card>
    </div>

    <x-card title="Quick Action">
        <div class="flex flex-wrap gap-3">
            <x-button href="{{ route('mahasiswa.pengajuan.create') }}">Ajukan Magang</x-button>
            <x-button href="{{ route('mahasiswa.logbook') }}" variant="secondary">Tambah Logbook</x-button>
            <x-button href="{{ route('mahasiswa.status-magang') }}" variant="secondary">Lihat Status</x-button>
            <x-button href="{{ route('mahasiswa.laporan') }}" variant="secondary">Upload Laporan</x-button>
        </div>
    </x-card>
@endsection
