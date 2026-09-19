@extends('layouts.app')
@section('title','Form Penilaian')
@section('content')
<div class="mx-auto max-w-3xl space-y-6"><a href="{{ route('dosen.penilaian') }}" class="text-sm font-semibold underline">← Kembali</a><div><h1 class="text-2xl font-bold">Penilaian Magang</h1><p class="text-sm text-slate-500">{{ $magang->mahasiswa?->nama }} · {{ $magang->instansi?->nama_instansi }}</p></div>
<div class="rounded-xl border bg-white p-6 shadow-sm"><form method="POST" action="{{ route('dosen.penilaian.store',$magang) }}" class="space-y-5">@csrf
@foreach(['kedisiplinan'=>'Kedisiplinan','tanggung_jawab'=>'Tanggung Jawab','kerja_sama'=>'Kerja Sama','kemampuan_teknis'=>'Kemampuan Teknis','sikap'=>'Sikap'] as $name=>$label)<div><label class="mb-1 block text-sm font-semibold">{{ $label }}</label><input type="number" name="{{ $name }}" min="0" max="100" step="0.01" value="{{ old($name,$magang->penilaian?->{$name}) }}" class="w-full rounded-lg border p-3" required>@error($name)<p class="text-xs text-rose-600">{{ $message }}</p>@enderror</div>@endforeach
<div><label class="mb-1 block text-sm font-semibold">Catatan</label><textarea name="catatan" rows="4" class="w-full rounded-lg border p-3">{{ old('catatan',$magang->penilaian?->catatan) }}</textarea></div>
<div class="rounded-lg bg-slate-50 p-3 text-xs text-slate-600">Nilai akhir dihitung otomatis dari rata-rata 5 komponen.</div><button class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white">Simpan Penilaian</button></form></div></div>
@endsection