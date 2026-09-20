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
        <x-card title="Mahasiswa" :value="$stats['totalMahasiswa']" icon="fa-users" icon-class="bg-slate-100 text-slate-700" />
        <x-card title="Dosen" :value="$stats['totalDosen']" icon="fa-user-tie" icon-class="bg-sky-50 text-sky-700" />
        <x-card title="Instansi" :value="$stats['totalInstansi']" icon="fa-building" icon-class="bg-indigo-50 text-indigo-700" />
        <x-card title="Pengajuan Menunggu" :value="$stats['pengajuanMenunggu']" icon="fa-file-circle-exclamation" icon-class="bg-amber-50 text-amber-700" />
        <x-card title="Sedang Magang" :value="$stats['mahasiswaSedangMagang']" icon="fa-briefcase" icon-class="bg-sky-50 text-sky-700" />
        <x-card title="Magang Selesai" :value="$stats['magangSelesai']" icon="fa-circle-check" icon-class="bg-emerald-50 text-emerald-700" />
        <x-card title="Logbook Menunggu" :value="$stats['logbookMenunggu']" icon="fa-book-open" icon-class="bg-violet-50 text-violet-700" />
        <x-card title="Laporan Menunggu" :value="$stats['laporanMenunggu']" icon="fa-file-lines" icon-class="bg-rose-50 text-rose-700" />
    </div>

    <div class="grid gap-6 lg:grid-cols-12">
        <div class="lg:col-span-8">
            <x-chart
                type="bar"
                title="Status Pengajuan"
                description="Distribusi pengajuan magang berdasarkan status."
                :labels="['Diajukan', 'Disetujui', 'Ditolak']"
                :data="[$pengajuanStatus['diajukan'], $pengajuanStatus['disetujui'], $pengajuanStatus['ditolak']]"
                :colors="['#0ea5e9', '#10b981', '#64748b']"
            />
        </div>

        <div class="lg:col-span-4">
            <x-chart
                type="doughnut"
                title="Komposisi Pengajuan"
                description="Perbandingan status seluruh pengajuan."
                :labels="['Diajukan', 'Disetujui', 'Ditolak']"
                :data="[$pengajuanStatus['diajukan'], $pengajuanStatus['disetujui'], $pengajuanStatus['ditolak']]"
                :colors="['#0ea5e9', '#10b981', '#64748b']"
            />
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm lg:col-span-2">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-slate-900">Perlu Perhatian</h2>
                    <p class="mt-1 text-xs text-slate-500">Data yang membutuhkan tindakan admin.</p>
                </div>
            </div>

            <div class="mt-4 grid gap-3 sm:grid-cols-3">
                <a href="{{ route('admin.pengajuan.index', ['status' => 'diajukan']) }}" class="group rounded-xl border border-slate-200 bg-white p-4 transition hover:-translate-y-0.5 hover:border-amber-300 hover:shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-50 text-amber-700">
                            <i class="fa-solid fa-file-circle-exclamation"></i>
                        </div>
                        <i class="fa-solid fa-arrow-up-right-from-square text-xs text-slate-300 transition group-hover:text-amber-600"></i>
                    </div>
                    <p class="mt-4 text-sm font-semibold text-slate-800">Pengajuan</p>
                    <p class="mt-1 text-2xl font-bold text-slate-950">{{ $stats['pengajuanMenunggu'] }}</p>
                    <p class="mt-0.5 text-xs text-slate-500">menunggu persetujuan</p>
                </a>

                <a href="{{ route('admin.monitoring.index') }}" class="group rounded-xl border border-slate-200 bg-white p-4 transition hover:-translate-y-0.5 hover:border-sky-300 hover:shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-sky-50 text-sky-700">
                            <i class="fa-solid fa-book-open"></i>
                        </div>
                        <i class="fa-solid fa-arrow-up-right-from-square text-xs text-slate-300 transition group-hover:text-sky-600"></i>
                    </div>
                    <p class="mt-4 text-sm font-semibold text-slate-800">Logbook</p>
                    <p class="mt-1 text-2xl font-bold text-slate-950">{{ $stats['logbookMenunggu'] }}</p>
                    <p class="mt-0.5 text-xs text-slate-500">menunggu validasi</p>
                </a>

                <a href="{{ route('admin.laporan.index', ['status' => 'belum_validasi']) }}" class="group rounded-xl border border-slate-200 bg-white p-4 transition hover:-translate-y-0.5 hover:border-rose-300 hover:shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-rose-50 text-rose-700">
                            <i class="fa-solid fa-file-lines"></i>
                        </div>
                        <i class="fa-solid fa-arrow-up-right-from-square text-xs text-slate-300 transition group-hover:text-rose-600"></i>
                    </div>
                    <p class="mt-4 text-sm font-semibold text-slate-800">Laporan</p>
                    <p class="mt-1 text-2xl font-bold text-slate-950">{{ $stats['laporanMenunggu'] }}</p>
                    <p class="mt-0.5 text-xs text-slate-500">menunggu validasi</p>
                </a>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="font-semibold text-slate-900">Aktivitas Terbaru</h2>
            <div class="mt-4 space-y-3">
                @forelse($aktivitasTerbaru->take(4) as $aktivitas)
                    <div class="flex items-start gap-3">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-xs text-slate-600">
                            <i class="fa-solid {{ $aktivitas['type'] === 'logbook' ? 'fa-book-open' : ($aktivitas['type'] === 'laporan' ? 'fa-file-lines' : 'fa-file-circle-plus') }}"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-slate-800">{{ $aktivitas['description'] }}</p>
                            <p class="text-xs text-slate-400">{{ $aktivitas['date']?->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Belum ada aktivitas terbaru.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-5 py-4">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="font-semibold text-slate-900">Pengajuan Terbaru</h2>
                    <p class="mt-1 text-xs text-slate-500">Lima pengajuan berdasarkan tanggal pengajuan terbaru.</p>
                </div>
                <a href="{{ route('admin.pengajuan.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-950">Lihat semua</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-[800px] w-full divide-y divide-slate-200 text-sm">
                <thead>
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold">Mahasiswa</th>
                        <th class="px-5 py-3 text-left font-semibold">Instansi</th>
                        <th class="px-5 py-3 text-left font-semibold">Tanggal</th>
                        <th class="px-5 py-3 text-left font-semibold">Status</th>
                        <th class="px-5 py-3 text-left font-semibold">Aksi</th>
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
</div>
@endsection