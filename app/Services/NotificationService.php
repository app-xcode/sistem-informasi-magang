<?php

namespace AppServices;

use AppModelsLogKegiatan;
use AppModelsLaporan;
use AppModelsMagang;
use AppModelsUser;
use CarbonCarbon;
use IlluminateSupportCollection;

class NotificationService
{
    public function forUser(User $user): array
    {
        return match ($user->role) {
            'admin' => $this->admin(),
            'dosen' => $this->dosen($user),
            'mahasiswa' => $this->mahasiswa($user),
            default => ['items' => collect(), 'total' => 0, 'counts' => []],
        };
    }

    private function admin(): array
    {
        $count = Magang::where('status_pengajuan', 'diajukan')->count();

        $items = collect();
        if ($count > 0) {
            $items->push([
                'title' => 'Pengajuan magang perlu divalidasi',
                'description' => $count.' pengajuan menunggu keputusan.',
                'href' => route('admin.pengajuan.index', ['status' => 'diajukan']),
                'icon' => 'fa-file-circle-check',
                'class' => 'text-amber-600 bg-amber-50',
            ]);
        }

        return ['items' => $items, 'total' => $items->count(), 'counts' => ['pengajuan' => $count]];
    }

    private function dosen(User $user): array
    {
        $dosen = $user->dosen;
        if (! $dosen) {
            return ['items' => collect(), 'total' => 0, 'counts' => []];
        }

        $approved = fn ($query) => $query
            ->where('dosen_id', $dosen->id)
            ->where('status_pengajuan', 'disetujui');

        $logbookCount = LogKegiatan::where('status_validasi', 'menunggu')
            ->whereHas('magang', $approved)
            ->count();

        $laporanCount = Laporan::where('status', 'belum_validasi')
            ->whereHas('magang', $approved)
            ->count();

        $penilaianCount = Magang::where('dosen_id', $dosen->id)
            ->where('status_pengajuan', 'disetujui')
            ->where('status_magang', 'selesai')
            ->whereDoesntHave('penilaian')
            ->count();

        $items = collect();

        if ($logbookCount > 0) {
            $items->push([
                'title' => 'Validasi logbook',
                'description' => $logbookCount.' logbook menunggu validasi.',
                'href' => route('dosen.logbook', ['status' => 'menunggu']),
                'icon' => 'fa-book-open',
                'class' => 'text-amber-600 bg-amber-50',
            ]);
        }

        if ($laporanCount > 0) {
            $items->push([
                'title' => 'Validasi laporan',
                'description' => $laporanCount.' laporan menunggu validasi.',
                'href' => route('dosen.laporan', ['status' => 'belum_validasi']),
                'icon' => 'fa-file-lines',
                'class' => 'text-amber-600 bg-amber-50',
            ]);
        }

        if ($penilaianCount > 0) {
            $items->push([
                'title' => 'Penilaian mahasiswa',
                'description' => $penilaianCount.' mahasiswa selesai magang dan belum dinilai.',
                'href' => route('dosen.penilaian'),
                'icon' => 'fa-star',
                'class' => 'text-sky-600 bg-sky-50',
            ]);
        }

        return [
            'items' => $items,
            'total' => $items->sum(fn ($item) => (int) preg_replace('/D/', '', $item['description'] ?? '')) ?: $items->count(),
            'counts' => [
                'logbook' => $logbookCount,
                'laporan' => $laporanCount,
                'penilaian' => $penilaianCount,
            ],
        ];
    }

    private function mahasiswa(User $user): array
    {
        $mahasiswa = $user->mahasiswa;
        if (! $mahasiswa) {
            return ['items' => collect(), 'total' => 0, 'counts' => []];
        }

        $magang = Magang::query()
            ->where('mahasiswa_id', $mahasiswa->id)
            ->latest('tanggal_pengajuan')
            ->first();

        $items = collect();

        if (! $magang) {
            $items->push([
                'title' => 'Pengajuan magang diperlukan',
                'description' => 'Anda belum memiliki pengajuan magang.',
                'href' => route('mahasiswa.pengajuan.create'),
                'icon' => 'fa-file-circle-plus',
                'class' => 'text-amber-600 bg-amber-50',
            ]);
            return ['items' => $items, 'total' => 1, 'counts' => ['pengajuan' => 1]];
        }

        $recent = Carbon::now()->subDays(7);

        if (in_array($magang->status_pengajuan, ['disetujui', 'ditolak'], true) && $magang->updated_at?->gte($recent)) {
            $approved = $magang->status_pengajuan === 'disetujui';
            $items->push([
                'title' => $approved ? 'Pengajuan magang disetujui' : 'Pengajuan magang ditolak',
                'description' => $approved ? 'Pengajuan Anda baru saja disetujui.' : 'Pengajuan Anda baru saja ditolak.',
                'href' => route('mahasiswa.status-magang'),
                'icon' => $approved ? 'fa-circle-check' : 'fa-circle-xmark',
                'class' => $approved ? 'text-emerald-600 bg-emerald-50' : 'text-rose-600 bg-rose-50',
            ]);
        }

        if ($magang->status_pengajuan === 'disetujui') {
            if (in_array($magang->status_magang, ['berlangsung', 'selesai'], true) && $magang->updated_at?->gte($recent)) {
                $items->push([
                    'title' => 'Status magang diperbarui',
                    'description' => 'Status magang Anda: '.str_replace('_', ' ', $magang->status_magang).'.',
                    'href' => route('mahasiswa.status-magang'),
                    'icon' => 'fa-briefcase',
                    'class' => 'text-sky-600 bg-sky-50',
                ]);
            }

            $today = Carbon::today();
            $isPeriodActive = $magang->status_magang === 'berlangsung'
                && $magang->tanggal_mulai?->lte($today)
                && $magang->tanggal_selesai?->gte($today);

            if ($isPeriodActive && ! $magang->logKegiatan()->whereDate('tanggal', $today)->exists()) {
                $items->push([
                    'title' => 'Logbook hari ini belum diisi',
                    'description' => 'Tambahkan kegiatan logbook untuk hari ini.',
                    'href' => route('mahasiswa.logbook'),
                    'icon' => 'fa-book-open',
                    'class' => 'text-amber-600 bg-amber-50',
                ]);
            }

            $logbookApproved = $magang->logKegiatan()->where('status_validasi', 'disetujui')->count();
            $logbookRejected = $magang->logKegiatan()->where('status_validasi', 'ditolak')->where('updated_at', '>=', $recent)->get();

            if ($logbookApproved > 0) {
                $latestApproved = $magang->logKegiatan()->where('status_validasi', 'disetujui')->latest('updated_at')->first();
                if ($latestApproved?->updated_at?->gte($recent)) {
                    $items->push([
                        'title' => 'Logbook disetujui',
                        'description' => 'Logbook “'.$latestApproved->judul_kegiatan.'” telah disetujui.',
                        'href' => route('mahasiswa.logbook'),
                        'icon' => 'fa-circle-check',
                        'class' => 'text-emerald-600 bg-emerald-50',
                    ]);
                }
            }

            if ($logbookRejected->isNotEmpty()) {
                $latestRejected = $logbookRejected->sortByDesc('updated_at')->first();
                $items->push([
                    'title' => 'Logbook ditolak',
                    'description' => 'Logbook “'.$latestRejected->judul_kegiatan.'” perlu diperiksa kembali.',
                    'href' => route('mahasiswa.logbook'),
                    'icon' => 'fa-circle-xmark',
                    'class' => 'text-rose-600 bg-rose-50',
                ]);
            }

            $laporanExists = $magang->laporan()->exists();
            $reportDue = $magang->status_magang === 'selesai'
                || ($magang->tanggal_selesai && $magang->tanggal_selesai->lte($today));

            if ($reportDue && ! $laporanExists) {
                $items->push([
                    'title' => 'Laporan magang perlu dibuat',
                    'description' => 'Masa magang telah selesai. Unggah laporan magang.',
                    'href' => route('mahasiswa.laporan'),
                    'icon' => 'fa-file-circle-plus',
                    'class' => 'text-amber-600 bg-amber-50',
                ]);
            }

            $latestReport = $magang->laporan()->latest('updated_at')->first();
            if ($latestReport && in_array($latestReport->status, ['disetujui', 'ditolak'], true) && $latestReport->updated_at?->gte($recent)) {
                $approved = $latestReport->status === 'disetujui';
                $items->push([
                    'title' => $approved ? 'Laporan disetujui' : 'Laporan ditolak',
                    'description' => $approved ? 'Laporan magang Anda telah disetujui.' : 'Laporan magang Anda ditolak dan perlu diperiksa kembali.',
                    'href' => route('mahasiswa.laporan'),
                    'icon' => $approved ? 'fa-circle-check' : 'fa-circle-xmark',
                    'class' => $approved ? 'text-emerald-600 bg-emerald-50' : 'text-rose-600 bg-rose-50',
                ]);
            }
        }

        return [
            'items' => $items,
            'total' => $items->count(),
            'counts' => [
                'pengajuan' => $magang && $magang->status_pengajuan === 'diajukan' ? 0 : ($magang ? 0 : 1),
                'status' => $items->filter(fn ($item) => in_array($item['icon'], ['fa-circle-check', 'fa-circle-xmark', 'fa-briefcase'], true))->count(),
                'logbook' => $items->filter(fn ($item) => in_array($item['icon'], ['fa-book-open', 'fa-circle-check', 'fa-circle-xmark'], true))->count(),
                'laporan' => $items->filter(fn ($item) => in_array($item['icon'], ['fa-file-circle-plus', 'fa-circle-check', 'fa-circle-xmark'], true))->count(),
                'total' => $items->count(),
            ],
        ];
    }
}
