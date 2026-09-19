@extends('layouts.app')

@section('title', 'Data Magang')

@section('content')
<div class="space-y-6">
    <div>
        <p class="text-sm font-medium text-slate-500">Manajemen Magang</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">Data Magang</h1>
        <p class="mt-1 text-sm text-slate-500">Kelola mahasiswa yang telah memiliki pengajuan magang yang disetujui.</p>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.magang.index') }}" class="grid gap-3 md:grid-cols-[1fr_220px_auto_auto]">
            <input name="q" value="{{ $search }}" type="search"
                placeholder="Cari mahasiswa, NIM, judul, dosen, atau perusahaan..."
                class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
            <select name="status" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm text-slate-700 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
                <option value="">Semua Status</option>
                <option value="belum_mulai" @selected($status === 'belum_mulai')>Belum Mulai</option>
                <option value="berlangsung" @selected($status === 'berlangsung')>Berlangsung</option>
                <option value="selesai" @selected($status === 'selesai')>Selesai</option>
            </select>
            <button class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">Filter</button>
            @if($search || $status)
                <a href="{{ route('admin.magang.index') }}" class="rounded-lg border border-slate-300 px-4 py-2.5 text-center text-sm font-semibold text-slate-700 hover:bg-slate-50">Reset</a>
            @endif
        </form>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Mahasiswa</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Judul Magang</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Dosen Pembimbing</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Perusahaan</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Status Magang</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                @forelse($magang as $item)
                    @php
                        $statusClass = match($item->status_magang) {
                            'berlangsung' => 'bg-sky-50 text-sky-700',
                            'selesai' => 'bg-emerald-50 text-emerald-700',
                            default => 'bg-amber-50 text-amber-700',
                        };
                        $statusLabel = match($item->status_magang) {
                            'belum_mulai' => 'Belum Mulai',
                            'berlangsung' => 'Berlangsung',
                            'selesai' => 'Selesai',
                            default => ucfirst($item->status_magang),
                        };
                    @endphp
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-4">
                            <div class="font-semibold text-slate-900">{{ $item->mahasiswa?->nama ?? '-' }}</div>
                            <div class="text-xs text-slate-500">{{ $item->mahasiswa?->nim ?? '-' }}</div>
                        </td>
                        <td class="max-w-xs px-5 py-4 text-slate-700">{{ $item->judul_magang ?: '-' }}</td>
                        <td class="px-5 py-4 text-slate-600">{{ $item->dosen?->nama ?? '-' }}</td>
                        <td class="px-5 py-4 text-slate-600">{{ $item->instansi?->nama_instansi ?? '-' }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClass }}">{{ $statusLabel }}</span>
                        </td>
                        <td class="whitespace-nowrap px-5 py-4">
                            <a href="{{ route('admin.magang.show', $item) }}" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-sm text-slate-500">Belum ada data magang yang disetujui.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($magang->hasPages())
            <div class="border-t border-slate-200 px-5 py-4">{{ $magang->links() }}</div>
        @endif
    </div>
</div>
@endsection
