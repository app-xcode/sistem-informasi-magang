<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mahasiswa\LogbookRequest;
use App\Models\LogKegiatan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LogbookController extends Controller
{
    public function index(): View
    {
        $mahasiswa = auth()->user()->mahasiswa;

        $magang = $mahasiswa?->magang()
            ->with(['dosen', 'instansi'])
            ->where('status_pengajuan', 'disetujui')
            ->whereIn('status_magang', ['belum_mulai', 'berlangsung'])
            ->latest('id')
            ->first();

        $logbook = $magang
            ? $magang->logKegiatan()->latest('tanggal')->latest('id')->paginate(10)->withQueryString()
            : collect();

        return view('mahasiswa.logbook.index', compact('mahasiswa', 'magang', 'logbook'));
    }

    public function store(LogbookRequest $request): RedirectResponse
    {
        $mahasiswa = auth()->user()->mahasiswa;

        abort_unless($mahasiswa, 403);

        $magang = $this->activeMagang($mahasiswa);

        abort_unless($magang, 403);

        $this->validateTanggal($request, $magang);

        $data = $request->validated();

        if ($request->hasFile('bukti_kegiatan')) {
            $data['bukti_kegiatan'] = $request->file('bukti_kegiatan')->store('logbook', 'public');
        }

        $data['magang_id'] = $magang->id;
        $data['status_validasi'] = 'menunggu';
        $data['catatan_dosen'] = null;

        LogKegiatan::create($data);

        return back()->with('success', 'Kegiatan logbook berhasil ditambahkan dan menunggu validasi dosen.');
    }

    public function edit(LogKegiatan $logbook): View
    {
        $mahasiswa = auth()->user()->mahasiswa;

        abort_unless($mahasiswa && $logbook->magang?->mahasiswa_id === $mahasiswa->id, 403);
        abort_unless($logbook->status_validasi === 'menunggu', 403);

        $magang = $this->activeMagang($mahasiswa);
        abort_unless($magang && $logbook->magang_id === $magang->id, 403);

        return view('mahasiswa.logbook.edit', compact('logbook', 'magang'));
    }

    public function update(LogbookRequest $request, LogKegiatan $logbook): RedirectResponse
    {
        $mahasiswa = auth()->user()->mahasiswa;

        abort_unless($mahasiswa && $logbook->magang?->mahasiswa_id === $mahasiswa->id, 403);
        abort_unless($logbook->status_validasi === 'menunggu', 403);

        $magang = $this->activeMagang($mahasiswa);
        abort_unless($magang && $logbook->magang_id === $magang->id, 403);

        $this->validateTanggal($request, $magang);

        $data = $request->validated();

        if ($request->hasFile('bukti_kegiatan')) {
            $newPath = $request->file('bukti_kegiatan')->store('logbook', 'public');

            if ($logbook->bukti_kegiatan) {
                Storage::disk('public')->delete($logbook->bukti_kegiatan);
            }

            $data['bukti_kegiatan'] = $newPath;
        } else {
            $data['bukti_kegiatan'] = $logbook->bukti_kegiatan;
        }

        $data['status_validasi'] = 'menunggu';
        $data['catatan_dosen'] = null;

        $logbook->update($data);

        return redirect()
            ->route('mahasiswa.logbook')
            ->with('success', 'Logbook berhasil diperbarui dan tetap menunggu validasi dosen.');
    }

    public function destroy(LogKegiatan $logbook): RedirectResponse
    {
        $mahasiswa = auth()->user()->mahasiswa;

        abort_unless($mahasiswa && $logbook->magang?->mahasiswa_id === $mahasiswa->id, 403);

        if ($logbook->status_validasi !== 'menunggu') {
            return back()->with('error', 'Logbook yang sudah divalidasi tidak dapat dihapus.');
        }

        if ($logbook->bukti_kegiatan) {
            Storage::disk('public')->delete($logbook->bukti_kegiatan);
        }

        $logbook->delete();

        return back()->with('success', 'Logbook berhasil dihapus.');
    }

    private function activeMagang($mahasiswa)
    {
        return $mahasiswa->magang()
            ->where('status_pengajuan', 'disetujui')
            ->whereIn('status_magang', ['belum_mulai', 'berlangsung'])
            ->latest('id')
            ->first();
    }

    private function validateTanggal(LogbookRequest $request, $magang): void
    {
        if ($magang->tanggal_mulai && $request->date('tanggal')->lt($magang->tanggal_mulai)) {
            throw ValidationException::withMessages([
                'tanggal' => 'Tanggal kegiatan tidak boleh sebelum tanggal mulai magang.',
            ]);
        }

        if ($magang->tanggal_selesai && $request->date('tanggal')->gt($magang->tanggal_selesai)) {
            throw ValidationException::withMessages([
                'tanggal' => 'Tanggal kegiatan tidak boleh setelah tanggal selesai magang.',
            ]);
        }
    }
}
