@extends('layouts.app')
@section('title','Pengajuan Magang')
@section('content')
<div class="space-y-6">
    <div><h1 class="text-2xl font-bold tracking-tight text-slate-950">Pengajuan Magang</h1><p class="mt-1 text-sm text-slate-500">Pengajuan mahasiswa yang ditugaskan kepada Anda.</p></div>
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('dosen.pengajuan') }}" class="flex flex-col gap-3 lg:flex-row">
            <input name="q" value="{{ $search }}" type="search" placeholder="Cari mahasiswa, NIM, judul, atau perusahaan..." class="min-w-0 flex-1 rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
            <select name="status" class="rounded-lg border border-slate-300 px-3 py-2.5 text-sm"><option value="">Semua</option><option value="diajukan" @selected($status==='diajukan')>Menunggu</option><option value="disetujui" @selected($status==='disetujui')>Disetujui</option><option value="ditolak" @selected($status==='ditolak')>Ditolak</option></select>
            <select name="per_page" class="rounded-lg border border-slate-300 px-3 py-2.5 text-sm">@foreach([10,25,50,100] as $size)<option value="{{ $size }}" @selected($perPage===$size)>{{ $size }} / halaman</option>@endforeach</select>
            <button class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white">Filter</button>
            <button name="export" value="excel" class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700"><i class="fa-solid fa-file-excel mr-2"></i>Export Excel</button>
            @if($search || $status)<a href="{{ route('dosen.pengajuan') }}" class="rounded-lg border border-slate-300 px-4 py-2.5 text-center text-sm font-semibold text-slate-700">Reset</a>@endif
            <input type="hidden" name="sort" value="{{ $sort }}"><input type="hidden" name="direction" value="{{ $direction }}">
        </form>
    </div>
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto"><table class="min-w-full divide-y divide-slate-200 text-sm"><thead><tr>
            <th class="px-5 py-3 text-left"><x-table-sort column="mahasiswa" label="Mahasiswa" /></th>
            <th class="px-5 py-3 text-left"><x-table-sort column="perusahaan" label="Perusahaan" /></th>
            <th class="px-5 py-3 text-left"><x-table-sort column="judul_magang" label="Judul" /></th>
            <th class="px-5 py-3 text-left"><x-table-sort column="tanggal_pengajuan" label="Tanggal" /></th>
            <th class="px-5 py-3 text-left"><x-table-sort column="status_pengajuan" label="Status" /></th>
        </tr></thead><tbody class="divide-y divide-slate-100">
            @forelse($pengajuan as $item)
                <tr class="hover:bg-slate-50"><td class="px-5 py-4"><b>{{ $item->mahasiswa?->nama }}</b><div class="text-xs text-slate-500">{{ $item->mahasiswa?->nim }}</div></td><td class="px-5 py-4">{{ $item->instansi?->nama_instansi }}</td><td class="px-5 py-4">{{ $item->judul_magang }}</td><td class="px-5 py-4 whitespace-nowrap">{{ $item->tanggal_pengajuan?->format('d M Y') }}</td><td class="px-5 py-4"><x-badge :status="$item->status_pengajuan"/></td></tr>
            @empty<tr><td colspan="5" class="px-5 py-12 text-center text-sm text-slate-500">Tidak ada pengajuan.</td></tr>@endforelse
        </tbody></table></div>
        <div class="flex flex-col gap-3 border-t border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between"><p class="text-xs text-slate-500">Menampilkan {{ $pengajuan->firstItem() ?? 0 }}–{{ $pengajuan->lastItem() ?? 0 }} dari {{ $pengajuan->total() }} data</p>{{ $pengajuan->onEachSide(1)->links() }}</div>
    </div>
</div>
@endsection
