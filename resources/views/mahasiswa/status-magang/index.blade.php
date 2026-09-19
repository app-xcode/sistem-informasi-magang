@extends('layouts.app')
@section('title', 'Status Magang')
@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold">Status Magang</h1>
        <p class="mt-1 text-sm text-slate-500">Pantau seluruh riwayat pengajuan dan pelaksanaan magang Anda.</p>
    </div>

    <div class="space-y-4">
        @forelse($magang as $item)
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h2 class="font-bold text-slate-900">{{ $item->judul_magang }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ $item->instansi?->nama_instansi ?? '-' }}</p>
                    </div>

                    <div class="flex gap-2">
                        <x-badge :status="$item->status_pengajuan" />
                        <x-badge :status="$item->status_magang" />
                    </div>
                </div>

                <div class="mt-5 grid gap-4 text-sm sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <p class="text-xs text-slate-500">Pengajuan</p>
                        <p class="font-semibold">{{ $item->tanggal_pengajuan?->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Mulai</p>
                        <p class="font-semibold">{{ $item->tanggal_mulai?->format('d M Y') ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Selesai</p>
                        <p class="font-semibold">{{ $item->tanggal_selesai?->format('d M Y') ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Pembimbing</p>
                        <p class="font-semibold">{{ $item->dosen?->nama ?? 'Belum ditentukan' }}</p>
                    </div>
                </div>

                @if($item->status_pengajuan === 'ditolak')
                    <div class="mt-4 rounded-lg bg-rose-50 p-3 text-sm text-rose-700">
                        <b>Alasan penolakan:</b> {{ $item->alasan_penolakan ?: '-' }}
                    </div>
                @endif

                @if($item->status_pengajuan === 'disetujui')
                    <div class="mt-4 flex flex-wrap gap-3">
                        <a href="{{ route('mahasiswa.logbook') }}" class="text-sm font-semibold underline">
                            Logbook ({{ $item->logKegiatan->count() }})
                        </a>
                        <a href="{{ route('mahasiswa.laporan') }}" class="text-sm font-semibold underline">
                            Laporan ({{ $item->laporan->count() }})
                        </a>
                    </div>
                @endif
            </div>
        @empty
            <div class="rounded-xl border border-dashed border-slate-300 p-10 text-center text-sm text-slate-500">
                Belum ada riwayat pengajuan magang.
            </div>
        @endforelse
    </div>
</div>
@endsection