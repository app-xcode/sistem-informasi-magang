@extends('layouts.app')

@section('title', 'Dashboard Dosen')

@section('content')
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
        <x-card title="Mahasiswa Bimbingan" :value="$stats['mahasiswaBimbingan']" />
        <x-card title="Pengajuan Baru" :value="$stats['pengajuanBaru']" />
        <x-card title="Sedang Magang" :value="$stats['sedangMagang']" />
        <x-card title="Logbook Menunggu" :value="$stats['logbookMenunggu']" />
        <x-card title="Laporan Menunggu" :value="$stats['laporanMenunggu']" />
    </div>

    <x-card title="Mahasiswa Bimbingan">
        @if ($mahasiswaBimbingan->isEmpty())
            <div class="rounded-md border border-dashed border-slate-300 p-6 text-center text-sm text-slate-500">
                Belum ada mahasiswa bimbingan.
            </div>
        @else
            <x-table>
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Mahasiswa</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">NIM</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Perusahaan</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Status Magang</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Progress</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($mahasiswaBimbingan as $magang)
                        <tr>
                            <td class="px-4 py-3 font-medium text-slate-900">{{ $magang->mahasiswa?->nama ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $magang->mahasiswa?->nim ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $magang->instansi?->nama_instansi ?? '-' }}</td>
                            <td class="px-4 py-3"><x-badge :status="$magang->status_magang" /></td>
                            <td class="px-4 py-3 text-slate-600">{{ $magang->status_magang === 'selesai' ? '100%' : ($magang->status_magang === 'berlangsung' ? '50%' : '0%') }}</td>
                            <td class="px-4 py-3"><x-button :href="route('dosen.mahasiswa-bimbingan.show', $magang)" variant="secondary">Detail</x-button></td>
                        </tr>
                    @endforeach
                </tbody>
            </x-table>
        @endif
    </x-card>

    <x-card title="Logbook Terbaru">
        @if ($logbookTerbaru->isEmpty())
            <div class="rounded-md border border-dashed border-slate-300 p-6 text-center text-sm text-slate-500">
                Tidak ada logbook yang menunggu validasi.
            </div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach ($logbookTerbaru as $logbook)
                    <div class="flex flex-col gap-2 py-4 first:pt-0 last:pb-0 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="font-semibold text-slate-950">{{ $logbook->judul_kegiatan }}</p>
                            <p class="text-sm text-slate-500">{{ $logbook->magang?->mahasiswa?->nama ?? '-' }} - {{ $logbook->tanggal?->format('d M Y') }}</p>
                        </div>
                        <a href="{{ route('dosen.logbook') }}" class="inline-flex"><x-badge :status="$logbook->status_validasi" /></a>
                    </div>
                @endforeach
            </div>
        @endif
    </x-card>
@endsection
