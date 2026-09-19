@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        <x-card title="Total Mahasiswa" :value="$stats['totalMahasiswa']" />
        <x-card title="Total Dosen" :value="$stats['totalDosen']" />
        <x-card title="Total Perusahaan" :value="$stats['totalPerusahaan']" />
        <x-card title="Pengajuan Menunggu" :value="$stats['pengajuanMenunggu']" />
        <x-card title="Mahasiswa Sedang Magang" :value="$stats['mahasiswaSedangMagang']" />
        <x-card title="Magang Selesai" :value="$stats['magangSelesai']" />
    </div>

    <x-card title="Pengajuan Terbaru" description="Data diambil dari tabel magang terbaru.">
        @if ($pengajuanTerbaru->isEmpty())
            <div class="rounded-md border border-dashed border-slate-300 p-6 text-center text-sm text-slate-500">
                Belum ada pengajuan magang.
            </div>
        @else
            <x-table>
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Mahasiswa</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Perusahaan</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Tanggal Pengajuan</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Status</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($pengajuanTerbaru as $pengajuan)
                        <tr>
                            <td class="px-4 py-3 font-medium text-slate-900">{{ $pengajuan->mahasiswa?->nama ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $pengajuan->instansi?->nama_instansi ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $pengajuan->tanggal_pengajuan?->format('d M Y') }}</td>
                            <td class="px-4 py-3"><x-badge :status="$pengajuan->status_pengajuan" /></td>
                            <td class="px-4 py-3"><x-button href="#" variant="secondary">Detail</x-button></td>
                        </tr>
                    @endforeach
                </tbody>
            </x-table>
        @endif
    </x-card>

    <x-card title="Aktivitas Terbaru">
        <div class="rounded-md border border-dashed border-slate-300 p-6 text-center text-sm text-slate-500">
            Aktivitas sistem akan tampil saat modul lanjutan mulai mencatat perubahan data.
        </div>
    </x-card>
@endsection
