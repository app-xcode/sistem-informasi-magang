@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
<div class="space-y-6">
    <div>
        <p class="text-sm font-medium text-slate-500">Dokumen Magang</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">Laporan Magang</h1>
        <p class="mt-1 text-sm text-slate-500">Lihat laporan mahasiswa yang telah dikirim dan pantau status validasinya.</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        @php
            $counts = \App\Models\Laporan::query()
                ->whereHas('magang', fn ($query) => $query->where('status_pengajuan', 'disetujui'))
                ->selectRaw("status, COUNT(*) as total")
                ->groupBy('status')
                ->pluck('total', 'status');
        @endphp

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Belum Validasi</p>
            <p class="mt-1 text-2xl font-bold text-amber-600">{{ $counts['belum_validasi'] ?? 0 }}</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Disetujui</p>
            <p class="mt-1 text-2xl font-bold text-emerald-600">{{ $counts['disetujui'] ?? 0 }}</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Ditolak</p>
            <p class="mt-1 text-2xl font-bold text-rose-600">{{ $counts['ditolak'] ?? 0 }}</p>
        </div>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.laporan.index') }}" class="grid gap-3 md:grid-cols-[1fr_220px_auto_auto]">
            <input name="q" value="{{ $search }}" type="search"
                placeholder="Cari nama file, mahasiswa, NIM, dosen, atau perusahaan..."
                class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">

            <select name="status" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm text-slate-700 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
                <option value="">Semua Status</option>
                <option value="belum_validasi" @selected($status === 'belum_validasi')>Belum Validasi</option>
                <option value="disetujui" @selected($status === 'disetujui')>Disetujui</option>
                <option value="ditolak" @selected($status === 'ditolak')>Ditolak</option>
            </select>

            <button class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">Filter</button>

            @if($search || $status)
                <a href="{{ route('admin.laporan.index') }}" class="rounded-lg border border-slate-300 px-4 py-2.5 text-center text-sm font-semibold text-slate-700 hover:bg-slate-50">Reset</a>
            @endif
        </form>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-[1050px] w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Mahasiswa</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Perusahaan</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Dosen Pembimbing</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">File Laporan</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Tanggal Upload</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Status</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                @forelse($laporan as $item)
                    @php
                        $statusClass = match($item->status) {
                            'disetujui' => 'bg-emerald-50 text-emerald-700',
                            'ditolak' => 'bg-rose-50 text-rose-700',
                            default => 'bg-amber-50 text-amber-700',
                        };

                        $statusLabel = match($item->status) {
                            'disetujui' => 'Disetujui',
                            'ditolak' => 'Ditolak',
                            default => 'Belum Validasi',
                        };
                    @endphp

                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-4">
                            <div class="font-semibold text-slate-900">{{ $item->magang?->mahasiswa?->nama ?? '-' }}</div>
                            <div class="text-xs text-slate-500">{{ $item->magang?->mahasiswa?->nim ?? '-' }}</div>
                        </td>
                        <td class="px-5 py-4 text-slate-700">{{ $item->magang?->instansi?->nama_instansi ?? '-' }}</td>
                        <td class="px-5 py-4 text-slate-700">{{ $item->magang?->dosen?->nama ?? '-' }}</td>
                        <td class="px-5 py-4">
                            <div class="max-w-xs truncate font-medium text-slate-800">{{ $item->nama_file }}</div>
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-slate-600">
                            {{ $item->tanggal_upload?->format('d M Y H:i') ?? '-' }}
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClass }}">{{ $statusLabel }}</span>
                            @if($item->catatan_dosen)
                                <p class="mt-1 max-w-xs text-xs text-slate-500">{{ $item->catatan_dosen }}</p>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-5 py-4">
                            <a href="{{ route('admin.laporan.download', $item) }}"
                               class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                Unduh
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-sm text-slate-500">
                            Belum ada laporan magang yang dikirim.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($laporan->hasPages())
            <div class="border-t border-slate-200 px-5 py-4">{{ $laporan->links() }}</div>
        @endif
    </div>
</div>
@endsection
