<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dosen\LaporanValidationRequest;
use App\Models\Laporan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(): View
    {
        $dosen = auth()->user()->dosen;
        abort_unless($dosen,403);
        $laporan = Laporan::query()->with(['magang.mahasiswa','magang.instansi'])
            ->whereHas('magang',fn($q)=>$q->where('dosen_id',$dosen->id)->where('status_pengajuan','disetujui'))
            ->latest('tanggal_upload')->latest('id')->paginate(10);
        return view('dosen.laporan.index',compact('laporan'));
    }

    public function download(Laporan $laporan)
    {
        $dosen = auth()->user()->dosen;
        abort_unless($dosen && $laporan->magang?->dosen_id === $dosen->id && $laporan->magang?->status_pengajuan === 'disetujui',403);
        abort_unless(Storage::disk('public')->exists($laporan->file_path),404);
        return Storage::disk('public')->download($laporan->file_path,$laporan->nama_file);
    }

    public function validateReport(LaporanValidationRequest $request,Laporan $laporan): RedirectResponse
    {
        $dosen = auth()->user()->dosen;
        abort_unless($dosen && $laporan->magang?->dosen_id === $dosen->id && $laporan->magang?->status_pengajuan === 'disetujui',403);
        $data=$request->validated();
        $laporan->update(['status'=>$data['status'],'catatan_dosen'=>$data['catatan_dosen'] ?? null]);
        return back()->with('success','Status laporan berhasil diperbarui.');
    }
}