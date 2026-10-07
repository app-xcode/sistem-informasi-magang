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
                <thead>
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold">Mahasiswa</th>
                        <th class="px-5 py-3 text-left font-semibold">Tanggal</th>
                        <th class="px-5 py-3 text-left font-semibold">Kegiatan</th>
                        <th class="px-5 py-3 text-left font-semibold">Status</th>
                        <th class="px-5 py-3 text-left font-semibold">Aksi</th>
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
                                <a href="{{ route('dosen.logbook.bukti', $item) }}" data-file-preview data-preview-url="{{ route('dosen.logbook.bukti', $item) }}" data-preview-type="{{ in_array(strtolower(pathinfo($item->bukti_kegiatan, PATHINFO_EXTENSION)), ['jpg','jpeg','png','webp','gif']) ? 'image' : 'pdf' }}" data-preview-name="{{ $item->judul_kegiatan }}" class="mt-2 inline-flex items-center gap-2 text-xs font-semibold text-slate-700 underline"><i class="fa-solid fa-eye"></i>Lihat bukti</a>
                            @endif
                        </td>
                        <td class="px-5 py-4"><span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $class }}">{{ ucfirst($item->status_validasi) }}</span></td>
                        <td class="whitespace-nowrap px-5 py-4">
                            @if($item->status_validasi === 'menunggu')
                                <button type="button"
                                    data-logbook-review-modal="logbook-review-modal-{{ $item->id }}"
                                    class="inline-flex items-center gap-1.5 rounded-lg bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-800"
                                    title="Validasi logbook">
                                    <i class="fa-solid fa-check-double" aria-hidden="true"></i>
                                    Validasi
                                </button>

                                <dialog id="logbook-review-modal-{{ $item->id }}" class="fixed left-1/2 top-1/2 m-0 w-[calc(100%-2rem)] max-w-lg -translate-x-1/2 -translate-y-1/2 rounded-xl border border-slate-200 bg-white p-0 shadow-2xl backdrop:bg-slate-950/40">
                                    <div class="border-b border-slate-200 px-6 py-4">
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <h2 class="text-lg font-bold text-slate-950">Validasi Logbook</h2>
                                                <p class="mt-1 text-sm text-slate-500">{{ $item->magang?->mahasiswa?->nama ?? '-' }} · {{ $item->tanggal?->format('d M Y') }}</p>
                                            </div>
                                            <button type="button" data-close-logbook-review class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 hover:text-slate-900" aria-label="Tutup">
                                                <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div data-logbook-review-choice class="px-6 py-6">
                                        <p class="mb-4 text-sm text-slate-600">Pilih tindakan untuk logbook ini.</p>
                                        <div class="grid gap-3 sm:grid-cols-2">
                                            <form method="POST" action="{{ route('dosen.logbook.validate', $item) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status_validasi" value="disetujui">
                                                <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-3 text-sm font-semibold text-white hover:bg-emerald-700">
                                                    <i class="fa-solid fa-check" aria-hidden="true"></i>
                                                    Setujui
                                                </button>
                                            </form>
                                            <button type="button" data-show-logbook-reject
                                                class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-rose-600 px-4 py-3 text-sm font-semibold text-white hover:bg-rose-700">
                                                <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                                                Tolak
                                            </button>
                                        </div>
                                    </div>

                                    <form method="POST" action="{{ route('dosen.logbook.validate', $item) }}" data-logbook-review-reject class="hidden">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status_validasi" value="ditolak">
                                        <div class="px-6 py-6">
                                            <label for="catatan-logbook-{{ $item->id }}" class="mb-1.5 block text-sm font-semibold text-slate-700">
                                                Alasan Penolakan <span class="text-rose-500">*</span>
                                            </label>
                                            <textarea id="catatan-logbook-{{ $item->id }}" name="catatan_dosen" rows="5" required maxlength="1000"
                                                placeholder="Masukkan alasan penolakan..."
                                                class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-slate-500 focus:ring-2 focus:ring-slate-200"></textarea>
                                            <p class="mt-1.5 text-xs text-slate-500">Alasan ini akan ditampilkan kepada mahasiswa.</p>
                                        </div>
                                        <div class="flex flex-col-reverse gap-2 border-t border-slate-200 bg-slate-50 px-6 py-4 sm:flex-row sm:justify-end">
                                            <button type="button" data-back-logbook-review class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Kembali</button>
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

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-logbook-review-modal]').forEach((button) => {
        const modal = document.getElementById(button.dataset.logbookReviewModal);
        if (!modal) return;

        const choice = modal.querySelector('[data-logbook-review-choice]');
        const reject = modal.querySelector('[data-logbook-review-reject]');

        const showChoice = () => {
            choice.classList.remove('hidden');
            reject.classList.add('hidden');
        };

        button.addEventListener('click', () => {
            showChoice();
            modal.showModal();
        });

        modal.querySelector('[data-show-logbook-reject]')?.addEventListener('click', () => {
            choice.classList.add('hidden');
            reject.classList.remove('hidden');
            reject.querySelector('textarea')?.focus();
        });

        modal.querySelector('[data-back-logbook-review]')?.addEventListener('click', showChoice);
        modal.querySelector('[data-close-logbook-review]')?.addEventListener('click', () => modal.close());

        modal.addEventListener('click', (event) => {
            if (event.target === modal) modal.close();
        });
    });
});
</script>
@endsection
