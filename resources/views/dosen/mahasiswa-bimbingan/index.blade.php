@extends('layouts.app')
@section('title','Mahasiswa Bimbingan')
@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-950">Mahasiswa Bimbingan</h1>
        <p class="mt-1 text-sm text-slate-500">Daftar mahasiswa yang berada dalam bimbingan Anda.</p>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('dosen.mahasiswa-bimbingan') }}" class="flex flex-col gap-3 lg:flex-row">
            <input name="q" value="{{ $search }}" type="search" placeholder="Cari nama, NIM, atau instansi..." class="min-w-0 flex-1 rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
            <select name="status" class="rounded-lg border border-slate-300 px-3 py-2.5 text-sm">
                <option value="">Semua Status</option>
                <option value="belum_mulai" @selected($status==='belum_mulai')>Belum Mulai</option>
                <option value="berlangsung" @selected($status==='berlangsung')>Berlangsung</option>
                <option value="selesai" @selected($status==='selesai')>Selesai</option>
            </select>
            <select name="per_page" class="rounded-lg border border-slate-300 px-3 py-2.5 text-sm">
                @foreach([10,25,50,100] as $size)<option value="{{ $size }}" @selected($perPage===$size)>{{ $size }} / halaman</option>@endforeach
            </select>
            <button class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">Filter</button>
            <button name="export" value="excel" class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"><i class="fa-solid fa-file-excel mr-2"></i>Export Excel</button>
            @if($search || $status)<a href="{{ route('dosen.mahasiswa-bimbingan') }}" class="rounded-lg border border-slate-300 px-4 py-2.5 text-center text-sm font-semibold text-slate-700 hover:bg-slate-50">Reset</a>@endif
            <input type="hidden" name="sort" value="{{ $sort }}"><input type="hidden" name="direction" value="{{ $direction }}">
        </form>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead><tr>
                    <th class="px-5 py-3 text-left"><x-table-sort column="mahasiswa" label="Mahasiswa" /></th>
                    <th class="px-5 py-3 text-left"><x-table-sort column="instansi" label="Instansi" /></th>
                    <th class="px-5 py-3 text-left"><x-table-sort column="tanggal_mulai" label="Mulai" /></th>
                    <th class="px-5 py-3 text-left"><x-table-sort column="tanggal_selesai" label="Selesai" /></th>
                    <th class="px-5 py-3 text-left"><x-table-sort column="status_magang" label="Status" /></th>
                    <th class="px-5 py-3 text-left">Logbook</th>
                    <th class="px-5 py-3 text-left">Aksi</th>
                </tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($magang as $item)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-4"><div class="font-semibold text-slate-900">{{ $item->mahasiswa?->nama }}</div><div class="text-xs text-slate-500">{{ $item->mahasiswa?->nim }}</div></td>
                            <td class="px-5 py-4 text-slate-600">{{ $item->instansi?->nama_instansi }}</td>
                            <td class="px-5 py-4 whitespace-nowrap">{{ $item->tanggal_mulai?->format('d M Y') ?? '-' }}</td>
                            <td class="px-5 py-4 whitespace-nowrap">{{ $item->tanggal_selesai?->format('d M Y') ?? '-' }}</td>
                            <td class="px-5 py-4"><x-badge :status="$item->status_magang"/></td>
                            <td class="px-5 py-4">{{ $item->logbook_disetujui_count }}/{{ $item->log_kegiatan_count }} disetujui</td>
                            <td class="px-5 py-4"><a href="{{ route('dosen.mahasiswa-bimbingan.show',$item) }}" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">Detail</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-12 text-center text-sm text-slate-500">Belum ada mahasiswa bimbingan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex flex-col gap-3 border-t border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-xs text-slate-500">Menampilkan {{ $magang->firstItem() ?? 0 }}–{{ $magang->lastItem() ?? 0 }} dari {{ $magang->total() }} data</p>
            {{ $magang->onEachSide(1)->links() }}
        </div>
    </div>
</div>
@endsection
