@extends('layouts.app')
@section('title','Detail Mahasiswa Bimbingan')
@section('content')
<div class="space-y-6">
    <div>
        <a href="{{ route('dosen.mahasiswa-bimbingan') }}" class="inline-flex items-center text-sm font-semibold text-slate-600 hover:text-slate-950">← Kembali</a>
        <h1 class="mt-3 text-2xl font-bold tracking-tight text-slate-950">Detail Mahasiswa Bimbingan</h1>
        <p class="mt-1 text-sm text-slate-500">Informasi mahasiswa, logbook, dan laporan magang.</p>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="text-xl font-bold text-slate-950">{{ $magang->mahasiswa?->nama }}</h2>
            <p class="mt-1 text-sm text-slate-500">{{ $magang->mahasiswa?->nim }} · {{ $magang->mahasiswa?->program_studi }}</p>
        </div>

        <div class="grid gap-5 p-6 sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Perusahaan</p>
                <p class="mt-1 font-semibold text-slate-900">{{ $magang->instansi?->nama_instansi }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Periode</p>
                <p class="mt-1 font-semibold text-slate-900">{{ $magang->tanggal_mulai?->format('d M Y') ?? '-' }} — {{ $magang->tanggal_selesai?->format('d M Y') ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Status</p>
                <div class="mt-1"><x-badge :status="$magang->status_magang"/></div>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Judul Magang</p>
                <p class="mt-1 font-semibold text-slate-900">{{ $magang->judul_magang ?: '-' }}</p>
            </div>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="font-bold text-slate-900">Logbook</h2>
                <p class="mt-1 text-sm text-slate-500">Aktivitas terbaru mahasiswa.</p>
            </div>
            <div class="px-6">
                @forelse($magang->logKegiatan->take(8) as $log)
                    <div class="border-b border-slate-200 py-4 last:border-b-0">
                        <p class="font-semibold text-slate-900">{{ $log->judul_kegiatan }}</p>
                        <p class="mt-1 text-xs text-slate-500">{{ $log->tanggal?->format('d M Y') }} · {{ ucfirst($log->status_validasi) }}</p>
                    </div>
                @empty
                    <p class="py-6 text-sm text-slate-500">Belum ada logbook.</p>
                @endforelse
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="font-bold text-slate-900">Laporan</h2>
                <p class="mt-1 text-sm text-slate-500">Laporan yang telah diunggah mahasiswa.</p>
            </div>
            <div class="px-6">
                @forelse($magang->laporan->take(8) as $lap)
                    <div class="border-b border-slate-200 py-4 last:border-b-0">
                        <p class="font-semibold text-slate-900">{{ $lap->nama_file }}</p>
                        <p class="mt-1 text-xs text-slate-500">{{ $lap->tanggal_upload?->format('d M Y H:i') }} · {{ str_replace('_',' ',$lap->status) }}</p>
                    </div>
                @empty
                    <p class="py-6 text-sm text-slate-500">Belum ada laporan.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection