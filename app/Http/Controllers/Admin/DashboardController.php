<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Instansi;
use App\Models\Laporan;
use App\Models\LogKegiatan;
use App\Models\Magang;
use App\Models\Mahasiswa;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            'totalMahasiswa' => Mahasiswa::count(),
            'totalDosen' => Dosen::count(),
            'totalPerusahaan' => Instansi::count(),
            'pengajuanMenunggu' => Magang::where('status_pengajuan', 'diajukan')->count(),
            'mahasiswaSedangMagang' => Magang::where('status_pengajuan', 'disetujui')
                ->where('status_magang', 'berlangsung')
                ->count(),
            'magangSelesai' => Magang::where('status_pengajuan', 'disetujui')
                ->where('status_magang', 'selesai')
                ->count(),
            'logbookMenunggu' => LogKegiatan::where('status_validasi', 'menunggu')
                ->whereHas('magang', fn ($query) => $query->where('status_pengajuan', 'disetujui'))
                ->count(),
            'laporanMenunggu' => Laporan::where('status', 'belum_validasi')
                ->whereHas('magang', fn ($query) => $query->where('status_pengajuan', 'disetujui'))
                ->count(),
        ];

        $pengajuanStatus = [
            'diajukan' => Magang::where('status_pengajuan', 'diajukan')->count(),
            'disetujui' => Magang::where('status_pengajuan', 'disetujui')->count(),
            'ditolak' => Magang::where('status_pengajuan', 'ditolak')->count(),
        ];

        $pengajuanTerbaru = Magang::query()
            ->with(['mahasiswa', 'instansi'])
            ->latest('tanggal_pengajuan')
            ->limit(5)
            ->get();

        $aktivitas = collect();

        Magang::query()
            ->with(['mahasiswa', 'instansi'])
            ->latest('updated_at')
            ->limit(5)
            ->get()
            ->each(fn ($item) => $aktivitas->push([
                'type' => 'pengajuan',
                'title' => 'Pengajuan magang diperbarui',
                'description' => ($item->mahasiswa?->nama ?? 'Mahasiswa') . ' — ' . ($item->instansi?->nama_instansi ?? 'Perusahaan'),
                'status' => $item->status_pengajuan,
                'date' => $item->updated_at,
            ]));

        LogKegiatan::query()
            ->with('magang.mahasiswa')
            ->whereHas('magang', fn ($query) => $query->where('status_pengajuan', 'disetujui'))
            ->latest('updated_at')
            ->limit(5)
            ->get()
            ->each(fn ($item) => $aktivitas->push([
                'type' => 'logbook',
                'title' => 'Logbook diperbarui',
                'description' => ($item->magang?->mahasiswa?->nama ?? 'Mahasiswa') . ' — ' . $item->judul_kegiatan,
                'status' => $item->status_validasi,
                'date' => $item->updated_at,
            ]));

        Laporan::query()
            ->with('magang.mahasiswa')
            ->whereHas('magang', fn ($query) => $query->where('status_pengajuan', 'disetujui'))
            ->latest('updated_at')
            ->limit(5)
            ->get()
            ->each(fn ($item) => $aktivitas->push([
                'type' => 'laporan',
                'title' => 'Laporan diperbarui',
                'description' => ($item->magang?->mahasiswa?->nama ?? 'Mahasiswa') . ' — ' . $item->nama_file,
                'status' => $item->status,
                'date' => $item->updated_at,
            ]));

        $aktivitasTerbaru = $aktivitas
            ->sortByDesc('date')
            ->take(8)
            ->values();

        return view('admin.dashboard', compact(
            'stats',
            'pengajuanStatus',
            'pengajuanTerbaru',
            'aktivitasTerbaru'
        ));
    }
}
