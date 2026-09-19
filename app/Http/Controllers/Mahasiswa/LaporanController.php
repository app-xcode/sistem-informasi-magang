<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mahasiswa\LaporanRequest;
use App\Models\Laporan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(): View
    {
        $mahasiswa = auth()->user()->mahasiswa;
        abort_unless($mahasiswa, 404);

        $magang = $mahasiswa->magang()
            ->with(['instansi','dosen'])
            ->where('status_pengajuan','disetujui')
            ->whereIn('status_magang',['belum_mulai','berlangsung','selesai'])
            ->latest('id')
            ->first();

        $laporan = $magang
            ? $magang->laporan()->latest('tanggal_upload')->latest('id')->get()
            : collect();

        $canUpload = $magang && ! $magang->laporan()->where('status','belum_validasi')->exists();

        return view('mahasiswa.laporan.index', compact('mahasiswa','magang','laporan','canUpload'));
    }

    public function store(LaporanRequest $request): RedirectResponse
    {
        $mahasiswa = auth()->user()->mahasiswa;
        abort_unless($mahasiswa, 404);

        $magang = $mahasiswa->magang()
            ->where('status_pengajuan','disetujui')
            ->whereIn('status_magang',['belum_mulai','berlangsung','selesai'])
            ->latest('id')->first();

        abort_unless($magang, 403);

        if ($magang->laporan()->where('status','belum_validasi')->exists()) {
            return back()->with('error', 'Masih ada laporan yang menunggu validasi dosen.');
        }

        $file = $request->file('file');
        $path = $file->store('laporan', 'public');

        $magang->laporan()->create([
            'nama_file' => $file->getClientOriginalName(),
            'file_path' => $path,
            'tanggal_upload' => now(),
            'status' => 'belum_validasi',
            'catatan_dosen' => null,
        ]);

        return back()->with('success', 'Laporan berhasil diunggah dan menunggu validasi dosen.');
    }

    public function download(Laporan $laporan)
    {
        $mahasiswa = auth()->user()->mahasiswa;
        abort_unless($mahasiswa && $laporan->magang?->mahasiswa_id === $mahasiswa->id, 403);
        abort_unless(Storage::disk('public')->exists($laporan->file_path), 404);

        return Storage::disk('public')->download($laporan->file_path, $laporan->nama_file);
    }

    public function destroy(Laporan $laporan): RedirectResponse
    {
        $mahasiswa = auth()->user()->mahasiswa;
        abort_unless($mahasiswa && $laporan->magang?->mahasiswa_id === $mahasiswa->id, 403);

        if ($laporan->status !== 'belum_validasi') {
            return back()->with('error', 'Laporan yang sudah divalidasi tidak dapat dihapus.');
        }

        Storage::disk('public')->delete($laporan->file_path);
        $laporan->delete();

        return back()->with('success', 'Laporan berhasil dihapus.');
    }
}
