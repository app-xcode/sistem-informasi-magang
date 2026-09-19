@extends('layouts.app')

@section('title', 'Validasi Logbook')

@section('content')
<div class="space-y-6">
    <div>
        <p class="text-sm font-medium text-slate-500">Bimbingan Mahasiswa</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">Validasi Logbook</h1>
        <p class="mt-1 text-sm text-slate-500">Tinjau dan validasi kegiatan mahasiswa yang berada dalam bimbingan Anda.</p>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('dosen.logbook') }}" class="flex flex-col gap-3 sm:flex-row">
            <select name="status" class="rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm sm:w-64">
                <option value="">Semua Status</option>
                <option value="menunggu" @selected($status === 'menunggu')>Menunggu</option>
                <option value="disetujui" @selected($status === 'disetujui')>Disetujui</option>
                <option value="ditolak" @selected($status === 'ditolak')>Ditolak</option>
            </select>
            <button class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white">Filter</button>
            @if($status)<a href="{{ route('dosen.logbook') }}" class="rounded-lg border border-slate-300 px-4 py-2.5 text-center text-sm font-semibold text-slate-700">Reset</a>@endif
        </form>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Mahasiswa</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Tanggal</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Kegiatan</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Status</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Validasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                @forelse($logbook as $item)
                    @php
                        $class = match($item->status_validasi) {
                            'disetujui' => 'bg-emerald-50 text-emerald-700',
                            'ditolak' => 'bg-rose-50 text-rose-700',
                            default => 'bg-amber-50 text-amber-700',
                        };
                    @endphp
                    <tr class="align-top">
                        <td class="px-5 py-4">
                            <p class="font-semibold text-slate-900">{{ $item->magang?->mahasiswa?->nama ?? '-' }}</p>
                            <p class="text-xs text-slate-500">{{ $item->magang?->mahasiswa?->nim ?? '-' }}</p>
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-slate-600">{{ $item->tanggal?->format('d M Y') }}</td>
                        <td class="min-w-[280px] px-5 py-4">
                            <p class="font-semibold text-slate-900">{{ $item->judul_kegiatan }}</p>
                            <p class="mt-1 whitespace-pre-line text-slate-600">{{ $item->deskripsi }}</p>
                            @if($item->bukti_kegiatan)
                                <a href="{{ asset('storage/'.$item->bukti_kegiatan) }}" target="_blank" class="mt-2 inline-block text-xs font-semibold text-slate-700 underline">Lihat bukti</a>
                            @endif
                        </td>
                        <td class="px-5 py-4"><span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $class }}">{{ ucfirst($item->status_validasi) }}</span></td>
                        <td class="min-w-[250px] px-5 py-4">
                            @if($item->status_validasi === 'menunggu')
                                <form method="POST" action="{{ route('dosen.logbook.validate', $item) }}" class="space-y-2">
                                    @csrf @method('PATCH')
                                    <select name="status_validasi" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs">
                                        <option value="">Pilih keputusan</option>
                                        <option value="disetujui">Setujui</option>
                                        <option value="ditolak">Tolak</option>
                                    </select>
                                    <textarea name="catatan_dosen" rows="2" placeholder="Catatan, wajib jika ditolak..." class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs"></textarea>
                                    <button class="w-full rounded-lg bg-slate-900 px-3 py-2 text-xs font-semibold text-white">Simpan Validasi</button>
                                </form>
                            @else
                                <p class="text-xs text-slate-500">{{ $item->catatan_dosen ?: 'Tidak ada catatan.' }}</p>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-12 text-center text-sm text-slate-500">Belum ada logbook.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($logbook->hasPages())
            <div class="border-t border-slate-200 px-5 py-4">{{ $logbook->links() }}</div>
        @endif
    </div>
</div>
@endsection
