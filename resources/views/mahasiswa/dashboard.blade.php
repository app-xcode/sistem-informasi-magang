@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('content')
    <div class="grid gap-4 lg:grid-cols-3">
        <x-card title="Informasi Mahasiswa" class="lg:col-span-2">
            @if ($mahasiswa)
                <dl class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm text-slate-500">Nama</dt>
                        <dd class="font-semibold text-slate-950">{{ $mahasiswa->nama }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-slate-500">NIM</dt>
                        <dd class="font-semibold text-slate-950">{{ $mahasiswa->nim }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-slate-500">Program Studi</dt>
                        <dd class="font-semibold text-slate-950">{{ $mahasiswa->program_studi }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-slate-500">Status</dt>
                        <dd><x-badge :status="$magang?->status_magang ?? 'belum_mengajukan'" /></dd>
                    </div>
                </dl>
            @else
                <div class="rounded-md border border-dashed border-slate-300 p-6 text-sm text-slate-500">
                    Profil mahasiswa belum tersedia untuk akun ini.
                </div>
            @endif
        </x-card>

        <x-card
            title="Status Magang"
            :value="$magang ? str_replace('_', ' ', $magang->status_pengajuan) : 'Belum Mengajukan'"
            description="Status pengajuan terbaru."
        />
    </div>

    <div class="grid gap-4 lg:grid-cols-2">
        <x-card title="Ringkasan Magang">
            @if ($magang)
                <dl class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm text-slate-500">Perusahaan</dt>
                        <dd class="font-semibold text-slate-950">{{ $magang->instansi?->nama_instansi ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-slate-500">Dosen Pembimbing</dt>
                        <dd class="font-semibold text-slate-950">{{ $magang->dosen?->nama ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-slate-500">Tanggal Mulai</dt>
                        <dd class="font-semibold text-slate-950">{{ $magang->tanggal_mulai?->format('d M Y') ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-slate-500">Tanggal Selesai</dt>
                        <dd class="font-semibold text-slate-950">{{ $magang->tanggal_selesai?->format('d M Y') ?? '-' }}</dd>
                    </div>
                </dl>
            @else
                <div class="rounded-md border border-dashed border-slate-300 p-6 text-sm text-slate-500">
                    Belum ada data magang. Ajukan magang setelah modul pengajuan tersedia.
                </div>
            @endif
        </x-card>

        <x-card title="Logbook">
            <dl class="grid gap-4 sm:grid-cols-3">
                <div>
                    <dt class="text-sm text-slate-500">Jumlah</dt>
                    <dd class="text-2xl font-semibold text-slate-950">{{ $logbookCount }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-sm text-slate-500">Terakhir</dt>
                    <dd class="font-semibold text-slate-950">{{ $logbookTerakhir?->judul_kegiatan ?? 'Belum ada logbook' }}</dd>
                    @if ($logbookTerakhir)
                        <dd class="mt-2"><x-badge :status="$logbookTerakhir->status_validasi" /></dd>
                    @endif
                </div>
            </dl>
        </x-card>
    </div>

    <x-card title="Quick Action">
        <div class="flex flex-wrap gap-3">
            <x-button href="#">Ajukan Magang</x-button>
            <x-button href="#" variant="secondary">Tambah Logbook</x-button>
            <x-button href="#" variant="secondary">Lihat Status</x-button>
            <x-button href="#" variant="secondary">Upload Laporan</x-button>
        </div>
    </x-card>
@endsection
