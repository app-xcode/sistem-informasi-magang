@extends('layouts.app')
@section('title','Form Penilaian')
@section('content')
<div class="space-y-6">
    <div>
        <a href="{{ route('dosen.penilaian') }}" class="inline-flex items-center text-sm font-semibold text-slate-600 hover:text-slate-950">← Kembali</a>
        <h1 class="mt-3 text-2xl font-bold tracking-tight text-slate-950">Penilaian Magang</h1>
        <p class="mt-1 text-sm text-slate-500">{{ $magang->mahasiswa?->nama }} · {{ $magang->mahasiswa?->nim }} · {{ $magang->instansi?->nama_instansi }}</p>
    </div>
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="font-bold text-slate-900">Form Penilaian</h2>
            <p class="mt-1 text-sm text-slate-500">Isi nilai setiap komponen dengan rentang 0 sampai 100.</p>
        </div>
        <form method="POST" action="{{ route('dosen.penilaian.store',$magang) }}" class="p-6">@csrf
            <div class="grid gap-5 sm:grid-cols-2">
                @foreach(['kedisiplinan'=>'Kedisiplinan','tanggung_jawab'=>'Tanggung Jawab','kerja_sama'=>'Kerja Sama','kemampuan_teknis'=>'Kemampuan Teknis','sikap'=>'Sikap'] as $name=>$label)
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">{{ $label }}</label>
                        <input type="number" name="{{ $name }}" min="0" max="100" step="0.01" value="{{ old($name,$magang->penilaian?->{$name}) }}" class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200" required>
                        @error($name)<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                @endforeach
            </div>
            <div class="mt-5">
                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Catatan</label>
                <textarea name="catatan" rows="5" class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200">{{ old('catatan',$magang->penilaian?->catatan) }}</textarea>
            </div>
            <div class="mt-5 rounded-lg border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">Nilai akhir dihitung otomatis dari rata-rata 5 komponen.</div>
            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <a href="{{ route('dosen.penilaian') }}" class="rounded-lg border border-slate-300 px-5 py-2.5 text-center text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</a>
                <button class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">Simpan Penilaian</button>
            </div>
        </form>
    </div>
</div>
@endsection