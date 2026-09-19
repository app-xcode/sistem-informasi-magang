<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dosen\PenilaianRequest;
use App\Models\Magang;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PenilaianController extends Controller
{
    public function index(): View
    {
        $dosen=auth()->user()->dosen;
        abort_unless($dosen,403);
        $magang=Magang::query()->with(['mahasiswa','instansi','penilaian'])
            ->where('dosen_id',$dosen->id)->where('status_pengajuan','disetujui')
            ->latest('id')->paginate(10);
        return view('dosen.penilaian.index',compact('magang'));
    }

    public function edit(Magang $magang): View
    {
        $dosen=auth()->user()->dosen;
        abort_unless($dosen && $magang->dosen_id===$dosen->id && $magang->status_pengajuan==='disetujui',403);
        $magang->load(['mahasiswa','instansi','penilaian']);
        return view('dosen.penilaian.edit',compact('magang'));
    }

    public function store(PenilaianRequest $request,Magang $magang): RedirectResponse
    {
        $dosen=auth()->user()->dosen;
        abort_unless($dosen && $magang->dosen_id===$dosen->id && $magang->status_pengajuan==='disetujui',403);
        $data=$request->validated();
        $data['dosen_id']=$dosen->id;
        $data['tanggal_penilaian']=now()->toDateString();
        $data['nilai_akhir']=round(collect(['kedisiplinan','tanggung_jawab','kerja_sama','kemampuan_teknis','sikap'])->avg(fn($k)=>(float)$data[$k]),2);
        $magang->penilaian()->updateOrCreate([], $data);
        return redirect()->route('dosen.penilaian')->with('success','Penilaian berhasil disimpan.');
    }
}