@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-6">
    <div>
        <p class="text-sm font-medium text-slate-500">Ringkasan Sistem</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">Dashboard Admin</h1>
        <p class="mt-1 text-sm text-slate-500">Pantau data mahasiswa, magang, logbook, dan laporan secara langsung dari database.</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach([
            ['label' => 'Mahasiswa', 'value' => $stats['totalMahasiswa']],
            ['label' => 'Dosen', 'value' => $stats['totalDosen']],
            ['label' => 'Perusahaan', 'value' => $stats['totalPerusahaan']],
            ['label' => 'Pengajuan Menunggu', 'value' => $stats['pengajuanMenunggu']],
            ['label' => 'Sedang Magang', 'value' => $stats['mahasiswaSedangMagang']],
            ['label' => 'Magang Selesai', 'value' => $stats['magangSelesai']],
            ['label' => 'Logbook Menunggu', 'value' => $stats['logbookMenunggu']],
            ['label' => 'Laporan Menunggu', 'value' => $stats['laporanMenunggu']],
        ] as $stat)
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">{{ $stat['label'] }}</p>
                <p class="mt-2 text-2xl font-bold text-slate-950">{{ $stat['value'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm lg:col-span-2">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-slate-900">Status Pengajuan</h2>
                    <p class="mt-1 text-xs text-slate-500">Ringkasan seluruh pengajuan magang.</p>
                </div>
                <a href="{{ route('admin.pengajuan.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-950">Lihat semua</a>
            </div>

            <div class="mt-5 grid gap-3 sm:grid-cols-3">
                @foreach([
                    ['key' => 'diajukan', 'label' => 'Diajukan', 'class' => 'bg-amber-50 text-amber-700'],
                    ['key' => 'disetujui', 'label' => 'Disetujui', 'class' => 'bg-emerald-50 text-emerald-700'],
                    ['key' => 'ditolak', 'label' => 'Ditolak', 'class' => 'bg-rose-50 text-rose-700'],
                ] as $item)
                    <div class="rounded-xl border border-slate-200 p-4">
                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $item['class'] }}">{{ $item['label'] }}</span>
                        <p class="mt-3 text-2xl font-bold text-slate-900">{{ $pengajuanStatus[$item['key']] }}</p>
                        <p class="text-xs text-slate-500">pengajuan</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="font-semibold text-slate-900">Perlu Perhatian</h2>
            <div class="mt-4 space-y-3">
                <a href="{{ route('admin.pengajuan.index', ['status' => 'diajukan']) }}" class="flex items-center justify-between rounded-lg bg-amber-50 p-3">
                    <span class="text-sm text-slate-700">Pengajuan menunggu</span>
                    <span class="font-bold text-amber-700">{{ $stats['pengajuanMenunggu'] }}</span>
                </a>
                <a href="{{ route('admin.logbook') }}" class="flex items-center justify-between rounded-lg bg-sky-50 p-3">
                    <span class="text-sm text-slate-700">Logbook menunggu</span>
                    <span class="font-bold text-sky-700">{{ $stats['logbookMenunggu'] }}</span>
                </a>
                <a href="{{ route('admin.laporan.index', ['status' => 'belum_validasi']) }}" class="flex items-center justify-between rounded-lg bg-rose-50 p-3">
                    <span class="text-sm text-slate-700">Laporan menunggu</span>
                    <span class="font-bold text-rose-700">{{ $stats['laporanMenunggu'] }}</span>
                </a>
            </div>
        </div>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-5 py-4">
            <h2 class="font-semibold text-slate-900">Pengajuan Terbaru</h2>
            <p class="mt-1 text-xs text-slate-500">Lima pengajuan berdasarkan tanggal pengajuan terbaru.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-[800px] w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Mahasiswa</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Perusahaan</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Tanggal</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Status</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pengajuanTerbaru as $pengajuan)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-4 font-medium text-slate-900">{{ $pengajuan->mahasiswa?->nama ?? '-' }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $pengajuan->instansi?->nama_instansi ?? '-' }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $pengajuan->tanggal_pengajuan?->format('d M Y') ?? '-' }}</td>
                            <td class="px-5 py-4"><x-badge :status="$pengajuan->status_pengajuan" /></td>
                            <td class="px-5 py-4">
                                <a href="{{ route('admin.pengajuan.edit', $pengajuan) }}" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-10 text-center text-sm text-slate-500">Belum ada pengajuan magang.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-5 py-4">
            <h2 class="font-semibold text-slate-900">Aktivitas Terbaru</h2>
            <p class="mt-1 text-xs text-slate-500">Perubahan data terbaru dari pengajuan, logbook, dan laporan.</p>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($aktivitasTerbaru as $aktivitas)
                <div class="flex items-start gap-4 px-5 py-4">
                    <div class="mt-1 flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-100 text-xs font-bold text-slate-600">
                        {{ strtoupper(substr($aktivitas['type'], 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <p class="text-sm font-semibold text-slate-900">{{ $aktivitas['title'] }}</p>
                            <span class="text-xs text-slate-400">{{ $aktivitas['date']?->format('d M Y H:i') }}</span>
                        </div>
                        <p class="mt-1 truncate text-sm text-slate-500">{{ $aktivitas['description'] }}</p>
                    </div>
                    <x-badge :status="$aktivitas['status']" />
                </div>
            @empty
                <div class="px-5 py-10 text-center text-sm text-slate-500">Belum ada aktivitas terbaru.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
