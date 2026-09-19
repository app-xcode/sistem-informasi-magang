@extends('layouts.app')

@section('title', 'Logbook Magang')

@section('content')
<div class="space-y-6">
    <div>
        <p class="text-sm font-medium text-slate-500">Aktivitas Magang</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">Logbook Magang</h1>
        <p class="mt-1 text-sm text-slate-500">Catat kegiatan harian selama pelaksanaan magang dan pantau hasil validasi dosen.</p>
    </div>

    @if(!$magang)
        <div class="rounded-xl border border-amber-200 bg-amber-50 p-5 text-sm text-amber-800">
            Anda belum memiliki magang yang disetujui dan aktif. Logbook dapat ditambahkan setelah pengajuan magang disetujui.
        </div>
    @else
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div><p class="text-xs uppercase tracking-wide text-slate-500">Mahasiswa</p><p class="mt-1 font-semibold">{{ $mahasiswa?->nama ?? '-' }}</p></div>
                <div><p class="text-xs uppercase tracking-wide text-slate-500">Perusahaan</p><p class="mt-1 font-semibold">{{ $magang->instansi?->nama_instansi ?? '-' }}</p></div>
                <div><p class="text-xs uppercase tracking-wide text-slate-500">Dosen Pembimbing</p><p class="mt-1 font-semibold">{{ $magang->dosen?->nama ?? '-' }}</p></div>
                <div><p class="text-xs uppercase tracking-wide text-slate-500">Periode</p><p class="mt-1 font-semibold">{{ $magang->tanggal_mulai?->format('d M Y') ?? '-' }} — {{ $magang->tanggal_selesai?->format('d M Y') ?? '-' }}</p></div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[380px_1fr]">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-base font-bold text-slate-900">Tambah Kegiatan</h2>
                <form method="POST" action="{{ route('mahasiswa.logbook.store') }}" enctype="multipart/form-data" class="mt-5 space-y-4">
                    @csrf
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Tanggal <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', now()->format('Y-m-d')) }}" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm">
                        @error('tanggal')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Judul Kegiatan <span class="text-rose-500">*</span></label>
                        <input type="text" name="judul_kegiatan" value="{{ old('judul_kegiatan') }}" maxlength="150" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm">
                        @error('judul_kegiatan')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Deskripsi <span class="text-rose-500">*</span></label>
                        <textarea name="deskripsi" rows="5" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Bukti Kegiatan</label>
                        <input type="file" name="bukti_kegiatan" accept=".jpg,.jpeg,.png,.pdf" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                        <p class="mt-1 text-xs text-slate-500">JPG, PNG, PDF. Maks. 2 MB.</p>
                        @error('bukti_kegiatan')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <button class="w-full rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">Simpan Kegiatan</button>
                </form>
            </div>

            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="font-bold text-slate-900">Riwayat Kegiatan</h2>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($logbook as $item)
                        @php
                            $class = match($item->status_validasi) {
                                'disetujui' => 'bg-emerald-50 text-emerald-700',
                                'ditolak' => 'bg-rose-50 text-rose-700',
                                default => 'bg-amber-50 text-amber-700',
                            };
                            $label = ucfirst($item->status_validasi);
                        @endphp
                        <div class="p-5">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $item->judul_kegiatan }}</p>
                                    <p class="text-xs text-slate-500">{{ $item->tanggal?->format('d M Y') }}</p>
                                </div>
                                <span class="inline-flex w-fit rounded-full px-2.5 py-1 text-xs font-semibold {{ $class }}">{{ $label }}</span>
                            </div>
                            <p class="mt-3 whitespace-pre-line text-sm text-slate-600">{{ $item->deskripsi }}</p>
                            @if($item->bukti_kegiatan)
                                <a href="{{ asset('storage/'.$item->bukti_kegiatan) }}" target="_blank" class="mt-3 inline-block text-sm font-semibold text-slate-700 underline">Lihat bukti</a>
                            @endif
                            @if($item->catatan_dosen)
                                <div class="mt-3 rounded-lg bg-slate-50 p-3 text-sm text-slate-600"><strong>Catatan dosen:</strong> {{ $item->catatan_dosen }}</div>
                            @endif
                            @if($item->status_validasi === 'menunggu')
                                <form method="POST" action="{{ route('mahasiswa.logbook.destroy', $item) }}" class="mt-3" onsubmit="return confirm('Hapus logbook ini?')">
                                    @csrf @method('DELETE')
                                    <button class="text-xs font-semibold text-rose-600 hover:text-rose-700">Hapus</button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <div class="p-10 text-center text-sm text-slate-500">Belum ada kegiatan logbook.</div>
                    @endforelse
                </div>
                @if(method_exists($logbook, 'hasPages') && $logbook->hasPages())
                    <div class="border-t border-slate-200 px-5 py-4">{{ $logbook->links() }}</div>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
