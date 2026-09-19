@extends('layouts.app')
@section('title','Penilaian')
@section('content')
<div class="space-y-6">
    <div><h1 class="text-2xl font-bold tracking-tight text-slate-950">Penilaian Magang</h1><p class="mt-1 text-sm text-slate-500">Kelola penilaian mahasiswa bimbingan.</p></div>
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm"><form method="GET" action="{{ route('dosen.penilaian') }}" class="flex flex-col gap-3 lg:flex-row">
        <input name="q" value="{{ $search }}" type="search" placeholder="Cari mahasiswa, NIM, atau perusahaan..." class="min-w-0 flex-1 rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
        <select name="per_page" class="rounded-lg border border-slate-300 px-3 py-2.5 text-sm">@foreach([10,25,50,100] as $size)<option value="{{ $size }}" @selected($perPage===$size)>{{ $size }} / halaman</option>@endforeach</select>
        <button class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white">Cari</button><button name="export" value="excel" class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700"><i class="fa-solid fa-file-excel mr-2"></i>Export Excel</button>
        @if($search)<a href="{{ route('dosen.penilaian') }}" class="rounded-lg border border-slate-300 px-4 py-2.5 text-center text-sm font-semibold text-slate-700">Reset</a>@endif
        <input type="hidden" name="sort" value="{{ $sort }}"><input type="hidden" name="direction" value="{{ $direction }}">
    </form></div>
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm"><div class="overflow-x-auto"><table class="min-w-full divide-y divide-slate-200 text-sm"><thead><tr>
        <th class="px-5 py-3 text-left"><x-table-sort column="mahasiswa" label="Mahasiswa" /></th><th class="px-5 py-3 text-left"><x-table-sort column="perusahaan" label="Perusahaan" /></th><th class="px-5 py-3 text-center"><x-table-sort column="nilai_akhir" label="Nilai Akhir" /></th><th class="px-5 py-3 text-left"><x-table-sort column="tanggal_penilaian" label="Tanggal" /></th><th class="px-5 py-3 text-right">Aksi</th>
    </tr></thead><tbody class="divide-y divide-slate-100">
        @forelse($magang as $item)<tr class="align-middle hover:bg-slate-50"><td class="px-5 py-4"><p class="font-semibold text-slate-900">{{ $item->mahasiswa?->nama }}</p><p class="text-xs text-slate-500">{{ $item->mahasiswa?->nim }}</p></td><td class="px-5 py-4 text-slate-600">{{ $item->instansi?->nama_instansi }}</td><td class="px-5 py-4 text-center font-semibold text-slate-900">{{ $item->penilaian?->nilai_akhir ?? 'Belum dinilai' }}</td><td class="px-5 py-4 text-slate-600">{{ $item->penilaian?->tanggal_penilaian?->format('d M Y') ?? '-' }}</td><td class="px-5 py-4 text-right"><a href="{{ route('dosen.penilaian.edit',$item) }}" class="rounded-lg bg-slate-900 px-3.5 py-2 text-xs font-semibold text-white hover:bg-slate-800">{{ $item->penilaian ? 'Edit Nilai' : 'Beri Nilai' }}</a></td></tr>
        @empty<tr><td colspan="5" class="px-5 py-12 text-center text-sm text-slate-500">Belum ada mahasiswa untuk dinilai.</td></tr>@endforelse
    </tbody></table></div><div class="flex flex-col gap-3 border-t border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between"><p class="text-xs text-slate-500">Menampilkan {{ $magang->firstItem() ?? 0 }}–{{ $magang->lastItem() ?? 0 }} dari {{ $magang->total() }} data</p>{{ $magang->onEachSide(1)->links() }}</div></div>
</div>
@endsection
