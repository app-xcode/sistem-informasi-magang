<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Instansi;
use App\Models\Magang;
use App\Models\Mahasiswa;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            'totalMahasiswa' => Mahasiswa::count(),
            'totalDosen' => Dosen::count(),
            'totalPerusahaan' => Instansi::count(),
            'pengajuanMenunggu' => Magang::where('status_pengajuan', 'diajukan')->count(),
            'mahasiswaSedangMagang' => Magang::where('status_magang', 'berlangsung')->count(),
            'magangSelesai' => Magang::where('status_magang', 'selesai')->count(),
        ];

        /** @var Collection<int, Magang> $pengajuanTerbaru */
        $pengajuanTerbaru = Magang::query()
            ->with(['mahasiswa', 'instansi'])
            ->latest('tanggal_pengajuan')
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'pengajuanTerbaru' => $pengajuanTerbaru,
        ]);
    }
}
