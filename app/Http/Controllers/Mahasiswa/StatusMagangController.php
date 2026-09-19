<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class StatusMagangController extends Controller
{
    public function index(): View
    {
        $mahasiswa = auth()->user()->mahasiswa;
        abort_unless($mahasiswa, 404);

        $magang = $mahasiswa->magang()
            ->with(['dosen','instansi','logKegiatan','laporan'])
            ->latest('tanggal_pengajuan')
            ->get();

        return view('mahasiswa.status-magang.index', compact('mahasiswa','magang'));
    }
}
