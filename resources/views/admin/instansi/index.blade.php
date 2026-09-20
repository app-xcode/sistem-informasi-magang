@extends('layouts.app')

@section('title', 'Data Instansi')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500">Manajemen Data</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">Data Instansi</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola instansi tempat pelaksanaan magang.</p>
        </div>
        <x-button href="{{ route('admin.instansi.create') }}">Tambah Instansi</x-button>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.instansi.index') }}" class="flex flex-col gap-3 lg:flex-row">
            <div class="min-w-0 flex-1"><label for="q" class="sr-only">Cari instansi</label><input id="q" name="q" value="{{ $search }}" type="search" placeholder="Cari nama, alamat, telepon, email, atau penanggung jawab..."
                class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm text-slate-900 outline-none placeholder:text-slate-400 focus:border-slate-500 focus:ring-2 focus:ring-slate-200"></div>
            <select name="per_page" class="rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">@foreach([10,25,50,100] as $size)<option value="{{ $size }}" @selected($perPage === $size)>{{ $size }} / halaman</option>@endforeach</select>
            <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">Cari</button>
            <button type="submit" name="export" value="excel" class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"><i class="fa-solid fa-file-excel mr-2"></i>Export Excel</button>
            @if($search)<a href="{{ route('admin.instansi.index') }}" class="rounded-lg border border-slate-300 px-4 py-2.5 text-center text-sm font-semibold text-slate-700 hover:bg-slate-50">Reset</a>@endif
            <input type="hidden" name="sort" value="{{ $sort }}"><input type="hidden" name="direction" value="{{ $direction }}">
        </form>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead><tr>
                    <th class="px-5 py-3 text-left"><x-table-sort column="nama_instansi" label="Instansi" /></th>
                    <th class="px-5 py-3 text-left"><x-table-sort column="alamat" label="Alamat" /></th>
                    <th class="px-5 py-3 text-left"><x-table-sort column="no_telp" label="Kontak" /></th>
                    <th class="px-5 py-3 text-left"><x-table-sort column="penanggung_jawab" label="Penanggung Jawab" /></th>
                    <th class="px-5 py-3 text-left font-semibold text-white">Aksi</th>
                </tr></thead>
                <tbody class="divide-y divide-slate-100">
                @forelse($instansi as $item)
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-4"><div class="font-semibold text-slate-900">{{ $item->nama_instansi }}</div><div class="mt-0.5 text-xs text-slate-500">{{ $item->email ?: '-' }}</div></td>
                        <td class="max-w-sm px-5 py-4 text-slate-600">{{ $item->alamat }}</td>
                        <td class="px-5 py-4 text-slate-600">{{ $item->no_telp ?: '-' }}</td>
                        <td class="px-5 py-4 text-slate-600">{{ $item->penanggung_jawab ?: '-' }}</td>
                        <td class="whitespace-nowrap px-5 py-4"><div class="flex items-center gap-2">
                            <a href="{{ route('admin.instansi.edit', $item) }}" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">Edit</a>
                            <form method="POST" action="{{ route('admin.instansi.destroy', $item) }}" onsubmit="return confirm('Hapus data instansi ini?')">@csrf @method('DELETE')<button type="submit" class="rounded-lg border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-700 hover:bg-rose-50">Hapus</button></form>
                        </div></td>
                    </tr>
                @empty<tr><td colspan="5" class="px-5 py-12 text-center text-sm text-slate-500">Belum ada data instansi.</td></tr>@endforelse
                </tbody>
            </table>
        </div>
        <div class="flex flex-col gap-3 border-t border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-xs text-slate-500">Menampilkan {{ $instansi->firstItem() ?? 0 }}–{{ $instansi->lastItem() ?? 0 }} dari {{ $instansi->total() }} data</p>
            {{ $instansi->onEachSide(1)->links() }}
        </div>
    </div>
</div>
@endsection