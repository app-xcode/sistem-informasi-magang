<?php

namespace App\Http\Controllers;

use App\Models\Magang;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $totalPengajuan = Magang::count();

        $berlangsung = Magang::query()
            ->where('status_pengajuan', 'disetujui')
            ->where('status_magang', 'berlangsung')
            ->count();

        $selesai = Magang::query()
            ->where('status_pengajuan', 'disetujui')
            ->where('status_magang', 'selesai')
            ->count();

        $disetujui = Magang::where('status_pengajuan', 'disetujui')->count();

        $progress = $disetujui > 0
            ? (int) round(($selesai / $disetujui) * 100)
            : 0;

        return view('home', compact(
            'totalPengajuan',
            'berlangsung',
            'selesai',
            'progress'
        ));
    }
}
