@extends('layouts.app')

@section('title', 'Edit Logbook')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">
    <div>
        <p class="text-sm font-medium text-slate-500">Logbook Magang</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">Edit Kegiatan</h1>
        <p class="mt-1 text-sm text-slate-500">Perubahan hanya dapat dilakukan selama logbook masih menunggu validasi dosen.</p>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="mb-5 rounded-lg bg-slate-50 p-4 text-sm">
            <p class="font-semibold text-slate-900">{{ $magang->instansi?->nama_instansi ?? '-' }}</p>
            <p class="mt-1 text-slate-500">Periode: {{ $magang->tanggal_mulai?->format('d M Y') ?? '-' }} — {{ $magang->tanggal_selesai?->format('d M Y') ?? '-' }}</p>
        </div>

        <form method="POST" action="{{ route('mahasiswa.logbook.update', $logbook) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')
            <div>
                <label for="tanggal" class="mb-1.5 block text-sm font-semibold text-slate-700">Tanggal <span class="text-rose-500">*</span></label>
                <input id="tanggal" type="date" name="tanggal" value="{{ old('tanggal', $logbook->tanggal?->format('Y-m-d')) }}" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm">
                @error('tanggal')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="judul_kegiatan" class="mb-1.5 block text-sm font-semibold text-slate-700">Judul Kegiatan <span class="text-rose-500">*</span></label>
                <input id="judul_kegiatan" type="text" name="judul_kegiatan" value="{{ old('judul_kegiatan', $logbook->judul_kegiatan) }}" maxlength="150" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm">
                @error('judul_kegiatan')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="deskripsi" class="mb-1.5 block text-sm font-semibold text-slate-700">Deskripsi <span class="text-rose-500">*</span></label>
                <textarea id="deskripsi" name="deskripsi" rows="6" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm">{{ old('deskripsi', $logbook->deskripsi) }}</textarea>
                @error('deskripsi')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="bukti_kegiatan" class="mb-1.5 block text-sm font-semibold text-slate-700">Bukti Kegiatan</label>
                @if($logbook->bukti_kegiatan)
                    <div class="mb-3 rounded-lg border border-slate-200 bg-slate-50 p-3">
                        <p class="text-xs text-slate-500">Bukti saat ini</p>
                        <a href="{{ asset('storage/'.$logbook->bukti_kegiatan) }}" target="_blank" class="mt-1 inline-block text-sm font-semibold text-slate-700 underline">Lihat bukti kegiatan</a>
                    </div>
                @endif
                <input id="bukti_kegiatan" type="file" name="bukti_kegiatan" accept=".jpg,.jpeg,.png,.pdf" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                <p class="mt-1 text-xs text-slate-500">Kosongkan jika tetap menggunakan bukti lama. JPG, PNG, PDF. Maks. 2 MB.</p>
                @error('bukti_kegiatan')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end">
                <a href="{{ route('mahasiswa.logbook') }}" class="rounded-lg border border-slate-300 px-4 py-2.5 text-center text-sm font-semibold text-slate-700">Batal</a>
                <button class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
