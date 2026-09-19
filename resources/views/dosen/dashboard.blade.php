@extends('layouts.app')

@section('title', 'Dashboard Dosen')

@section('content')
    <div>
        <p class="text-sm font-medium text-slate-500">Ringkasan Bimbingan</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">Dashboard Dosen</h1>
        <p class="mt-1 text-sm text-slate-500">Pantau mahasiswa bimbingan, logbook, laporan, dan proses penilaian.</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
        <x-card title="Mahasiswa Bimbingan" :value="$stats['mahasiswaBimbingan']" icon="fa-user-group" icon-class="bg-slate-100 text-slate-700" />
        <x-card title="Pengajuan Baru" :value="$stats['pengajuanBaru']" icon="fa-file-circle-plus" icon-class="bg-amber-50 text-amber-700" />
        <x-card title="Sedang Magang" :value="$stats['sedangMagang']" icon="fa-briefcase" icon-class="bg-sky-50 text-sky-700" />
        <x-card title="Logbook Menunggu" :value="$stats['logbookMenunggu']" icon="fa-book-open" icon-class="bg-violet-50 text-violet-700" />
        <x-card title="Laporan Menunggu" :value="$stats['laporanMenunggu']" icon="fa-file-lines" icon-class="bg-rose-50 text-rose-700" />
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <x-chart
                type="bar"
                title="Status Magang Bimbingan"
                description="Status mahasiswa yang sudah mendapatkan persetujuan magang."
                :labels="['Belum Mulai', 'Berlangsung', 'Selesai']"
                :data="[$statusMagang['belum_mulai'], $statusMagang['berlangsung'], $statusMagang['selesai']]"
            />
        </div>
        <div>
            <x-chart
                type="doughnut"
                title="Komposisi Status"
                description="Distribusi status magang mahasiswa bimbingan."
                :labels="['Belum Mulai', 'Berlangsung', 'Selesai']"
                :data="[$statusMagang['belum_mulai'], $statusMagang['berlangsung'], $statusMagang['selesai']]"
            />
        </div>
    </div>

    <x-card title="Mahasiswa Bimbingan">
        @if ($mahasiswaBimbingan->isEmpty())
            <div class="rounded-lg border border-dashed border-slate-300 p-6 text-center text-sm text-slate-500">
                Belum ada mahasiswa bimbingan.
            </div>
        @else
            <x-table>
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">Mahasiswa</th>
                        <th class="px-4 py-3 text-left font-semibold">NIM</th>
                        <th class="px-4 py-3 text-left font-semibold">Perusahaan</th>
                        <th class="px-4 py-3 text-left font-semibold">Status Magang</th>
                        <th class="px-4 py-3 text-left font-semibold">Progress</th>
                        <th class="px-4 py-3 text-left font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($mahasiswaBimbingan as $magang)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 font-medium text-slate-900">{{ $magang->mahasiswa?->nama ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $magang->mahasiswa?->nim ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $magang->instansi?->nama_instansi ?? '-' }}</td>
                            <td class="px-4 py-3"><x-badge :status="$magang->status_magang" /></td>
                            <td class="px-4 py-3">
                                <div class="min-w-28">
                                    <div class="mb-1 flex items-center justify-between text-xs text-slate-500">
                                        <span>{{ $magang->status_magang === 'selesai' ? 'Selesai' : ($magang->status_magang === 'berlangsung' ? 'Berjalan' : 'Belum mulai') }}</span>
                                        <span class="font-semibold text-slate-700">{{ $magang->status_magang === 'selesai' ? '100%' : ($magang->status_magang === 'berlangsung' ? '50%' : '0%') }}</span>
                                    </div>
                                    <div class="h-1.5 overflow-hidden rounded-full bg-slate-100">
                                        <div class="h-full rounded-full bg-slate-900" style="width: {{ $magang->status_magang === 'selesai' ? '100%' : ($magang->status_magang === 'berlangsung' ? '50%' : '0%') }}"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3"><x-button :href="route('dosen.mahasiswa-bimbingan.show', $magang)" variant="secondary">Detail</x-button></td>
                        </tr>
                    @endforeach
                </tbody>
            </x-table>
        @endif
    </x-card>

    <x-card title="Logbook Menunggu Validasi">
        @if ($logbookTerbaru->isEmpty())
            <div class="rounded-lg border border-dashed border-slate-300 p-6 text-center text-sm text-slate-500">
                Tidak ada logbook yang menunggu validasi.
            </div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach ($logbookTerbaru as $logbook)
                    <div class="flex flex-col gap-2 py-4 first:pt-0 last:pb-0 sm:flex-row sm:items-center sm:justify-between">
                        <div class="min-w-0">
                            <p class="font-semibold text-slate-950">{{ $logbook->judul_kegiatan }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $logbook->magang?->mahasiswa?->nama ?? '-' }} · {{ $logbook->tanggal?->format('d M Y') }}</p>
                        </div>
                        <a href="{{ route('dosen.logbook') }}" class="inline-flex self-start"><x-badge :status="$logbook->status_validasi" /></a>
                    </div>
                @endforeach
            </div>
        @endif
    </x-card>
@endsection
