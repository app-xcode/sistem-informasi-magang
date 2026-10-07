@extends('layouts.app')
@section('title','Laporan Magang')
@section('content')
<div class="space-y-6">
    <div>
        <p class="text-sm font-medium text-slate-500">Bimbingan Mahasiswa</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">Laporan Magang</h1>
        <p class="mt-1 text-sm text-slate-500">Tinjau dan validasi laporan mahasiswa bimbingan.</p>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('dosen.laporan') }}" class="flex flex-col gap-3 lg:flex-row">
            <input name="q" value="{{ $search }}" type="search" placeholder="Cari mahasiswa, NIM, instansi, atau file..." class="min-w-0 flex-1 rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
            <select name="status" class="rounded-lg border border-slate-300 px-3 py-2.5 text-sm"><option value="">Semua Status</option><option value="belum_validasi" @selected($status==='belum_validasi')>Belum Validasi</option><option value="disetujui" @selected($status==='disetujui')>Disetujui</option><option value="ditolak" @selected($status==='ditolak')>Ditolak</option></select>
            <select name="per_page" class="rounded-lg border border-slate-300 px-3 py-2.5 text-sm">@foreach([10,25,50,100] as $size)<option value="{{ $size }}" @selected($perPage===$size)>{{ $size }} / halaman</option>@endforeach</select>
            <button class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white">Filter</button>
            <button name="export" value="excel" class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700"><i class="fa-solid fa-file-excel mr-2"></i>Export Excel</button>
            @if($search || $status)<a href="{{ route('dosen.laporan') }}" class="rounded-lg border border-slate-300 px-4 py-2.5 text-center text-sm font-semibold text-slate-700">Reset</a>@endif
            <input type="hidden" name="sort" value="{{ $sort }}"><input type="hidden" name="direction" value="{{ $direction }}">
        </form>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead><tr>
                    <th class="px-5 py-3 text-left"><x-table-sort column="mahasiswa" label="Mahasiswa" /></th>
                    <th class="px-5 py-3 text-left"><x-table-sort column="instansi" label="Instansi" /></th>
                    <th class="px-5 py-3 text-left"><x-table-sort column="file" label="File" /></th>
                    <th class="px-5 py-3 text-left"><x-table-sort column="tanggal_upload" label="Upload" /></th>
                    <th class="px-5 py-3 text-left"><x-table-sort column="status" label="Status" /></th>
                    <th class="px-5 py-3 text-left">Aksi</th>
                </tr></thead>
                <tbody class="divide-y divide-slate-100">
                @forelse($laporan as $item)
                    <tr class="align-top hover:bg-slate-50">
                        <td class="px-5 py-4"><b>{{ $item->magang?->mahasiswa?->nama }}</b><div class="text-xs text-slate-500">{{ $item->magang?->mahasiswa?->nim }}</div></td>
                        <td class="px-5 py-4">{{ $item->magang?->instansi?->nama_instansi }}</td>
                        <td class="px-5 py-4"><a class="font-semibold text-slate-700 underline" href="{{ route('dosen.laporan.download',$item) }}">{{ $item->nama_file }}</a></td>
                        <td class="whitespace-nowrap px-5 py-4">{{ $item->tanggal_upload?->format('d M Y H:i') }}</td>
                        <td class="px-5 py-4"><x-badge :status="$item->status"/></td>
                        <td class="whitespace-nowrap px-5 py-4">
                            @if($item->status==='belum_validasi')
                                <button type="button"
                                    data-report-review-modal="report-review-modal-{{ $item->id }}"
                                    class="inline-flex items-center gap-1.5 rounded-lg bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-800"
                                    title="Validasi laporan">
                                    <i class="fa-solid fa-check-double" aria-hidden="true"></i>
                                    Validasi
                                </button>

                                <dialog id="report-review-modal-{{ $item->id }}" class="fixed left-1/2 top-1/2 m-0 w-[calc(100%-2rem)] max-w-lg -translate-x-1/2 -translate-y-1/2 rounded-xl border border-slate-200 bg-white p-0 shadow-2xl backdrop:bg-slate-950/40">
                                    <div class="border-b border-slate-200 px-6 py-4">
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <h2 class="text-lg font-bold text-slate-950">Validasi Laporan Magang</h2>
                                                <p class="mt-1 text-sm text-slate-500">{{ $item->magang?->mahasiswa?->nama ?? '-' }} · {{ $item->nama_file }}</p>
                                            </div>
                                            <button type="button" data-close-report-review class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 hover:text-slate-900" aria-label="Tutup">
                                                <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div data-report-review-choice class="px-6 py-6">
                                        <p class="mb-4 text-sm text-slate-600">Pilih tindakan untuk laporan ini.</p>
                                        <div class="grid gap-3 sm:grid-cols-2">
                                            <form method="POST" action="{{ route('dosen.laporan.validate',$item) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="disetujui">
                                                <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-3 text-sm font-semibold text-white hover:bg-emerald-700">
                                                    <i class="fa-solid fa-check" aria-hidden="true"></i>
                                                    Setujui
                                                </button>
                                            </form>
                                            <button type="button" data-show-report-reject class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-rose-600 px-4 py-3 text-sm font-semibold text-white hover:bg-rose-700">
                                                <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                                                Tolak
                                            </button>
                                        </div>
                                    </div>

                                    <form method="POST" action="{{ route('dosen.laporan.validate',$item) }}" data-report-review-reject class="hidden">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="ditolak">
                                        <div class="px-6 py-6">
                                            <label for="catatan-report-{{ $item->id }}" class="mb-1.5 block text-sm font-semibold text-slate-700">
                                                Alasan Penolakan <span class="text-rose-500">*</span>
                                            </label>
                                            <textarea id="catatan-report-{{ $item->id }}" name="catatan_dosen" rows="5" required maxlength="1000"
                                                placeholder="Masukkan alasan penolakan..."
                                                class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-slate-500 focus:ring-2 focus:ring-slate-200"></textarea>
                                            <p class="mt-1.5 text-xs text-slate-500">Alasan ini akan ditampilkan kepada mahasiswa.</p>
                                        </div>
                                        <div class="flex flex-col-reverse gap-2 border-t border-slate-200 bg-slate-50 px-6 py-4 sm:flex-row sm:justify-end">
                                            <button type="button" data-back-report-review class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Kembali</button>
                                            <button type="submit" class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
                                                <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
                                                Update
                                            </button>
                                        </div>
                                    </form>
                                </dialog>
                            @else
                                <p class="max-w-xs whitespace-normal text-xs text-slate-500">{{ $item->catatan_dosen ?: 'Tidak ada catatan.' }}</p>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-12 text-center text-sm text-slate-500">Belum ada laporan.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex flex-col gap-3 border-t border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-xs text-slate-500">Menampilkan {{ $laporan->firstItem() ?? 0 }}–{{ $laporan->lastItem() ?? 0 }} dari {{ $laporan->total() }} data</p>
            {{ $laporan->onEachSide(1)->links() }}
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-report-review-modal]').forEach((button) => {
        const modal = document.getElementById(button.dataset.reportReviewModal);
        if (!modal) return;

        const choice = modal.querySelector('[data-report-review-choice]');
        const reject = modal.querySelector('[data-report-review-reject]');

        const showChoice = () => {
            choice.classList.remove('hidden');
            reject.classList.add('hidden');
        };

        button.addEventListener('click', () => {
            showChoice();
            modal.showModal();
        });

        modal.querySelector('[data-show-report-reject]')?.addEventListener('click', () => {
            choice.classList.add('hidden');
            reject.classList.remove('hidden');
            reject.querySelector('textarea')?.focus();
        });

        modal.querySelector('[data-back-report-review]')?.addEventListener('click', showChoice);
        modal.querySelector('[data-close-report-review]')?.addEventListener('click', () => modal.close());

        modal.addEventListener('click', (event) => {
            if (event.target === modal) modal.close();
        });
    });
});
</script>
@endsection
