@extends('layouts.app')
@section('title', 'Pengajuan Magang')
@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div><p class="text-sm font-medium text-slate-500">Manajemen Magang</p><h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">Pengajuan Magang</h1><p class="mt-1 text-sm text-slate-500">Kelola pengajuan, dosen pembimbing, dan tempat magang mahasiswa.</p></div>
        <x-button href="{{ route('admin.pengajuan.create') }}">Tambah Pengajuan</x-button>
    </div>
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.pengajuan.index') }}" class="flex flex-col gap-3 lg:flex-row">
            <input name="q" value="{{ $search }}" type="search" placeholder="Cari mahasiswa, NIM, judul, dosen, atau perusahaan..." class="min-w-0 flex-1 rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
            <select name="status" class="rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-700"><option value="">Semua Status</option><option value="diajukan" @selected($status === 'diajukan')>Diajukan</option><option value="disetujui" @selected($status === 'disetujui')>Disetujui</option><option value="ditolak" @selected($status === 'ditolak')>Ditolak</option></select>
            <select name="per_page" class="rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-700">@foreach([10,25,50,100] as $size)<option value="{{ $size }}" @selected($perPage === $size)>{{ $size }} / halaman</option>@endforeach</select>
            <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white">Filter</button>
            <button type="submit" name="export" value="excel" class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700"><i class="fa-solid fa-file-excel mr-2"></i>Export Excel</button>
            @if($search || $status)<a href="{{ route('admin.pengajuan.index') }}" class="rounded-lg border border-slate-300 px-4 py-2.5 text-center text-sm font-semibold text-slate-700">Reset</a>@endif
            <input type="hidden" name="sort" value="{{ $sort }}"><input type="hidden" name="direction" value="{{ $direction }}">
        </form>
    </div>
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto"><table class="min-w-full divide-y divide-slate-200 text-sm"><thead><tr>
            <th class="px-5 py-3 text-left"><x-table-sort column="mahasiswa" label="Mahasiswa" /></th>
            <th class="px-5 py-3 text-left"><x-table-sort column="judul_magang" label="Judul Magang" /></th>
            <th class="px-5 py-3 text-left"><x-table-sort column="dosen" label="Pembimbing" /></th>
            <th class="px-5 py-3 text-left"><x-table-sort column="perusahaan" label="Perusahaan" /></th>
            <th class="px-5 py-3 text-left"><x-table-sort column="status_pengajuan" label="Status" /></th>
            <th class="px-5 py-3 text-left font-semibold text-white">Aksi</th>
        </tr></thead><tbody class="divide-y divide-slate-100">
        @forelse($magang as $item)
            @php $statusClass=match($item->status_pengajuan){'disetujui'=>'bg-emerald-50 text-emerald-700','ditolak'=>'bg-rose-50 text-rose-700',default=>'bg-amber-50 text-amber-700'}; @endphp
            <tr class="hover:bg-slate-50">
                <td class="px-5 py-4"><div class="font-semibold text-slate-900">{{ $item->mahasiswa?->nama ?? '-' }}</div><div class="text-xs text-slate-500">{{ $item->mahasiswa?->nim ?? '-' }}</div></td>
                <td class="max-w-xs px-5 py-4 text-slate-700">{{ $item->judul_magang }}</td><td class="px-5 py-4 text-slate-600">{{ $item->dosen?->nama ?? '-' }}</td><td class="px-5 py-4 text-slate-600">{{ $item->instansi?->nama_instansi ?? '-' }}</td>
                <td class="px-5 py-4">
                    @if($item->status_pengajuan === 'diajukan')
                        <button type="button"
                            data-review-modal="review-modal-{{ $item->id }}"
                            class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClass }} hover:ring-2 hover:ring-amber-200"
                            title="Klik untuk memproses pengajuan">
                            <span>Diajukan</span>
                            <i class="fa-solid fa-chevron-down text-[9px]" aria-hidden="true"></i>
                        </button>

                        <dialog id="review-modal-{{ $item->id }}" class="w-[calc(100%-2rem)] max-w-lg rounded-xl border border-slate-200 bg-white p-0 shadow-2xl backdrop:bg-slate-950/40">
                            <div class="border-b border-slate-200 px-6 py-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <h2 class="text-lg font-bold text-slate-950">Proses Pengajuan Magang</h2>
                                        <p class="mt-1 text-sm text-slate-500">{{ $item->mahasiswa?->nama ?? '-' }} · {{ $item->instansi?->nama_instansi ?? '-' }}</p>
                                    </div>
                                    <button type="button" data-close-review class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 hover:text-slate-900" aria-label="Tutup">
                                        <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>

                            <div data-review-choice class="px-6 py-6">
                                <p class="mb-4 text-sm text-slate-600">Pilih tindakan untuk pengajuan ini.</p>
                                <div class="grid gap-3 sm:grid-cols-2">
                                    <form method="POST" action="{{ route('admin.pengajuan.review',$item) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status_pengajuan" value="disetujui">
                                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-3 text-sm font-semibold text-white hover:bg-emerald-700">
                                            <i class="fa-solid fa-check" aria-hidden="true"></i>
                                            Setujui
                                        </button>
                                    </form>
                                    <button type="button" data-show-reject
                                        class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-rose-600 px-4 py-3 text-sm font-semibold text-white hover:bg-rose-700">
                                        <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                                        Tolak
                                    </button>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('admin.pengajuan.review',$item) }}" data-review-reject class="hidden">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status_pengajuan" value="ditolak">
                                <div class="px-6 py-6">
                                    <label for="alasan-{{ $item->id }}" class="mb-1.5 block text-sm font-semibold text-slate-700">
                                        Alasan Penolakan <span class="text-rose-500">*</span>
                                    </label>
                                    <textarea id="alasan-{{ $item->id }}" name="alasan_penolakan" rows="5" required maxlength="1000"
                                        placeholder="Masukkan alasan penolakan..."
                                        class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-slate-500 focus:ring-2 focus:ring-slate-200"></textarea>
                                    <p class="mt-1.5 text-xs text-slate-500">Alasan ini akan ditampilkan kepada mahasiswa.</p>
                                </div>
                                <div class="flex flex-col-reverse gap-2 border-t border-slate-200 bg-slate-50 px-6 py-4 sm:flex-row sm:justify-end">
                                    <button type="button" data-back-review class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                        Kembali
                                    </button>
                                    <button type="submit" class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
                                        <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
                                        Update
                                    </button>
                                </div>
                            </form>
                        </dialog>
                    @else
                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClass }}">{{ ucfirst($item->status_pengajuan) }}</span>
                    @endif
                </td>
                <td class="whitespace-nowrap px-5 py-4">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.pengajuan.edit',$item) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-700">
                            <i class="fa-solid fa-pen w-3.5 text-center" aria-hidden="true"></i>
                            Edit
                        </a>
                        <form method="POST" action="{{ route('admin.pengajuan.destroy',$item) }}" onsubmit="return confirm('Hapus pengajuan ini?')">
                            @csrf @method('DELETE')
                            <button class="inline-flex items-center gap-1.5 rounded-lg border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-700">
                                <i class="fa-solid fa-trash w-3.5 text-center" aria-hidden="true"></i>
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty<tr><td colspan="6" class="px-5 py-12 text-center text-sm text-slate-500">Belum ada pengajuan magang.</td></tr>@endforelse
        </tbody></table></div>
        <div class="flex flex-col gap-3 border-t border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between"><p class="text-xs text-slate-500">Menampilkan {{ $magang->firstItem() ?? 0 }}–{{ $magang->lastItem() ?? 0 }} dari {{ $magang->total() }} data</p>{{ $magang->onEachSide(1)->links() }}</div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-review-modal]').forEach((button) => {
        const modal = document.getElementById(button.dataset.reviewModal);
        if (!modal) return;

        const choice = modal.querySelector('[data-review-choice]');
        const reject = modal.querySelector('[data-review-reject]');

        const showChoice = () => {
            choice.classList.remove('hidden');
            reject.classList.add('hidden');
        };

        button.addEventListener('click', () => {
            showChoice();
            modal.showModal();
        });

        modal.querySelector('[data-show-reject]')?.addEventListener('click', () => {
            choice.classList.add('hidden');
            reject.classList.remove('hidden');
            reject.querySelector('textarea')?.focus();
        });

        modal.querySelector('[data-back-review]')?.addEventListener('click', showChoice);
        modal.querySelector('[data-close-review]')?.addEventListener('click', () => modal.close());

        modal.addEventListener('click', (event) => {
            if (event.target === modal) modal.close();
        });
    });
});
</script>
@endsection
