<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dosen\LogbookValidationRequest;
use App\Models\LogKegiatan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LogbookController extends Controller
{
    public function index(Request $request): View
    {
        $dosen = auth()->user()->dosen;

        abort_unless($dosen, 403);

        $status = $request->string('status')->toString();

        $logbook = LogKegiatan::query()
            ->with(['magang.mahasiswa', 'magang.instansi'])
            ->whereHas('magang', function ($query) use ($dosen) {
                $query->where('dosen_id', $dosen->id)
                    ->where('status_pengajuan', 'disetujui');
            })
            ->when($status, fn ($query, $status) => $query->where('status_validasi', $status))
            ->latest('tanggal')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('dosen.logbook.index', compact('logbook', 'status'));
    }

    public function bukti(LogKegiatan $logbook): BinaryFileResponse
    {
        $dosen = auth()->user()->dosen;
        abort_unless($dosen && $logbook->magang?->dosen_id === $dosen->id && $logbook->magang?->status_pengajuan === 'disetujui', 403);
        abort_unless($logbook->bukti_kegiatan, 404);
        $disk = Storage::disk('public');
        abort_unless($disk->exists($logbook->bukti_kegiatan), 404);
        return response()->file($disk->path($logbook->bukti_kegiatan));
    }

    public function validateLogbook(LogbookValidationRequest $request, LogKegiatan $logbook): RedirectResponse
    {
        $dosen = auth()->user()->dosen;

        abort_unless($dosen, 403);

        abort_unless(
            $logbook->magang?->dosen_id === $dosen->id &&
            $logbook->magang?->status_pengajuan === 'disetujui',
            403
        );

        $logbook->update($request->validated());

        return back()->with('success', 'Status logbook berhasil diperbarui.');
    }
}
