<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Magang;
use App\Models\Mahasiswa;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $mahasiswa = Mahasiswa::query()
            ->where('user_id', Auth::id())
            ->first();

        $magang = $mahasiswa
            ? Magang::query()
                ->with(['dosen', 'instansi'])
                ->where('mahasiswa_id', $mahasiswa->id)
                ->latest('tanggal_pengajuan')
                ->first()
            : null;

        $logbookCount = $magang?->logKegiatan()->count() ?? 0;
        $logbookTerakhir = $magang?->logKegiatan()->latest('tanggal')->first();

        return view('mahasiswa.dashboard', [
            'mahasiswa' => $mahasiswa,
            'magang' => $magang,
            'logbookCount' => $logbookCount,
            'logbookTerakhir' => $logbookTerakhir,
        ]);
    }
}
