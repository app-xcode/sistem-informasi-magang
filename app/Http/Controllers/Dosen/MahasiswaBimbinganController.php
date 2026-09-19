<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Magang;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MahasiswaBimbinganController extends Controller
{
    public function index(Request $request): View
    {
        $dosen = auth()->user()->dosen;
        abort_unless($dosen, 403);
        $search = trim((string)$request->query('q'));
        $status = $request->string('status')->toString();

        $magang = Magang::query()->with(['mahasiswa','instansi'])
            ->withCount(['logKegiatan','logKegiatan as logbook_disetujui_count'=>fn($q)=>$q->where('status_validasi','disetujui'),'logKegiatan as logbook_menunggu_count'=>fn($q)=>$q->where('status_validasi','menunggu')])
            ->where('dosen_id',$dosen->id)->where('status_pengajuan','disetujui')
            ->when($search,fn($q)=>$q->whereHas('mahasiswa',fn($m)=>$m->where('nama','like',"%{$search}%")->orWhere('nim','like',"%{$search}%")))
            ->when($status,fn($q)=>$q->where('status_magang',$status))
            ->latest('id')->paginate(10)->withQueryString();

        return view('dosen.mahasiswa-bimbingan.index',compact('magang','search','status'));
    }

    public function show(Magang $magang): View
    {
        $dosen = auth()->user()->dosen;
        abort_unless($dosen && $magang->dosen_id === $dosen->id && $magang->status_pengajuan === 'disetujui',403);
        $magang->load(['mahasiswa.user','instansi','logKegiatan'=>fn($q)=>$q->latest('tanggal'),'laporan'=>fn($q)=>$q->latest('tanggal_upload'),'penilaian']);
        return view('dosen.mahasiswa-bimbingan.show',compact('magang'));
    }
}