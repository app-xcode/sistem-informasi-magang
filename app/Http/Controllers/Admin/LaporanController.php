<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('q')->toString();
        $status = $request->string('status')->toString();

        $laporan = Laporan::query()
            ->with(['magang.mahasiswa', 'magang.dosen', 'magang.instansi'])
            ->whereHas('magang', fn ($query) => $query->where('status_pengajuan', 'disetujui'))
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('nama_file', 'like', "%{$search}%")
                        ->orWhereHas('magang.mahasiswa', fn ($q) => $q
                            ->where('nama', 'like', "%{$search}%")
                            ->orWhere('nim', 'like', "%{$search}%"))
                        ->orWhereHas('magang.dosen', fn ($q) => $q->where('nama', 'like', "%{$search}%"))
                        ->orWhereHas('magang.instansi', fn ($q) => $q->where('nama_instansi', 'like', "%{$search}%"));
                });
            })
            ->when($status, fn ($query, $status) => $query->where('status', $status))
            ->latest('tanggal_upload')
            ->paginate(10)
            ->withQueryString();

        return view('admin.laporan.index', compact('laporan', 'search', 'status'));
    }

    public function download(Laporan $laporan): Response
    {
        abort_unless($laporan->magang?->status_pengajuan === 'disetujui', 404);
        abort_unless(Storage::disk('public')->exists($laporan->file_path), 404);

        return Storage::disk('public')->download(
            $laporan->file_path,
            $laporan->nama_file
        );
    }
}
