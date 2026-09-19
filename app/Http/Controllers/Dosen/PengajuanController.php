<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Magang;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengajuanController extends Controller
{
    public function index(Request $request): View
    {
        $dosen = auth()->user()->dosen;
        abort_unless($dosen,403);
        $status = $request->string('status')->toString();
        $search = trim((string)$request->query('q'));

        $pengajuan = Magang::query()->with(['mahasiswa','instansi'])
            ->where('dosen_id',$dosen->id)
            ->when($status,fn($q)=>$q->where('status_pengajuan',$status))
            ->when($search,fn($q)=>$q->whereHas('mahasiswa',fn($m)=>$m->where('nama','like',"%{$search}%")->orWhere('nim','like',"%{$search}%")))
            ->latest('tanggal_pengajuan')->paginate(10)->withQueryString();

        return view('dosen.pengajuan.index',compact('pengajuan','status','search'));
    }
}