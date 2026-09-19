@extends('layouts.app')

@section('title', 'Detail Magang')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500">Data Magang</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">Detail Magang</h1>
            <p class="mt-1 text-sm text-slate-500">{{ $magang->judul_magang ?: 'Magang Mahasiswa' }}</p>
        </div>
        <a href="{{ route('admin.magang.index') }}" class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Kembali</a>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-base font-bold text-slate-900">Informasi Magang</h2>
                <dl class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Judul</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-900">{{ $magang->judul_magang ?: '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Tanggal Pengajuan</dt>
                        <dd class="mt-1 text-sm text-slate-700">{{ $magang->tanggal_pengajuan?->format('d M Y') ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Tanggal Mulai</dt>
                        <dd class="mt-1 text-sm text-slate-700">{{ $magang->tanggal_mulai?->format('d M Y') ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Tanggal Selesai</dt>
                        <dd class="mt-1 text-sm text-slate-700">{{ $magang->tanggal_selesai?->format('d M Y') ?? '-' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Keterangan</dt>
                        <dd class="mt-1 whitespace-pre-line text-sm text-slate-700">{{ $magang->keterangan ?: '-' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h2 class="text-sm font-bold text-slate-900">Mahasiswa</h2>
                    <p class="mt-3 font-semibold text-slate-900">{{ $magang->mahasiswa?->nama ?? '-' }}</p>
                    <p class="mt-1 text-sm text-slate-500">NIM: {{ $magang->mahasiswa?->nim ?? '-' }}</p>
                    <p class="mt-1 text-sm text-slate-500">{{ $magang->mahasiswa?->program_studi ?? '-' }}</p>
                    <p class="mt-3 text-xs text-slate-500">{{ $magang->mahasiswa?->no_hp ?? '-' }}</p>
                </div>

                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h2 class="text-sm font-bold text-slate-900">Dosen Pembimbing</h2>
                    <p class="mt-3 font-semibold text-slate-900">{{ $magang->dosen?->nama ?? '-' }}</p>
                    <p class="mt-1 text-sm text-slate-500">NIDN: {{ $magang->dosen?->nidn ?? '-' }}</p>
                    <p class="mt-3 text-xs text-slate-500">{{ $magang->dosen?->no_hp ?? '-' }}</p>
                </div>

                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h2 class="text-sm font-bold text-slate-900">Perusahaan / Instansi</h2>
                    <p class="mt-3 font-semibold text-slate-900">{{ $magang->instansi?->nama_instansi ?? '-' }}</p>
                    <p class="mt-1 text-sm text-slate-500">{{ $magang->instansi?->alamat ?? '-' }}</p>
                    <p class="mt-3 text-xs text-slate-500">{{ $magang->instansi?->no_telp ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-base font-bold text-slate-900">Status Magang</h2>
                @php
                    $statusClass = match($magang->status_magang) {
                        'berlangsung' => 'bg-sky-50 text-sky-700',
                        'selesai' => 'bg-emerald-50 text-emerald-700',
                        default => 'bg-amber-50 text-amber-700',
                    };
                    $statusLabel = match($magang->status_magang) {
                        'belum_mulai' => 'Belum Mulai',
                        'berlangsung' => 'Berlangsung',
                        'selesai' => 'Selesai',
                        default => ucfirst($magang->status_magang),
                    };
                @endphp
                <span class="mt-3 inline-flex rounded-full px-3 py-1.5 text-xs font-semibold {{ $statusClass }}">{{ $statusLabel }}</span>

                <form method="POST" action="{{ route('admin.magang.status', $magang) }}" class="mt-6 space-y-3">
                    @csrf
                    @method('PATCH')
                    <label for="status_magang" class="block text-sm font-semibold text-slate-700">Ubah status</label>
                    <select id="status_magang" name="status_magang" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
                        <option value="belum_mulai" @selected($magang->status_magang === 'belum_mulai')>Belum Mulai</option>
                        <option value="berlangsung" @selected($magang->status_magang === 'berlangsung')>Berlangsung</option>
                        <option value="selesai" @selected($magang->status_magang === 'selesai')>Selesai</option>
                    </select>
                    @error('status_magang')<p class="text-xs text-rose-600">{{ $message }}</p>@enderror
                    <button class="w-full rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">Simpan Status</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
