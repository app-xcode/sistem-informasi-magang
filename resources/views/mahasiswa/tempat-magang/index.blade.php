@extends('layouts.app')
@section('title','Tempat Magang')
@section('content')
<div class="space-y-6">
    <div><h1 class="text-2xl font-bold">Tempat Magang</h1><p class="mt-1 text-sm text-slate-500">Cari informasi instansi yang tersedia untuk pengajuan magang.</p></div>
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm"><form method="GET" action="{{ route('mahasiswa.tempat-magang') }}" class="flex flex-col gap-3 lg:flex-row">
        <input name="q" value="{{ $search }}" placeholder="Cari instansi, alamat, penanggung jawab..." class="min-w-0 flex-1 rounded-lg border border-slate-300 px-4 py-2.5 text-sm">
        <select name="sort" class="rounded-lg border border-slate-300 px-3 py-2.5 text-sm"><option value="nama_instansi" @selected($sort==='nama_instansi')>Nama</option><option value="alamat" @selected($sort==='alamat')>Alamat</option><option value="penanggung_jawab" @selected($sort==='penanggung_jawab')>Penanggung Jawab</option><option value="no_telp" @selected($sort==='no_telp')>Telepon</option></select>
        <select name="direction" class="rounded-lg border border-slate-300 px-3 py-2.5 text-sm"><option value="asc" @selected($direction==='asc')>A–Z / Naik</option><option value="desc" @selected($direction==='desc')>Z–A / Turun</option></select>
        <select name="per_page" class="rounded-lg border border-slate-300 px-3 py-2.5 text-sm">@foreach([10,25,50,100] as $size)<option value="{{ $size }}" @selected($perPage===$size)>{{ $size }} / halaman</option>@endforeach</select>
        <button class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white">Filter</button><button name="export" value="excel" class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700"><i class="fa-solid fa-file-excel mr-2"></i>Export Excel</button>
        @if($search)<a href="{{ route('mahasiswa.tempat-magang') }}" class="rounded-lg border border-slate-300 px-4 py-2.5 text-center text-sm font-semibold text-slate-700">Reset</a>@endif
    </form></div>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($instansi as $item)<div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm hover:shadow-md"><h2 class="font-bold">{{ $item->nama_instansi }}</h2><p class="mt-2 text-sm text-slate-600">{{ $item->alamat }}</p><dl class="mt-4 space-y-1 text-xs text-slate-500"><div><dt class="inline font-semibold">PIC:</dt> <dd class="inline">{{ $item->penanggung_jawab ?: '-' }}</dd></div><div><dt class="inline font-semibold">Telepon:</dt> <dd class="inline">{{ $item->no_telp ?: '-' }}</dd></div></dl><a href="{{ route('mahasiswa.pengajuan.create',['instansi_id'=>$item->id]) }}" class="mt-5 inline-flex rounded-lg bg-slate-900 px-3 py-2 text-xs font-semibold text-white">Ajukan di sini</a></div>
        @empty<div class="sm:col-span-2 lg:col-span-3 rounded-xl border border-dashed border-slate-300 p-10 text-center text-sm text-slate-500">Belum ada tempat magang tersedia.</div>@endforelse
    </div>
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"><p class="text-xs text-slate-500">Menampilkan {{ $instansi->firstItem() ?? 0 }}–{{ $instansi->lastItem() ?? 0 }} dari {{ $instansi->total() }} data</p>{{ $instansi->onEachSide(1)->links() }}</div>
</div>
@endsection
