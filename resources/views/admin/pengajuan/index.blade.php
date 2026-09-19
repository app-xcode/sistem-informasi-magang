@extends('layouts.app')

@section('title', 'Pengajuan Magang')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500">Manajemen Magang</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">Pengajuan Magang</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola pengajuan, dosen pembimbing, dan tempat magang mahasiswa.</p>
        </div>
        <x-button href="{{ route('admin.pengajuan.create') }}">Tambah Pengajuan</x-button>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.pengajuan.index') }}" class="grid gap-3 md:grid-cols-[1fr_220px_auto_auto]">
            <input name="q" value="{{ $search }}" type="search"
                placeholder="Cari mahasiswa, NIM, judul, dosen, atau perusahaan..."
                class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
            <select name="status" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm text-slate-700 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
                <option value="">Semua Status</option>
                <option value="diajukan" @selected($status === 'diajukan')>Diajukan</option>
                <option value="disetujui" @selected($status === 'disetujui')>Disetujui</option>
                <option value="ditolak" @selected($status === 'ditolak')>Ditolak</option>
            </select>
            <button class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">Filter</button>
            @if($search || $status)
                <a href="{{ route('admin.pengajuan.index') }}" class="rounded-lg border border-slate-300 px-4 py-2.5 text-center text-sm font-semibold text-slate-700 hover:bg-slate-50">Reset</a>
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
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Pembimbing</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Perusahaan</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Status</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                @forelse($magang as $item)
                    @php
                        $statusClass = match($item->status_pengajuan) {
                            'disetujui' => 'bg-emerald-50 text-emerald-700',
                            'ditolak' => 'bg-rose-50 text-rose-700',
                            default => 'bg-amber-50 text-amber-700',
                        };
                    @endphp
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-4">
                            <div class="font-semibold text-slate-900">{{ $item->mahasiswa?->nama ?? '-' }}</div>
                            <div class="text-xs text-slate-500">{{ $item->mahasiswa?->nim ?? '-' }}</div>
                        </td>
                        <td class="max-w-xs px-5 py-4 text-slate-700">{{ $item->judul_magang }}</td>
                        <td class="px-5 py-4 text-slate-600">{{ $item->dosen?->nama ?? '-' }}</td>
                        <td class="px-5 py-4 text-slate-600">{{ $item->instansi?->nama_instansi ?? '-' }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClass }}">
                                {{ ucfirst($item->status_pengajuan) }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-5 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.pengajuan.edit', $item) }}" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">Edit</a>
                                <form method="POST" action="{{ route('admin.pengajuan.destroy', $item) }}" onsubmit="return confirm('Hapus pengajuan ini?')">
                                    @csrf @method('DELETE')
                                    <button class="rounded-lg border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-700 hover:bg-rose-50">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-12 text-center text-sm text-slate-500">Belum ada pengajuan magang.</td></tr>
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