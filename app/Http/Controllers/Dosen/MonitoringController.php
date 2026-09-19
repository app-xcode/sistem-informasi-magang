<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Magang;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MonitoringController extends Controller
{
    public function index(Request $request): View
    {
        $dosen = auth()->user()->dosen;
        abort_unless($dosen,403);
        $search = trim((string)$request->query('q'));
        $status = $request->string('status')->toString();

        $magang = Magang::query()->with(['mahasiswa','instansi'])
            ->withCount(['logKegiatan','logKegiatan as logbook_disetujui_count'=>fn($q)=>$q->where('status_validasi','disetujui'),'logKegiatan as logbook_menunggu_count'=>fn($q)=>$q->where('status_validasi','menunggu'),'laporan as laporan_count'])
            ->where('dosen_id',$dosen->id)->where('status_pengajuan','disetujui')
            ->when($search,fn($q)=>$q->whereHas('mahasiswa',fn($m)=>$m->where('nama','like',"%{$search}%")->orWhere('nim','like',"%{$search}%")))
            ->when($status,fn($q)=>$q->where('status_magang',$status))
            ->latest('id')->paginate(10)->withQueryString();

        return view('dosen.monitoring.index',compact('magang','search','status'));
    }
}