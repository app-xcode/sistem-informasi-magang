<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Laporan;
use App\Models\LogKegiatan;
use App\Models\Magang;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $dosen = Dosen::query()
            ->where('user_id', Auth::id())
            ->first();

        if (! $dosen) {
            return view('dosen.dashboard', [
                'dosen' => null,
                'stats' => [
                    'mahasiswaBimbingan' => 0,
                    'pengajuanBaru' => 0,
                    'sedangMagang' => 0,
                    'logbookMenunggu' => 0,
                    'laporanMenunggu' => 0,
                ],
                'mahasiswaBimbingan' => new Collection,
                'logbookTerbaru' => new Collection,
            ]);
        }

        $magangQuery = Magang::query()->where('dosen_id', $dosen->id);

        $stats = [
            'mahasiswaBimbingan' => (clone $magangQuery)->distinct('mahasiswa_id')->count('mahasiswa_id'),
            'pengajuanBaru' => (clone $magangQuery)->where('status_pengajuan', 'diajukan')->count(),
            'sedangMagang' => (clone $magangQuery)->where('status_magang', 'berlangsung')->count(),
            'logbookMenunggu' => LogKegiatan::query()
                ->where('status_validasi', 'menunggu')
                ->whereHas('magang', fn ($query) => $query->where('dosen_id', $dosen->id))
                ->count(),
            'laporanMenunggu' => Laporan::query()
                ->where('status', 'belum_validasi')
                ->whereHas('magang', fn ($query) => $query->where('dosen_id', $dosen->id))
                ->count(),
        ];

        $mahasiswaBimbingan = (clone $magangQuery)
            ->with(['mahasiswa', 'instansi'])
            ->latest()
            ->limit(5)
            ->get();

        $logbookTerbaru = LogKegiatan::query()
            ->with(['magang.mahasiswa', 'magang.instansi'])
            ->where('status_validasi', 'menunggu')
            ->whereHas('magang', fn ($query) => $query->where('dosen_id', $dosen->id))
            ->latest('tanggal')
            ->limit(5)
            ->get();

        return view('dosen.dashboard', [
            'dosen' => $dosen,
            'stats' => $stats,
            'mahasiswaBimbingan' => $mahasiswaBimbingan,
            'logbookTerbaru' => $logbookTerbaru,
        ]);
    }
}
